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
                    <div class="form-group">
                        <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
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
        <b>Payment Details</b>
        </div>
        <div class="card-body">
            <div class="row">       
                <div class="col-md-6" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="ptotal">Total Payment</label>
                        <input type="number" class="form-control" name="ptotal" id="ptotal">
                    </div>
                </div> 
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="idpayment" id="idpayment">
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="save('{{ $data['student']->ic }}')">Save</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">Student Due List</label>
                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width: 10%">
                                        Tarikh
                                    </th>
                                    <th style="width: 10%">
                                        About
                                    </th>
                                    <th style="width: 5%">
                                        Semester
                                    </th>
                                    <th style="width: 5%">
                                        Due
                                    </th>
                                    <th style="width: 10%">
                                        Payment
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['tuition'] as $key => $tsy)
                                @if($data['balance'][$key] > 0)
                                <tr>
                                    <td>
                                    {{ date("Y-m-d") }}
                                    </td>
                                    <td>
                                    {{ $tsy->name }}
                                    </td>
                                    <td>
                                    {{ $tsy->semester_id }}
                                    </td>
                                    <td>
                                    {{ number_format((float)$data['balance'][$key], 2, '.', '') }}
                                    </td>
                                    <td>
                                        <div class="col-md-12" id="payment-card">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="phyid[]" id="phyid[]" value="{{ $tsy->id }}" hidden>
                                                <input type="number" class="form-control" name="payment[]" id="payment[]" step='0.01' max="{{ $data['amount'][$key] }}">
                                            </div>
                                        </div> 
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <td>
                                        
                                        </td>
                                        <td>
                                        TOTAL AMOUNT
                                        </td>
                                        <td>
                                        :
                                        </td>
                                        <td>
                                        
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="text_sum" id="text_sum" readonly>
                                            </div> 
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
    
    <script>
    $('input[id="payment[]"]').keyup(function(){

        var sum = 0;
        $('input[id="payment[]"]').each(function() {
            sum += Number($(this).val());
        });

        $('#sum2').val(sum);
        $('#text_sum').val(sum);

    });
    </script>
