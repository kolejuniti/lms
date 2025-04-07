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
                        <i class="fas fa-wallet me-2"></i> Payment Portal
                    </h4>
                    <div class="d-inline-block align-items-center mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="#" class="text-white-50"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item text-white" aria-current="page">Payment</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Warning alert for invalid payment -->
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeIn shadow-sm rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                    <div>
                        <strong>Warning:</strong> {{ session('warning') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="row">
                <div class="col-xl-12 col-12">
                    <!-- Main Payment Card -->
                    <div class="card rounded-4 border-0 shadow animate__animated animate__fadeInUp">
                        <div class="card-header bg-gradient-primary text-white py-3 rounded-top-4">
                            <div class="d-flex align-items-center">
                                <div class="card-header-icon rounded-circle bg-white text-primary p-3 me-3 shadow-sm">
                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                </div>
                                <h4 class="card-title mb-0">Yuran Pengajian</h4>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Payment Progress Stepper -->
                            <div class="payment-stepper mb-5">
                                <div class="d-flex justify-content-between position-relative">
                                    <div class="stepper-line"></div>
                                    <div class="stepper-item active" data-step="1">
                                        <div class="stepper-circle">1</div>
                                        <div class="stepper-label">Review Details</div>
                                    </div>
                                    <div class="stepper-item" data-step="2">
                                        <div class="stepper-circle">2</div>
                                        <div class="stepper-label">Select Amount</div>
                                    </div>
                                    <div class="stepper-item" data-step="3">
                                        <div class="stepper-circle">3</div>
                                        <div class="stepper-label">Confirm Payment</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Info Card -->
                            <div class="card border-0 rounded-4 shadow-sm hover-card mb-4" id="stud_info">
                                <div class="card-header bg-light rounded-top-4 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-icon bg-primary text-white rounded-circle p-2 me-3">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <h5 class="mb-0 text-white">Student Information</h5>
                                        {{-- <div class="ms-auto pull">
                                            <button class="btn toggle-card" data-target="student-info-body">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="card-body p-4" id="student-info-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Student Name</div>
                                                <div class="info-value fw-bold">{{ $data['student']->name }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Status</div>
                                                <div class="info-value fw-bold">
                                                    <span class="badge bg-info rounded-pill">{{ $data['student']->status }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Program</div>
                                                <div class="info-value fw-bold">{{ $data['student']->program }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Intake</div>
                                                <div class="info-value fw-bold">{{ $data['student']->intake_name }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">No. IC / No. Passport</div>
                                                <div class="info-value fw-bold">{{ $data['student']->ic }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">No. Matric</div>
                                                <div class="info-value fw-bold text-primary">{{ $data['student']->no_matric }}</div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Semester</div>
                                                <div class="info-value fw-bold">
                                                    <span class="badge bg-primary rounded-pill">{{ $data['student']->semester }}</span>
                                                </div>
                                            </div>
                                            <div class="info-item mb-3">
                                                <div class="info-label text-muted mb-1">Session</div>
                                                <div class="info-value fw-bold">{{ $data['student']->session_name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Methods Card removed as requested -->

                            <!-- Payment Details Card -->
                            <div class="card border-0 rounded-4 shadow-sm hover-card mb-4" id="payment_details">
                                <div class="card-header bg-light rounded-top-4 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-icon bg-warning text-white rounded-circle p-2 me-3">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <h5 class="mb-0 text-white">Payment Details</h5>
                                        <span class="badge bg-danger ms-2 pulse-animation" id="payment-required-badge">Required</span>
                                        {{-- <div class="ms-auto">
                                            <button class="btn toggle-card" data-target="payment-details-body">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="card-body p-4" id="payment-details-body">
                                    <div class="payment-details-wrapper">
                                        <h6 class="text-muted mb-3"><i class="fas fa-info-circle me-2"></i> Enter the amount you wish to pay for each item</h6>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-hover rounded">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Tarikh</th>
                                                        <th>About</th>
                                                        <th>Semester</th>
                                                        <th class="text-end">Due (RM)</th>
                                                        <th>Payment (RM)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table">
                                                    @foreach ($data['tuition'] as $key => $tsy)
                                                        @if($data['balance'][$key] != 0)
                                                        <tr class="payment-row">
                                                            <td>{{ date("Y-m-d") }}</td>
                                                            <td>
                                                                <span class="fw-medium">{{ $tsy->name }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info rounded-pill">{{ $tsy->semester_id }}</span>
                                                            </td>
                                                            <td class="text-end fw-bold text-danger">
                                                                RM {{ number_format((float)$data['balance'][$key], 2, '.', ',') }}
                                                            </td>
                                                            <td>
                                                                <div class="payment-input-wrapper">
                                                                    <input type="text" class="form-control" name="phyid[]" id="phyid[]" value="{{ $tsy->id }}" hidden>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">RM</span>
                                                                        <input type="number" 
                                                                            class="form-control payment-input" 
                                                                            name="payment[]" 
                                                                            id="payment[]" 
                                                                            step='0.01' 
                                                                            max="{{ $data['amount'][$key] }}" 
                                                                            placeholder="0.00"
                                                                            data-max="{{ $data['amount'][$key] }}">
                                                                        <button class="btn btn-outline-secondary pay-max-btn" type="button" 
                                                                            data-bs-toggle="tooltip" 
                                                                            data-bs-placement="top" 
                                                                            title="Pay Maximum Amount">
                                                                            Max
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-active">
                                                        <td colspan="3" class="text-end fw-bold">TOTAL AMOUNT :</td>
                                                        <td></td>
                                                        <td>
                                                            <div class="total-amount-container">
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-primary text-white">RM</span>
                                                                    <input type="text" class="form-control bg-light fw-bold fs-5 text-primary total-display" 
                                                                        name="text_sum" id="text_sum" readonly placeholder="0.00">
                                                                </div>
                                                                <div class="progress mt-2" style="height: 6px;">
                                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="payment-progress" role="progressbar" style="width: 0%"></div>
                                                                </div>
                                                                <small class="text-muted mt-1 d-block" id="payment-status">No payment entered</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <div class="col-md-6" hidden>
                                                <input type="text" class="form-control" name="total" id="total">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Confirm Payment Section -->
                            <div class="text-center payment-action-section my-4">
                                <div class="payment-action-container p-4 rounded-4 shadow-sm">
                                    <div class="mb-3" id="payment-summary">
                                        <h5>Payment Summary</h5>
                                        <div class="payment-amount-display">
                                            <span class="amount-label me-2">Total:</span>
                                            <span class="amount-value fs-2 fw-bold text-primary" id="summary-amount">RM 0.00</span>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="confirm-payment-btn" class="btn btn-lg btn-primary rounded-pill px-5 py-3 shadow-sm" onclick="confirm()">
                                        <i class="fas fa-lock me-2"></i> Confirm Payment
                                    </button>
                                    
                                    <div class="payment-security-info mt-3">
                                        <small class="text-muted d-flex align-items-center justify-content-center">
                                            <i class="fas fa-shield-alt me-2"></i> Your payment information is secure
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    
    .card-header .toggle-card {
        color: white;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin-right: 10px;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    
    .card-header .toggle-card:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }
    
    .card-header .toggle-card:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
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
    
    /* Stepper Styling */
    .payment-stepper {
        padding: 20px 0;
    }
    
    .stepper-line {
        position: absolute;
        top: 24px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    
    .stepper-item {
        z-index: 2;
        text-align: center;
        position: relative;
        width: 80px;
    }
    
    .stepper-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 10px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }
    
    .stepper-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .stepper-item.active .stepper-circle {
        background-color: #4e73df;
        color: white;
    }
    
    .stepper-item.active .stepper-label {
        color: #4e73df;
        font-weight: bold;
    }
    
    .stepper-item.completed .stepper-circle {
        background-color: #1cc88a;
        color: white;
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
    
    .payment-input {
        font-weight: 500;
        text-align: right;
    }
    
    .payment-input:focus {
        background-color: #fff8e6;
    }
    
    /* Pay Max Button */
    .pay-max-btn {
        border-radius: 0 0.5rem 0.5rem 0;
    }
    
    .pay-max-btn:hover {
        background-color: #e9ecef;
    }
    
    /* Total Amount Styling */
    .total-display {
        font-size: 1.1rem;
        text-align: right;
    }
    
    /* Confirmation Button */
    .payment-action-section {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s forwards;
        animation-delay: 0.3s;
    }
    
    .payment-action-container {
        background-color: #f8f9fa;
        border: 1px dashed #ced4da;
        transition: all 0.3s;
    }
    
    .payment-action-container:hover {
        border-color: #4e73df;
        background-color: #f8f9fd;
    }
    
    #confirm-payment-btn {
        transition: all 0.3s ease;
        min-width: 250px;
    }
    
    #confirm-payment-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(78, 115, 223, 0.3) !important;
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
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }
    
    /* Flash effect for total amount when it changes */
    .flash-update {
        animation: flash-animation 0.8s;
    }
    
    @keyframes flash-animation {
        0% { background-color: #fff; }
        30% { background-color: #d1ecf1; }
        100% { background-color: #fff; }
    }
    
    /* Toast Notifications */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .custom-toast {
        min-width: 300px;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
        animation: slideInRight 0.3s forwards;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Move to step 1 initially
        moveToStep(1);
        
        // Toggle card content
        $('.toggle-card').on('click', function() {
            const target = $(this).data('target');
            $('#' + target).slideToggle(300);
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });
        
        // Add Max button functionality
        $('.pay-max-btn').on('click', function() {
            const input = $(this).closest('.input-group').find('.payment-input');
            const max = parseFloat(input.data('max'));
            input.val(max);
            input.trigger('input');
            
            // Flash animation on the input
            input.addClass('flash-update');
            setTimeout(function() {
                input.removeClass('flash-update');
            }, 800);
        });
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
    
    // Calculate sum from payment inputs with enhanced visual feedback
    $('.payment-input').on('input', function() {
        let sum = 0;
        let totalDue = 0;
        
        // Calculate total payment and total due
        $('.payment-input').each(function() {
            sum += Number($(this).val()) || 0;
            totalDue += parseFloat($(this).data('max')) || 0;
        });
        
        // Update hidden input and display values
        $('#total').val(sum);
        $('#text_sum').val(sum.toFixed(2));
        $('#summary-amount').text('RM ' + sum.toFixed(2));
        
        // Calculate and update progress bar
        const progressPercentage = totalDue > 0 ? Math.min((sum / totalDue) * 100, 100) : 0;
        $('#payment-progress').css('width', progressPercentage + '%');
        
        // Update payment status text
        updatePaymentStatus(sum, totalDue);
        
        // Apply flash effect when the total changes
        $('#text_sum').addClass('flash-update');
        setTimeout(function() {
            $('#text_sum').removeClass('flash-update');
        }, 800);
        
        // Move to appropriate step based on payment
        if (sum > 0) {
            moveToStep(2);
        } else {
            moveToStep(1);
        }
    });
    
    // Update payment status text
    function updatePaymentStatus(currentAmount, totalDue) {
        const statusElement = $('#payment-status');
        
        if (currentAmount <= 0) {
            statusElement.text('No payment entered');
            statusElement.removeClass('text-success text-warning').addClass('text-muted');
        } else if (currentAmount < totalDue) {
            const percentage = Math.round((currentAmount / totalDue) * 100);
            statusElement.text(`Partial payment (${percentage}% of total)`);
            statusElement.removeClass('text-muted text-success').addClass('text-warning');
        } else {
            statusElement.text('Full payment');
            statusElement.removeClass('text-muted text-warning').addClass('text-success');
        }
        
        // Show/hide required badge
        if (currentAmount > 0) {
            $('#payment-required-badge').fadeOut();
        } else {
            $('#payment-required-badge').fadeIn();
        }
    }
    
    // Format numbers as currency
    function formatCurrency(input) {
        const value = parseFloat(input.value);
        if (!isNaN(value)) {
            input.value = value.toFixed(2);
        }
    }
    
    // Move to specified step in the stepper
    function moveToStep(step) {
        $('.stepper-item').removeClass('active completed');
        
        for (let i = 1; i <= 3; i++) {
            if (i < step) {
                $(`.stepper-item[data-step="${i}"]`).addClass('completed');
            } else if (i === step) {
                $(`.stepper-item[data-step="${i}"]`).addClass('active');
            }
        }
    }
    
    // Add event listeners to format inputs when focus is lost
    document.querySelectorAll('.payment-input').forEach(input => {
        input.addEventListener('blur', function() {
            formatCurrency(this);
        });
    });
    
    // Confirm payment function
    function confirm() {
        var total = $('#total').val();
        
        if(total != '') {
            if(parseFloat(total) > 2) {
                // Move to step 3
                moveToStep(3);
                
                // Show loading state on button
                const confirmBtn = document.getElementById('confirm-payment-btn');
                const originalText = confirmBtn.innerHTML;
                confirmBtn.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div> Processing Payment...';
                confirmBtn.disabled = true;
                
                var forminput = [];
                var formData = new FormData();
                
                var input = [];
                var input2 = [];
                
                forminput = {
                    total: total,
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
                    error: function(err) {
                        console.log(err);
                        
                        // Restore button state
                        confirmBtn.innerHTML = originalText;
                        confirmBtn.disabled = false;
                        
                        // Move back to step 2
                        moveToStep(2);
                        
                        // Show error toast
                        showToast('Payment processing failed. Please try again.', 'error');
                    },
                    success: function(res) {
                        try {
                            // Restore button state after delay for better UX
                            setTimeout(function() {
                                confirmBtn.innerHTML = originalText;
                                confirmBtn.disabled = false;
                            }, 1000);
                            
                            if(res.message == "Success") {
                                // Show success animation on button
                                confirmBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Payment Successful!';
                                confirmBtn.classList.remove('btn-primary');
                                confirmBtn.classList.add('btn-success');
                                
                                // Show success toast
                                showToast('Payment processed successfully!', 'success');
                                
                                if(res.alert != null) {
                                    showToast(res.alert, 'info');
                                }
                                
                                // Show confirmation animation
                                showPaymentConfirmation();
                                
                                // Open receipt in new tab
                                setTimeout(function() {
                                    window.open('/yuran-pengajian/showQuotation?id=' + res.id, '_blank');
                                    window.location.reload();
                                }, 1500);
                            } else {
                                // Move back to step 2
                                moveToStep(2);
                                
                                $('.error-field').html('');
                                if(res.message == "Field Error") {
                                    for (f in res.error) {
                                        $('#'+f+'_error').html(res.error[f]);
                                    }
                                    showToast('Please check form errors.', 'warning');
                                }
                                else if(res.message == "Please fill all required field!") {
                                    showToast(res.message, 'warning');
                                }
                                else {
                                    showToast(res.message, 'error');
                                }
                                $("html, body").animate({ scrollTop: 0 }, "fast");
                            }
                        } catch(err) {
                            // Restore button state
                            confirmBtn.innerHTML = originalText;
                            confirmBtn.disabled = false;
                            
                            // Move back to step 2
                            moveToStep(2);
                            
                            showToast('Oops, something went wrong!', 'error');
                        }
                    }
                });
            } else {
                showToast('Please make sure the amount is more than RM2!', 'warning');
            }
        } else {
            showToast('Please fill payment details first!', 'warning');
        }
    }
    
    // Show payment confirmation animation
    function showPaymentConfirmation() {
        // Create confirmation overlay
        const overlay = document.createElement('div');
        overlay.className = 'payment-confirmation-overlay';
        overlay.innerHTML = `
            <div class="payment-confirmation-container animate__animated animate__zoomIn">
                <div class="success-checkmark">
                    <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                    </div>
                </div>
                <h3 class="mt-4">Payment Successful!</h3>
                <p class="text-muted">Your payment has been processed successfully.</p>
                <div class="mt-3">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span>Redirecting to receipt...</span>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Create overlay styles
        const style = document.createElement('style');
        style.textContent = `
            .payment-confirmation-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                backdrop-filter: blur(3px);
            }
            
            .payment-confirmation-container {
                background-color: white;
                border-radius: 1rem;
                padding: 2rem;
                text-align: center;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                width: 90%;
            }
            
            .success-checkmark {
                width: 80px;
                height: 80px;
                margin: 0 auto;
            }
            
            .success-checkmark .check-icon {
                width: 80px;
                height: 80px;
                position: relative;
                border-radius: 50%;
                box-sizing: content-box;
                border: 4px solid #4CAF50;
            }
            
            .success-checkmark .check-icon::before {
                top: 3px;
                left: -2px;
                width: 30px;
                transform-origin: 100% 50%;
                border-radius: 100px 0 0 100px;
            }
            
            .success-checkmark .check-icon::after {
                top: 0;
                left: 30px;
                width: 60px;
                transform-origin: 0 50%;
                border-radius: 0 100px 100px 0;
                animation: rotate-circle 4.25s ease-in;
            }
            
            .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
                content: '';
                height: 100px;
                position: absolute;
                background: #FFFFFF;
                transform: rotate(-45deg);
            }
            
            .success-checkmark .check-icon .icon-line {
                height: 5px;
                background-color: #4CAF50;
                display: block;
                border-radius: 2px;
                position: absolute;
                z-index: 10;
            }
            
            .success-checkmark .check-icon .icon-line.line-tip {
                top: 46px;
                left: 14px;
                width: 25px;
                transform: rotate(45deg);
                animation: icon-line-tip 0.75s;
            }
            
            .success-checkmark .check-icon .icon-line.line-long {
                top: 38px;
                right: 8px;
                width: 47px;
                transform: rotate(-45deg);
                animation: icon-line-long 0.75s;
            }
            
            .success-checkmark .check-icon .icon-circle {
                top: -4px;
                left: -4px;
                z-index: 10;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                position: absolute;
                box-sizing: content-box;
                border: 4px solid rgba(76, 175, 80, 0.5);
            }
            
            .success-checkmark .check-icon .icon-fix {
                top: 8px;
                width: 5px;
                left: 26px;
                z-index: 1;
                height: 85px;
                position: absolute;
                transform: rotate(-45deg);
                background-color: #FFFFFF;
            }
            
            @keyframes rotate-circle {
                0% {
                    transform: rotate(-45deg);
                }
                5% {
                    transform: rotate(-45deg);
                }
                12% {
                    transform: rotate(-405deg);
                }
                100% {
                    transform: rotate(-405deg);
                }
            }
            
            @keyframes icon-line-tip {
                0% {
                    width: 0;
                    left: 1px;
                    top: 19px;
                }
                54% {
                    width: 0;
                    left: 1px;
                    top: 19px;
                }
                70% {
                    width: 50px;
                    left: -8px;
                    top: 37px;
                }
                84% {
                    width: 17px;
                    left: 21px;
                    top: 48px;
                }
                100% {
                    width: 25px;
                    left: 14px;
                    top: 45px;
                }
            }
            
            @keyframes icon-line-long {
                0% {
                    width: 0;
                    right: 46px;
                    top: 54px;
                }
                65% {
                    width: 0;
                    right: 46px;
                    top: 54px;
                }
                84% {
                    width: 55px;
                    right: 0px;
                    top: 35px;
                }
                100% {
                    width: 47px;
                    right: 8px;
                    top: 38px;
                }
            }
        `;
        
        document.head.appendChild(style);
        
        // Remove overlay after delay
        setTimeout(function() {
            overlay.remove();
            style.remove();
        }, 3000);
    }
    
    // Function to show toast notifications
    function showToast(message, type) {
        // Check if toast container exists, if not create it
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
        
        // Set icon and background based on type
        let iconClass, bgClass;
        switch(type) {
            case 'success':
                iconClass = 'fas fa-check-circle';
                bgClass = 'bg-success';
                break;
            case 'warning':
                iconClass = 'fas fa-exclamation-triangle';
                bgClass = 'bg-warning';
                break;
            case 'error':
                iconClass = 'fas fa-times-circle';
                bgClass = 'bg-danger';
                break;
            case 'info':
                iconClass = 'fas fa-info-circle';
                bgClass = 'bg-info';
                break;
            default:
                iconClass = 'fas fa-info-circle';
                bgClass = 'bg-primary';
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `custom-toast ${bgClass} text-white animate__animated animate__fadeInRight shadow-lg`;
        
        toast.innerHTML = `
            <div class="d-flex p-3">
                <div class="toast-icon me-3">
                    <i class="${iconClass} fa-2x"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-message">${message}</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        // Add toast to container
        toastContainer.appendChild(toast);
        
        // Remove toast after timeout
        setTimeout(() => {
            toast.classList.replace('animate__fadeInRight', 'animate__fadeOutRight');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
</script>

@endsection