
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
       margin: 0cm;
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
     </style>
  </head>
  
  
  
 <body>
    <div class="container">
        <!-- BEGIN INVOICE -->
        <div class="col-12">
            <!--pre registration -->
            <!-- new student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalNewALL = 0;
                    @endphp
                    @foreach ($data['newStudent'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalNewALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalNewALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- old student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalOldALL = 0;
                    @endphp
                    @foreach ($data['oldStudent'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalOldALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalOldALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <div class="row justify-content-center">
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
                                                        New Student
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
                                                {{ (!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['newStudentTotals']), 2) }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table-fit-content">
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center">
                                                        Old Student
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 2%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 2%">
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
                                                {{ (!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['oldStudentTotals']), 2) }}
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
            </div>

            <br>
            
            <!-- debit -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Debit Note</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Remark
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalDebitALL = 0;
                    @endphp
                    @foreach ($data['debit'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->type }}
                        </td>
                        <td>
                        {{ $rgs->remark }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalDebitALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalDebitALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="row justify-content-center">
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
                                                        Debit Note
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 2%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 2%">
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
                                                {{ (!empty($data['debitTotals'])) ? $data['debitTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['debitTotals']), 2) }}
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
            </div>

            <br>
            
            <!-- fine -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Summons / Fine</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalFineALL = 0;
                    @endphp
                    @foreach ($data['fine'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->type }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalFineALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalFineALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <!-- other -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Others</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalOtherALL = 0;
                    @endphp
                    @foreach ($data['other'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->type }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalOtherALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalOtherALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="row justify-content-center">
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
                                                        Other Type
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th>
                                                        TYPE
                                                    </th>
                                                    <th>
                                                        TOTAL
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="table">
                                            @foreach ($data['otherCharge'] as $key => $chg)
                                            <tr>
                                                <td>
                                                {{ $key+1 }}
                                                </td>
                                                <td>
                                                {{ $chg->name }}
                                                </td>
                                                <td>
                                                {{ (!empty($data['other'])) ? $data['otherTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['otherTotals']), 2) }}
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
            </div>

            <br>

            <!-- creditFee -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Credit Note (Fee)</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Remark
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalcreditFeeALL = 0;
                    @endphp
                    @foreach ($data['creditFee'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->reduction_id }}
                        </td>
                        <td>
                        {{ $rgs->remark }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalcreditFeeALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalcreditFeeALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="row justify-content-center">
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
                                                        Credit Note (Fee)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 2%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 2%">
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
                                                    {{ (!empty($data['creditFeeTotals'])) ? $data['creditFeeTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['creditFeeTotals']), 2) }}
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
            </div>

            <br>

            <!-- creditFine -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Credit Note (Fine)</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Remark
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalcreditFineALL = 0;
                    @endphp
                    @foreach ($data['creditFine'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->reduction_id }}
                        </td>
                        <td>
                        {{ $rgs->remark }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalcreditFineALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalcreditFineALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="row justify-content-center">
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
                                                        Credit Note (Fine)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 2%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 2%">
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
                                                        {{ (!empty($data['creditFineTotals'])) ? $data['creditFineTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['creditFineTotals']), 2) }}
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
            </div>

            <br>

            <!-- creditDiscount -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Credit Note (Discount)</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped projects display dataTable">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                No. Resit
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                No.KP
                            </th>
                            <th>
                                No.Matric
                            </th>
                            <th>
                                Program
                            </th>
                            <th>
                                Claim
                            </th>
                            <th>
                                Remark
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalcreditDiscountALL = 0;
                    @endphp
                    @foreach ($data['creditDiscount'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->student_ic }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->progname }}
                        </td>
                        <td>
                        {{ $rgs->reduction_id }}
                        </td>
                        <td>
                        {{ $rgs->remark }}
                        </td>
                        <td>
                        <div>{{  $rgs->amount }}</div>
                        @php
                            $totalcreditDiscountALL += $rgs->amount;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{  number_format($totalcreditDiscountALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="row justify-content-center">
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
                                                        Credit Note (Discount)
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 1%">
                                                        No.
                                                    </th>
                                                    <th style="width: 2%">
                                                        PROGRAM
                                                    </th>
                                                    <th style="width: 2%">
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
                                                        {{ (!empty($data['creditDiscountTotals'])) ? $data['creditDiscountTotals'][$key] : 0}}
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
                                                        {{ number_format(array_sum($data['creditDiscountTotals']), 2) }}
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