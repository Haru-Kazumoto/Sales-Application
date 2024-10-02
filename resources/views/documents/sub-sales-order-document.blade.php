<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO DOCUMENT</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 10mm 15mm 10mm;
            margin: 0 0 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 2cm;
        }

        .title {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        .title h2 {
            margin: 0;
            color: #444;
            font-size: 18px;
        }

        .title h5 {
            margin: 3px 0;
            color: #666;
            font-size: 13px;
        }

        .allign-info {
            display: flex;
            justify-content: space-between;
            /* Mengatur agar kedua div terpisah di kiri dan kanan */
            margin-bottom: 15px;
        }

        .allign-info div {
            width: 48%;
            /* Memberikan lebar pada kedua kolom */
        }

        .allign-info h5 {
            margin: 5px 0;
            font-size: 12px;
        }

        .allign-info div:first-child {
            text-align: left;
            /* Konten di kolom kiri */
        }

        .allign-info div:last-child {
            text-align: right;
            /* Konten di kolom kanan */
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        .main-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            /* margin-top: 20px; */
            padding-top: 15px;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
            color: #444;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 2px;
            vertical-align: top;
            border: none;
        }

        .summary-label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
        }

        .summary-value {
            color: #333;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        @media print {
            thead {
                display: table-header-group;
            }
        }

        body {
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 5px;
        }

        .logo {
            width: 130px;
            height: auto;
        }

        .company-name {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            justify-content: end;
        }

        .company-address {
            font-size: 10pt;
            line-height: 1.1;
            justify-content: end;
            display: flex;
            justify-content: end;
            text-align: right;
        }

        .document-title {
            font-size: 16pt;
            font-weight: bold;
            margin-top: 15px;
            text-align: center;
        }

        /* .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-table th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        .left-cell {
            width: 40%;
        }

        .right-cell {
            width: 100%;
        }

        .sub-left {
            width: 50%;
        } */
        .left-cell {
            width: 4%;
        }

        .right-cell {
            width: 3%;
        }

        .sub-left {
            width: 30%;
        }
    </style>
</head>

<body>
    <table class="header" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div class="company-name"></div>
                <div class="company-address">
                    <span style="font-weight: bold;">PT. DANITAMA NIAGA PRIMA</span><br>
                    Jl. Raya Cikunir, Kp Jaha Rt/Rw. 003/011, Jatimekar<br>
                    Jati Asih, Bekasi, 17422, Indonesia<br>
                    Telp : (021) 2961 3236, 2961 3237<br>
                    Fax: (021) 2961 3235<br>
                </div>
            </td>
        </tr>
    </table>

    <h2 style="text-align: center;">Sub Sales Order</h2>

    <!-- Customer and Transport Information -->
    <table class="customer-info" style="font-size: 12px;">
        <tr>
            <td style="width: 25%; vertical-align: top;">
                <strong>Nama Perusahaan</strong><br>
                <strong>Alamat</strong>
            </td>
            <td style="width: 60%; word-wrap: break-word; word-break: break-all;">
                <strong>: PT. Danitama Niagaprima</strong><br>
                <strong>: Jl. Cikunir Raya, Kp. Jaha, Jatimekar Jati Asih, Bekasi </strong>
            </td>
            <td style="width: 30%; vertical-align: top;">
                <strong>No. SO Induk</strong><br>
                <span>No. Sub SO</span><br>
                <span>Tanggal</span><br>
            </td>
            <td style="width: 25%; word-wrap: break-word; word-break: break-all;">
                <span>: SO24.2934</span><br>
                <span>: {{ $subSalesOrder->sales_order_number }}</span><br>
                <span>: {{ $subSalesOrder->order_date }}</span><br>
            </td>
        </tr>
    </table>

    <div style="display: flex; flex-direction: column; gap: 5px; font-size: 13px; ">
        <span style="line-height: 1.5;">
            Kepada Yth: <br>
            <strong>{{ $subSalesOrder->supplier }}</strong><br>
            Harap diberikan barang yang tercantum dibawah ini kepada : <br>
            <strong>PT / CV : GI. Transport</strong>
        </span>
    </div>

    <div style="justify-content: center; display: flex; font-size: 12px; text-align: center; ">
        <h2>Untuk : Danitama Niagaprima, PT<br>Jl. Raya Cikunir No 50A Bekasi</h2>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Nama Barang</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subSalesOrder->subSalesOrderProducts as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product->product_code }} - {{ $product->product_name }}</td>
                    <td>{{ $product->amount }} {{ $product->package }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td class="left-cell" colspan="2" style="border: 1px solid black; padding-bottom: 20px;">
                <span style="display: block; margin: 20px 0;">Diisi oleh petugas loading permit: </span>
                <!-- Jarak atas dan bawah ditambahkan menggunakan margin -->
                <table style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="width: 30%; padding-right: 10px;">
                            <span>NOPOL : -</span>
                        </td>
                        <td style="width: 30%; padding-right: 10px;">
                            <span>SOPIR : -</span>
                        </td>
                        <td style="width: 30%; padding-right: 10px;">
                            <span>KTP / SIM : -</span>
                        </td>
                    </tr>
                </table>
            </td>


            <td class="right-cell" rowspan="2"
                style="border: 1px solid black; padding: 10px; text-align: center; width: 20%; margin-top: 40px;">
                <div style="display: flex; flex-direction: column; gap: 150px;">
                    <span>Hormat Kami</span>
                    <span>Katimin</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="sub-left" style="border: 1px solid black; padding: 10px; height: 50px;">
                <div style="margin-bottom: 30px;">
                    <span style="padding-right: 50px;">GATE OFFICE</span>
                    <span>FP5</span>
                </div>
                <div style="text-align: center;">
                    <span >IN</span>
                </div>
            </td>
            <td class="sub-left" style="border: 1px solid black; padding: 10px; text-align: center; height: 50px;"></td>
        </tr>
    </table>
</body>

</html>
