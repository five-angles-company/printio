<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Label</title>
    <style>
        @page {
            margin: 0;
            size: {{ $width }}px {{ $height }}px;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            width: {{ $width }}px;
            height: {{ $height }}px;
            padding: 0;
            margin: 0;
        }

        .label-container {
            width: {{ $width }}px;
            height: {{ $height }}px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: calc({{ $height }}px * 0.03);
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.3;
        }

        .barcode-section {
            text-align: center;
        }

        .barcode-wrapper {
            width: 100%;
            text-align: center;
        }

        .barcode-wrapper img {
            max-width: 70%;
            max-height: 40px;
            height: 40px;
        }

        .barcode-number {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            padding: 0;
            vertical-align: middle;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            text-align: left;
        }

        .date {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            padding-right: 500px;
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
        <div class="header">
            <div class="store-name">{{ $storeName }}</div>
            <div class="product-name">{{ $productName }}</div>
        </div>
        <div class="barcode-section">
            <div class="barcode-wrapper"> <img src="data:image/png;base64,{{ $barcodeImg }}" alt="barcode" /> </div>
            <div class="barcode-number">{{ $barcode }}</div>
        </div>
        <table class="footer-table">
            <tr>
                <td class="price">SR: {{ $price }}</td>
                <td class="date">{{ $expiry }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
