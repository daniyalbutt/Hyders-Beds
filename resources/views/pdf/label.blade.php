<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Label</title>
    <style>
        @page {
            margin: 0; /* Remove default margins */
        }
        body {
            font-family: 'nimbussans', sans-serif;
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
            justify-content: center;
            padding: 10pt;
            box-sizing: border-box;
            text-align: center;
        }
        .code {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 8pt;
        }
        .desc {
            font-size: 14pt;
            margin-bottom: 6pt;
        }
        .price {
            font-size: 16pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach($selectedItems as $item)
        <div class="label">
            <div class="code">{{ $item->product_code }}</div>
            <div class="desc">{{ $item->description }}</div>
            <div class="price">£{{ number_format($item->sale_price, 2) }}</div>
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
