<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Payment Method</b>
    </div>
    <div class="card-body">
        <div class="row">       
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="method">Method</label>
                    <select class="form-select" id="method" name="method">
                        <option value="-" selected disabled>-</option>
                        @foreach($data['method'] as $key => $mtd)
                        <option value="{{ $mtd->id }}">{{ $mtd->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="bank">Bank</label>
                    <select class="form-select" id="bank" name="bank">
                        <option value="-" selected disabled>-</option>
                        @foreach($data['bank'] as $key => $bnk)
                        <option value="{{ $bnk->id }}">{{ $bnk->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="nodoc">No. Document</label>
                    <input type="text" class="form-control" id="nodoc" name="nodoc">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label" for="amount">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01">
                </div>
            </div>
        </div>
        <div class="col-md-6" hidden>
            <input type="text" class="form-control" name="mainid" id="mainid" value="{{ $id }}">
        </div>
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary pull-right mb-3" onclick="add2('{{ $id }}')">Add</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="form-group mt-3">
                    <label class="form-label">Payment Method List</label>
                    <table class="w-100 table table-bordered display margin-top-10 w-p100" id="payment_list">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    No.
                                </th>
                                <th style="width: 10%">
                                    Date
                                </th>
                                <th style="width: 15%">
                                    Type
                                </th>
                                <th style="width: 10%">
                                    Amount
                                </th>
                                <th style="width: 20%">
                                    Remark
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

