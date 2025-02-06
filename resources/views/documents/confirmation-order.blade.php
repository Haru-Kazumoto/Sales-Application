<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Confirmation Order</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 20px;
        }

        .content-table {
            margin-bottom: 20px;
        }

        .content-table td:first-child {
        padding-right: 50px; /* Tambahkan jarak khusus untuk kolom pertama */
    }

        .items-table td,
        .items-table th {
            border: 1px solid #000;
            padding: 5px;
        }

        .logo-cell {
            vertical-align: middle;
            display: flex;
            align-items: center;
            width: 30rem;
        }

        .logo {
            width: 150px;
        }

        .logo-text {
            font-size: 24px;
            color: #000;
            margin-left: 10px;
        }

        .address {
            font-size: 12px;
            text-align: right;
        }

        .customer-box {
            border: 1px solid #000;
            padding: 10px;
        }

        .border-bottom {
            border-bottom: 1px solid #000;
        }

        #buttondownload {
            position: fixed;
            bottom: 100px;
            right: 15px;
            z-index: 99;
            background-color: #ffffff;
            border-radius: 50px;
            color: #1583cb;
            text-decoration: none;
            width: 70px;
            height: 70px;
            font-size: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            -webkit-box-shadow: 0px 0px 25px -6px rgba(0, 0, 0, 1);
            -moz-box-shadow: 0px 0px 25px -6px rgba(0, 0, 0, 1);
            box-shadow: 0px 0px 25px -6px rgba(0, 0, 0, 1);
            animation: effect 5s infinite ease-in;
        }

        #buttondownload:hover::after {
            content: attr(data-text);
            /* Tampilkan keterangan */
            position: absolute;
            top: 30%;
            right: calc(100% + 10px);
            /* Sesuaikan posisi keterangan */
            background-color: #1583cb;
            color: #ffffff;
            padding: 5px;
            border-radius: 5px;
            font-size: 15px;
            white-space: nowrap;
            animation: fadeIn 0.3s ease-in-out;
        }

        @media print {
            #buttondownload {
                display: none !important;
            }
        }

        .summary-section {
            /* margin-top: 20px; */
            padding-top: 15px;
        }

        .summary-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: underline;
            border-width: 10px;
            color: black;
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
            color: black;
            font-size: 11.5px;
        }

        .summary-value {
            color: black;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- Header Table -->
    <table class="header-table border-bottom">
        <tr>
            <td width="30%">
                <table>
                    <tr>
                        <td class="logo-cell">
                            <img src="{{ public_path('images/dnp_hd.png') }}" alt="Logo Perusahaan" class="logo">
                        </td>
                    </tr>
                </table>
            </td>
            <td width="70%" class="address">
                PT. DANITAMA NIAGAPRIMA<br />
                Jl. Cikunir Raya No.50 A<br>
                RT003/RW.011 Kampung Jaha<br>
                Kel. Jatimekar Kec. Jatiasih<br>
                Bekasi - Jawa Barat<br>
                Tel. :  021-29613236 / 29613237<br>
                Fax: 021-29613235<br>
            </td>
        </tr>
    </table>

    <center>
        <span style="font-size: 20px; font-weight: bold;">CONFIRMATION ORDER</span>
    </center>

    <!-- Content Table -->
    <table class="content-table">
        <tr>
            <td width="50%">
                Kepada YTH:<br />
                <div class="customer-box">
                    {{ $document_info->name }}<br /><br />
                    {{-- {{ $customer_address }}<br /> --}}
                    <br>
                    {{ $document_info->address }}<br />
                </div>
            </td>
            <td width="45%" style="text-align: right">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td>{{ $document_info->document_code }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $date }}</td>
                    </tr>

                    <tr>
                        <td>Telphon</td>
                        <td>:</td>
                        <td>{{ $document_info->phone }}</td>
                    </tr>
                    <tr>
                        <td>Fax</td>
                        <td>:</td>
                        <td>{{ $document_info->fax || ''}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div>Mohon dikirim kepada kami barang-barang seperti dibawah ini : </div>
    <br />

    <!-- Items Table -->
    <table class="items-table" border="1" style="width: 100%; border-collapse: collapse;">
        <tr>
            <th style="width: 50%;">URAIAN</th>
            <th style="width: 13%;">Jumlah (Zak)</th>
            <th style="width: 15%; ">Harga (Rp)</th>
            <th style="width: 10%; ">Disc (Rp)</th>
            <th style="width: 30%; ">Total (Rp)</th>
        </tr>
        @foreach ($document_items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td style="text-align: right;">{{ number_format($item->amount, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ $item->discount }}%</td>
                <td style="text-align: right;">{{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid #000; text-align: right">Jumlah :</td>
            <td style="border: 1px solid #000;">{{ $result->total_all }}</td>
            <td style="border: 1px solid #000;" colspan="2">&nbsp;</td>
        <td style="border: 1px solid #000; text-align: right">{{ number_format($result->total_all_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 20px; text-align: center;">
        <tr>
            <td style="width: 17%; padding: 10px;">
                Buyer,
                <br><br>
                <br><br>
                _________________
                <br>
                Nama Lengkap
            </td>
            <td style="width: 18%; padding: 10px;">
                Prepared By
                <br><br>
                <br><br>
                _________________
                <br>
                Sales Marketing
            </td>
            <td style="width: 18%; padding: 10px;">
                Checked By, 
                <br><br>
                <br><br>
                _________________
                <br>
                Supervisor
            </td>
            <td style="width: 18%; padding: 10px;">
                PT. Danitama Niaga Prima
                <br><br>
                <br><br>
                ___________________
                <br>
                Direktur
            </td>
        </tr>
    </table>
    
    <div style="display: flex; width: 100%; border: 1px solid black;"></div>

    <div class="summary-section">
        <h3 class="summary-title">Syarat Penjualan : </h3>
        <table class="summary-table">
            <tr>
                <td width="20%">
                    <div class="summary-label">Pembayaran</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Tunai/Kredit ....... Hari Setelah Barang Diterima</div>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <div class="summary-label">Harga</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: Sudah Termasuk PPN</div>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <div class="summary-label">Syarat Penagihan</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: ..................................</div>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <div class="summary-label">Pembayaran Ditransfer ke</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: PT.Danitama Niaga Prima 
                        <br>-Bank Mandiri Cab. Komsen Jati Asih - Bekasi
                        <br>A/C NO.167-000-221-69-91
                        <br>-Bank BCA Cab Jati Asih
                        <br>A/C 6755.303.449
                    </div>
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <div class="summary-label">Jadwal Pengiriman</div>
                </td>
                <td width="40%">
                    <div class="summary-label">: {{ $date }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="summary-section">
        <h3 class="summary-title">Syarat Pembayaran Melampirkan : </h3>
        <table class="summary-table">
            @if (filter_var($document_info->invoice, FILTER_VALIDATE_BOOLEAN))
            <tr>
                <td width="20%">
                    <div class="summary-label">- Invoice</div>
                </td>
            </tr>
            @endif
        
            @if (filter_var($document_info->travel_document, FILTER_VALIDATE_BOOLEAN))
            <tr>
                <td width="20%">
                    <div class="summary-label">- Surat Jalan Asli CO/PO</div>
                </td>
            </tr>
            @endif
        
            @if (filter_var($document_info->tax_invoice, FILTER_VALIDATE_BOOLEAN))
            <tr>
                <td width="20%">
                    <div class="summary-label">- Faktur Pajak</div>
                </td>
            </tr>
            @endif
        
            @if (filter_var($document_info->receipt, FILTER_VALIDATE_BOOLEAN))
            <tr>
                <td width="20%">
                    <div class="summary-label">- Kwitansi</div>
                </td>
            </tr>
            @endif
        
            @if (filter_var($document_info->items_receipt, FILTER_VALIDATE_BOOLEAN))
            <tr>
                <td width="20%">
                    <div class="summary-label">- Surat Penerimaan Barang Asli</div>
                </td>
            </tr>
            @endif
        </table>
        
    </div>
</body>

</html>
