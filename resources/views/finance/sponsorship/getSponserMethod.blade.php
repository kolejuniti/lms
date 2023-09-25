<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Payment Details</b>
  </div>
  <div class="card-body">
      <div class="row">
          <div class="form-group">
              <b>Payment Charge Details</b>
          </div>
      </div>
      <div class="row">
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
                  <input type="text" class="form-control" name="nodoc" id="nodoc">
              </div>
          </div> 
          <div class="col-md-2" id="amount-card">
              <div class="form-group">
                  <label class="form-label" for="amount">Amount (RM)</label>
                  <input type="number" class="form-control" name="amount" id="amount">
              </div>
          </div>
          <div class="col-md-3" hidden>
            <div class="form-group">
                <input type="text" class="form-control" name="mainid" id="mainid" value="{{ $id }}">
            </div>
          </div> 
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
                  <table id="payment_list" class="table table-striped projects display dataTable">
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>