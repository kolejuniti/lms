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
        <div class="card-header">
        <b>Payment Charge Addition Details</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2" id="type-card">
                    <div class="form-group">
                    <label class="form-label" for="type">Charge Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="" selected disabled>-</option>
                        @foreach ($data['type'] as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-md-2" id="unit-card">
                    <div class="form-group">
                        <label class="form-label" for="unit">Unit</label>
                        <input type="number" class="form-control" name="unit" id="unit">
                    </div>
                </div> 
                <div class="col-md-2" id="amount-card">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount (RM)</label>
                        <input type="number" class="form-control" name="amount" id="amount">
                    </div>
                </div> 
                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label class="form-label">Remark</label>
                        <textarea id="remark" name="remark" class="form-control mt-2" rows="10" cols="80"></textarea>
                    </div>   
                </div>  
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="idpayment" id="idpayment">
            </div> 
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="save('{{ $data['student']->ic }}')">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="form-group mt-3">
            <button type="submit" class="btn btn-info pull-right mb-3" onclick="getStatement('{{ $data['student']->ic }}')" >Student Statement</button>
            </div>
        </div>
    </div>
