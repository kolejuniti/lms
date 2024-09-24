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
                    <div class="form-group">
                        <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                    </div>
                    <div class="form-group">
                        <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                    </div>
                    <div class="form-group">
                        <p>Session &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session_name }}</p>
                    </div>
                </div>      
                <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="session">Session</label>
                      <select class="form-select" id="session" name="session" {{ ($data['student']->campus_id == 1 || $data['student']->campus_id == '') ? 'disabled' : '' }}>
                        <option value="" selected disabled>-</option>
                        @foreach ($data['session'] as $ses)
                        <option value="{{ ($data['student']->campus_id == 1) ? '' : $ses->SessionID }}" {{ ($data['student']->session == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName }}</option> 
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="semester">Semester</label>
                      <select class="form-select" id="semester" name="semester" disabled>
                        <option value="" selected disabled>-</option>
                        @foreach ($data['semester'] as $ses)
                        <option value="{{ $ses->id }}" {{ ($data['student']->semester == $ses->id) ? 'selected' : '' }}>{{ $ses->semester_name }}</option> 
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="row d-flex mt-2 pull-right">
                    <div class="col-md-6">
                      <input type="checkbox" id="withheld" class="filled-in" name="withheld" value="1">
                      <label for="withheld">Postponed</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var no_matric = "{{ $data['student']->no_matric }}";
    </script>
