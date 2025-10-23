<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>وصفة طبية</title>
    <style>
        body {
            font-family: "Cairo", sans-serif;
            direction: rtl;
            font-size: 24px;
            font-weight: bold;
        }

        table {
            border: 2px solid #000;
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }

        td {
            border: 2px solid #000;
            padding: 10px;
        }

        .header {
            font-size: 20px;
            font-weight: bold;
        }

        .usage {
            text-align: right;
            padding-right: 20px;
        }
    </style>
</head>

<body>

    <table>
        <tr>
            <td colspan="2" class="header">صيدلية المحارب</td>
        </tr>
        <tr>
            <td colspan="2">
                {{ $data->productName }}
            </td>
        </tr>
        <tr>
            <td>اسم المستفيد</td>
            <td>{{ $data->patientName }}</td>
        </tr>
        <tr>
            <td>Patient</td>
            <td>{{ $data->patientName }}</td>
        </tr>
        <tr>
            <td colspan="2" class="usage"><b>Usage / الاستخدام:</b></td>
        </tr>
        <tr>
            <td colspan="2" class="usage">- {{ $data->line1 }}</td>
        </tr>
        <tr>
            <td colspan="2" class="usage">- {{ $data->line2 }}</td>
        </tr>
        <tr>
            <td colspan="2" class="usage">- {{ $data->line3 }}</td>
        </tr>
        <tr>
            <td colspan="2" class="usage">- {{ $data->line4 }}</td>
        </tr>
        <tr>
            <td>الصيدلي</td>
            <td>{{ $data->pharmacistName }}</td>
        </tr>
        <tr>
            <td>Pharmacist</td>
            <td>{{ $data->pharmacistName }}</td>
        </tr>
    </table>

</body>

</html>
