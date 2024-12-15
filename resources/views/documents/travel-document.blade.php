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

        /* Styling untuk tabel utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            /* Hilangkan jarak antar border */
        }

        .main-table th,
        .main-table td {
            border: 1px solid #ddd;
            padding: 10px;
            /* Jarak dalam sel */
            text-align: center;
            /* Teks di tengah secara horizontal */
            vertical-align: middle;
            /* Teks di tengah secara vertikal */
        }

        .main-table th {
            background-color: #f2f2f2;
            /* Tambahkan warna latar untuk header */
            font-weight: bold;
            /* Tebalkan teks header */
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
            padding-bottom: 2px;
            margin-bottom: 5px;
        }

        .logo {
            width: 100px;
            height: auto;
        }

        .sub-logo {
            width: 50px;
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
            font-size: 9pt;
            line-height: 1.1;
            display: flex;
            text-align: left;
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
    <table class="header" cellpadding="0" cellspacing="0" style="width: 100%; align-items: center;">
        <tr>
            <td style="vertical-align: middle; width: 100px; padding-right: 15px;"> <!-- Tambahkan padding-right -->
                <img src="{{ $warehouse === 'DNP' ? public_path('images/dnp_hd.png') : public_path('images/PT DKU.png') }}"
                    alt="Logo Perusahaan" class="logo">
            </td>
            <td style="vertical-align: middle; padding-left: 10px;"> <!-- Tambahkan padding-left -->
                <div class="company-address" style="line-height: 1.5;">
                    <!-- Tambahkan line-height untuk jarak antar baris -->
                    <span style="font-weight: bold; font-size: 18px;">
                        {{ $warehouse === 'DNP' ? 'PT. DANITAMA NIAGAPRIMA' : 'PT. DUTA KOMODITI UTAMA' }}

                    </span><br>
                    Jl. Raya Cikunir No 50A RT.003 RW.011 Kampung Jaha<br>
                    Kel. Jatimekar Kec. Jatiasih Bekasi 17422 Telp.<br>
                    021-29613236/29613237 Fax 021-29613235<br>
                </div>
            </td>
            <table style="width: 100%;border-collapse: collapse;">
                <tr>
                    <td class="left-cell" colspan="2"
                        style="border: 1px solid black; position: relative; padding: 10px;">
                        <span style="font-size: 20px; font-weight: bold; text-align: center;">Security</span>
                        <img src="{{ $warehouse === 'DNP' ? public_path('images/dnp_hd.png') : public_path('images/PT DKU.png') }}"
                            alt="Icon" style="float: right; width: 50px; height: auto; text-align: center;">
                    </td>
                </tr>
                <tr>
                    <td class="sub-left" style="border: 1px solid black; padding: 2px;">
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 15px; font-weight: bold;">
                            <span>Nama</span>
                            <span>:</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 5px; font-size: 15px; font-weight: bold;">
                            <span>TTD</span>
                            <span>:</span>
                        </div>
                    </td>

                    <td class="sub-left" style="border: 1px solid black; padding: 2px; text-align: center;">
                        <div style="margin-bottom: 10px; display: flex; flex-direction: column;">
                            <span style="font-size: 15px; font-weight: bold;">Tanggal</span><br>
                        </div>
                        <div style="text-align: center; font-weight: bold; font-size: 10px;">
                            <span>{{ $travel_document_date }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </tr>
    </table>

    <span style="display: block; text-align: center; font-weight: bold; font-size: 20px;">
        Surat Jalan
    </span>

    <!-- Customer and Transport Information -->
    <table class="customer-info" style="font-size: 12px; width: 100%; border-collapse: collapse;">
        <tr>
            <!-- Kolom Judul -->
            <td style="width: 20%; vertical-align: top; padding: 5px;">
                <strong>Nama Pelanggan</strong><br>
                <strong>Alamat</strong>
            </td>
            <!-- Kolom Isi -->
            <td style="width: 35%; vertical-align: top; padding: 5px;">
                <strong>: {{ $customer }}</strong><br>
                <strong>: {{ $customer_address }}</strong>
            </td>
            <!-- Kolom Judul Tambahan -->
            <td style="width: 15%; vertical-align: top; text-align: end; padding: 5px;">
                <strong>Nomor SJ</strong><br>
                <strong>Tanggal SJ</strong><br>
                <strong>No. Pol Kend</strong><br>
            </td>
            <!-- Kolom Isi Tambahan -->
            <td style="width: 25%; vertical-align: top; padding: 5px;">
                <strong>: {{ $travel_document->document_code }}</strong><br>
                <strong>: {{ $travel_document_date }}</strong><br>
                <strong>: {{ $number_plate }}</strong><br>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produksi</th>
                <th>Jenis Produk</th>
                <th>Jumlah</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->batch_code }}</td>
                    <td>{{ $product->name ?? 'null' }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->unit ?? 'null' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <h4>NOTE : SAMPLE DIBAWA PAK RUSDI</h4>

    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td class="sub-left" style="border: 1px solid black; padding: 5px; height: 130px; width: 33%">
                <div style="margin-bottom: 80px; text-align: center;">
                    <span>Dibuat Oleh,</span>
                </div>
                <div style="text-align: center;">
                    <div style="text-align: center; display: flex; align-items: center; text-align: left;">
                        <span style="padding-right: 11rem;">&#40;</span>
                        <span>&#41;</span>
                    </div>
                    <span>Administrasi Gudang</span>
                </div>
            </td>
            <td class="sub-left" style="border: 1px solid black; padding: 5px; height: 130px; width: 33%">
                <div style="margin-bottom: 80px; text-align: center;">
                    <span>Pembawa Barang</span>
                </div>
                <div style="text-align: center;">
                    <div style="text-align: center; display: flex; align-items: center; text-align: left;">
                        <span style="padding-right: 11rem;">&#40;</span>
                        <span>&#41;</span>
                    </div>
                    <span>Supir</span>
                </div>
            </td>
            <td class="sub-left" style="border: 1px solid black; padding: 5px; height: 130px; width: 33%">
                <div style="margin-bottom: 80px; text-align: center;">
                    <span>Diterima Oleh,</span>
                </div>
                <div style="text-align: center;">
                    <div style="text-align: center; display: flex; align-items: center; text-align: left;">
                        <span style="padding-right: 11rem;">&#40;</span>
                        <span>&#41;</span>
                    </div>
                    <span>Nama Terang Cap Perusahaan</span>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
