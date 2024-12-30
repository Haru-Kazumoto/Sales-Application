<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FAKTUR</title>

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
            width: 100px;
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
                            <span class="logo-text">DANITAMA NIAGAPRIMA</span>
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

    <!-- Content Table -->
    <table class="content-table">
        <tr>
            <td width="55%">
                <div class="customer-box">
                    Kepada YTH:<br />
                    {{ $customer }}<br /><br />
                    {{ $customer_address }}<br />
                    <br>
                    NPWP : {{ $customer_npwp }}<br />
                </div>
            </td>
            <td width="45%" style="text-align: right">
                <table>
                    <tr>
                        <td>No Faktur</td>
                        <td>:</td>
                        <td>{{ $invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $invoice_date }}</td>
                    </tr>

                    <tr>
                        <td>No. PO</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>No. CO</td>
                        <td>:</td>
                        <td>{{ $co_number }}</td>
                    </tr>
                    <tr>
                        <td>No. Surat Jalan</td>
                        <td>:</td>
                        <td>{{ $travel_document_number }}</td>
                    </tr>
                    <tr>
                        <td>No. Polisi</td>
                        <td>:</td>
                        <td>{{ $number_plate }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Kirim</td>
                        <td>:</td>
                        <td>{{ $customer_return_address }}</td>
                    </tr>
                    <tr>
                        <td>Lama Pembayaran</td>
                        <td>:</td>
                        <td>{{ $payment_terms }} HARI</td>
                    </tr>
                    <tr>
                        <td>Tanggal Jatuh Tempo</td>
                        <td>:</td>
                        <td>{{$due_date}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div>Salesman: {{ $salesman }}</div>
    <br />

    <!-- Items Table -->
    <table class="items-table">
        <tr>
            <th>No.</th>
            <th>Kode & Nama Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
        @foreach ($transaction_items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['product_code'] }} - {{ $item['product_name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit'] }}</td>
                <td style="text-align: right">{{ number_format($item['amount'], 0, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($item['total_price'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <!-- Total Table -->
    <table style="margin-top: 20px">
        <tr>
            <td width="60%">
                Harap ditransfer ke:<br />
                PT. DANITAMA NIAGAPRIMA<br />
                NPWP: 01.346.766.7-064.000<br />
                BANK CENTRAL ASIA CAB HASANUDIN JAKARTA A/C NO. 523.0300.200<br />
                BANK CENTRAL ASIA CAB JATIASIH A/C NO. 675.5010.255
            </td>
            <td width="40%" style="text-align: right">
                <table>
                    <tr>
                        <td>Jumlah</td>
                        <td>: Rp</td>
                        <td style="text-align: right">{{ number_format($sub_total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Ppn 11%</td>
                        <td>: Rp</td>
                        <td style="text-align: right">{{ number_format($tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>: Rp</td>
                        <td style="text-align: right">{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signature Table -->
    <table style="margin-top: 100px">
        <tr>
            <td width="40%" style="text-align: left">
                {{-- <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Tanda_Tangan_Greg_Moore.png" alt="Signature" width="120" /><br /> --}}
                <span style="font-weight: bold">Rechtji Thaher</span><br />
                <span style="font-style: italic">Direktur</span>
            </td>
        </tr>
    </table>

    {{-- <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script> --}}
    {{-- <button onclick="clickdownload()" id="buttondownload" data-text="Download">
      <img
        src="https://w7.pngwing.com/pngs/596/75/png-transparent-download-now-download-icon-download-button-download-logo-flat-icon-flat-logo-flat-image-button-flat-round.png"
        alt=""
        style="width: 50px; height: 50px"
      />
    </button> --}}

    {{-- <script>
      function clickdownload() {
        var element = document.body;
        hideButtons();

        var fileName = "Surat Jalan";
        html2pdf(element, {
          filename: fileName,
        }).then(() => {
          // Setelah proses download selesai, tampilkan kembali tombol setelah beberapa waktu (dalam contoh ini, 5 detik)
          setTimeout(showButtons, 1000);
        });
      }

      function hideButtons() {
        var downloadButton = document.getElementById("buttondownload");

        downloadButton.style.display = "none";
      }

      function showButtons() {
        var downloadButton = document.getElementById("buttondownload");

        downloadButton.style.display = "block";
      }
    </script> --}}
</body>

</html>
