<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وصفة طبية</title>
    <style>
        @page {
            margin: 0;
            size: {{ $width }}px {{ $height }}px;
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
            height: {{ $height }}px;
            margin: 0;
            padding: 0;
        }

        .label-container {
            width: {{ $width }}px;
            height: {{ $height }}px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 8px;
        }

        /* Header section */
        .header {
            text-align: center;
            margin-bottom: 6px;
        }

        .pharmacist-name {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.3;
        }

        .patient-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* Product section */
        .product-name {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 8px 0;
        }

        /* Instructions grid */
        .instructions-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            text-align: center;
        }

        .instructions-table td {
            font-size: 18px;
            font-weight: bold;
            padding: 4px;
            vertical-align: middle;
            width: 50%;
        }

        /* Print handling */
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
            <div class="pharmacist-name">{{ $data->pharmacistName }}</div>
            <div class="patient-name">المريض: {{ $data->patientName }}</div>
        </div>

        <div class="product-name">{{ $data->productName }}</div>

        @php
            $lines = array_values(
                array_filter([$data->line1 ?? null, $data->line2 ?? null, $data->line3 ?? null, $data->line4 ?? null]),
            );
        @endphp

        @if (count($lines) > 0)
            <table class="instructions-table">
                <tr>
                    <td>{{ $lines[0] ?? '' }}</td>
                    <td>{{ $lines[1] ?? '' }}</td>
                </tr>
                @if (count($lines) > 2)
                    <tr>
                        <td>{{ $lines[2] ?? '' }}</td>
                        <td>{{ $lines[3] ?? '' }}</td>
                    </tr>
                @endif
            </table>
        @endif
    </div>
</body>

</html>
