<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Label</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            width: 289.5pt;
            margin: 0;
            padding: 0;
        }
        table.label-table {
            width: 289.5pt;
            height: 430.5pt;
            border-collapse: collapse;
        }
        table.label-table td {
            text-align: center;
            vertical-align: top;
            padding: 20pt 15pt;
        }
        .heading {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5pt;
        }
        .company {
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            margin-bottom: 16pt;
        }
        .product-description {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.4;
            margin-bottom: 16pt;
        }
        .meta {
            font-size: 10pt;
            line-height: 2;
            margin-bottom: 10pt;
        }
        .addon {
            font-size: 10pt;
            line-height: 2;
        }
        .box-info {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 14pt;
        }
    </style>
</head>
<body>
@foreach($selectedItems as $outerLoop)
@php $qty = $outerLoop->quantity ?? 1; @endphp
@for($box = 1; $box <= $qty; $box++)
<table class="label-table">
    <tr>
        <td>
            <div class="heading">{{ optional($outerLoop->product)->production_type ?? 'Production' }}</div>
            <div class="company">{{ config('app.name') }} t/a<br>Furniture and Interiors</div>
            <div class="product-description">{{ $outerLoop->description }}</div>
            <div class="meta">
                Customer Ref : {{ $order->order_reference ?? '' }}<br>
                Line Number : {{ $outerLoop->id }}<br>
                Order Number : {{ $order->id }}
            </div>
            @if($outerLoop->drawer_name)
            <div class="addon">Drawers : {{ $outerLoop->drawer_name }}</div>
            @endif
            @if($outerLoop->fabric_name)
            <div class="addon">Fabric : {{ $outerLoop->fabric_name }}</div>
            @endif
            <div class="box-info">box {{ $box }} of {{ number_format($qty, 2) }}</div>
        </td>
    </tr>
</table>
@if(!($loop->last && $box == $qty))
<div style="page-break-after:always;"></div>
@endif
@endfor
@endforeach
</body>
</html>