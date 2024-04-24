
<head>
    <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>BORANG KEHADIRAN KULIAH/TUTORIAL/AMALI @yield('title')</title>
   


  {{-- <link rel="stylesheet" media="screen, print" href="{{ asset('assets/src/css/datagrid/datatables/datatables.bundle.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('assets/assets/vendor_components/datatable/datatables.css') }}"> --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/css-skeletons@1.0.3/css/css-skeletons.min.css"/> --}}
  <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
   <style>
    @page {
       size: A4 potrait; /* reduced height for A5 size in landscape orientation */
       margin: 0cm;
     }
 
     * {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 10px;
         padding: 1px;   
     }
     h2,h3,p {
         margin: 0;
         padding: 0;
         border: 0;
         outline: 0;
         vertical-align: baseline;
         background: transparent;
         font-size: 10px;
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
    border: 1px solid black; /* Light gray border */
}

/* Cells */
td {
    padding: 5px;          /* Add padding to cells */
    border: 1px solid black; /* Light gray border */
    vertical-align: top;    /* Align content to top */
}

/* Rows */
tr:nth-child(even) {
    background-color: #f9f9f9; /* Alternate row color for better readability */
}

tr:hover {
    background-color: #e6e6e6; /* Highlight row on hover */
}

.signature-box {
    border: 2px solid black;
    padding: 20px;
    width: 300px;
    margin: 20px;
  }
  .title {
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
  }
  .content {
    text-align: center;
    margin-bottom: 15px;
  }
  .date {
    border-top: 1px solid black;
    width: 150px;
    margin: 0 auto;
  }

     </style>
  </head>
  
  
  
 <body>
    <div class="container">
        <!-- BEGIN INVOICE -->
        <div class="col-12">
            <!--student info -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p>NAME PENSYARAH &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ Auth::user()->name }}</p>
                            </div>
                            <div class="form-group">
                                <p>MINGGU/BULAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; </p>
                            </div>
                            <div class="form-group">
                                <p>KOD PROGRAM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; </p>
                            </div>
                            <div class="form-group">
                                <p>SEMESTER PENGAJIAN &nbsp;&nbsp;: &nbsp;&nbsp; </p>
                            </div>
                            <div class="form-group">
                                <p>SESI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['session']->SessionName }}</p>
                            </div>
                            <div class="form-group">
                                <p>SEMESTER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; </p>
                            </div>
                            <div class="form-group">
                                <p>NAMA KURSUS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['course']->course_name }}</p>
                            </div>
                            <div class="form-group">
                                <p>KOD KURSUS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['course']->course_code }}</p>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>


            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Senarai Kehadiran</b>
                </div>
                <div class="card-body p-0">
                <table >
                    <thead>
                        <tr>
                            <th rowspan="4">
                                Bil
                            </th>
                            <th rowspan="4">
                                Name
                            </th>
                            <th rowspan="4">
                                Program
                            </th>
                            <th>
                                Tarikh
                            </th>
                            @for($i = 0; $i < 12; $i++)
                            <th>
                            ............           
                            </th>
                            @endfor
                            <th rowspan="4" style="text-align:center">
                                Kehadiran %
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Masa Mula
                            </th>
                            @for($i = 0; $i < 12; $i++)
                            <th>
                             
                            </th>
                            @endfor
                        </tr>
                        <tr>
                            <th>
                                Masa Akhir
                            </th>
                            @for($i = 0; $i < 12; $i++)
                            <th>
                             
                            </th>
                            @endfor
                        </tr>
                        <tr>
                            <th>
                                No. Matriks
                            </th>
                            <th colspan="12" style="text-align:center">
                                KEHADIRAN
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @foreach($data['students'] as $key => $std)
                        <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <td>
                                {{ $std->name }}
                            </td>
                            <td>
                                {{ $std->progcode  }}
                            </td>
                            <td>
                                {{ $std->no_matric }}
                            </td>
                            @for($i = 0; $i < 12; $i++)
                            <td>
                             
                            </td>
                            @endfor
                            <td>

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

            <!--student info -->
            <div class="card md-12 mb-3 d-flex" id="stud_info">
                <div class="card-header">
                <b>NOTA:</b>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p>T/T &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; Hadir</p>
                            </div>
                            <div class="form-group">
                                <p>TH &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; Tidak Hadir</p>
                            </div>
                            <div class="form-group">
                                <p>THB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; Tidak Hadir Bersebab</p>
                            </div>
                            <div class="form-group">
                                <p>MC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; Cuti Sakit</p>
                            </div>
                        </div>  
                    </div>
                    
                </div>
            </div>

            <div class="card text-right">
              <div class="card-body">
                <div class="col-3 mb-2 mt-2">
                    <div class="signature-box">
                        <div class="title">DISAHKAN:</div>
                        <div class="content">(DEKAN / TIMBALAN DEKAN / KETUA PROGRAM)</div>
                        <div class="date">TARIKH: ________________</div>
                      </div>
                </div>
              </div>
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