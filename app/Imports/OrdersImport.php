<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrdersImport implements ToCollection, WithHeadingRow
{
    public array $errors   = [];
    public int   $imported = 0;
    public array $createdCustomers = [];

    public function collection(Collection $rows)
    {
        /*
         * CSV columns (case-insensitive, spaces become underscores via WithHeadingRow):
         *
         *  customer_name        – for reference only (not used for lookup)
         *  customer_email       – matched against customers.email; row is skipped if no match
         *  delivery_address     – stored on the order itself (can differ from customer address)
         *  order_date           – format: YYYY-MM-DD  or  MM/DD/YYYY
         *  order_reference      – used to group rows into a single order
         *  order_type           – e.g. order / sample  (defaults to "order")
         *  required_date        – optional, same date formats as order_date
         *  product_code         – looked up in products table; product_id resolved automatically
         *  description          – item description (falls back to product.description if blank)
         *  price                – unit sale price
         *  quantity             – integer ≥ 1
         *  fabric_name          – optional fabric name (stored as-is)
         *  fabric_price         – optional fabric price; defaults to 0.00
         *  drawer_name          – optional drawer name (stored as-is)
         *  drawer_price         – optional drawer price; defaults to 0.00
         */

        // ── 1. Group rows by order_reference + customer_email ─────────────────
        $grouped = $rows->groupBy(function ($row) {
            return trim($row['order_reference'] ?? '') . '|||' . trim($row['customer_email'] ?? '');
        });

        foreach ($grouped as $groupKey => $orderRows) {
            $firstRow = $orderRows->first();

            // ── 2. Resolve customer ──────────────────────────────────────────
            $customerEmail = trim($firstRow['customer_email'] ?? '');

            if (empty($customerEmail)) {
                $this->errors[] = "Row skipped – customer_email is empty (order_reference: {$firstRow['order_reference']}).";
                continue;
            }

            $customer = Customer::where('email', $customerEmail)->where('status', 0)->first();

            if (! $customer) {
                $this->errors[] = "Order skipped – no customer found with email '{$customerEmail}' (reference: {$firstRow['order_reference']}).";
                continue;
            }

            // ── 3. Parse dates ───────────────────────────────────────────────
            $orderDate    = $this->parseDate($firstRow['order_date']    ?? null);
            $requiredDate = $this->parseDate($firstRow['required_date'] ?? null);

            if (! $orderDate) {
                $this->errors[] = "Order skipped – invalid or missing order_date for reference '{$firstRow['order_reference']}' (customer: {$customerEmail}).";
                continue;
            }

            // ── 4. Create the Order ──────────────────────────────────────────
            $order = Order::create([
                'customer'        => $customer->id,
                'address'         => trim($firstRow['delivery_address'] ?? $customer->address),
                'order_date'      => $orderDate,
                'order_reference' => trim($firstRow['order_reference'] ?? ''),
                'order_type'      => trim($firstRow['order_type']      ?? 'order'),
                'required_date'   => $requiredDate,
                'status'          => 0,
                'draft'           => 0,
                'added_by'        => Auth::id(),
            ]);

            // ── 5. Create order items ────────────────────────────────────────
            foreach ($orderRows as $rowIndex => $row) {
                $productCode = trim($row['product_code'] ?? '');

                if (empty($productCode)) {
                    $this->errors[] = "Item skipped on order #{$order->id} – product_code is empty (row ~{$rowIndex}).";
                    continue;
                }

                // Look up the product; product_id will be null if not found (still saves the row)
                $product = Product::where('product_code', $productCode)->where('status', 0)->first();

                $description = trim($row['description'] ?? '');
                if (empty($description) && $product) {
                    $description = $product->description;
                }

                $price    = (float) ($row['price']    ?? ($product ? $product->sale_price : 0));
                $quantity = max(1, (int) ($row['quantity'] ?? 1));

                // ── Fabric & Drawer (optional) ───────────────────────────────
                $fabricName  = trim($row['fabric_name']  ?? '') ?: null;
                $fabricPrice = strlen(trim($row['fabric_price'] ?? '')) ? (float) $row['fabric_price'] : 0.00;
                $drawerName  = trim($row['drawer_name']  ?? '') ?: null;
                $drawerPrice = strlen(trim($row['drawer_price'] ?? '')) ? (float) $row['drawer_price'] : 0.00;

                $order->items()->create([
                    'product_id'   => $product?->id,
                    'product_code' => $productCode,
                    'description'  => $description,
                    'price'        => $price,
                    'quantity'     => $quantity,
                    'total'        => $price * $quantity,
                    'user_id'      => Auth::id(),
                    'fabric_name'  => $fabricName,
                    'fabric_price' => $fabricPrice,
                    'drawer_name'  => $drawerName,
                    'drawer_price' => $drawerPrice,
                ]);
            }

            // ── 6. Recalculate totals ────────────────────────────────────────
            $order->recalcTotals();
            $this->imported++;
        }
    }

    // ── Helper: parse a date string into Y-m-d ─────────────────────────────
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Excel numeric serial date
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)
                    ->format('Y-m-d');
            } catch (\Throwable $e) {
                // fall through
            }
        }

        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'd-m-Y', 'm-d-Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value))->format('Y-m-d');
            } catch (\Throwable $e) {
                // try next format
            }
        }

        return null;
    }
}