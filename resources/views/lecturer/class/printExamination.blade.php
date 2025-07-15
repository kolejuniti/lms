<head>
    <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
   <title>SENARAI NAMA PELAJAR PEPERIKSAAN @yield('title')</title>
   
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   
   <style>
    @page {
       size: A4 portrait;
       margin: 1cm;
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
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    margin-bottom: 20px;
}

     /* Headers */
th {
    background-color: #f4f4f4;
    font-weight: bold;
    text-align: center;
    padding: 8px;
    border: 1px solid black;
    font-size: 10px;
}

/* Cells */
td {
    padding: 5px;
    border: 1px solid black;
    vertical-align: top;
    font-size: 10px;
}

/* Rows */
tr:nth-child(even) {
    background-color: #f9f9f9;
}

.page-break {
    page-break-before: always;
}

.header-info {
    margin-bottom: 20px;
    font-size: 10px;
    line-height: 1.4;
}

.program-header {
    text-align: center;
    font-weight: bold;
    font-size: 12px;
    margin: 15px 0;
    border: 2px solid black;
    padding: 10px;
    background-color: #f0f0f0;
}

     </style>
  </head>
  
 <body>
    <div class="container">
        @foreach($data as $index => $programData)
            @if($index > 0)
                <div class="page-break"></div>
            @endif
            
            <!-- Header Info for each program -->
            <div class="header-info">
                                 <p><strong>SENARAI NAMA PELAJAR {{ $programData['program']->progcode }} - {{ $programData['program']->progname }}</strong></p>
                <br>
                <p><strong>PENSYARAH :</strong> {{ Auth::user()->name }}</p>
                <p><strong>SUBJEK :</strong> {{ $course->course_code }} - {{ $course->course_name }}</p>
                <br>
                <p>GROUP A</p>
            </div>

            <!-- Program Header -->
            <div class="program-header">
                {{ $programData['program']->progcode }} - {{ $programData['program']->progname }}
            </div>

            <!-- Student List Table -->
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">Bil.</th>
                        <th style="width: 200px;">Nama Pelajar</th>
                        <th style="width: 100px;">No. Matrik</th>
                        <th style="width: 100px;">Sesi</th>
                        <th style="width: 150px;">Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programData['students'] as $key => $student)
                        <tr>
                            <td style="text-align: center;">{{ $key + 1 }}</td>
                            <td>{{ strtoupper($student->name) }}</td>
                            <td style="text-align: center;">{{ $student->no_matric }}</td>
                            <td style="text-align: center;">{{ $student->SessionName }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Summary for each program -->
            <div style="margin-top: 20px;">
                <p><strong>Jumlah Pelajar: {{ count($programData['students']) }} orang</strong></p>
                <br>
                <p>Tarikh: _________________</p>
                <br>
                <p>Tandatangan Pengawas: _________________</p>
            </div>
        @endforeach
    </div>
 </body>
 
 <script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
 </script> 