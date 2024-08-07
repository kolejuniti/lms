<form action="/AR/session/create?idS={{ $id }}" method="post" role="form" enctype="multipart/form-data">
  @csrf
  @method('POST')
  <div class="modal-header">
  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
            <label class="form-label" for="year">Year</label>
            <select class="form-select" id="year" name="year">
            <option value="-" selected disabled>-</option>
            @foreach($data['year'] as $yr)
            <option value="{{ $yr->year }}" {{ ($data['course']->Year == $yr->year ? 'selected' : '') }}>{{ $yr->year }}</option>
            @endforeach
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="month">Month</label>
            <select class="form-select" id="month" name="month">
            <option value="-" selected disabled>-</option>
              <option value="JAN" {{ (substr($data['course']->SessionName, 0, 3) == "JAN" ? 'selected' : '') }}>January</option> 
              <option value="FEB" {{ (substr($data['course']->SessionName, 0, 3) == "FEB" ? 'selected' : '') }}>February</option> 
              <option value="MAC" {{ (substr($data['course']->SessionName, 0, 3) == "MAC" ? 'selected' : '') }}>March</option> 
              <option value="APR" {{ (substr($data['course']->SessionName, 0, 3) == "APR" ? 'selected' : '') }}>April</option> 
              <option value="MAY" {{ (substr($data['course']->SessionName, 0, 3) == "MAY" ? 'selected' : '') }}>May</option> 
              <option value="JUN" {{ (substr($data['course']->SessionName, 0, 3) == "JUN" ? 'selected' : '') }}>June</option> 
              <option value="JUL" {{ (substr($data['course']->SessionName, 0, 3) == "JUL" ? 'selected' : '') }}>July</option>
              <option value="OGS" {{ (substr($data['course']->SessionName, 0, 3) == "OGS" ? 'selected' : '') }}>August</option>
              <option value="SEP" {{ (substr($data['course']->SessionName, 0, 3) == "SEP" ? 'selected' : '') }}>September</option>
              <option value="OKT" {{ (substr($data['course']->SessionName, 0, 3) == "OKT" ? 'selected' : '') }}>October</option>
              <option value="NOV" {{ (substr($data['course']->SessionName, 0, 3) == "NOV" ? 'selected' : '') }}>November</option>
              <option value="DEC" {{ (substr($data['course']->SessionName, 0, 3) == "DEC" ? 'selected' : '') }}>December</option>
            </select>
        </div>
      </div>
      <div class="row align-items-center">
        <div class="col-md-5">
          <div class="form-group">
            <label class="form-label" for="year1">Year</label>
            <select class="form-select" id="year1" name="year1">
              <option value="-" selected disabled>-</option>
              @foreach($data['year'] as $yr)
                <option value="{{ $yr->year }}" {{ ($data['year1'] == $yr->year ? 'selected' : '') }}>{{ $yr->year }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-2 text-center">
          <div class="form-group">
            <span style="font-size: 45px;">\</span>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label class="form-label" for="year2">Year</label>
            <select class="form-select" id="year2" name="year2">
              <option value="-" selected disabled>-</option>
              @foreach($data['year'] as $yr)
                <option value="{{ $yr->year }}" {{ ($data['year2'] == $yr->year ? 'selected' : '') }}>{{ $yr->year }}</option>
              @endforeach
            </select>
          </div>
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