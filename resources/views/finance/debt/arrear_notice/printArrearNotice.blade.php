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
        size: A4; /* reduced height for A5 size in landscape orientation */
        margin: 1cm;
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
            font-size: 12px;
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
        {{-- @php

            // Get the date two weeks before
            $twoWeeksBefore = Carbon::parse($data['student']->date_offer)->subWeeks(2);

            // Convert the date format
            $formattedDate = $twoWeeksBefore->format('d/m/Y');

        @endphp --}}
        <br>
        <br>
        <br>
        <br>
        <p>Ruj. Kami : KUSB/UPTP/TAMATPENGAJIAN/2024/{{ $data['student']->no_matric }}</p>
        <p>Tarikh &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {{ $data['originalDate'] }}</p>
        <br>
        <p>Kepada:-</p>
        <div class="col-12 mb-1 mt-1">  
            <div style="border: 1px solid white; padding: 10px;">
            <b>
            <p>{{ $data['student']->name }}</p>
            <br>
            <p>{{ $data['student']->address1 }}</p>
            <p>{{ $data['student']->address2 }}, {{ $data['student']->address3 }}</p>
            <p>{{ $data['student']->postcode }} {{ $data['student']->city }}</p>
            <p>{{ $data['student']->state }}</p>
            </b>   
            </div> 
        </div>
        <p>Tuan/Puan,</p>
        <p class="mt-2"><i>Assalamualaikum WRT. WBT.</i></p>
        <p class="mt-2"><b>NOTIS TUNGGAKAN YURAN PENGAJIAN PELAJAR {{ strtoupper($data['student']->name) }} - {{ $data['student']->ic }}</b></p>
        <p class="mt-2">Dengan segala hormatnya, perkara di atas adalah dirujuk.</p>
        <p class="mt-2">2. Mengikut rekod Kolej Uniti Sdn Bhd sehingga <b>{{ $data['originalDate'] }}</b>, jumlah tunggakan pembiayaan pelajaran tuan/puan dengan pihak Kolej Uniti Sdn Bhd adalah berjumah <b>{{ $data['balance']->balance }}</b></p>
        <p class="mt-2">3. Seperti persetujuan dalam pakej kewangan pelajar yang telah dipersetujui pihak tuan/puan, dikehendaki membayar tunggakan tersebut secara ansuran selepas 6 bulan menamatkan tempoh pengajian seperti berikut:</p>
        <div class="col-md-12 mt-2">
            <table class="custom-table">
                <thead>
                    <tr class="line">
                        <th class="text-center">JUMLAH TUNGGAKAN</th>
                        <th class="text-center">NILAI ANSURAN BULANAN</th>
                        <th class="text-center">TEMPOH ANSURAN</th>
                        <th class="text-center">TARIKH BERMULA</th>
                        <th class="text-center">TARIKH SELESAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">RM{{ $data['balance']->balance }}</td>
                        <td class="text-center">RM{{ $data['details']['money'] }}</td>
                        <td class="text-center">{{ $data['details']['period'] }} BULAN</td>
                        <td class="text-center">{{ $data['startDate'] }}</td>
                        <td class="text-center">{{ $data['endDate'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mt-2">4. Jika terdapat sebarang pertanyaan, masalah atau perlukan sebarang rayuan ansuran, tuan/puan boleh menghubungi Puan Emi Marlida Binti Jabar / Puan Nur Azwa Najwa Binti Mohamad Zaidi (Unit Penstrukturan Tunggakan Pelajar) di talian 06-6490350.</p>
        <p class="mt-2">5. Bersama ini disertakan penyata kewangan dan salinan pakej kewangan untuk rujukan pihak tuan/puan. Pelajar juga boleh membuat semakan tunggakan di <b>Portal Pelajar Kolej Uniti</b> dan membuat bayaran menerusi pindahan bank ke akaun kolej (<b>KOLEJ UNITI SDN BHD : Bank Muamalat - 1402 0000 9187 15</b>). Bukti bayaran perlu di whatsapp ke nombor <b>016-9631663</b>. Sila lampirkan nama, nombor matrik dan nombor kad pengenalan pelajar sebagai rujukan pihak kolej.</p>
        <p class="mt-3">Sila sahkan penerimaan notis dan persetujuan ansuran ini. Kegagalan mengesahkan menerima notis ini akan menyebabkan pihak kami tiada pilihan selain menggunakan saluran yang dibenarkan perundangan.</p>
        <p class="mt-3">Segala kerjasama daripada pihak tuan/puan mengesahkan baki tersebut amatlah dihargai</p>
        <p class="mt-1 mb-1">Sekian, terima kasih.</p>
        <br>
        {{-- <p style="text-align: center;"><b>[THIS IS A COMPUTER GENERATED AND DOES NOT REQUIRE SIGNATURE]</b></p> --}}
        {{-- <p>Yang benar,</p> --}}
        <br>
        <p><b>_____________</b></p>
        <br>
        <p><b>NORASIAH JAMHARI</b><br>
        <p>Ketua Unit Kewangan Pelajar</p>
        <br>
        <b>KOLEJ UNITI SDN BHD</b></p><br>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>