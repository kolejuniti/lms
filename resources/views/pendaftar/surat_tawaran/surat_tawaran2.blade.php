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
        body {
            background-image: url('{{ asset("assets/images/letter_head/letter_head.jpg") }}');
            background-size: cover; /* Cover the entire page */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Do not repeat the image */
            margin-bottom: 10cm;
            margin-left: 1cm;
            margin-right: 1cm;
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

            $formattedDate2 = Carbon::parse($data['student']->date_offer)->format('d/F/Y');

            // Convert the month to uppercase
            $formattedDate2 = strtoupper($formattedDate2);

        @endphp

        <br>
        <br>
        <p>Surat Kami : UNITI/KUSB/2023/040616070397</p>
        <p>Tarikh : {{ $formattedDate }}</p>
        <br>
        <p>
          {{ strtoupper($data['student']->name) }}<br>
          @if($data['address']->address1 != null){{ strtoupper($data['address']->address1) }}<br>@endif
          @if($data['address']->address2 != null){{ strtoupper($data['address']->address2) }}<br>@endif
          @if($data['address']->address3 != null){{ strtoupper($data['address']->address3) }}<br>@endif
          {{ $data['address']->postcode }}<br>
          {{ strtoupper($data['address']->city) }}, {{ strtoupper($data['address']->state) }}<br>
          {{ strtoupper($data['address']->country) }}
        </p>
        <br>
        <p>
          Saudara/Saudari,
        </p>
        <br>
        <p>
          <b>TAWARAN MENGIKUTI PROGRAM UiTM DI KOLEJ UNITI, PORT DICKSON</b>
        </p>
        <br>
        <p>
          <b>SYABAS DAN TAHNIAH</b> kerana saudara/saudari telah ditawarkan untuk melanjutkan pengajian di Kolej UNITI dengan kerjasama penuh Universiti Teknologi MARA bagi mengikuti program berikut:
        </p>
        <br>
        <p><strong>Program Pengajian &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $data['student']->progname }} - SEPENUH MASA</p>
        <p><strong>Tarikh &amp; Masa Lapor Diri &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> {{ $formattedDate2 }} (9.00 Pagi - 3.00 Petang)</p>
        <p><strong>Tempoh Pengajian &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> 2 Tahun 6 Bulan</p>   
        <p><strong>Tempat &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> PEJABAT PENTADBIRAN, KOLEJ UNITI</p>
        <br>
        <p>
          Semasa mendaftar, sila sertakan salinan dokumen seperti berikut yang telah disahkan terlebih dahulu:
        </p>
        {{-- <ol>
          <li>1 salinan keputusan SPM</li>
          <li>1 salinan Kad Pengenalan pelajar, ibu dan bapa</li>
          <li>1 salinan Sijil Kelahiran pelajar, ibu dan bapa</li>
          <li>1 salinan Sijil Tamat Persekolahan di peringkat menengah</li>
          <li>4 keping gambar berukuran paspot</li>
        </ol> --}}
        <div class="row">
            <div class="d-flex p-2">
                <div class="col-md-6">
                    <div class="form-group">
                        <ul>
                            <li>Surat Tawaran</li>
                            <li>4 keping gambar ukuran pasport</li>
                            <li>Salinan Kad Pengenalan pemohon dan penjaga/ibubapa (1 salinan)</li>
                            <li>Salinan Sijil Kelahiran pemohon dan penjaga/ibubapa (1 salinan)</li>
                            <li>Slip gaji penjaga/ibubapa terkini (1 salinan)</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <ul>
                            <li>Salinan Slip peperiksaan yang berkenaan (1 salinan)</li>
                            <li>Salinan Sijil Berhenti Sekolah (1 salinan)</li>
                            <li>Salinan Sijil Kurikulum - 3 Terbaik (1 salinan)</li>
                            <li>Buku Pendaftaran Pelajar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <p>
          Saudara/saudari juga dikehendaki membawa salinan asal dokumen di atas untuk tujuan penyemakan. Surat tawaran ini akan terbatal sekiranya saudara/saudari telah mendaftar untuk mengikuti program UiTM di mana-mana Kolej Bersekutu lain.
        </p>
        <br>
        <div class="row">
            <div class="d-flex">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Sekian, dimaklumkan.</p>
                    </div>
                </div>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
        <p>Yang benar,</p>
        <br>
        <img src="{{ asset('storage/signature/signature1.png') }}" alt="Image" width="10%" height="10%">
        <p>
        <b>AMIR HAMZAH BIN MD. ISA</b><br>
        KETUA EKSEKUTIF<br>
        <b>KOLEJ UNITI.</b>
        </p>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>