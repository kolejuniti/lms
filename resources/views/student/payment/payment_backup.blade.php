
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
                    <form action="{{ route('checkout') }}" method="GET" id="payment-form">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="price">Price (MYR):</label>
                                    <input type="number" class="form-control" name="price" id="price" step="0.01" min="0.01" required>
                                </div>
                            </div>
                        
                            <!-- Add the payment method selection elements -->
                            <label for="payment-method">Payment Method:</label>
                            <div>
                                <input type="radio" id="credit-card" name="payment-method" value="credit-card" checked>
                                <label for="credit-card">Credit Card</label>
                            </div>
                            <div>
                                <input type="radio" id="fpx" name="payment-method" value="fpx">
                                <label for="fpx">FPX</label>
                            </div>
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <div id="card-errors" role="alert"></div>
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

<script>
   document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe('pk_live_51N38ilGR9MLCfZFKHcJYgrRQSWenHaEwsQ3uyM1xZTEIF5Gc7OWDRFov2hEYuShnQPUWKvdInkGzgF4X0OsI6rha00zF9rOvnd');
    const form = document.getElementById('payment-form');
    
    form.addEventListener('submit', function (event) {
        event.preventDefault();
    
        const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
        createCheckoutSession(paymentMethod);
    });
    
    function createCheckoutSession(paymentMethod) {

        fetch('{{ route("checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                price: document.querySelector('input[name="price"]').value,
                'payment-method': paymentMethod
            }),
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (session) {
                return stripe.redirectToCheckout({ sessionId: session.id });
            })
            .then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
            });
    }

});



</script>
    
@endsection