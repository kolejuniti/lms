<!-- form start -->
    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Student Info</b>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Student Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                    </div>
                    <div class="form-group">
                        <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                    </div>
                    <div class="form-group">
                        <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
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
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    @if (array_intersect([2,3,4,5], (array) $req->process_type_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,6,7,8,9,15,16], (array) $req->process_type_id))
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
                                        <td colspan="3" style="text-align:center">
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
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    @if (array_intersect([2,3,4,5], (array) $req->process_type_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,6,7,8,9,15,16], (array) $req->process_type_id))
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
                                        <td colspan="3" style="text-align:center">
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
                                    {{ $req->name }}
                                    </td>
                                    <td>
                                    @if (array_intersect([2,3,4,5], (array) $req->process_type_id))
                                    {{ number_format($req->amount, 2) }}
                                    @else
                                    0.00
                                    @endif
                                    </td>
                                    <td>
                                    @if (array_intersect([1,6,7,8,9,15,16], (array) $req->process_type_id))
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
                                        <td colspan="3" style="text-align:center">
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
    
    
