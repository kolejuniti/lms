<div class="row col-md-12 d-flex align-items-center">
    <div class="col-md-5">
      <div class="form-group">
          <label class="form-label" for="campus">Old Semester</label>
          <select class="form-select" id="campus" name="campus" style="height:500px" multiple>
            @foreach ($data['campus'] as $cps)
            <option value="{{ $cps->no_matric }}">{{ $cps->name }} - {{ $cps->no_matric }}</option>   
            @endforeach
          </select>
      </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <button class="btn btn-success btn-sm mr-2" style="width: 100px;" onclick="onLeave()">
                <i class="fa fa-arrow-right">
                </i>
                {{-- &nbsp;&nbsp;&nbsp;&nbsp; --}}
            </button>
        </div>
        {{-- <div class="form-group">
            <button class="btn btn-success btn-sm mr-2" style="width: 100px;" onclick="onCampus()">
                <i class="fa fa-arrow-left">
                </i>
                Campus
            </button>
        </div> --}}
      </div>
    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="leave">New Semester</label>
            <select class="form-select" id="leave" name="leave" style="height:500px" multiple>
                @foreach ($data['leave'] as $cps)
                <option value="{{ $cps->no_matric }}">{{ $cps->name }} - {{ $cps->no_matric }}</option>   
                @endforeach
            </select>
        </div>
    </div>
  </div>