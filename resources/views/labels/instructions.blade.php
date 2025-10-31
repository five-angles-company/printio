<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وصفة طبية</title>
    <style>
        @page {
            margin: 0;
            size: {{ $width }}px auto;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Cairo", sans-serif;
            direction: rtl;
            text-align: right;
            width: {{ $width }}px;
            font-size: 18px;
            line-height: 1.4;
            padding: 8px;
        }

        .label-container {
            width: 100%;
        }

        .product-name {
            text-align: center;
            font-size: 50px;
            font-weight: bold;
            decoration: underline;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }



        .usage-line {
            font-size: 40px;
            margin-top: 2px;
            font-weight: bold;
        }



        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="label-container">
        <div class="product-name">{{ $data->productName }}</div>

        @foreach ([$data->line1, $data->line2, $data->line3, $data->line4] as $line)
            @if (!empty($line))
                <div class="usage-line">- {{ $line }}</div>
            @endif
        @endforeach

    </div>
</body>

</html>
