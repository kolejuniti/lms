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
        <title>Tawaran Kemasukan ke Program Akademik Kolej UNITI</title>
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
            font-size: 14px;
            
        }
        body {
                background-image: url('{{ asset("assets/images/letter_head/letter_head.jpg") }}');
                background-size: cover; /* Cover the entire page */
                background-position: center; /* Center the background image */
                background-repeat: no-repeat; /* Do not repeat the image */
            }
        h2,h3,p {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 14px;
        }
        .form-group {
            page-break-inside: avoid;
        }

        .custom-table, .custom-table th, .custom-table td {
            border: 1px solid black; /* Adds black grid lines */
        }

        .custom-table {
            width: 100%; /* Ensures the table stretches to the container width */
            border-collapse: collapse; /* Removes double borders */
        }

        .text-center {
            text-align: center; /* Centers text */
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
        <br>
        <br>
        <br>
        <br>
        <p>Ruj. Kami : KUSB/KU/HEA/{{ $data['student']->progcode }}/{{ str_replace(' ', '', $data['warning']->course_code)  }}/{{ $data['student']->no_matric }}/0{{ $data['warning']->warning }}</p>
        <p>Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{ $formattedDate }}</p>
        <br>
        <p>Kepada:-</p>
        <div class="col-12 mb-1 mt-1">  
            <div style="border: 1px solid white; padding: 10px;">
            <p>Nama Pelajar     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['student']->name }}</p>
            <p>No. Matric Pelajar &nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['student']->no_matric }}</p>
            <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['student']->semester }}</p>
            <p>Nama Program &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['student']->progname }}</p>
            <p>Sesi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['warning']->SessionName }}</p>   
            </div> 
        </div>
        <p>Saudara/Saudari,</p>
        <p class="mt-2"><b>SURAT PERINGATAN {{ $data['warning']->warning }} : KETIDAKHADIRAN KE KULIAH/TUTORIAL BAGI KURSUS {{ str_replace(' ', '', $data['warning']->course_code)  }} – {{ $data['warning']->course_name  }}</b></p>
        <p class="mt-2">Laporan telah dibuat bahawa pada 
        @php
        $dates = $data['absent']->pluck('date')->toArray();
        $lastDate = array_pop($dates); // Remove the last date to handle it separately
        $datesString = $dates ? implode(', ', $dates) : '';
        $datesString .= count($dates) ? ' dan ' : '';
        $datesString .= $lastDate;
        echo $datesString;
        @endphp 
        anda telah tidak hadir ke kuliah/tutorial di atas seperti yang telah tersenarai di bawah ini tanpa sebab:-</p>
        <div class="col-md-12 mt-2">
            <table class="custom-table">
                <thead>
                    <tr class="line">
                        <th class="text-center" rowspan="2">Bil</th>
                        <th class="text-center" colspan="3">Tidak Hadir Kuliah/Tutorial</th>
                        <th class="text-center" rowspan="2">Peratus Keseluruhan Kehadiran</th>
                    </tr>
                    <tr class="line">
                        <th class="text-center">Tarikh</th>
                        <th class="text-center">Hari</th>
                        <th class="text-center">Masa</th>
                </thead>
                <tbody>
                    @foreach($data['absent'] as $key => $abs)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td class="text-center">{{ $abs['date'] }}</td>
                        <td class="text-center">{{ $abs['day'] }}</td>
                        <td class="text-center">{{ $abs['time1'] }} – {{ $abs['time2'] }}</td>
                        @if($key+1 == 1)
                        <td rowspan="{{ count($data['absent']) }}" class="text-center">{{ $data['warning']->balance_attendance }}/{{ $data['courseCredit']->total }} <br>{{ $data['warning']->percentage_attendance }}% </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-2">Adalah diingatkan mengenai para 3.8.4 (c), Peraturan Akademik Kolej UNITI Pindaan 2021 seperti berikut: </p>
        <p class="mt-3"><i>“Pelajar yang kehadirannya kurang daripada 80% dalam sesuatu kursus tanpa sebabsebab yang boleh diterima akan dikira gagal dalam kursus tersebut.”</i></p>
        <p class="mt-3">Dengan ini anda diberi amaran bahawa sekiranya kehadiran yang tidak memuaskan
            ini berterusan, pihak Kolej berhak mengambil tindakan terhadap anda mengikut para
            3.8.4 (c) seperti di atas.</p>
        <p class="mt-1 mb-1">Sekian, terima kasih.</p>
        <p>Yang benar,</p>
        <img src="{{ asset('storage/signature/signature2.png') }}" alt="Image" width="10%" height="10%">

        <p><b>Azhar bin Zunaidak</b><br>
        Penolong Pendaftar Akademik<br>
        <b>KOLEJ UNITI</b></p><br>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>