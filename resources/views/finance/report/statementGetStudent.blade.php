<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Info</b>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Name/Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</p>
                    </div>
                    <div class="form-group">
                        <p>
                            {{ $data['student']->name }} <br>
                            {{ strtoupper($data['student']->address1) }} <br>
                            {{ strtoupper($data['student']->address2) }} <br>
                            {{ strtoupper($data['student']->address3) }} <br>
                            {{ strtoupper($data['student']->postcode) }} <br>
                            {{ strtoupper($data['student']->city) }}, {{ strtoupper($data['student']->state) }} <br>
                            {{ strtoupper($data['student']->country) }}
                        </p>
                    </div>
                    <div class="form-group">
                        <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                    </div>
                    <div class="form-group">
                        <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                    </div>
                    <div class="form-group">
                        <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
                    </div>
                    <div class="form-group">
                        <p>Sponsor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['sponsorStudent']->name ?? 'TIADA PENAJA/SENDIRI' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                    </div>
                    <div class="form-group">
                        <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                    </div>
                    <div class="form-group">
                        <p>Session &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session_name }}</p>
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
                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width: 5%">
                                        Tarikh
                                    </th>
                                    <th style="width: 5%">
                                        Ref No
                                    </th>
                                    <th style="width: 5%">
                                        Program
                                    </th>
                                    <th style="width: 5%">
                                        Description
                                    </th>
                                    <th style="width: 5%">
                                        Claim (RM)
                                    </th>
                                    <th style="width: 5%">
                                        Payment (RM)
                                    </th>
                                    <th style="width: 5%">
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
                                    @if (array_intersect([2,3,4,5,11], (array) $req->process_type_id) && $req->source == 'claim')
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id) && $req->source == 'payment')
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

                        <div class="card mb-3" id="stud_info">
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <p>PACKAGE &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['sponsor']->package) ? $data['sponsor']->package : null }}</p>
                                        </div>
                                        <div class="form-group">
                                            <p>METHOD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['sponsor']->type) ? $data['sponsor']->type : null }}</p>
                                        </div>
                                        <div class="form-group">
                                            <p>PAYMENT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['sponsor']->amount) ? number_format($data['sponsor']->amount, 2) : 0.00 }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
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
                                        </div>
                                        <div class="form-group">
                                            <p>CURRENT ARREARS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['value']) ? $data['value'] : 0.00 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3" id="stud_info">
                            <div class="card-body">
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        {{-- <div class="form-group">
                                            <p>TUNGGAKAN SEMESTER (RM) &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['total_balance']) ? number_format($data['total_balance'], 2) : 0.00 }}</p>
                                        </div> --}}
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
                        </div>
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
                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width: 5%">
                                        Tarikh
                                    </th>
                                    <th style="width: 5%">
                                        Ref No
                                    </th>
                                    <th style="width: 5%">
                                        Program
                                    </th>
                                    <th style="width: 5%">
                                        Description
                                    </th>
                                    <th style="width: 5%">
                                        Claim (RM)
                                    </th>
                                    <th style="width: 5%">
                                        Payment (RM)
                                    </th>
                                    <th style="width: 5%">
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
                                    @if (array_intersect([2,3,4,5,11], (array) $req->process_type_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id))
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

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Lain - Lain Bayaran</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width: 5%">
                                        Tarikh
                                    </th>
                                    <th style="width: 5%">
                                        Ref No
                                    </th>
                                    <th style="width: 5%">
                                        Program
                                    </th>
                                    <th style="width: 5%">
                                        Description
                                    </th>
                                    <th style="width: 5%">
                                        Claim (RM)
                                    </th>
                                    <th style="width: 5%">
                                        Payment (RM)
                                    </th>
                                    <th style="width: 5%">
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
                                    @if (array_intersect([2,3,4,5,11], (array) $req->process_type_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,5,6,7,8,9,10,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26], (array) $req->process_type_id))
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
    
    
