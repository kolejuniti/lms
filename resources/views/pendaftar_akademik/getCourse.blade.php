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
            <label>ID</label>
            <input type="number" name="id" id="id" class="form-control" value="{{ $data['course']->sub_id }}">
          </div>
        </div>
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
              <label class="form-label" for="program2">Program</label>
              <select class="form-select" id="program2" name="program2">
              <option value="-" selected disabled>-</option>
                @foreach ($data['program'] as $prg)
                <option value="{{ $prg->id }}" {{ ($data['course']->prgid == $prg->id) ? 'selected' : '' }}>{{ $prg->progname}}</option> 
                @endforeach
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="semester">Semester</label>
              <select class="form-select" id="semester" name="semester">
              <option value="-" selected disabled>-</option>
                <option value="1" {{ ($data['course']->semesterid == '1') ? 'selected' : '' }}>Semester 1</option> 
                <option value="2" {{ ($data['course']->semesterid == '2') ? 'selected' : '' }}>Semester 2</option> 
                <option value="3" {{ ($data['course']->semesterid == '3') ? 'selected' : '' }}>Semester 3</option> 
                <option value="4" {{ ($data['course']->semesterid == '4') ? 'selected' : '' }}>Semester 4</option> 
                <option value="5" {{ ($data['course']->semesterid == '5') ? 'selected' : '' }}>Semester 5</option> 
                <option value="6" {{ ($data['course']->semesterid == '6') ? 'selected' : '' }}>Semester 6</option> 
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