<form action="/AR/course/create?idS={{ $id }}" method="post" role="form" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="modal-header">
        <div class="">
            <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                &times;
            </button>
        </div>
    </div>
    <div class="modal-body">
      <div class="row col-md-12">
        <div>
          <div class="form-group">
            <label>Course Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $data['course']->course_name }}">
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Course Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $data['course']->course_code }}">
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Credit</label>
            <input type="text" name="credit" id="credit" class="form-control" value="{{ $data['course']->course_credit }}">
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="prerequisite">Prerequisite</label>
              <select class="form-select" id="prerequisite" name="prerequisite">
                <option value="-" selected disabled>-</option>
                @foreach ($data['courselist'] as $prg)
                <option value="{{ $prg->sub_id }}" {{ ($data['course']->prerequisite_id == $prg->sub_id) ? 'selected' : '' }}>{{ $prg->course_code}} - {{ $prg->course_name}}</option> 
                @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="clid">Course Level ID</label>
              <select class="form-select" id="clid" name="clid">
                <option value="-" selected disabled>-</option>
                @foreach ($data['level'] as $clid)
                <option value="{{ $clid->id }}" {{ ($data['course']->course_level_id == $clid->id) ? 'selected' : '' }}>{{ $clid->name}}</option> 
                @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="offer">Offer</label>
              <select class="form-select" id="offer" name="offer">
                <option value="" selected disabled>-</option>
                <option value="1" {{ ($data['course']->offer == 1) ? 'selected' : '' }}>Offered</option>
                <option value="0" {{ ($data['course']->offer == 0) ? 'selected' : '' }}>Not Offered</option>
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