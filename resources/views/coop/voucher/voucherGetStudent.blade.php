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
        <div class="card-header {{ ($data['voucher']->status == 'BARU') ? 'bg-success' : 'bg-warning' }}">
        <b>Voucher Details</b>
        </div>
        <div class="card-body">
            <div class="row">       
                <div class="col-md-12" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="voucher">No. Voucher</label>
                        <input type="text" class="form-control" name="voucher" id="voucher" value="{{ $data['voucher']->no_voucher }}" readonly>
                    </div>
                </div> 
                <div class="col-md-12" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" class="form-control" name="amount" id="amount" value="{{ $data['voucher']->amount }}" readonly>
                    </div>
                </div>
                <div class="col-md-6" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <input type="text" class="form-control" name="status" id="status" value="{{ $data['voucher']->status }}" readonly>
                    </div>
                </div> 
                <div class="col-md-6" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="date">Expiry Date</label>
                        <input type="date" class="form-control" name="date" id="date" value="{{ $data['voucher']->expiry_date }}" readonly>
                    </div>
                </div>
                <div class="col-md-6" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="date">Redeem Date</label>
                        <input type="date" class="form-control" name="r_date" id="r_date" value="{{ $data['voucher']->redeem_date }}" {{ ($data['voucher']->status == 'BARU') ? '' : 'readonly' }}>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer {{ ($data['voucher']->status == 'BARU') ? 'bg-success' : 'bg-warning' }}">
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary pull-right mb-3" onclick="redeem('{{ $data['voucher']->id }}')">Redeem</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (isset($error))
    <script>
        // JavaScript code to display the alert
        if('{{ $error }}' != '')
        {
            alert('{{ $error }}');
        }
    </script>
    @endif
