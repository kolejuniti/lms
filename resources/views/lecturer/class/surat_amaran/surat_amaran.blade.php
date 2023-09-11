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
            font-size: 13px;
            
        }
        h2,h3,p {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            font-size: 13px;
        }
        .form-group {
            page-break-inside: avoid;
        }
        </style>
    </head>
    <body>
        @php

            // // Get the date two weeks before
            // $twoWeeksBefore = Carbon::parse($data['student']->date_offer)->subWeeks(2);

            // // Convert the date format
            // $formattedDate = $twoWeeksBefore->format('d/m/Y');

        @endphp

        <p class="mt-2">Surat Kami :KUSB/KU/HEA/DPK/DPU2013/nomatric/01</p>
        <p>Tarikh : 30 Mei 2023</p>
        <br>
        <p>Kepada:-</p>
        <br>
        <p>Name Pelajar &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&thinsp;&thinsp;: Noor Syafina binti Syahdi</p>
        <p>No. Matric Pelajar : 22230669</p>
        <p>Semester &thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;: 2</p>
        <p>Name Program &thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;: Diploma Psikologi Kaunseling (DPK)</p>
        <p>Semester/Sesi &thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&thinsp;&nbsp;&thinsp;: Kedua 2022/2023</p>
        <br>
        <p>Saudara/Saudari,</p>
        <h2 class="mt-2"><b>SURAT PERINGATAN 1 : KETIDAKHADIRAN KE KULIAH/TUTORIAL BAGI KURSUS DPU2013 – ASAS KEUSAHAWANAN</b></h2>
        <p class="mt-2">Laporan telah dibuat bahawa pada <b>13/4/2023</b> dan <b>18/4/2023</b> anda telah tidak hadir
            ke kuliah/tutorial di atas seperti yang telah tersenarai di bawah ini tanpa sebab:-</p>
        <br>
        <div class="table-responsive">
            <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div id="status">
                        <div class="col-sm-12">
                            <table id="myTable" class="w-100 table display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%; border: 1px solid black !important; border-collapse: collapse;" role="grid" aria-describedby="complex_header_info">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; border: 1px solid black !important;" rowspan="2">Bil</th>
                                        <th style="text-align: center; border: 1px solid black !important;" colspan="3">Tidak Hadir Kuliah/Tutorial</th>
                                        <th style="text-align: center; border: 1px solid black !important;" rowspan="2">Peratus Keseluruhan Kehadiran</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center; border: 1px solid black !important;">Tarikh</th>
                                        <th style="text-align: center; border: 1px solid black !important;">Hari</th>
                                        <th style="text-align: center; border: 1px solid black !important;">Masa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; border: 1px solid black !important;">1</td>
                                        <td style="text-align: center; border: 1px solid black !important;">13/4/2023</td>
                                        <td style="text-align: center; border: 1px solid black !important;">Khamis</td>
                                        <td style="text-align: center; border: 1px solid black !important;">2:00 petang – 4:00 petang</td>
                                        <td style="text-align: center; border: 1px solid black !important;">39/42 92.86%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="mt-2">Adalah diingatkan mengenai para 3.8.4 (c), Peraturan Akademik Kolej UNITI Pindaan
            2021 seperti berikut:</p>
        <p class="mt-2">“Pelajar yang kehadirannya kurang daripada 80% dalam sesuatu kursus tanpa sebabsebab yang boleh diterima akan dikira gagal dalam kursus tersebut.”</p>
        <p>Dengan ini anda diberi amaran bahawa sekiranya kehadiran yang tidak memuaskan
            ini berterusan, pihak Kolej berhak mengambil tindakan terhadap anda mengikut para
            3.8.4 (c) seperti di atas.</p>
        <br>
        <p>Sekian, terima kasih.</p>
        <br>
        <p>Yang benar,</p>
        <img src="{{ asset('storage/signature/signature1.png') }}" alt="Image" width="5%" height="5%">
        <p><b>Azhar bin Zunaidak</b><br>
        Penolong Pendaftar Akademik<br>
        <b>KOLEJ UNITI</b></p><br>
        {{-- <p>* Pihak Kolej berhak menarik balik tawaran ini di atas apa-apa jua alasan dari semasa ke semasa</p>
        <p>* Universiti Teknologi MARA (UiTM) tidak bertanggungjawab menyerap pelajar program usahasama sekiranya Kolej UNITI menghadapi masalah untuk mengendalikan program.</p>
        <p>* Kos perkhidmatan UiTM RM300.00 tidak akan dikembalikan setelah pelajar mendaftar di Kolej (program UiTM sahaja)</p> --}}
        <p style="text-align: center"><b>[ Ini adalah dokumen yang dihasilkan oleh komputer. Tiada tandatangan diperlukan. ]</b></p>
    </body>
</html>

<script type="text/javascript">

$(document).ready(function () {
    window.print();
});

</script>