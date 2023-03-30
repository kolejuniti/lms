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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                    </div>
                    <div class="form-group">
                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                    </div>
                </div>
            </div>
            <div class="row" id="stud_info">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="intake">Session (Intake)</label>
                        <select class="form-select" id="intake" name="intake">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}" {{ ($data['student']->session == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="batch">Batch</label>
                        <select class="form-select" id="batch" name="batch">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}" {{ ($data['student']->batch == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="session">Current Session</label>
                        <select class="form-select" id="session" name="session">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}" {{ ($data['student']->session == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="semester">Semester</label>
                        <select class="form-select" id="semester" name="semester">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['semester'] as $sem)
                        <option value="{{ $sem->id }}" {{ ($data['student']->semester == $sem->id) ? 'selected' : '' }}>{{ $sem->semester_name }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="status">Student Status</label>
                        <select class="form-select" id="status" name="status">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['status'] as $sts)
                        <option value="{{ $sts->id }}" {{ ($data['student']->status == $sts->id) ? 'selected' : '' }}>{{ $sts->name }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea id="commenttxt" name="comment" class="mt-2" rows="10" cols="80" required></textarea>
                    </div>   
                  </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submitForm('{{ $data['student']->ic }}')">Submit</button>
        </div>
    </div>
<div class="card mb-3">
    <div class="card-header">
      <b>Status Change History</b>
    </div>
    <div class="card-body">
        <div class="card-body">
            <table id="complex_header" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 10%">
                        No. IC
                    </th>
                    <th style="width: 15%">
                        Semester
                    </th>
                    <th style="width: 10%">
                        Session
                    </th>
                    <th style="width: 10%">
                        Status
                    </th>
                    <th style="width: 10%">
                        Date
                    </th>
                    <th style="width: 20%">
                        Remark
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['history'] as $key=> $hs)
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>
                        {{ $hs->student_ic }}
                    </td>
                    <td>
                        {{ $hs->semester_id }}
                    </td>
                    <td>
                        {{ $hs->SessionName }}
                    </td>
                    <td>
                        {{ $hs->name }}
                    </td>
                    <td>
                        {{ $hs->date }}
                    </td>
                    <td>
                        {!! $hs->remark !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>