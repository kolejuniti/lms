
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
       margin: 0.5cm;
     }
 
     * {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 9px;
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
         font-size: 9px;
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

.half-page {
        width: 50%;
        float: left;
    }
     </style>
  </head>
  
  
  
 <body>
    <div class="container">
        <!-- BEGIN INVOICE -->
        <div class="col-12">
            <h1>Senate Report as of {{ now() }}</h1>
            <br>
            <br>
            <!--pre registration -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Pre Registration</b>
                </div>
                <div class="card-body p-0">
                <table class="w-100 table table-bordered display margin-top-10 w-p100">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 1%">
                                Bil.
                            </th>
                            <th rowspan="2" style="width: 15%">
                                No. Matriks
                            </th>
                            <th rowspan="2" style="width: 5%">
                                Nama Pelajar
                            </th>
                            <th rowspan="2" style="width: 5%">
                                Penaja
                            </th>
                            @foreach($data['course'] as $key => $crs)
                            <th colspan="2">
                                {{ $crs->course_code }} 
                                {{-- - {{  $crs->sub_id }} --}}
                            </th>
                            @endforeach
                            <th colspan="7">

                            </th>
                        </tr>
                        <tr>
                            @foreach($data['course'] as $key => $crs)
                            <th>
                                Gred
                            </th>
                            <th>
                                Nilai
                            </th>
                            @endforeach
                            <th>
                                KS
                            </th>
                            <th>
                                NGS
                            </th>
                            <th>
                                PNGS
                            </th>
                            <th>
                                KK
                            </th>
                            <th>
                                NGK
                            </th>
                            <th>
                                PNGK
                            </th>
                            <th>
                                Keputusan
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @foreach($data['student'] as $key => $std)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $std->no_matric }}
                        </td>
                        <td>
                            {{ $std->name }}
                        </td>
                        <td>

                        </td>
                        @foreach($data['course'] as $key2 => $crs)
                        <td>
                            {{ $data['dtl'][$key][$key2]->grade ?? null }}
                        </td>
                        <td>
                            {{ $data['dtl'][$key][$key2]->pointer ?? null }}
                        </td>
                        @endforeach
                        <td>
                            {{ $std->total_credit_s }}
                        </td>
                        <td>
                            {{ $std->grade_pointer_s }}
                        </td>
                        <td>
                            {{ $std->gpa }}
                        </td>
                        <td>
                            {{ $std->count_credit_c }}
                        </td>
                        <td>
                            {{ $std->grade_pointer_c }}
                        </td>
                        <td>
                            {{ $std->cgpa }}
                        </td>
                        <td>
                            {{ $std->status }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                       
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-6 half-page">
            {{-- <h1>Second table</h1>
            <br>
            <br> --}}
            <!--second table -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                    {{-- <b>Second Table</b> --}}
                </div>
                <div class="card-body p-0">
                    <table class="">
                        <thead>
                            <tr>
                                <th>KEPUTUSAN</th>
                                <th>Bil</th>
                            </tr>
                        </thead>
                        <tbody id="table">
                            @foreach($data['status'] as $key => $sts)
                            <tr>
                                <td>
                                    {{ $sts->status_name }}
                                </td>
                                <td>
                                    {{ $data['total'][$key] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- END INVOICE -->
    </div>
 </body>
 <script type="text/javascript">
 
    $(document).ready(function () {
        window.print();
    });
    
    </script>