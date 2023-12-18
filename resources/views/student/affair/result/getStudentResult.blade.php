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
      <b>Student Result List</b>
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
                            Name
                        </th>
                        <th style="width: 5%">
                            Ic / Passport No.
                        </th>
                        <th style="width: 5%">
                            No. Matric
                        </th>
                        <th style="width: 5%">
                            Semester
                        </th>
                        <th style="width: 5%">
                            Session
                        </th>
                        <th style="width: 5%">
                        </th>
                    </tr>
                </thead>
                <tbody id="table">
                    @foreach ($data['result'] as $key=> $rlt)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $rlt->name }}
                        </td>
                        <td>
                            {{ $rlt->student_ic }}
                        </td>
                        <td>
                            {{ $rlt->no_matric }}
                        </td>
                        <td>
                            {{ $rlt->semester }}
                        </td>
                        <td>
                            {{ $rlt->SessionName }}
                        </td>
                        <td>
                            <a class="btn btn-info btn-sm btn-sm mr-2" href="/pendaftar/student/result/overallResult?id={{ $rlt->id }}&&std=1" target="_blank">
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