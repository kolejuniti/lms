@extends('layouts.student')

@section('main')

<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Receipt</h4>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Payment Details</h4>
                        </div>
                        <div class="box-body">
                            <p><strong>Amount:</strong> {{ $amount }} {{ $currency }}</p>
                            <p><strong>Payment Method:</strong> {{ $payment_method }}</p>
                            <p><strong>Status:</strong> {{ $status }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection
