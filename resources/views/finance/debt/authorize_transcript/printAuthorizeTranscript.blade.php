@php

use Carbon\Carbon;

@endphp

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Surat Peringatan Pelajar Tidak Hadir Ke Kelas</title>
        <!-- Vendors Style-->
        <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
    <!-- Style-->  
    <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
    {{-- <link rel="stylesheet" media="screen, print" href="{{ asset('assets/src/css/datagrid/datatables/datatables.bundle.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/assets/vendor_components/datatable/datatables.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/css-skeletons@1.0.3/css/css-skeletons.min.css"/> --}}
    <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        * {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 12px;
        }
        html, body {
            height: 100%;
            width: 100%;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("assets/images/letter_head/letter_head.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }
        h2,h3,p {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 12px;
        }
        .form-group {
            page-break-inside: avoid;
        }
        .custom-table, .custom-table th, .custom-table td {
            border: 1px solid black;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }

        /* Use border instead of background */
        .border-line {
            width: 100%;
            border-top: 1px solid black; /* Border as the line */
            margin: 15px 0; /* Space around the line */
        }
    </style>
    <body>
        {{-- @php

            // Get the date two weeks before
            $twoWeeksBefore = Carbon::parse($data['student']->date_offer)->subWeeks(2);

            // Convert the date format
            $formattedDate = $twoWeeksBefore->format('d/m/Y');

        @endphp --}}
        <div class="row">
            <div class="col-12 d-flex">
                <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="" height="50">
                <address>
                    <strong>KOLEJ UNITI</strong><br>
                    PERSIARAN UNITI VILLAGE, TANJUNG AGAS<br>
                    71250, PORT DICKSON, NEGERI SEMBILAN.<br>
                    <abbr title="Phone">Tel:</abbr> 06-649 0350 | <abbr title="Phone">Fax:</abbr> 06-661 0509<br>
                    http://www.uniti.edu.my | <abbr title="Email">Email:</abbr> info@uniti.edu.my
                </address>
            </div>
        </div>
        <!-- Black Line Divider using Border -->
        <div class="border-line"></div>

        <p>Pelajar seperti berikut hanya dibenarkan mengambil salinan transkrip sahaja kerana mempunyai baki tunggakan semasa pengajian di Kolej Uniti:-</p>
        <div class="col-12 mb-1 mt-1">  
            <div style="border: 1px solid white; padding: 10px;">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $data['student']->name }}</b></td>
                    </tr>
                    <tr>
                        <td>No. Kad Pengenalan</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $data['student']->ic }}</b></td>
                    </tr>
                    <tr>
                        <td>No. Matriks</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $data['student']->no_matric }}</b></td>
                    </tr>
                    <tr>
                        <td>Program</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;<b>{{ $data['student']->code }} - {{ $data['student']->program }}</b></td>
                    </tr>
                </table>
            </div> 
        </div>
        <p>Pelajar boleh membuat tuntutan salinan transkrip bermula pada tarikh 21/10/2024 di Pejabat Pentadbiran Kolej Uniti dengan Pegawai Hal Akademik (Cik Suriya) atau permohonan melalui emel di suriya@uniti.edu.my</p>
        <br>
        {{-- <p style="text-align: center;"><b>[THIS IS A COMPUTER GENERATED AND DOES NOT REQUIRE SIGNATURE]</b></p> --}}
        <p>Dikeluarkan oleh:</p>
        <br>
        <p><b>EMI MARLIDA BINTI JABAR</b><br>
        <p>Ketua Unit</p>
        <p>Unit Penstrukturan Tunggakan Pelajar</p>
        <b>KOLEJ UNITI SDN BHD</b></p><br>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>