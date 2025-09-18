<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Label</title>
    <style>
        @page {
            margin: 0; /* no default margin for printing */
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            width: 289.5pt;   /* 386px */
            height: 430.5pt;  /* 574px */
            margin: 0;
        }
        .label {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 15pt;
            box-sizing: border-box;
            text-align: center;
        }
        .heading {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 5pt;
            margin-bottom: 5pt;
        }
        .subheading {
            font-size: 12pt;
            margin-bottom: 15pt;
        }
        .order-block {
            margin: 15pt 0;
            text-align: center;
        }
        .order-number {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 3pt;
        }
        .order-details {
            font-size: 10pt;
            line-height: 1.3;
        }
        .product {
            margin: 25pt 0;
            font-size: 13pt;
            font-weight: bold;
        }
        .box-info {
            margin-top: auto; /* push to bottom */
            font-size: 12pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach($selectedItems as $item)
        <div class="label">
            <div class="heading">QR Cods Test</div>
            <div class="subheading">Showroom</div>

            <div class="order-block">
                <div class="order-number">Order Number : {{ $order->id }}</div>
                <div class="order-details">
                    Line Number : {{ $item->line_number ?? '----' }}<br>
                    Customer Ref : {{ $order->customer_ref ?? '' }}
                </div>
            </div>

            <div class="product">
                {{ $item->description }}
            </div>

            <div class="box-info">
                box {{ $item->box_no }} of {{ number_format($item->box_total, 2) }}
            </div>
        </div>

        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
