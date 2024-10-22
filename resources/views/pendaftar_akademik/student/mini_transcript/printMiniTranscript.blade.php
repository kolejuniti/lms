<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Transcript Pelajar</title>
        <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <style>
            @page {
                size: A4;
                margin: 0.5cm;
            }
            * {
                margin: 0;
                padding: 0;
                border: 0;
                outline: 0;
                font-size: 100%;
                vertical-align: baseline;
                background: transparent;
                font-size: 9px;
            }
            h2, h3, p {
                margin: 0;
                padding: 0;
                border: 0;
                outline: 0;
                font-size: 100%;
                vertical-align: baseline;
                background: transparent;
                font-size: 9px;
            }
            h1 {
                font-size:35px; /* increase font-size */
            }
            .b2 {
                font-weight: bold;
                font-size: 10px; /* increase font-size */
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

            /* Ensure the columns stay side-by-side in print */
            @media print {
                .flex-container {
                    display: flex;
                }
                .col-md-6 {
                    width: 48%;
                    margin-right: 2%;
                }
                .col-md-3 {
                    width: 20%;
                    margin-right: 2%;
                }
                .col-md-4 {
                    width: 100%;
                    margin-right: 2%;
                }
                .col-md-12 {
                    width: 100%;
                    margin-right: 2%;
                }
            }

            /* Use border instead of background */
            .border-line {
                width: 100%;
                border-top: 1px solid black; /* Border as the line */
                margin: 15px 0; /* Space around the line */
            }

            .fixed-bottom-container {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: white; /* Optional: to ensure the background is consistent */
            }

            .fixed-bottom-container .flex-container {
                display: flex;
                justify-content: space-between;
            }

            .fixed-bottom-container table {
                border: 1px solid white; /* Change border color to white */
                width: 100%;
            }

            .fixed-bottom-container th, .fixed-bottom-container td {
                border: 1px solid white; /* Change cell border color to white */
            }
        </style>
        @if(Session::get('StudInfo'))
        
        @else
        <style>
            body {
                background-image: url('{{ asset("assets/images/letter_head/letter_head.jpg") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
        @endif
    </head>
    <body>
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="me-2">
                    <img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="Kolej Uniti Logo" height="35">
                </div>
                <div>
                    <h1 class="mb-0">KOLEJ UNITI</h1>
                </div>
            </div>
            <div>
                <div class="b2 text-center">MINI TRANSKRIP AKADEMIK</div>
            </div>
        </div>
        
        <div class="flex-container col-12 mt-1">  
            <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
                <table>
                    <tr>
                        <td style="padding-right: 10px;">NAMA</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NO. K.P. / NO. PASSPORT</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->ic }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">FAKULTI</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->faculty }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">PROGRAM</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->program }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NO. MATRIKS</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->no_matric }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">SESI KEMASUKAN</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->intake }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">TAHAP</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">DIPLOMA (SEPENUH MASA)</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">PNGK AKHIR</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['lastCGPA'] }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6" style="border: 1px solid white; padding: 10px;">
                <table>
                    <tr>
                        <td style="padding-right: 10px;">&nbsp;</td>
                        <td> </td>
                        <td style="padding-left: 10px;"> </td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">&nbsp;</td>
                        <td> </td>
                        <td style="padding-left: 10px;"> </td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">&nbsp;</td>
                        <td> </td>
                        <td style="padding-left: 10px;"> </td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">&nbsp;</td>
                        <td> </td>
                        <td style="padding-left: 10px;"> </td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">&nbsp;</td>
                        <td> </td>
                        <td style="padding-left: 10px;"> </td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">SESI AKHIR</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">{{ $data['student']->session }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">KEPUTUSAN</td>
                        <td>:</td>
                        <td style="padding-left: 10px;">PENUH</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Black Line Divider using Border -->
        <div class="border-line"></div>
  
        {{-- <div class="flex-container col-md-12 mt-2">
            <div class="col-md-6">
                <table class="custom-table">
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 2%">KOD</th>
                            <th class="text-center"style="width: 10%">KURSUS</th>
                            <th class="text-center" style="width: 1%">KREDIT</th>
                            <th class="text-center" style="width: 1%">GRED</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-6" style="padding-left: 10px;">
                <table class="custom-table">
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 3%">KOD</th>
                            <th class="text-center" style="width: 15%">KURSUS</th>
                            <th class="text-center" style="width: 1%">KREDIT</th>
                            <th class="text-center" style="width: 1%">GRED</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div> --}}

        <div class="row col-md-12">
            <div class="col-md-6 mt-1">
                <table class="custom-table">
                    <tbody>
                        <tr class="line">
                            <td class="text-center" style="width: 8%">KOD</td>
                            <td class="text-center" style="width: 30%">KURSUS</td>
                            <td class="text-center" style="width: 4%">KREDIT</td>
                            <td class="text-center" style="width: 3%">GRED</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div> 
            <div class="col-md-6 mt-1">
                <table class="custom-table">
                    <tbody>
                        <tr class="line">
                            <td class="text-center" style="width: 8%">KOD</td>
                            <td class="text-center" style="width: 31%">KURSUS</td>
                            <td class="text-center" style="width: 4%">KREDIT</td>
                            <td class="text-center" style="width: 3%">GRED</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Another Black Line Divider if needed -->
        <div class="border-line"></div>
        <div class="row col-md-12 mt-1">
            @php
            $total_credit = 0;
            @endphp
            @foreach($data['semesters'] as $key => $sm)
            @php
            $total_credit_c = 0;
            @endphp
            <div class="col-md-6 mt-3">
                <div class="mb-1"><b>SESI {{ $data['detail'][$key]->session }} SEMESTER {{ $sm }}</b></div>
                <table class="custom-table">
                    <tbody>
                        @foreach($data['course'][$key] as $key2 => $crs)
                        <tr class="line">
                            <td class="text-center" style="width: 4%">{{ $crs->course_code }}</td>
                            <td class="text-center" style="width: 18%">{{ $crs->course_name }}</td>
                            <td class="text-center" style="width: 4%">{{ $crs->credit }}</td>
                            <td class="text-center" style="width: 3%">{{ $crs->grade }}</td>
                        </tr>
                        @php
                        $total_credit += (!in_array($crs->grade, ['E','F','GL'])) ? $crs->credit : 0;
                        $total_credit_c += (!in_array($crs->grade, ['E','F','GL'])) ? $crs->credit : 0;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        {{-- <tr class="line">
                            <td colspan="2"></td>
                            <td>PNGS :</td>
                            <td>{{ $data['detail'][$key]->gpa }}</td>
                        </tr>
                        <tr class="line">
                            <td colspan="2"></td>
                            <td>PNGK :</td>
                            <td>{{ $data['detail'][$key]->cgpa }}</td>
                        </tr> --}}
                    </tfoot>
                </table>
                <div class="flex-container col-md-12 mt-2">
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td style="width: 10%; padding-left: 10px; padding-right: 10px;"><b>PNGS</b></td>
                                <td style="padding-left: 10px;"><b>{{ $data['detail'][$key]->gpa }}</b></td>
                            </tr>
                            <tr>
                                <td style="width: 10%; padding-left: 10px; padding-right: 10px;"><b>KREDIT</b></td>
                                <td style="padding-left: 10px;"><b>{{ $total_credit_c }} ({{ $total_credit }})</b></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td style="width: 10%; padding-left: 10px; padding-right: 10px;"><b>PNGK</b></td>
                                <td style="padding-left: 10px;"><b>{{ $data['detail'][$key]->cgpa }}</b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach  
        </div>

        <br>
        <br>

        <div class="flex-container col-md-12">
            <div class="col-md-6" style="border: 1px solid white">
                <table>
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 2%">TARIKH TAMAT PENGAJIAN</th>
                            <th class="text-center"style="width: 5%">{{ $data['date'] }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-3" style="border: 1px solid white">
                <table>
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 15%">DISAHKAN OLEH</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-3" style="border: 1px solid white">
                <table>
                    <thead>
                        <tr class="line">
                            <img src="{{ asset('storage/chop/uniti-chop.png') }}" alt="Image" width="50%" height="100%">
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="fixed-bottom-container">
            <div class="flex-container col-md-12 mt-2">
                <div class="col-md-4">
                    <table>
                        <thead>
                            <tr class="line">
                                <th class="text-center">BERILMU</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <table>
                        <thead>
                            <tr class="line">
                                <th class="text-center">BERILMU</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-md-4">
                    <table>
                        <thead>
                            <tr class="line">
                                <th class="text-center">BERILMU</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="flex-container col-md-12 mt-2">
                <div class="col-md-12">
                    <table>
                        <thead>
                            <tr class="line">
                                <th class="text-center">*<i>Cetakan komputer. Tiada tandatangan diperlukan.</i>*</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        {{-- <div class="col-12 mb-1 mt-1" style="text-align: right;">  
            <div style="border: 1px solid white; padding: 10px;">
            <table style="float: right;">
                <tr>
                <td style="padding-right: 10px;"><b>PURATA TIMBUNAN MATA NILAIAN</b></td>
                <td><b>:</b></td>
                <td style="padding-left: 10px;"><b>{{ $data['lastCGPA'] }}</b></td>
                </tr>
                <tr>
                <td style="padding-right: 10px;"><b>JUMLAH KREDIT KESELURUHAN</b></td>
                <td><b>:</b></td>
                <td style="padding-left: 10px;"><b>{{ $total_credit }}</b></td>
                </tr>
            </table>
            </div> 
        </div> --}}
        {{-- <img src="{{ asset('storage/signature/signature2.png') }}" alt="Image" width="10%" height="10%" style="float: right;"> --}}
        {{-- <br>
        <br>
        <br>
        <br>

        <p style="text-align: right;"><b>......................................<br>
        AZHAR BIN ZUNAIDAK<br>
        PENOLONG PENDAFTAR<br>
        HAL EHWAL AKADEMIK<br>
        BP: KETUA EKSEKUTIF</b></p><br>

        <p style="text-align: right;"><b>TARIKH: {{ $data['date'] }}</b><br> --}}
        
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>
