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
        <b>Payment Details</b>
        </div>
        <div class="card-body">
            <div class="row">       
                {{-- <div class="col-md-3" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" class="form-control" name="date" id="date">
                    </div>
                </div> --}}
                <div class="col-md-9" id="payment-card">
                    <div class="form-group">
                        <label class="form-label" for="discount">Type of Reduction</label>
                        <fieldset>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="discount" id="discount1" value="1">
                                <label for="discount1">
                                    Discount
                                </label>
                                <input class="form-check-input" type="radio" name="discount" id="discount2" value="2">
                                <label for="discount2">
                                    Fee Arrangement
                                </label>
                                <input class="form-check-input" type="radio" name="discount" id="discount3" value="3">
                                <label for="discount3">
                                    Dismissed / Postpone
                                </label>
                                <input class="form-check-input" type="radio" name="discount" id="discount4" value="4">
                                <label for="discount4">
                                    Excellent Incentive
                                </label>
                                <input class="form-check-input" type="radio" name="discount" id="discount5" value="5">
                                <label for="discount5">
                                    Others
                                </label>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="col-md-3" id="method-card">
                    <div class="form-group">
                    <label class="form-label" for="program">Program</label>
                    <select class="form-select" id="program" name="program" required>
                        <option value="" selected disabled>-</option>
                        @foreach ($data['program'] as $prg)
                        <option value="{{ $prg->id }}">{{ $prg->progname }}</option>
                        @endforeach
                    </select>
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
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="save('{{ $data['student']->ic }}')">Save</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">Payment Fee Details</label>
                        <table id="payment_list" class="table table-striped projects display dataTable">
                        </table>
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
