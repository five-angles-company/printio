<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Closing Receipt</title>
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
            justify-content: center;
            font-size: 24px;
            width: 100%;
        }

        .logo {
            width: 70%;
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .receipt-container {
            width: 100%;
            margin-top: 1rem;
            font-size: inherit;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.3em;
            margin: 1.5rem 0;
            border-bottom: 2px solid black;
            padding-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .session-table tr {
            border-bottom: 1px solid #000;
        }

        .session-table td {
            padding: 0.8rem 1rem;
        }

        .session-table .label {
            text-align: left;
            font-weight: 600;
        }

        .session-table .value {
            text-align: right;
            font-weight: 400;
        }

        .session-log {
            margin-top: 2rem;
            border: 2px dashed #666;
            padding: 1rem;
        }

        .session-log-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1rem;
        }

        .log-entry {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-top: 1px dashed #666;
        }

        .log-entry:first-of-type {
            border-top: none;
        }

        .log-time {
            text-align: center;
            font-size: 0.95em;
            padding: 0.5rem 0;
            border-top: 1px dashed #666;
        }

        .log-action {
            text-align: left;
            font-weight: bold;
        }

        .log-user {
            text-align: right;
            font-weight: bold;
        }

        .footer-logo {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem 0;
        }

        .footer-logo img {
            width: 80px;
            height: auto;
        }

        .footer-date {
            text-align: center;
            font-size: 0.9em;
            color: #333;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo"
        class="logo">

    <div class="receipt-container">
        <div class="header-title">Session Closing Receipt</div>

        <table class="session-table">
            <tbody>
                <tr>
                    <td class="label">Branch :</td>
                    <td class="value">{{ $branchName }}</td>
                </tr>
                <tr>
                    <td class="label">Date :</td>
                    <td class="value">{{ \Carbon\Carbon::parse($createdAt)->format('d F, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Session :</td>
                    <td class="value">
                        @php
                            $hour = \Carbon\Carbon::parse($createdAt)->hour;
                            if ($hour >= 6 && $hour < 12) {
                                echo 'MORNING';
                            } elseif ($hour >= 12 && $hour < 18) {
                                echo 'AFTERNOON';
                            } elseif ($hour >= 18 && $hour < 22) {
                                echo 'EVENING';
                            } else {
                                echo 'NIGHT';
                            }
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td class="label">Session No :</td>
                    <td class="value">{{ $id }}</td>
                </tr>
                <tr>
                    <td class="label">Opened At :</td>
                    <td class="value">{{ \Carbon\Carbon::parse($createdAt)->format('d M, Y h:i:s A') }}</td>
                </tr>
                <tr>
                    <td class="label">Cash :</td>
                    <td class="value">{{ number_format($totalCash, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Net Cash :</td>
                    <td class="value">{{ number_format($netCash, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Mada :</td>
                    <td class="value">{{ number_format($totalMada, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Visa :</td>
                    <td class="value">{{ number_format($totalVisa, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Card :</td>
                    <td class="value">{{ number_format($totalCard, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Moqafaat :</td>
                    <td class="value">{{ number_format($totalMoqafaat, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">STC Pay :</td>
                    <td class="value">{{ number_format($totalStc, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tabby :</td>
                    <td class="value">{{ number_format($totalTabby, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tamara :</td>
                    <td class="value">{{ number_format($totalTamara, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Insurance :</td>
                    <td class="value">{{ number_format($totalInsurance, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Ins. Disc. :</td>
                    <td class="value">{{ number_format($insuranceDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Cust. Disc. :</td>
                    <td class="value">{{ number_format($customerDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Cash Return :</td>
                    <td class="value">{{ number_format($cashReturn, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Ins. Return :</td>
                    <td class="value">{{ number_format($insuranceReturn, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Sale Invoices :</td>
                    <td class="value">{{ $totalSaleInvoices }}</td>
                </tr>
                <tr>
                    <td class="label">Return Invoices :</td>
                    <td class="value">{{ $totalReturnInvoices }}</td>
                </tr>
                <tr>
                    <td class="label">Total Session :</td>
                    <td class="value">{{ $totalSession }}</td>
                </tr>
                <tr>
                    <td class="label">Total Sales :</td>
                    <td class="value">{{ number_format($totalSales, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Discrepancies :</td>
                    <td class="value">{{ number_format($discrepancies, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Op. Balance :</td>
                    <td class="value">{{ number_format($startBalance, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Cl. Balance :</td>
                    <td class="value">{{ number_format($closingBalance, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Total Time :</td>
                    <td class="value">{{ $totalTime }}</td>
                </tr>
                <tr>
                    <td class="label">Closed At :</td>
                    <td class="value">{{ \Carbon\Carbon::parse($closedAt)->format('d M, Y h:i:s A') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="session-log">
            <div class="session-log-title">Session Log</div>

            <div class="log-time">{{ \Carbon\Carbon::parse($createdAt)->format('d M, Y h:i:s A') }}</div>
            <div class="log-entry">
                <div class="log-action">OPENED</div>
                <div class="log-user">{{ strtoupper($userName) }}</div>
            </div>

            <div class="log-time">{{ \Carbon\Carbon::parse($closedAt)->format('d M, Y h:i:s A') }}</div>
            <div class="log-entry">
                <div class="log-action">CLOSED</div>
                <div class="log-user">{{ strtoupper($userName) }}</div>
            </div>
        </div>

        <div class="footer-date">
            {{ \Carbon\Carbon::parse($closedAt)->format('l, d F, Y h:i:s A') }}
        </div>

    </div>
</body>

</html>
