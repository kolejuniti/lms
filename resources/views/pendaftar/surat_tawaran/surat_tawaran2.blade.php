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
        <title>Tawaran Program UiTM di Kolej UNITI, Port Dickson</title>
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
        size: A4; /* reduced height for A5 size in landscape orientation */
        margin: 2cm;
        }
        * {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 10px;
            
        }
        h2,h3,p {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 10px;
        }
        .form-group {
            page-break-inside: avoid;
        }
        </style>
    </head>
    <body>
        @php

            // Get the date two weeks before
            $twoWeeksBefore = Carbon::parse($data['student']->date_offer)->subWeeks(2);

            // Convert the date format
            $formattedDate = $twoWeeksBefore->format('d/m/Y');

        @endphp

        <div style="text-align: center;">
            <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="Logo" width="15%" height="15%">
            <h1>KOLEJ UNITI</h1>
            <p>Kompleks UNITI, 71250 Pasir Panjang<br>Port Dickson, Negeri Sembilan</p>
            <p>No. Telefon : 06-661 0518 / 9211 / 0517 / 06-6468 444<br>No. Faks : 06-661 0509 / 9022</p>
            <p><a href="http://www.uniti.edu.my">www.uniti.edu.my</a></p>
        </div>
        <br>
        <div style="border-top: 1px solid black;"></div>
        <br>
        <p>Surat Kami : UNITI/KUSB/2023/040616070397</p>
        <p>Tarikh : {{ $formattedDate }}</p>
        <br>
        <p>
          {{ strtoupper($data['student']->name) }}<br>
          {{ strtoupper($data['address']->address1) }}<br>
          {{ strtoupper($data['address']->address2) }}<br>
          {{ strtoupper($data['address']->address3) }}<br>
          {{ $data['address']->postcode }} {{ strtoupper($data['address']->state) }}<br>
          {{ strtoupper($data['address']->country) }}
        </p>
        <br>
        <p>
          Saudara/Saudari,
        </p>
        <br>
        <p>
          TAWARAN MENGIKUTI PROGRAM UiTM DI KOLEJ UNITI, PORT DICKSON
        </p>
        <br>
        <p>
          SYABAS DAN TAHNIAH kerana saudara/i telah ditawarkan untuk melanjutkan pengajian di Kolej UNITI dengan kerjasama penuh Universiti Teknologi MARA bagi mengikuti program berikut:
        </p>
        <br>
        <p><strong>Program Pengajian &amp; Kod &nbsp;:</strong> {{ $data['student']->progname }}</p>
        <p><strong>Tarikh &amp; Masa Lapor Diri &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> 01/SEP/2022 (9.00 Pagi - 3.00 petang)</p>
        <p><strong>Tempat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> PEJABAT PENTADBIRAN, KOLEJ UNITI</p>
        <br>
        <p>
          Semasa mendaftar, sila sertakan salinan dokumen seperti berikut yang telah disahkan terlebih dahulu:
        </p>
        <ol>
          <li>1 salinan keputusan SPM</li>
          <li>1 salinan Kad Pengenalan pelajar, ibu dan bapa</li>
          <li>1 salinan Sijil Kelahiran pelajar, ibu dan bapa</li>
          <li>1 salinan Sijil Tamat Persekolahan di peringkat menengah</li>
          <li>4 keping gambar berukuran paspot</li>
        </ol>
        <p>
          Saudara/i juga dikehendaki membawa salinan asal dokumen di atas untuk tujuan penyemakan. Surat tawaran ini akan terbatal sekiranya saudara/i telah mendaftar untuk mengikuti program UiTM di mana-mana Kolej Bersekutu lain.
        </p>
        <br>
        <div class="row">
            <div class="d-flex">
                <div class="col-md-6">
                <div class="form-group">
                    <p>Sekian, dimaklumkan.</p>
                    <br>
                    <p>Yang benar,</p>
                    <br>
                    <img src="{{ asset('storage/signature/signature1.png') }}" alt="Image" width="40%" height="40%">
                </div>
                </div>
                <div class="col-md-6">
                <div style="border: 1px solid black; padding: 10px;">
                    <div class="form-group">
                    <p>Nota Penting:</p>
                    <ol>
                        <li>Tawaran untuk mengikuti program Diploma Pengajian Sukan / Diploma Seni Lukis &amp; Seni Reka / Diploma Kejuruteraan Elektrik / Diploma Komunikasi &amp; Media adalah tertakluk kepada ujian khas atau temuduga yang akan diadakan terlebih dahulu.</li>
                        <li>Hanya pemohon yang memenuhi syarat minima kemasukan UiTM sahaja yang layak mendaftar.</li>
                        <li>Tawaran kemasukan ini akan dibatalkan oleh UiTM sekiranya calon mengemukakan maklumat palsu atau tidak benar.</li>
                    </ol>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <br>
        <p>
        AMIR HAMZAH BIN MD. ISA<br>
        KETUA EKSEKUTIF<br>
        KOLEJ UNITI.
        </p>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>