<form action="/AR/batch/create?idS={{ $id }}" method="post" role="form" enctype="multipart/form-data">
  @csrf
  @method('POST')
  <div class="modal-header">
  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
            <label class="form-label" for="name">Batch Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Batch Name" value="{{ $data['course']->BatchName }}">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Start</label>
          <input type="date" name="start" id="start" class="form-control" value="{{ $data['course']->Start }}">
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>End</label>
          <input type="date" name="end" id="end" class="form-control" value="{{ $data['course']->End }}">
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="status">Status</label>
            <select class="form-select" id="status" name="status">
            <option value="-" selected disabled>-</option>
              <option value="ACTIVE" {{ ($data['course']->Status == "ACTIVE" ? 'selected' : '') }}>ACTIVE</option> 
              <option value="NOTACTIVE" {{ ($data['course']->Status == "NOTACTIVE" ? 'selected' : '') }}>NOTACTIVE</option> 
            </select>
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