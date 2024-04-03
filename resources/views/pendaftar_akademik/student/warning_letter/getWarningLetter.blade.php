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
        </div>
        <!-- /.card-body -->
    </div>
<div class="card mb-3">
    <div class="card-header">
      <b>Student Warning List</b>
    </div>
    <div class="card-body">
        <div class="card-body">
            <table id="complex_header" class="table table-striped projects display dataTable">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            No.
                        </th>
                        <th style="width: 5%">
                            Code
                        </th>
                        <th style="width: 10%">
                            Subject
                        </th>
                        <th style="width: 5%">
                            Session
                        </th>
                        <th style="width: 5%">
                            Balance
                        </th>
                        <th style="width: 5%">
                            Percentage
                        </th>
                        <th style="width: 5%">
                            Warning
                        </th>
                        <th style="width: 10%">
                            Lecturer
                        </th>
                        <th style="width: 5%">
                        </th>
                    </tr>
                </thead>
                <tbody id="table">
                    @foreach ($data['warning'] as $key=> $wrn)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $wrn->course_code }}
                        </td>
                        <td>
                            {{ $wrn->course_name }}
                        </td>
                        <td>
                            {{ $wrn->SessionName }}
                        </td>
                        <td>
                            {{ $wrn->balance_attendance }}
                        </td>
                        <td>
                            {{ $wrn->percentage_attendance }}
                        </td>
                        <td>
                            {{ $wrn->warning }}
                        </td>
                        <td>
                            {{ $wrn->lecturer }}
                        </td>
                        <td>
                            <a class="btn btn-info btn-sm btn-sm mr-2" href="/AR/student/printWarningLetter?id={{ $wrn->id }}" target="_blank">
                                <i class="ti-pencil-alt">
                                </i>
                                Print
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>