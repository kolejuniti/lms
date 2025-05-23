<head>
    <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>EduHub - @yield('title')</title>

  {{-- <link rel="stylesheet" media="screen, print" href="{{ asset('assets/src/css/datagrid/datatables/datatables.bundle.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('assets/assets/vendor_components/datatable/datatables.css') }}"> --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/css-skeletons@1.0.3/css/css-skeletons.min.css"/> --}}
  <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
   <style>
    @page {
       size: A4 potrait; /* reduced height for A5 size in landscape orientation */
       margin: 1cm;
     }
 
     * {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 12px;
         padding: 1px;   
     }
     h1 {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 20px;
     }
     h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 12px;
     }

     .table-fit-content {
    width: auto;         /* Fit to content, rather than stretching to full width */
    max-width: 30%;     /* Ensure it doesn't overflow the parent container */
    border-collapse: collapse;
    margin: auto;        /* Center the table if smaller than the parent width */
}


     /* Base table styles */
table {
    width: 100%;            /* Make the table take up the full width */
    border-collapse: collapse; /* Remove gaps between cells */
    font-size: 16px;        /* Set base font size */
    margin-bottom: 20px;   /* Add space below the table */
}

     /* Headers */
th {
    background-color: #f4f4f4;  /* Light gray background */
    font-weight: bold;      /* Bold font for headers */
    text-align: left;       /* Left-align header text */
    padding: 5px;          /* Add padding */
    border: 1px solid #ddd; /* Light gray border */
}

/* Cells */
td {
    padding: 5px;          /* Add padding to cells */
    border: 1px solid #ddd; /* Light gray border */
    vertical-align: top;    /* Align content to top */
}

/* Rows */
tr:nth-child(even) {
    background-color: #f9f9f9; /* Alternate row color for better readability */
}

tr:hover {
    background-color: #e6e6e6; /* Highlight row on hover */
}
     </style>
  </head>
  
  
  
 <body>
    <div class="container">
        <!-- BEGIN INVOICE -->
        <div class="col-12">
        <h1 class="" style="text-align: center; font-size: 16px;">JADUAL REPORT PENCAPAIAN R BAGI TEMPOH {{ $data['from'] }} HINGGA {{ $data['to'] }}</h1>
        <br>
        <br>
            <!--pre registration -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Report Informasi</b>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p>Jumlah Pelajar R Per Bulan &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['totalAll']->total_student }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- Students -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Jumlah Pelajar R Per Minggu</b>
                </div>
                <div class="card-body p-0">
                <table class="w-100 table table-bordered display margin-top-10 w-p100">
                    <thead>
                        <tr>
                            <th style="width: 15%">
                                Minggu (Julat Tarikh)
                            </th>
                            <th style="width: 15%">
                                Bulan
                            </th>
                            <th style="width: 15%">
                                Jumlah Minggu
                            </th>
                            <th style="width: 15%">
                                Jumlah Kumulatif
                            </th>
                            <th style="width: 15%">
                                Jumlah Ditukar
                            </th>
                            <th style="width: 15%">
                                Baki Pelajar
                            </th>
                            <th style="width: 15%">
                                Pelajar Aktif
                            </th>
                            <th style="width: 15%">
                                Pelajar Ditolak
                            </th>
                            <th style="width: 15%">
                                Pelajar Ditawarkan
                            </th>
                            <th style="width: 15%">
                                Pelajar KIV
                            </th>
                            <th style="width: 15%">
                                Pelajar Lain-lain
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $total_allW = 0;
                    $total_allC = 0;
                    $total_allC2 = 0;
                    $total_allB = 0;
                    $total_allR = 0;
                    $total_allO = 0;
                    $total_allK = 0;
                    $total_allT = 0;
                    $total_allL = 0;
                    @endphp
                    @foreach ($data['dateRange'] as $key => $week)
                        <tr>
                        <td>
                        {{ $week['week'] }} ({{ \Carbon\Carbon::parse(reset($week['days']))->format('j F Y') }} - {{ \Carbon\Carbon::parse(end($week['days']))->format('j F Y') }})
                        </td>
                        <td>
                        {{ $week['month'] }}
                        </td>
                        <td>
                        {{ $data['totalWeek'][$key]->total_week }}
                        </td>
                        <td>
                        {{ $data['countedPerWeek'][$key] }}
                        </td>
                        <td>
                        {{ $data['totalConvert'][$key] }}
                        </td>
                        <td>
                        {{ $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key] }}
                        </td>
                        <td>
                        {{ $data['registeredPerWeek'][$key] }}
                        </td>
                        <td>
                        {{ $data['rejectedPerWeek'][$key] }}
                        </td>
                        <td>
                        {{ $data['offeredPerWeek'][$key] }}
                        </td>
                        <td>
                        {{ $data['KIVPerWeek'][$key] }}
                        </td>
                        <td>
                        {{ $data['othersPerWeek'][$key] }}
                        </td>
                        </tr>
                        @php
                        $total_allW += $data['totalWeek'][$key]->total_week;
                        $total_allC += $data['countedPerWeek'][$key];
                        $total_allC2 += $data['totalConvert'][$key];
                        $total_allB += $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key];
                        $total_allR += $data['registeredPerWeek'][$key];
                        $total_allO += $data['rejectedPerWeek'][$key];
                        $total_allK += $data['offeredPerWeek'][$key];
                        $total_allT += $data['KIVPerWeek'][$key];
                        $total_allL += $data['othersPerWeek'][$key];
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: center">
                                JUMLAH SEMUA
                            </td>
                            <td>
                                {{ $total_allW }}
                            </td>
                            <td>
                                {{ $total_allC }}
                            </td>
                            <td>
                                {{ $total_allC2 }}
                            </td>
                            <td>
                                {{ $total_allB }}
                            </td>
                            <td>
                                {{ $total_allR }}
                            </td>
                            <td>
                                {{ $total_allO }}
                            </td>
                            <td>
                                {{ $total_allK }}
                            </td>
                            <td>
                                {{ $total_allT }}
                            </td>
                            <td>
                                {{ $total_allL }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- Students -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Jumlah Pelajar R Per Hari</b>
                </div>
                <div class="card-body p-0">
                <table class="w-100 table table-bordered display margin-top-10 w-p100">
                    <thead>
                        <tr>
                            <th style="width: 15%">
                                Tarikh
                            </th>
                            <th style="width: 15%">
                                Jumlah Hari
                            </th>
                            <th style="width: 15%">
                                Jumlah Kumulatif
                            </th>
                            <th style="width: 15%">
                                Jumlah Ditukar
                            </th>
                            <th style="width: 15%">
                                Baki Pelajar
                            </th>
                            <th style="width: 15%">
                                Pelajar Aktif
                            </th>
                            <th style="width: 15%">
                                Pelajar Ditolak
                            </th>
                            <th style="width: 15%">
                                Pelajar Ditawarkan
                            </th>
                            <th style="width: 15%">
                                Pelajar KIV
                            </th>
                            <th style="width: 15%">
                                Pelajar Lain-lain
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $total_allD = 0;
                    $total_allQ = 0;
                    $total_allZ = 0;
                    $total_allB = 0;
                    $total_allR = 0;
                    $total_allO = 0;
                    $total_allK = 0;
                    $total_allT = 0;
                    $total_allL = 0;
                    @endphp
                    @foreach ($data['dateRange'] as $key => $week)
                    @foreach ($data['week'][$key] as $key2 => $day)
                    <tr>
                        <td>
                        {{ $day }}
                        </td>
                        <td>
                        {{ $data['totalDay'][$key][$key2]->total_day }}
                        </td>
                        <td>
                        {{ $data['countedPerDay'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['totalConvert2'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['registeredPerDay'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['rejectedPerDay'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['offeredPerDay'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['KIVPerDay'][$key][$key2] }}
                        </td>
                        <td>
                        {{ $data['othersPerDay'][$key][$key2] }}
                        </td>
                    </tr>
                    @php
                        $total_allD += $data['totalDay'][$key][$key2]->total_day;
                        $total_allQ += $data['countedPerDay'][$key][$key2];
                        $total_allZ += $data['totalConvert2'][$key][$key2];
                        $total_allB += $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2];
                        $total_allR += $data['registeredPerDay'][$key][$key2];
                        $total_allO += $data['rejectedPerDay'][$key][$key2];
                        $total_allK += $data['offeredPerDay'][$key][$key2];
                        $total_allT += $data['KIVPerDay'][$key][$key2];
                        $total_allL += $data['othersPerDay'][$key][$key2];
                    @endphp
                    @endforeach
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="1" style="text-align: center">
                                JUMLAH SEMUA
                            </td>
                            <td>
                                {{ $total_allD }}
                            </td>
                            <td>
                                {{ $total_allQ }}
                            </td>
                            <td>
                                {{ $total_allZ }}
                            </td>
                            <td>
                                {{ $total_allB }}
                            </td>
                            <td>
                                {{ $total_allR }}
                            </td>
                            <td>
                                {{ $total_allO }}
                            </td>
                            <td>
                                {{ $total_allK }}
                            </td>
                            <td>
                                {{ $total_allT }}
                            </td>
                            <td>
                                {{ $total_allL }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            {{-- <div class="row justify-content-center">
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                    <div class="card-body p-0">
                        <table class="table-fit-content">
                            <tbody id="table">
                                <tr>
                                    <td>
                                        <table class="table-fit-content">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center">
                                                        Sponsor Total By Program
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 5%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 5%">
                                                        QUOTE
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="table">
                                                @foreach ($data['program'] as $key => $prg)
                                                <tr>
                                                  <td>
                                                    {{ $prg->program_ID }}
                                                  </td>
                                                  <td>
                                                    {{ $prg->progcode }}
                                                  </td>
                                                  <td>
                                                    {{ (!empty($data['totalPayment'])) ? number_format($data['totalPayment'][$key], 2) : 0.00}}
                                                  </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" style="text-align: center">
                                                        TOTAL
                                                    </td>
                                                    <td>
                                                        {{ number_format(array_sum($data['totalPayment']), 2) }}
                                                    </td>
                                                  </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div> --}}
        </div>
        <!-- END INVOICE -->
    </div>
 </body>
 <script type="text/javascript">
 
    $(document).ready(function () {
        window.print();
    });
    
    </script>