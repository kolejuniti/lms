@extends('layouts.student')

@section('main')

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Quotation</h4>
                </div>
            </div>
        </div>

        <section class="content">
            <form action="{{ route('securepay.checkout') }}" method="POST" id="securepay-form">
                @csrf <!-- Ensure CSRF token is included for POST requests -->
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Payment Details</h4>
                            </div>
                            <div class="box-body">
                                <p><strong>Student: {{ Auth::guard('student')->user()->ic }}</strong> </p>
                                <p><strong>Amount: {{ $data['payment']->amount }}</strong> </p>
                                <p><strong>Payment Method: FPX</strong> </p>
                                <p><strong>Status: {{ $data['payment']->process_status_id }}</strong> </p>
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
                                    <label class="form-label">Payment List</label>
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
                                                    Amount
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="table">
                                            @foreach($data['details'] as $key => $dtl)
                                            <tr>
                                                <td>
                                                {{ $dtl->add_date }}
                                                </td>
                                                <td>
                                                {{ $dtl->name }}
                                                </td>
                                                <td>
                                                {{ $data['payment']->semester_id }}
                                                </td>
                                                <td>
                                                {{ $dtl->amount }}
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td>
                                                
                                                </td>
                                                <td>
                                                Payment Charge
                                                </td>
                                                <td>
                                                
                                                </td>
                                                <td>
                                                1.50
                                                </td>
                                            </tr>
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
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" name="text_sum" id="text_sum" value="{{ $data['payment']->amount + 1.50 }}" readonly>
                                                        </div> 
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <div class="col-md-6" hidden>
                                                <input type="text" class="form-control" name="amount" id="amount" value="{{ $data['payment']->amount + 1.50 }}">
                                                <input type="text" class="form-control" name="id" id="id" value="{{ $data['payment']->id }}">
                                                <input type="text" class="form-control" name="ic" id="ic" value="{{ Auth::guard('student')->user()->ic }}">
                                            </div> 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Proceed to Checkout</button>
            </form>
        </section>
    </div>
</div>

@endsection
