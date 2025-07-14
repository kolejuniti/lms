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
                font-size: 10px;
            }
            h2, h3, p {
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
            .custom-table, .custom-table th, .custom-table td {
                border: none;
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
            }

            /* Use border instead of background */
            .border-line {
                width: 100%;
                border-top: 1px solid black; /* Border as the line */
                margin: 15px 0; /* Space around the line */
            }
        </style>
        @if(Session::get('StudInfo'))
        
        @else
        <style>
            /* Screen version */
            @media screen {
                body {
                    background-image: url('{{ asset("assets/images/letter_head/letter_head_transcript.jpg") }}');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                }
            }
            
            /* Print version - definitive solution */
            @media print {
                /* Reset everything */
                * {
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                /* Force the root elements to have no margins */
                html {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                    height: 100% !important;
                }
                
                /* Create absolute positioned background that covers entire page */
                html::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-image: url('{{ asset("assets/images/letter_head/letter_head_transcript.jpg") }}');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    z-index: -999;
                    pointer-events: none;
                }
                
                /* Reset body and allow normal content flow */
                body {
                    background: transparent !important;
                    position: relative;
                    z-index: 1;
                    /* Restore the original page margins for content with extra top margin */
                    margin: 3cm 1cm 1cm 1cm !important;
                    padding: 0 !important;
                }
                
                /* Override the global @page rule for this specific case */
                @page {
                    margin: 0 !important;
                    size: A4;
                }
            }
        </style>
        @endif
    </head>
    <body>
        <div class="col-12 mb-1 mt-1">  
            <div style="border: 1px solid white; padding: 10px;">
                <table>
                    <tr>
                        <td style="padding-right: 10px;">PROGRAM</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td style="padding-left: 10px;">{{ $data['student']->program }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NO. RUJUKAN MQA</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td style="padding-left: 10px;">{{ $data['student']->mqa }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NAMA</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td style="padding-left: 10px;">{{ $data['student']->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NO. MATRIKS</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td style="padding-left: 10px;">{{ $data['student']->no_matric }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">NO. K.P. / NO. PASSPORT</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td style="padding-left: 10px;">{{ $data['student']->ic }}</td>
                    </tr>
                </table>
            </div> 
        </div>

        <!-- Black Line Divider using Border -->
        <div class="border-line"></div>
  
        <div class="flex-container col-md-12 mt-2">
            <div class="col-md-6">
                <table class="custom-table">
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 2%">KOD</th>
                            <th style="width: 10%">KURSUS</th>
                            <th class="text-center" style="width: 1%">KR</th>
                            <th style="width: 1%">GR</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-md-6">
                <table class="custom-table">
                    <thead>
                        <tr class="line">
                            <th class="text-center" style="width: 5%">KOD</th>
                            <th style="width: 16%">KURSUS</th>
                            <th class="text-center" style="width: 1%">KR</th>
                            <th style="width: 1%">GR</th>
                        </tr>
                    </thead>
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
            @if(isset($data['detail'][$key]))
            <div class="col-md-6 mt-3">
                <div class="text-center"><b><u>SESI {{ $data['detail'][$key]->session }} SEMESTER {{ $sm }}</u></b></div>
                <table class="custom-table">
                    <tbody>
                        @foreach($data['course'][$key] as $key2 => $crs)
                        <tr class="line">
                            <td style="width: 5%">{{ $crs->course_code }}</td>
                            <td style="width: 26%">{{ $crs->course_name }}</td>
                            <td class="text-center" style="width: 1%">{{ $crs->credit }}</td>
                            <td style="width: 1%">{{ $crs->grade }}</td>
                        </tr>
                        @php
                        $total_credit += (!in_array($crs->grade, ['E','F','GL'])) ? $crs->credit : 0;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="line">
                            <td colspan="2"></td>
                            <td>PNGS :</td>
                            <td>&nbsp;{{ $data['detail'][$key]->gpa }}</td>
                        </tr>
                        <tr class="line">
                            <td colspan="2"></td>
                            <td>PNGK :</td>
                            <td>&nbsp;{{ $data['detail'][$key]->cgpa }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @endif
            @endforeach  
        </div>

        <div class="col-12 mb-1 mt-1" style="text-align: right;">  
            <div style="border: 1px solid white; padding: 10px;">
            <table style="float: right;">
                <tr>
                <td style="padding-right: 10px;"><b>PURATA TIMBUNAN MATA NILAIAN</b></td>
                <td><b>&nbsp;:&nbsp;</b></td>
                <td style="padding-left: 10px;"><b>{{ $data['lastCGPA'] }}</b></td>
                </tr>
                <tr>
                <td style="padding-right: 10px;"><b>JUMLAH KREDIT KESELURUHAN</b></td>
                <td><b>&nbsp;:&nbsp;</b></td>
                <td style="padding-left: 10px;"><b>{{ $total_credit }}</b></td>
                </tr>
            </table>
            </div> 
        </div>
        <img src="{{ asset('storage/signature/signature2.png') }}" alt="Image" width="10%" height="10%" style="float: right;">
        <br>
        <br>
        <br>
        <br>

        <p style="text-align: right;"><b>......................................<br>
        AZHAR BIN ZUNAIDAK<br>
        PENOLONG PENDAFTAR<br>
        HAL EHWAL AKADEMIK<br>
        BP: KETUA EKSEKUTIF</b></p><br>

        <p style="text-align: right;"><b>TARIKH: {{ $data['date'] }}</b><br>
        
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>
