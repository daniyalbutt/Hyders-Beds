<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Auth;

class ProductsImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new Product([
            'product_code' => $row[0],
            'description' => $row[1],
            'sale_price' => $row[2],
            'volume' => number_format(($row[5]*$row[6]*$row[7])/1000000, 2),
            'weight' => $row[4],
            'width' => $row[5],
            'length' => $row[6],
            'height' => $row[7],
            'product_range' => $row[8],
            'product_section' => $row[9],
            'production_type' => $row[10],
            'status' => 0,
            'added_by' => Auth::user()->id
        ]);
    }

    public function rules(): array
    {
        return [
            'product_code' => 'required|product_code|unique:products'
        ];
    }
}
