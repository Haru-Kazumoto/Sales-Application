<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            gap: 20px;
            width: 100%;
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
    </style>
</head>

<body>
    <table class="header" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <img src="{{ public_path('images/dnp_hd.png') }}" alt="Logo Perusahaan" class="logo">
            </td>
            <td>
                <div class="company-address">
                    <span style="font-weight: bold;">PT. DANITAMA NIAGA PRIMA</span><br>
                    Jl. Cikunir Raya No.50 A, RT003/RW.011<br>
                    Kota Bekasi 17422 - Indonesia<br>
                    Telphon: (021) 7258749, 7205444, 7207850<br>
                    Fax: (021) 7397847 Tlx. 47373 HANG IA<br>
                    E-mail: info@danitama.com
                </div>
            </td>
        </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Kolom Kiri -->
            <td align="left" style="width: 33%; vertical-align: top;">
                <div>
                    <h5>Kepada: </h5>
                    <span>
                        {{ $supplier }}<br>
                        Gedung Menara 165 Unit A&B lt8 Jl TB<br>
                        Simatupang Kav.1 Rt 003/ rw 003 Cilandak
                    </span>
                </div>
            </td>

            <!-- Kolom Tengah -->
            <td align="center" style="width: 40%; vertical-align: top;">
                <h2 style="text-decoration: underline;">Purchase Order</h2>
            </td>

            <!-- Kolom Kanan -->
            <td align="left" style="width: 27%; vertical-align: top; line-height: 1.4;">
                <div>
                    <h5>
                        No. PO : {{ $purchase_order->document_code }}<br>
                        Tanggal : {{ $purchase_order_date }}<br>
                        No SO<br>
                        CC<br>
                    </h5>
                </div>
            </td>
        </tr>
    </table>


    <table class="main-table" style="margin-top: 10px;">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode & Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subsidi</th>
                <th>Disc.</th>
                <th>Harga Net</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase_order->transactionItems as $index => $txItem)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $txItem->product->code }} - {{ $txItem->product->name }}</td>
                    <td>{{ $txItem->quantity }}</td>
                    <td>Rp {{ number_format($txItem->amount, 0, ',', '.') }}</td>
                    <td class="text-center"></td>
                    <td class="text-right"></td>
                    <td class="text-center">Rp {{ number_format($txItem->amount, 0, ',', '.') }}</td>
                    <td class="text-center">Rp {{ number_format($txItem->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; justify-content: end; text-align: right;">
        <h4 style="line-height: 1.5;">
            PPN 11% : Rp {{ number_format($purchase_order->tax_amount, 0, ',', '.') }}<br>
            Grand Total : Rp {{ number_format($purchase_order->total, 0, ',', '.') }}
        </h4>
    </div>

    <div class="summary-section">
        <h3 class="summary-title">Syarat-syarat</h3>
        <table class="summary-table">
            <tr>
                <td width="10%">
                    <div class="summary-label">1. Nama Pembeli</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: PT DANITAMA NIAGAPRIMA</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">2. Alamat Pembeli</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Jl. Raya Cikunir No.50, Jati Asih, Bekasi</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">3. Tanggal Pengambilan</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: *SEGERA*</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">4. Harga</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Termasuk PPN 11%</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">5. Cara Pembayaran</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: 30/45/60 hari setelah barang diterima</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">6. Faktur Pajak / NPWP</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Perlu NPWP : 01.2345.239423.234</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">7. Ongkos Angkut</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: -</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">8. Catatan</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Beban Pabrik</div>
                </td>
            </tr>
            <tr>
                <td width="10%">
                    <div class="summary-label">9. Keterangan</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: PO-123-123</div>
                    <div class="summary-label">: PO dan syarat syarat tersebut didalamnya merupakan kontrak jual
                        beli<br>
                        dan mengikat kedua belah pihak
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="summary-section" style="margin-top: 30px;">
        <table class="summary-table">
            <tr>
                <td width="40%">
                    <div style="display: flex; flex-direction: column; gap: 7px; align-items: center;">
                        <div style="font-size: medium;">Hormat kami,</div>
                        <div style="font-weight: bold; font-size: 13px; margin-bottom: 100px;">PT DANITAMA NIAGAPRIMA
                        </div>
                        <div style="font-weight: medium; font-size: 13px;">Rochtji Thaher</div>
                        <div style="font-weight: bold; font-size: 11px; ">PT DANITAMA NIAGAPRIMA</div>
                    </div>
                </td>
                <td width="40%">
                    <div style="display: flex; flex-direction: column; gap: 7px; align-items: center;">
                        <div style="font-size: medium; margin-bottom: 130px;">Menyetujui,</div>
                        <div style="font-weight: bold; font-size: 11px; ">{{ $supplier }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
