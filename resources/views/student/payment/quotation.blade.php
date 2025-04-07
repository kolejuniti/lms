@extends('layouts.student')

@section('main')
<script src="https://js.stripe.com/v3/"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header bg-gradient-primary text-white py-3 px-4 rounded-bottom shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title mb-0 animate__animated animate__fadeIn">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Quotation
                    </h4>
                    <div class="d-inline-block align-items-center mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="#" class="text-white-50"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item text-white" aria-current="page">Payment</li>
                                <li class="breadcrumb-item text-white" aria-current="page">Quotation</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <form action="{{ route('securepay.checkout') }}" method="POST" id="securepay-form">
                @csrf <!-- Ensure CSRF token is included for POST requests -->
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <!-- Payment Summary Card -->
                        <div class="card rounded-4 border-0 shadow animate__animated animate__fadeInUp">
                            <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                                <div class="d-flex align-items-center">
                                    <div class="card-header-icon rounded-circle bg-white text-primary p-3 me-3 shadow-sm">
                                        <i class="fas fa-receipt fa-lg"></i>
                                    </div>
                                    <h4 class="card-title mb-0">Payment Summary</h4>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-3 mb-4">
                                        <div class="info-item p-3 rounded-3">
                                            <div class="info-label text-primary mb-1"><i class="fas fa-user me-2"></i>Student ID</div>
                                            <div class="info-value fw-bold">{{ Auth::guard('student')->user()->ic }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="info-item p-3 rounded-3">
                                            <div class="info-label text-primary mb-1"><i class="fas fa-money-bill me-2"></i>Amount</div>
                                            <div class="info-value fw-bold text-primary">RM {{ $data['payment']->amount }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="info-item p-3 rounded-3">
                                            <div class="info-label text-primary mb-1"><i class="fas fa-credit-card me-2"></i>Payment Method</div>
                                            <div class="info-value fw-bold">
                                                <span class="badge bg-info rounded-pill">FPX</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <div class="info-item p-3 rounded-3">
                                            <div class="info-label text-primary mb-1"><i class="fas fa-check-circle me-2"></i>Status</div>
                                            <div class="info-value fw-bold">
                                                <span class="badge bg-warning rounded-pill">{{ $data['payment']->process_status_id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details Card -->
                        <div class="card rounded-4 border-0 shadow hover-card mb-4 mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                            <div class="card-header bg-light rounded-top-4 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon bg-warning text-white rounded-circle p-2 me-3">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h5 class="mb-0 text-white">Payment Details</h5>
                                </div>
                            </div>
                            <div class="card-body p-4" id="payment-details-body">
                                <div class="table-responsive">
                                    <table class="table table-hover rounded">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tarikh</th>
                                                <th>About</th>
                                                <th>Semester</th>
                                                <th class="text-end">Amount (RM)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table">
                                            @foreach($data['details'] as $key => $dtl)
                                            <tr class="payment-row">
                                                <td>{{ $dtl->add_date }}</td>
                                                <td>
                                                    <span class="fw-medium">{{ $dtl->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info rounded-pill">{{ $data['payment']->semester_id }}</span>
                                                </td>
                                                <td class="text-end fw-bold">
                                                    RM {{ number_format((float)$dtl->amount, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr class="payment-row">
                                                <td></td>
                                                <td>
                                                    <span class="fw-medium">Payment Charge</span>
                                                </td>
                                                <td></td>
                                                <td class="text-end fw-bold">
                                                    RM 1.50
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-active">
                                                <td colspan="2" class="text-end fw-bold">TOTAL AMOUNT :</td>
                                                <td></td>
                                                <td>
                                                    <div class="total-amount-container">
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-primary text-white">RM</span>
                                                            <input type="text" class="form-control bg-info-light fw-bold fs-5 text-primary total-display" 
                                                                name="text_sum" id="text_sum" readonly value="{{ number_format((float)$data['payment']->amount + 1.50, 2, '.', '') }}">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div hidden>
                                        <input type="text" class="form-control" name="amount" id="amount" value="{{ $data['payment']->amount + 1.50 }}">
                                        <input type="text" class="form-control" name="id" id="id" value="{{ $data['payment']->id }}">
                                        <input type="text" class="form-control" name="ic" id="ic" value="{{ Auth::guard('student')->user()->ic }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Checkout Button Section -->
                <div class="text-center payment-action-section my-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="payment-action-container p-4 rounded-4 shadow-sm">
                        <div class="mb-3" id="payment-summary">
                            <h5>Ready to proceed?</h5>
                            <div class="payment-amount-display">
                                <span class="amount-label me-2">Total:</span>
                                <span class="amount-value fs-2 fw-bold text-primary">
                                    RM {{ number_format((float)$data['payment']->amount + 1.50, 2, '.', ',') }}
                                </span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-lg btn-primary rounded-pill px-5 py-3 shadow-sm" id="checkout-btn">
                            <i class="fas fa-lock me-2"></i> Proceed to Checkout
                        </button>
                        
                        <div class="payment-security-info mt-3">
                            <small class="text-muted d-flex align-items-center justify-content-center">
                                <i class="fas fa-shield-alt me-2"></i> Your payment information is secure
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
    /* Modern UI Styling */
    body {
        background-color: #f8f9fa;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .rounded-top-4 {
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
    }
    
    /* Header Styling */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #36b9cc);
    }
    
    /* Card Header Styling with Gradient */
    .card-header {
        position: relative;
        overflow: hidden;
    }
    
    .card-header.bg-light {
        background: linear-gradient(45deg, #ff4286, #643dff) !important;
        color: white;
        padding: 1rem;
        border: none;
        width: 100%;
    }
    
    /* Card Styling */
    .hover-card {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Avatar Icons */
    .avatar-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .card-header-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Form Styling */
    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
    }
    
    .input-group-text {
        border-radius: 0.5rem 0 0 0.5rem;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
    }
    
    /* Payment Row Styling */
    .payment-row {
        transition: all 0.2s;
    }
    
    .payment-row:hover {
        background-color: #f8f9fd;
    }
    
    /* Total Amount Styling */
    .total-display {
        font-size: 1.1rem;
        text-align: right;
    }
    
    .bg-info-light {
        background-color: #c2f0ff !important;
        border-color: #99e6ff !important;
    }
    
    /* Confirmation Button */
    .payment-action-section {
        opacity: 1;
        transform: translateY(0);
    }
    
    .payment-action-container {
        background-color: #f0f9ff !important;
        border: 1px dashed #99d6ff !important;
        transition: all 0.3s;
    }
    
    .payment-action-container:hover {
        border-color: #4e73df;
        background-color: #f8f9fd;
    }
    
    #checkout-btn {
        transition: all 0.3s ease;
        min-width: 250px;
    }
    
    #checkout-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(78, 115, 223, 0.3) !important;
    }
    
    /* Info Items */
    .info-item {
        transition: all 0.3s ease;
        background-color: #e6f7ff !important; /* Brighter light blue background */
        border: 1px solid #b3e0ff !important;
    }
    
    .info-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        background-color: #d4f1ff !important; /* Slightly darker on hover */
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Button hover animation
        $('#checkout-btn').hover(
            function() {
                $(this).find('i').addClass('animate__animated animate__heartBeat');
            },
            function() {
                $(this).find('i').removeClass('animate__animated animate__heartBeat');
            }
        );
    });
</script>
@endsection