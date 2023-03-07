<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Update Sponsorship</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-6" id="program-card">
          <div class="form-group">
            <label class="form-label" for="package2">Package PTPTN</label>
            <select class="form-select" id="package2" name="package2">
              <option value="" selected disabled>-</option>
              @foreach ($data['package'] as $pkg)
                <option value="{{ $pkg->id }}" {{ ($data['sponsorPackage']->package_id == $pkg->id) ? 'Selected' : '' }}>{{ $pkg->name }}</option>
              @endforeach
            </select>
          </div>
        </div>       
        <div class="col-md-6" id="intake-card">
          <div class="form-group">
            <label class="form-label" for="method2">Payment Method</label>
            <select class="form-select" id="method2" name="method2">
              <option value="" selected>-</option>
              @foreach ($data['method'] as $mth)
                <option value="{{ $mth->id }}" {{ ($data['sponsorPackage']->payment_type_id == $mth->id) ? 'Selected' : '' }}>{{ $mth->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-6" id="claim-card">
          <div class="form-group">
            <label class="form-label" for="amount2">Amount (RM)</label>
            <input type="number" id="amount2" name="amount2" value="{{ $data['sponsorPackage']->amount }}" class="form-control">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mt-3">
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary pull-right mb-3" onclick="update('{{ $id }}')">Submit</button>
          </div>
        </div>
      </div>
    </div>
</div>