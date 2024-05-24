
@extends('layouts.student')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>
<script src="https://js.stripe.com/v3/"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Payment</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Payment</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    {{-- @php
        
        $json = file_get_contents('https://sandbox.securepay.my/api/public/v1/banks/b2c');
        $obj = json_decode($json, true);
        //echo $obj->access_token;
        //$obj->fpx_bankList

        //print_r($obj['fpx_bankList']);

        $options = "";

        foreach ($obj['fpx_bankList'] as $value) {
            if($value['status_format2'])
            {
                $options .= "<option value=". $value['code'] . ">" . $value['name'] . "</option>";
            }
            else
            {
                $options .= "<option value=". $value['code'] . " disabled>" . $value['name'] . " (offline)</option>";
            }
            //echo $value['code'];
        }

    @endphp --}}

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xl-12 col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title">Yuran Pengajian
                        </h4>						
                    </div>
                    <div class="box-body">
  
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
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group mt-3">
                                                <label class="form-label">Payment Method List</label>
                                                <table id="payment_list" class="table table-striped projects display dataTable">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6" id="bank-card">
                                        <div class="form-group">
                                          <label class="form-label" for="buyer_bank_code">Bank</label>
                                          <select class="form-select" id="buyer_bank_code" name="buyer_bank_code">
                                            <option value="-" selected disabled>-</option>
                                            <?=$options?>
                                          </select>
                                        </div>
                                    </div> --}}
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
                                                        @if($data['balance'][$key] != 0)
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
                                                                        <input type="number" class="form-control payment-input" name="payment[]" id="payment[]" step='0.01' max="{{ $data['amount'][$key] }}">
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
                                                            <input type="text" class="form-control" name="total" id="total">
                                                        </div> 
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                            $('.payment-input').on('input', function() {
                                var sum = 0;
                                $('.payment-input').each(function() {
                                    sum += Number($(this).val()) || 0;
                                });

                                $('#total').val(sum);
                                $('#text_sum').val(sum);
                            });
                            </script>

                            <div class="row" id="confirm-card">
                                <div class="col-md-12 mt-3 text-center">
                                    <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary mb-3" onclick="confirm()">Confirm</button>
                                    </div>
                                </div>
                            </div>
                                            
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->
    
<script>
    function confirm()
    {

    var total = $('#total').val();
    // var buyer_bank_code = $('#buyer_bank_code').val();

    if(total != '')
    {

        if(parseInt(total) > 2)
        {

            var forminput = [];
            var formData = new FormData();

            var input = [];
            var input2 = [];

            forminput = {
            total: total,
            // buyer_bank_code: buyer_bank_code
            };

            formData.append('paymentDetail', JSON.stringify(forminput));
            
            $('input[id="phyid[]"]').each(function() {
            input.push({
                    id : $(this).val()
                });
            });

            $('input[id="payment[]"]').each(function() {
            input2.push({
                    payment : $(this).val()
                });
            });

            formData.append('paymentinput', JSON.stringify(input));
            formData.append('paymentinput2', JSON.stringify(input2));

            $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url: '{{ url('/yuran-pengajian/submitPayment') }}',
                type: 'POST',
                data: formData,
                cache : false,
                processData: false,
                contentType: false,
                error:function(err){
                    console.log(err);
                },
                success:function(res){
                    try{
                        if(res.message == "Success"){
                            alert("Success! Payment Details has been added!"  + res.id);
                            if(res.alert != null)
                            {
                                alert(res.alert);
                            }
                            window.open('/yuran-pengajian/showQuotation?id=' + res.id, '_blank');
                            window.location.reload();
                        }else{
                            $('.error-field').html('');
                            if(res.message == "Field Error"){
                                for (f in res.error) {
                                    $('#'+f+'_error').html(res.error[f]);
                                }
                            }
                            else if(res.message == "Please fill all required field!"){
                                alert(res.message);
                            }
                            else{
                                alert(res.message);
                            }
                            $("html, body").animate({ scrollTop: 0 }, "fast");
                        }
                    }catch(err){
                        alert("Ops sorry, there is an errorzz");
                    }
                }
            });

        }else{

            alert('Please make sure the amount is more than RM2!');

        }

    }else{

        alert('Please submit & fill payment details first!');

    }

    }
</script>

@endsection