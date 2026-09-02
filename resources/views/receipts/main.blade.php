<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Template</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: white;
            color: black;
            font-family: 'Cairo', sans-serif;
            font-size: 28px;
        }

        body {
            display: block;
            width: 100%;
            height: auto;
            /* ✅ allow natural height */
        }

        @page {
            margin: 0;
            size: auto;
            /* ✅ let page size expand automatically */
        }

        .logo {
            width: 70%;
            display: block;
            margin: 1rem auto;
        }

        .receipt-container {
            width: 100%;
            margin-top: 1.5rem;
            font-size: inherit;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 0.8rem;
        }

        .left {
            text-align: left;
            font-weight: 400;
        }

        .center {
            text-align: center;
            font-weight: bold;
        }

        .right {
            text-align: right;
            font-weight: 400;
            direction: rtl;
            font-family: 'Tajawal', sans-serif;
        }

        .rtl {
            direction: rtl;
            font-family: 'Tajawal', sans-serif;
        }

        .arabic-small {
            font-family: 'Tajawal', sans-serif;
            direction: rtl;
            font-size: 1.3em;
        }

        .arabic-smaller {
            font-size: 1.15em;
        }

        .bold {
            font-weight: bold;
        }

        .border-top {
            border-top: 2px solid black;
        }

        .border-bottom {
            border-bottom: 2px solid black;
        }

        tr.border-bottom td {
            border-bottom: 2px solid black;
        }

        .padding-md {
            padding: 1.2rem 0.8rem;
        }

        .padding-sm {
            padding: 0.8rem 0.6rem;
        }

        .qr-code-box {
            width: 180px;
            height: 180px;
            margin: 2rem auto 0;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
        }

        .footer-message {
            margin: 0.4rem 0;
            font-weight: bold;
            font-size: 1.4rem;
        }

        .footer-container {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .gray {
            color: #666;
            font-size: 1.3rem;
        }
    </style>

</head>

<body>
    @php
        // Sales orders are issued under the second brand, so they carry its logo.
        $logoFile = $type === 'sales_order' ? 'images/pharmacy2.png' : 'images/pharmacy.png';
    @endphp

    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($logoFile))) }}"
        alt="Logo" class="logo">

    <div class="receipt-container">
        <table>
            <tbody>
                <tr>
                    <td class="left">Branch</td>
                    <td class="center">{{ $branchName }}</td>
                    <td class="right">فرع المجمعة</td>
                </tr>
                <tr>
                    <td class="left">VAT Reg. No.</td>
                    <td class="center">310432040400003</td>
                    <td class="right">الرقم الضريبي</td>
                </tr>
                <tr>
                    <td class="left">Receipt No.</td>
                    <td class="center">{{ $invoiceNumber }}</td>
                    <td class="right">رقم الفاتورة</td>
                </tr>
                <tr>
                    <td class="left">Date / Time</td>
                    <td class="center">{{ $date }}</td>
                    <td class="right">التاريخ / الوقت</td>
                </tr>
                <tr>
                    <td class="left">Telephone No.</td>
                    <td class="center">{{ $phone }}</td>
                    <td class="right">رقم الهاتف</td>
                </tr>
                <tr class="header-row">
                    <td class="left">Client Name</td>
                    <td class="center">{{ $client_name }}</td>
                    <td class="right">اسم العميل</td>
                </tr>
                @if (!empty($client_tax_number))
                <tr>
                    <td class="left">Client Tax No.</td>
                    <td class="center">{{ $client_tax_number }}</td>
                    <td class="right">الرقم الضريبي للعميل</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @php
        // Receipt paper is narrow, so the discount column earns its place only
        // when something was actually forgiven — a promotion giveaway, priced in
        // full and cancelled, or a discounted line. An ordinary sale prints
        // exactly as it always did.
        $showsDiscount = collect($items)->contains(fn ($line) => (float) ($line['discount'] ?? 0) > 0);
    @endphp

    <div class="receipt-container">
        <table style="margin-top: 2rem">
            <thead>
                <tr class="border-bottom">
                    <td class="left padding-md bold">Product<br><span class="rtl">الصنف</span></td>
                    <td class="center padding-md bold">Quantity<br><span class="rtl">الكمية</span></td>
                    <td class="center padding-md bold">Price<br><span class="rtl">السعر</span></td>
                    @if ($showsDiscount)
                    <td class="center padding-md bold">Discount<br><span class="rtl">الخصم</span></td>
                    @endif
                    <td class="center padding-md bold">Total<br><span class="rtl">الإجمالي</span></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr class="border-bottom">
                    <td class="left padding-sm">
                        {{ $item['name'] }}<br>
                        @if (isset($item['arabic_name']))
                        <span class="arabic-small">{{ $item['arabic_name'] }}</span>
                        @endif
                    </td>
                    <td class="center padding-sm">{{ $item['quantity'] }}</td>
                    <td class="center padding-sm">{{ number_format($item['unitPrice'], 2) }}</td>
                    @if ($showsDiscount)
                    <td class="center padding-sm">{{ number_format($item['discount'] ?? 0, 2) }}</td>
                    @endif
                    <td class="center padding-sm">{{ number_format($item['totalPrice'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="left padding-md bold">
                        Total Quantity<br><span class="rtl">إجمالي الكمية</span>
                    </td>
                    <td class="center padding-md bold">
                        {{ array_sum(array_column($items, 'quantity')) }}
                    </td>
                    <td class="center padding-md"></td>
                    @if ($showsDiscount)
                    <td class="center padding-md bold">
                        {{ number_format(array_sum(array_column($items, 'discount')), 2) }}
                    </td>
                    @endif
                    <td class="center padding-md bold">
                        {{ number_format(array_sum(array_column($items, 'totalPrice')), 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="receipt-container">
        <table style="margin-top: 2rem">
            <tbody>
                <tr>
                    <td class="left padding-sm">
                        Total Amount Without VAT<br>
                        <span class="arabic-small">إجمالي القيمة بدون الضريبة</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($subtotal, 2) }} SAR</td>
                </tr>
                <tr>
                    <td class="left padding-sm">
                        Total VAT<br>
                        <span class="arabic-small">إجمالي ضريبة القيمة المضافة</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($tax, 2) }} SAR</td>
                </tr>
                <tr class="border-bottom">
                    <td class="left padding-sm">
                        Total Amount + VAT<br>
                        <span class="arabic-small">إجمالي القيمة شامل الضريبة</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($total, 2) }} SAR</td>
                </tr>

                @if ($type === 'return')
                <tr class="border-bottom">
                    <td class="left padding-sm">
                        Cash<br>
                        <span class="arabic-small">النقد</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($returnCash, 2) }} SAR</td>
                </tr>

                <tr class="border-bottom">
                    <td class="left padding-sm">
                        Refund<br>
                        <span class="arabic-small">المرتجع</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($totalRefund, 2) }} SAR</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @php
    use Endroid\QrCode\Builder\Builder;

    $vatNumber = '310432040400003';
    $qr = Builder::create()->data($vatNumber)->size(180)->margin(0)->build();
    $qrBase64 = base64_encode($qr->getString());
    @endphp
    <div class="footer-container">
        <div class="qr-code-box">
            <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
        </div>
        <p class="gray">User ID: <strong>{{ $userId ?? 'N/A' }}</strong></p>
        <div>
            <p class="footer-message">We Wish You a Quick Recovery</p>
            <p class="footer-message rtl">نتمنى لكم الشفاء العاجل</p>
        </div>
    </div>
</body>

</html>