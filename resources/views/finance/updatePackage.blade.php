<form action="/finance/claimpackage/addclaim?idS={{ $data['package']->id }}" method="post" role="form" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="modal-header">

    </div>
    <div class="modal-body">
      <div class="row col-md-12">
        <div>
          <div class="form-group">
              <label class="form-label" for="programs">Program</label>
              <select class="form-select" id="programs" name="programs">
              <option value="-" selected disabled>-</option>
              @foreach ($data['program'] as $prg)
              <option value="{{ $prg->id }}" {{ ($data['package']->program_id == $prg->id) ? 'selected' : '' }}>{{ $prg->progname }}</option>
              @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="intakes">Intake</label>
              <select class="form-select" id="intakes" name="intakes">
              <option value="-" selected disabled>-</option>
              @foreach ($data['session'] as $ses)
              <option value="{{ $ses->SessionID }}" {{ ($data['package']->intake_id == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName}}</option> 
              @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="semesters">Semester</label>
              <select class="form-select" id="semesters" name="semesters">
              <option value="-" selected disabled>-</option>
              @foreach ($data['semester'] as $ses)
              <option value="{{ $ses->id }}" {{ ($data['package']->semester_id == $ses->id) ? 'selected' : '' }}>{{ $ses->semester_name}}</option> 
              @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="claims">Claim Package</label>
              <select class="form-select" id="claims" name="claims">
              <option value="-" selected disabled>-</option>
              @foreach ($data['claim'] as $clm)
              <option value="{{ $clm->id }}" {{ ($data['package']->claim_id == $clm->id) ? 'selected' : '' }}>{{ $clm->name}}</option> 
              @endforeach
              </select>
          </div>
        </div>
        <div class="col-md-6" id="claim-card">
          <div class="form-group">
            <label class="form-label" for="prices">Price per Unit (RM)</label>
            <input type="number" id="prices" name="prices" class="form-control" value="{{ $data['package']->pricePerUnit }}">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
        <div class="form-group pull-right">
            <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
        </div>
    </div>
</form>