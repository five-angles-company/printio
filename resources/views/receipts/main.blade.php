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

        .document-title {
            text-align: center;
            font-weight: bold;
            font-size: 32px;
            border: 2px solid black;
            padding: 0.6rem;
            margin: 0.5rem 0;
        }

        .clearance-note {
            text-align: center;
            font-size: 24px;
            margin: 2rem 0 0;
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

        /* A signed e-invoice's QR code carries nine tags, some 550 characters:
           at 360 dots each of its squares is about four printer dots wide,
           half a millimetre on an 80 mm roll, which a phone reads. */
        .qr-code-box.zatca {
            width: 360px;
            height: 360px;
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

    {{-- What the document is, as ZATCA asks every invoice and credit note be titled. --}}
    @if (! empty($titleEn))
        <div class="document-title">
            {{ $titleEn }}<br>
            <span class="rtl">{{ $titleAr }}</span>
        </div>
    @endif

    <div class="receipt-container">
        <table>
            <tbody>
                <tr>
                    <td class="left">Branch</td>
                    <td class="center">{{ $branchName }}</td>
                    <td class="right">الفرع</td>
                </tr>
                {{-- The seller and VAT number come from the taxpayer the branch invoices
                     under. A server that predates them sends neither, so the VAT number
                     falls back to the one this receipt always printed. --}}
                @if (! empty($sellerName))
                <tr>
                    <td class="left">Seller</td>
                    <td class="center">{{ $sellerName }}</td>
                    <td class="right">البائع</td>
                </tr>
                @endif
                <tr>
                    <td class="left">VAT Reg. No.</td>
                    <td class="center">{{ $sellerVatNumber ?? '310432040400003' }}</td>
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
                @if ($creditSale)
                <tr>
                    <td class="left">Payment Method</td>
                    <td class="center">Credit Sale<br><span class="rtl">بيع آجل</span></td>
                    <td class="right">طريقة الدفع</td>
                </tr>
                @endif
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
                @if (! empty($clientAddress))
                <tr>
                    <td class="left">Client Address</td>
                    <td class="center">{{ $clientAddress }}</td>
                    <td class="right">عنوان العميل</td>
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

                {{-- A credit sale is a debt on the customer's account: what has been
                     paid on it, what is still owed and the day it falls due. --}}
                @if ($creditSale)
                <tr>
                    <td class="left padding-sm">
                        Paid Amount<br>
                        <span class="arabic-small">المبلغ المدفوع</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($paidAmount ?? 0, 2) }} SAR</td>
                </tr>
                <tr>
                    <td class="left padding-sm bold">
                        Remaining Amount<br>
                        <span class="arabic-small">المبلغ المتبقي</span>
                    </td>
                    <td class="right padding-sm bold">{{ number_format($remainingAmount ?? 0, 2) }} SAR</td>
                </tr>
                <tr class="border-bottom">
                    <td class="left padding-sm bold">
                        Due Date<br>
                        <span class="arabic-small">تاريخ الاستحقاق</span>
                    </td>
                    <td class="right padding-sm bold">{{ $dueDate }}</td>
                </tr>
                @endif

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

    // ZATCA's QR when the server sends one; otherwise the VAT number, as before.
    $qrText = $zatcaQr ?? $sellerVatNumber ?? '310432040400003';
    $qr = Builder::create()->data($qrText)->size($zatcaQr ? 360 : 180)->margin(0)->build();
    $qrBase64 = base64_encode($qr->getString());
    @endphp
    <div class="footer-container">
        {{-- A B2B invoice carries the QR code ZATCA stamps it with once cleared,
             which the customer gets then; this copy carries none. --}}
        @if ($awaitingClearance)
            <p class="clearance-note">
                The tax invoice, with ZATCA's QR code, follows once ZATCA has cleared it.<br>
                <span class="rtl">تُرسل الفاتورة الضريبية برمز الهيئة بعد اعتمادها من الهيئة.</span>
            </p>
        @else
            <div class="qr-code-box{{ $zatcaQr ? ' zatca' : '' }}">
                <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
            </div>
        @endif
        <p class="gray">User ID: <strong>{{ $userId ?? 'N/A' }}</strong></p>
        <div>
            <p class="footer-message">We Wish You a Quick Recovery</p>
            <p class="footer-message rtl">نتمنى لكم الشفاء العاجل</p>
        </div>
    </div>
</body>

</html>