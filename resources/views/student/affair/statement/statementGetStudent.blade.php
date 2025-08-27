<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Info</b>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Student Name:</span> <span class="info-value">{{ $data['student']->name }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Status:</span> <span class="info-value">{{ $data['student']->status }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Program:</span> <span class="info-value">{{ $data['student']->program }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Intake:</span> <span class="info-value">{{ $data['student']->intake_name }}</span></p>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">No. IC / No. Passport:</span> <span class="info-value">{{ $data['student']->ic }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">No. Matric:</span> <span class="info-value">{{ $data['student']->no_matric }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Semester:</span> <span class="info-value">{{ $data['student']->semester }}</span></p>
                    </div>
                    <div class="form-group">
                        <p class="student-info-item"><span class="info-label">Session:</span> <span class="info-value">{{ $data['student']->session_name }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Yuran</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered display margin-top-10" style="min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th style="min-width: 80px;">
                                            Tarikh
                                        </th>
                                        <th style="min-width: 80px;">
                                            Ref No
                                        </th>
                                        <th style="min-width: 100px;">
                                            Program
                                        </th>
                                        <th style="min-width: 150px;">
                                            Description
                                        </th>
                                        <th style="min-width: 90px;">
                                            Claim (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Payment (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Balance (RM)
                                        </th>
                                    </tr>
                                </thead>
                            <tbody id="table">
                                @foreach ($data['record'] as $key => $req)
                                <tr style="{{ (substr($req->ref_no, 0, 3) == 'INV') ? 'background-color: red' : ''}}">
                                    <td>
                                    {{ $req->date }}
                                    </td>
                                    <td>
                                    @if ($req->process_type_id == 7)
                                    PENAJA
                                    @else
                                    {{ $req->ref_no }}
                                    @endif
                                    </td>
                                    <td>
                                    {{ $req->program }}
                                    </td>
                                    <td>
                                    @if($req->process_type_id == 1 || $req->process_type_id == 2)
                                    {{ $req->name }}
                                    @elseif($req->process_type_id == 5 || $req->process_type_id == 11)
                                    {{ $req->remark }}
                                    @else
                                    {{ $req->process }}
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([2], (array) $req->group_id) && $req->source == 'claim')
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1], (array) $req->group_id) && $req->source == 'payment')
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    {{  number_format($data['total'][$key], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:center">
                                        TOTAL AMOUNT :
                                        </td>
                                        <td>
                                        {{ number_format($data['sum1'], 2) }}
                                        </td>
                                        <td>
                                        {{ number_format($data['sum2'], 2) }} 
                                        </td>
                                        <td>
                                        {{ number_format($data['sum3'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                                <div class="col-md-6" hidden>
                                    <input type="text" class="form-control" name="sum2" id="sum2">
                                </div> 
                            </tbody>
                            </table>
                        </div>

                        <div class="card mb-3" id="stud_info">
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <p class="student-info-item"><span class="info-label">PACKAGE:</span> <span class="info-value">{{ isset($data['package']->package) ? $data['package']->package : 0 }}</span></p>
                                        </div>
                                        <div class="form-group">
                                            <p class="student-info-item"><span class="info-label">METHOD:</span> <span class="info-value">{{ isset($data['package']->type) ? $data['package']->type : 0 }}</span></p>
                                        </div>
                                        <div class="form-group">
                                            <p class="student-info-item"><span class="info-label">PAYMENT:</span> <span class="info-value">{{ isset($data['sponsor']->amount) ? number_format($data['sponsor']->amount, 2) : 0.00 }}</span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        {{-- <div class="form-group">
                                            <table class="w-100 table table-bordered display margin-top-10 w-p100">
                                                <tr>
                                                    <th>Semester 1</th>
                                                    <th>Semester 2</th>
                                                    <th>Semester 3</th>
                                                    <th>Semester 4</th>
                                                    <th>Semester 5</th>
                                                    <th>Semester 6</th>
                                                </tr>
                                                <tr>
                                                    <td>{{ isset($data['package']->semester_1) ? $data['package']->semester_1 : 0.00 }}</td>
                                                    <td>{{ isset($data['package']->semester_2) ? $data['package']->semester_2 : 0.00 }}</td>
                                                    <td>{{ isset($data['package']->semester_3) ? $data['package']->semester_3 : 0.00 }}</td>
                                                    <td>{{ isset($data['package']->semester_4) ? $data['package']->semester_4 : 0.00 }}</td>
                                                    <td>{{ isset($data['package']->semester_5) ? $data['package']->semester_5 : 0.00 }}</td>
                                                    <td>{{ isset($data['package']->semester_6) ? $data['package']->semester_6 : 0.00 }}</td>
                                                </tr>
                                                <!-- More rows can be added here -->
                                            </table>
                                        </div> --}}
                                        <div class="form-group">
                                            <p class="student-info-item"><b style="color: red;"><span class="info-label">CURRENT SEMESTER ARREARS:</span> <span class="info-value">{{ isset($data['value']) ? $data['value'] : 0.00 }}</span></b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="card mb-3" id="stud_info">
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p>TUNGGAKAN SEMESTER (RM) &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['total_balance']) ? number_format($data['total_balance'], 2) : 0.00 }}</p>
                                        </div>
                                        <div class="form-group">
                                            <p>TUNGGAKAN SEMESTER (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['current_balance']) ? number_format($data['current_balance'], 2) : 0.00 }}</p>
                                        </div>
                                        <div class="form-group">
                                            <p>TUNGGAKAN PEMBIAYAAN KHAS (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['pk_balance']) ? number_format($data['pk_balance'], 2) : 0.00 }}</p>
                                        </div>
                                        <div class="form-group">
                                            <p>TUNGGAKAN KESULURUHAN (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['total_all']) ? number_format($data['total_all'], 2) : 0.00 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Denda / Saman</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered display margin-top-10" style="min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th style="min-width: 80px;">
                                            Tarikh
                                        </th>
                                        <th style="min-width: 80px;">
                                            Ref No
                                        </th>
                                        <th style="min-width: 100px;">
                                            Program
                                        </th>
                                        <th style="min-width: 150px;">
                                            Description
                                        </th>
                                        <th style="min-width: 90px;">
                                            Claim (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Payment (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Balance (RM)
                                        </th>
                                    </tr>
                                </thead>
                            <tbody id="table">
                                @foreach ($data['record2'] as $key => $req)
                                <tr>
                                    <td>
                                    {{ $req->date }}
                                    </td>
                                    <td>
                                    @if ($req->process_type_id == 7)
                                    PENAJA
                                    @else
                                    {{ $req->ref_no }}
                                    @endif
                                    </td>
                                    <td>
                                    {{ $req->program }}
                                    </td>
                                    <td>
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    @if (array_intersect([2], (array) $req->group_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1], (array) $req->group_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    {{  number_format($data['total2'][$key], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:center">
                                        TOTAL AMOUNT :
                                        </td>
                                        <td>
                                        {{ number_format($data['sum1_2'], 2) }}
                                        </td>
                                        <td>
                                        {{ number_format($data['sum2_2'], 2) }} 
                                        </td>
                                        <td>
                                        {{ number_format($data['sum3_2'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                                <div class="col-md-6" hidden>
                                    <input type="text" class="form-control" name="sum2" id="sum2">
                                </div> 
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Lain - Lain Bayaran</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <div class="table-responsive">
                            <table class="table table-bordered display margin-top-10" style="min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th style="min-width: 80px;">
                                            Tarikh
                                        </th>
                                        <th style="min-width: 80px;">
                                            Ref No
                                        </th>
                                        <th style="min-width: 100px;">
                                            Program
                                        </th>
                                        <th style="min-width: 150px;">
                                            Description
                                        </th>
                                        <th style="min-width: 90px;">
                                            Claim (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Payment (RM)
                                        </th>
                                        <th style="min-width: 100px;">
                                            Balance (RM)
                                        </th>
                                    </tr>
                                </thead>
                            <tbody id="table">
                                @foreach ($data['record3'] as $key => $req)
                                <tr>
                                    <td>
                                    {{ $req->date }}
                                    </td>
                                    <td>
                                    @if ($req->process_type_id == 7)
                                    PENAJA
                                    @else
                                    {{ $req->ref_no }}
                                    @endif
                                    </td>
                                    <td>
                                    {{ $req->program }}
                                    </td>
                                    <td>
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    @if (array_intersect([2], (array) $req->group_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1], (array) $req->group_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    {{  number_format($data['total3'][$key], 2) }}
                                    </td>
                                </tr>
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <td colspan="4" style="text-align:center">
                                        TOTAL AMOUNT :
                                        </td>
                                        <td>
                                        {{ number_format($data['sum1_3'], 2) }}
                                        </td>
                                        <td>
                                        {{ number_format($data['sum2_3'], 2) }} 
                                        </td>
                                        <td>
                                        {{ number_format($data['sum3_3'], 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                                <div class="col-md-6" hidden>
                                    <input type="text" class="form-control" name="sum2" id="sum2">
                                </div> 
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
