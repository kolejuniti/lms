
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
                        <form action="{{ route('securepay.checkout') }}" method="POST" id="securepay-form">
                            @csrf <!-- Ensure CSRF token is included for POST requests -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="price">Price (MYR):</label>
                                    <input type="number" class="form-control" name="amount" id="price" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <!-- Add additional SecurePay required fields here -->
                            <!-- ... -->
                            <button type="submit" class="btn btn-primary mb-3">Proceed to Checkout</button>
                        </form>                       
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->
    
@endsection