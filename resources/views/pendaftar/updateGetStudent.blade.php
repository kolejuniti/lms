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
                        <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->statusName }}</p>
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
                        <option value="{{ $ses->SessionID }}" {{ ($data['student']->intake == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName }}</option> 
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="batch">Batch</label>
                        <select class="form-select" id="batch" name="batch">
                        <option value="-" selected disabled>-</option>
                        @foreach ($data['batch'] as $ses)
                        <option value="{{ $ses->BatchID }}" {{ ($data['student']->batch == $ses->BatchID) ? 'selected' : '' }}>{{ $ses->BatchName }}</option> 
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
                <div class="col-md-6 mr-3" id="kuliah-card">
                    <div class="form-group">
                      <label class="form-label" for="kuliah">Lectures Status</label>
                      <select class="form-select" id="kuliah" name="kuliah">
                        <option value="-" selected disabled>-</option>
                        <option value="1" {{ ($data['student']->student_status == 1) ? 'selected' : '' }}>Holding</option>
                        <option value="2" {{ ($data['student']->student_status == 2) ? 'selected' : '' }}>Kuliah</option>
                        <option value="4" {{ ($data['student']->student_status == 4) ? 'selected' : '' }}>Latihan Industri</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" class="mt-2" rows="10" cols="80" onkeyup="this.value = this.value.toUpperCase();" required></textarea>
                    </div>   
                  </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-info pull-left mb-3" onclick="generateMatric('{{ $data['student']->ic }}')">Generate Matric No.</button>
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
                        Lectures Status
                    </th>
                    <th style="width: 10%">
                        Date
                    </th>
                    <th style="width: 20%">
                        Remark
                    </th>
                    <th style="width: 10%">
                        Staff
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
                        {{ $kuliah[$key] }}
                    </td>
                    <td>
                        {{ $hs->date }}
                    </td>
                    <td>
                        {!! $hs->remark !!}
                    </td>
                    <td>
                        {{ $hs->staff }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
        </div>
    </div>
</div>