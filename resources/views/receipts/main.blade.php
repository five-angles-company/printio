<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt Template</title>
    <style>
      body {
        font-family: 'Cairo', sans-serif;
        margin: 0 auto;
        padding: 0;
        background-color: white;
        color: black;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 28px; /* was 22px */
      }

      .logo {
        width: 50%; /* slightly wider logo */
      }

      .receipt-container {
        width: 100%;
        margin-top: 2rem;
        font-size: inherit;
      }

      table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
      }

      td {
        vertical-align: top;
        padding: 1rem; /* was 0.75rem */
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
        font-size: 1.3em; /* was 1.1em */
      }

      .arabic-smaller {
        font-size: 1.15em; /* was 1em */
      }

      .bold {
        font-weight: bold;
      }

      .border-top {
        border-top: 2px solid black;
      }

      .border-bottom {
        border-bottom: 4px solid black;
      }

      tr.border-bottom td {
        border-bottom: 2px solid black;
      }

      .padding-md {
        padding: 1.5rem 1rem; /* increased padding */
      }

      .padding-sm {
        padding: 1.25rem 0.75rem;
      }

      .qr-code-box {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem; /* bigger text inside QR */
        border: 2px solid #000;
      }

      .footer-message {
        margin: 0.5rem 0;
        font-weight: bold;
        font-size: 1.5rem;
      }

      .footer-container {
        text-align: center;
        margin-top: 3rem;
        padding: 2rem 0;
      }

      .gray {
        color: #666;
        font-size: 1.4rem;
      }
    </style>
  </head>
  <body>
    

    <div class="receipt-container">
      <table>
        <tbody>
          <tr>
            <td class="left">{{ $data['brand'] ?? 'Brand' }} Branch</td>
            <td class="center">01</td>
            <td class="right">فرع المجمعة</td>
          </tr>
          <tr>
            <td class="left">VAT Reg. No.</td>
            <td class="center">310432040400003</td>
            <td class="right">الرقم الضريبي</td>
          </tr>
          <tr>
            <td class="left">Receipt No.</td>
            <td class="center">PHI-20299</td>
            <td class="right">رقم الفاتورة</td>
          </tr>
          <tr>
            <td class="left">Date / Time</td>
            <td class="center">{{ $data['date'] ?? now()->format('d/m/Y H:i') }}</td>
            <td class="right">التاريخ / الوقت</td>
          </tr>
          <tr>
            <td class="left">Telephone No.</td>
            <td class="center">{{ $data['phone'] ?? 'N/A' }}</td>
            <td class="right">رقم الهاتف</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="receipt-container">
      <table style="margin-top: 2rem">
        <thead>
          <tr class="border-bottom">
            <td class="left padding-md bold">Product<br /><span class="rtl">الصنف</span></td>
            <td class="center padding-md bold">Quantity<br /><span class="rtl">الكمية</span></td>
            <td class="center padding-md bold">Price<br /><span class="rtl">السعر</span></td>
            <td class="center padding-md bold">Total<br /><span class="rtl">الإجمالي</span></td>
          </tr>
        </thead>
        <tbody>
          @foreach($data['items'] ?? [] as $item)
            <tr class="border-bottom">
              <td class="left padding-sm">
                {{ $item['name'] ?? 'Product Name' }}<br />
                <!-- If you have Arabic names, insert here -->
                @if(isset($item['arabic_name']))
                  <span class="arabic-small">{{ $item['arabic_name'] }}</span>
                @endif
              </td>
              <td class="center padding-sm">{{ $item['quantity'] ?? 0 }}</td>
              <td class="center padding-sm">{{ number_format($item['unitPrice'] ?? 0, 2) }}</td>
              <td class="center padding-sm">{{ number_format($item['totalPrice'] ?? 0, 2) }}</td>
            </tr>
          @endforeach

          <tr class="border-top">
            <td class="left padding-md bold">
              Total Quantity<br /><span class="rtl">إجمالي الكمية</span>
            </td>
            <td class="center padding-md bold">
              {{ collect($data['items'] ?? [])->sum('quantity') }}
            </td>
            <td class="center padding-md"></td>
            <td class="center padding-md"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="receipt-container">
      <table style="margin-top: 2rem">
       <tbody>
          <tr>
            <td class="left padding-sm">
              Total Amount Without VAT<br />
              <span class="arabic-small">إجمالي القيمة بدون الضريبة</span>
            </td>
            <td class="right padding-sm bold">{{ number_format($data['subtotal'] ?? 0, 2) }} SAR</td>
          </tr>
          <tr>
            <td class="left padding-sm">
              Total VAT.<br />
              <span class="arabic-small">إجمالي ضريبة القيمة المضافة</span>
            </td>
            <td class="right padding-sm bold">{{ number_format($data['tax'] ?? 0, 2) }} SAR</td>
          </tr>
          <tr class="border-bottom">
            <td class="left padding-sm">
              Total Amount + VAT<br />
              <span class="arabic-small">إجمالي القيمة شامل الضريبة</span>
            </td>
            <td class="right padding-sm bold">{{ number_format($data['total'] ?? 0, 2) }} SAR</td>
          </tr>
          <!-- Additional rows if needed -->
        </tbody>
      </table>
    </div>

    <div class="footer-container">
      <img src="{{ $qrcode ?? '' }}" alt="QR Code" style="width: 100%; height: 100%;" />
      <p class="gray">User ID: <strong>{{ $data['clientId'] ?? 'N/A' }}</strong></p>
      <div>
        <p class="footer-message">We Wish You a Quick Recovery</p>
        <p class="footer-message rtl">نتمنى لكم الشفاء العاجل</p>
      </div>
    </div>
  </body>
</html>