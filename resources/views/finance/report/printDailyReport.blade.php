
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduHub - @yield('title')</title>
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
         vertical-align: baseline;
         background: transparent;
         font-size: 9px;
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
     </style>
  </head>
  
  
  
 <body>
    <div class="container">
        <!-- BEGIN INVOICE -->
        <div class="col-12">
        <!--pre registration -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Pre Registration</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalPreALL = 0;
                    @endphp
                    @foreach ($data['preRegister'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['preMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['preMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['preMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalPre = 0;
                        @endphp
                        @foreach ($data['preDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalPre += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{  number_format($totalPre, 2) }}</div>
                        @php
                            $totalPreALL += $totalPre;
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
                                {{  number_format($totalPreALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- new student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
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
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['newStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['newStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['newStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalNew = 0;
                        @endphp
                        @foreach ($data['newStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalNew += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{  number_format($totalNew, 2) }}</div>
                        @php
                            $totalNewALL += $totalNew;
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
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
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
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['oldStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['oldStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['oldStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalOld = 0;
                        @endphp
                        @foreach ($data['oldStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalOld += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{  number_format($totalOld, 2) }}</div>
                        @php
                            $totalOldALL += $totalOld;
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
                                {{  number_format($totalOldALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
  
            <!-- withdraw student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                    <b>Withdraw Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    No.
                                </th>
                                <th style="width: 15%">
                                    Name
                                </th>
                                <th style="width: 5%">
                                    No.Matric
                                </th>
                                <th style="width: 5%">
                                    Date
                                </th>
                                <th style="width: 5%">
                                    No. Resit
                                </th>
                                <th style="width: 5%">
                                    Method
                                </th>
                                <th style="width: 5%">
                                    Bank
                                </th>
                                <th style="width: 5%">
                                    No. Document
                                </th>
                                <th style="width: 5%">
                                    Amount
                                </th>
                                <th style="width: 5%">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                        @php
                        $totalWithdrawALL = 0;
                        @endphp
                        @foreach ($data['withdrawStudent'] as $key => $rgs)
                        <tr>
                            <td>
                            {{ $key+1 }}
                            </td>
                            <td>
                            {{ $rgs->name }}
                            </td>
                            <td>
                            {{ $rgs->no_matric }}
                            </td>
                            <td>
                            {{ $rgs->date }}
                            </td>
                            <td>
                            {{ $rgs->ref_no }}
                            </td>
                            <td>
                            @foreach ($data['withdrawStudMethod'][$key] as $mth)
                            <div>{{ $mth->method }}</div>
                            @endforeach
                            </td>
                            <td>
                            @foreach ($data['withdrawStudMethod'][$key] as $mth)
                            <div>{{ $mth->bank }}</div>
                            @endforeach
                            </td>
                            <td>
                            @foreach ($data['withdrawStudMethod'][$key] as $mth)
                            <div>{{ $mth->no_document }}</div>
                            @endforeach
                            </td>
                            <td>
                            @php
                                $totalWithdraw = 0;
                            @endphp
                            @foreach ($data['withdrawStudDetail'][$key] as $mth)
                            <div>{{ $mth->amount }}</div>
                            @php
                                $totalWithdraw += $mth->amount;
                            @endphp
                            @endforeach
                            </td>
                            <td>
                            <div>{{  number_format($totalWithdraw, 2) }}</div>
                            @php
                                $totalWithdrawALL += $totalWithdraw;
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
                                    {{  number_format($totalWithdrawALL, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
  
            <!-- graduate student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Graduate Student</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalGraduateALL = 0;
                    @endphp
                    @foreach ($data['graduateStudent'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['graduateStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['graduateStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['graduateStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalGraduate = 0;
                        @endphp
                        @foreach ($data['graduateStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalGraduate += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{  number_format($totalGraduate, 2) }}</div>
                        @php
                            $totalGraduateALL += $totalGraduate;
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
                                {{  number_format($totalGraduateALL, 2) }}
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
                <div class="card-header mx-auto">
                <b>Pre Registration</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['preTotals'])) ? $data['preTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['preTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['newTotals'])) ? $data['newTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['newTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $prg->progcode }}
                        </td>
                        <td>
                            {{ (!empty($data['oldTotals'])) ? $data['oldTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['oldTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Withdraw</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['withdrawTotals'])) ? $data['withdrawTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['withdrawTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                    <b>Graduate</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['graduateTotals'])) ? $data['graduateTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['graduateTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            </div>
            
            <!-- hostel student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Hostel Collection</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th >
                                No.
                            </th>
                            <th >
                                Date
                            </th>
                            <th >
                                No. Resit
                            </th>
                            <th >
                                Method
                            </th>
                            <th >
                                Bank
                            </th>
                            <th >
                                No. Document
                            </th>
                            <th >
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @foreach ($data['hostel'] as $key => $rgs)
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
                        {{ $rgs->method }}
                        </td>
                        <td>
                        {{ $rgs->bank }}
                        </td>
                        <td>
                        {{ $rgs->no_document }}
                        </td>
                        <td>
                        {{ $rgs->amount }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format(array_sum(array_column((array) $data['hostel'], 'amount')), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- convo student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Convocation Collection</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th >
                                No.
                            </th>
                            <th >
                                Date
                            </th>
                            <th >
                                No. Resit
                            </th>
                            <th >
                                Method
                            </th>
                            <th >
                                Bank
                            </th>
                            <th >
                                No. Document
                            </th>
                            <th >
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @foreach ($data['convo'] as $key => $rgs)
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
                        {{ $rgs->method }}
                        </td>
                        <td>
                        {{ $rgs->bank }}
                        </td>
                        <td>
                        {{ $rgs->no_document }}
                        </td>
                        <td>
                        {{ $rgs->amount }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format(array_sum(array_column((array) $data['convo'], 'amount')), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- fine student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Fine Collection</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th >
                                No.
                            </th>
                            <th >
                                Date
                            </th>
                            <th >
                                No. Resit
                            </th>
                            <th >
                                Method
                            </th>
                            <th >
                                Bank
                            </th>
                            <th >
                                No. Document
                            </th>
                            <th >
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
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
                        {{ $rgs->method }}
                        </td>
                        <td>
                        {{ $rgs->bank }}
                        </td>
                        <td>
                        {{ $rgs->no_document }}
                        </td>
                        <td>
                        {{ $rgs->amount }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format(array_sum(array_column((array) $data['fine'], 'amount')), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- other student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Others</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Type
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
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
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['otherStudDetail'][$key] as $mth)
                        <div>{{ $mth->type }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['otherStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['otherStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['otherStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalOther = 0;
                        @endphp
                        @foreach ($data['otherStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalOther += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{ number_format($totalOther, 2) }}</div>
                        @php
                            $totalOtherALL += $totalOther;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format($totalOtherALL, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <!-- /.card-body -->
            </div>
            
            <!-- excess student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Excess Student</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Type
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalExcessALL = 0;
                    @endphp
                    @foreach ($data['excess'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['excessStudDetail'][$key] as $mth)
                        <div>{{ $mth->type }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['excessStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['excessStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['excessStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalExcess = 0;
                        @endphp
                        @foreach ($data['excessStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalExcess += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{ number_format($totalExcess, 2) }}</div>
                        @php
                            $totalExcessALL += $totalExcess;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format($totalExcessALL, 2) }}
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
                <div class="card-header mx-auto">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['newexcessTotals'])) ? $data['newexcessTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['newexcessTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['oldexcessTotals'])) ? $data['oldexcessTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['oldexcessTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            </div>
            
            <!-- Insentif student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Insentif</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Type
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalInsentifALL = 0;
                    @endphp
                    @foreach ($data['Insentif'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['InsentifStudDetail'][$key] as $mth)
                        <div>{{ $mth->type }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['InsentifStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['InsentifStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['InsentifStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalInsentif = 0;
                        @endphp
                        @foreach ($data['InsentifStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalInsentif += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{ number_format($totalInsentif, 2) }}</div>
                        @php
                            $totalInsentifALL += $totalInsentif;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format($totalInsentifALL, 2) }}
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
                <div class="card-header mx-auto">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['newInsentifTotals'])) ? $data['newInsentifTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['newInsentifTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['oldInsentifTotals'])) ? $data['oldInsentifTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['oldInsentifTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            </div>
            
            <!-- tabungkhas student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Tabung Khas</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th style="width: 15%">
                                Name
                            </th>
                            <th style="width: 5%">
                                No.Matric
                            </th>
                            <th style="width: 5%">
                                Date
                            </th>
                            <th style="width: 5%">
                                No. Resit
                            </th>
                            <th style="width: 5%">
                                Type
                            </th>
                            <th style="width: 5%">
                                Method
                            </th>
                            <th style="width: 5%">
                                Bank
                            </th>
                            <th style="width: 5%">
                                No. Document
                            </th>
                            <th style="width: 5%">
                                Amount
                            </th>
                            <th style="width: 5%">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totaltabungkhasALL = 0;
                    @endphp
                    @foreach ($data['tabungkhas'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->name }}
                        </td>
                        <td>
                        {{ $rgs->no_matric }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->ref_no }}
                        </td>
                        <td>
                        @foreach ($data['tabungkhasStudDetail'][$key] as $mth)
                        <div>{{ $mth->type }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totaltabungkhas = 0;
                        @endphp
                        @foreach ($data['tabungkhasStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totaltabungkhas += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{ number_format($totaltabungkhas, 2) }}</div>
                        @php
                            $totaltabungkhasALL += $totaltabungkhas;
                        @endphp
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" style="text-align: center">
                                TOTAL
                            </td>
                            <td>
                                {{ number_format($totaltabungkhasALL, 2) }}
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
                <div class="card-header mx-auto">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['newtabungkhasTotals'])) ? $data['newtabungkhasTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['newtabungkhasTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['oldtabungkhasTotals'])) ? $data['oldtabungkhasTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['oldtabungkhasTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            </div>
            
            <!-- sponsorship student -->
            <div class="card mb-3" id="stud_info">
                <div class="card-header">
                <b>Sponsorship</b>
                </div>
                <div class="card-body p-0">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No.
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Id Payment
                            </th>
                            <th>
                                Method
                            </th>
                            <th>
                                Bank
                            </th>
                            <th>
                                No. Document
                            </th>
                            <th>
                                Amount
                            </th>
                            <th>
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table">
                    @php
                    $totalsponsorALL = 0;
                    @endphp
                    @foreach ($data['sponsor'] as $key => $rgs)
                    <tr>
                        <td>
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $rgs->date }}
                        </td>
                        <td>
                        {{ $rgs->sponsor_id }}
                        </td>
                        <td>
                        @foreach ($data['sponsorStudMethod'][$key] as $mth)
                        <div>{{ $mth->method }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['sponsorStudMethod'][$key] as $mth)
                        <div>{{ $mth->bank }}</div>
                        @endforeach
                        </td>
                        <td>
                        @foreach ($data['sponsorStudMethod'][$key] as $mth)
                        <div>{{ $mth->no_document }}</div>
                        @endforeach
                        </td>
                        <td>
                        @php
                            $totalsponsor = 0;
                        @endphp
                        @foreach ($data['sponsorStudDetail'][$key] as $mth)
                        <div>{{ $mth->amount }}</div>
                        @php
                            $totalsponsor += $mth->amount;
                        @endphp
                        @endforeach
                        </td>
                        <td>
                        <div>{{ number_format($totalsponsor, 2) }}</div>
                        @php
                            $totalsponsorALL += $totalsponsor;
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
                                {{ number_format($totalsponsorALL, 2) }}
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
                <div class="card-header mx-auto">
                <b>New Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['newsponsorTotals'])) ? $data['newsponsorTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['newsponsorTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
                </div>
            
                <!-- pecahan -->
                <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
                <div class="card-header mx-auto">
                <b>Old Student</b>
                </div>
                <div class="card-body p-0">
                    <table id="myTable" class="table table-striped">
                    <thead>
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
                        {{ $key+1 }}
                        </td>
                        <td>
                        {{ $prg->progcode }}
                        </td>
                        <td>
                        {{ (!empty($data['oldsponsorTotals'])) ? $data['oldsponsorTotals'][$key] : 0}}
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
                                {{ number_format(array_sum($data['oldsponsorTotals']), 2) }}
                            </td>
                        </tr>
                    </tfoot>
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