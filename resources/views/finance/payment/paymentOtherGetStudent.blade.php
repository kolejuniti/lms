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
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="save('{{ $data['student']->ic }}')">Save</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <b>Payment Charge Details</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3" id="type-card">
                    <div class="form-group">
                    <label class="form-label" for="type">About</label>
                    <select class="form-select" id="type" name="type">
                        @foreach ($data['type'] as $ty)
                        <option value="{{ $ty->id }}">{{ $ty->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-md-2" id="method-card">
                    <div class="form-group">
                    <label class="form-label" for="method">Payment Method</label>
                    <select class="form-select" id="method" name="method">
                        <option value="" selected disabled>-</option>
                        @foreach ($data['method'] as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-md-2" id="bank-card">
                    <div class="form-group">
                    <label class="form-label" for="bank">Bank</label>
                    <select class="form-select" id="bank" name="bank">
                        <option value="" selected>-</option>
                        @foreach ($data['bank'] as $bk)
                        <option value="{{ $bk->id }}">{{ $bk->code }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-md-3" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="nodoc">Document No.</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="nodoc" id="nodoc">
                    </div>
                </div> 
                <div class="col-md-2" id="amount-card">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount (RM)</label>
                        <input type="number" class="form-control" name="amount" id="amount">
                    </div>
                </div> 
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="idpayment" id="idpayment">
            </div> 
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="add('{{ $data['student']->ic }}')">Add</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">Payment Charge List</label>
                        <table id="payment_list" class="table table-striped projects display dataTable">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
