<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Student Info</b>
    </div>
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <p>Student Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->name }}</p>
                </div>
                <div class="form-group">
                    <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->status }}</p>
                </div>
                <div class="form-group">
                    <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->program }}</p>
                </div>
                <div class="form-group">
                    <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->intake }}</p>
                </div>
                <div class="form-group">
                    <p>Session &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->session }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['students']->ic }}</p>
                </div>
                <div class="form-group">
                    <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->no_matric }}</p>
                </div>
                <div class="form-group">
                    <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['students']->semester }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="pull-right">
                <a class="btn btn-info btn-sm" target="_blank" href="/AR/student/getSlipExam?student={{ $data['students']->ic }}">
                    <i class="fa fa-info">
                    </i>
                    Slip Exam
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Register Custom</b>
    </div>
    <div class="card-body">
        <div class="col-12">
              <table id="table_projectprogress_course" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
                <thead class="thead-themed">
                    <tr>
                        <th>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="course2">Course</label>
                                    <select class="form-select" id="course2" name="course2">
                                    <option value="" selected disabled>-</option>
                                      @foreach ($data['course'] as $crs)
                                      <option value="{{ $crs->id }}">{{ $crs->course_code }} - {{ $crs->course_name }}</option> 
                                      @endforeach
                                    </select>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div>
                                <button class="btn btn-success btn-sm mr-2" onclick="register2('{{ $data['student']->ic }}')">
                                    <i class="fa fa-user-plus">
                                    </i>
                                    Register
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="col-12">
    <div class="box">
        <div class="card-header">
        <h3 class="card-title d-flex">Unregistered Course</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                    <table id="table_projectprogress_course" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
                        <thead class="thead-themed">
                        <tr>
                            <th style="width: 1%">
                            No.
                            </th>
                            <th style="width: 20%">
                            Course Name
                            </th>
                            <th style="width: 5%">
                            Course Code
                            </th>
                            <th style="width: 5%">
                            Course Credit
                            </th>
                            <th style="width: 5%">
                            Semester
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['regCourse'] as $keys=>$crs)
                            <tr>
                                <td>
                                {{ $keys+1 }}
                                </td>
                                <td >
                                {{ $crs->course_name }}
                                </td>
                                <td>
                                {{ $crs->course_code }}
                                </td>
                                <td>
                                {{ $crs->course_credit }}
                                </td>
                                <td>
                                {{ $crs->semesterid }}
                                </td>
                                <td style="text-align: center;">
                                    <div class="pull right">
                                        <button class="btn btn-success btn-sm mr-2" onclick="register('{{ $crs->id }}','{{ $data['student']->ic }}')">
                                            <i class="fa fa-user-plus">
                                            </i>
                                            Register
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="tfoot-themed">
                            <tr>
                               
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-12">
    <div class="box">
        <div class="card-header">
        <h3 class="card-title d-flex">All Registered Course</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                    <table id="table_projectprogress_course" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
                        <thead class="thead-themed">
                        <tr>
                            <th style="width: 1%">
                            No.
                            </th>
                            <th style="width: 20%">
                            Course Name
                            </th>
                            <th style="width: 5%">
                            Course Code
                            </th>
                            <th style="width: 5%">
                            Course Credit
                            </th>
                            <th style="width: 5%">
                            Semester
                            </th>
                            <th style="width: 5%">
                            Session
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['allCourse'] as $keys=>$crs )
                            <tr>
                                <td>
                                {{ $keys+1 }}
                                </td>
                                <td >
                                {{ $crs->course_name }}
                                </td>
                                <td>
                                {{ $crs->course_code }}
                                </td>
                                <td>
                                {{ $crs->course_credit }}
                                </td>
                                <td>
                                {{ $crs->semester }}
                                </td>
                                <td>
                                {{ $crs->SessionName }}
                                </td>
                                @if(Auth::user()->usrtype == 'AR')
                                    <td style="text-align: center;">
                                        <div class="pull right">
                                            <button class="btn btn-danger btn-sm mr-2" onclick="unregister('{{ $crs->IDS }}','{{ $data['student']->ic }}')">
                                                <i class="fa fa-user-times">
                                                </i>
                                                Un-Register
                                            </button>
                                        </div>
                                    </td>
                                @elseif(Auth::user()->usrtype == 'PL')
                                    @if($data['students']->semester == $crs->semester)
                                        <td style="text-align: center;">
                                            @php
                                                $start_date = strtotime('2/9/2024');
                                                $end_date = strtotime('13/9/2024');
                                                $current_date = strtotime(date('d/m/Y'));
                                            @endphp
                                            @if ($current_date >= $start_date && $current_date <= $end_date)
                                                <div class="pull right">
                                                    <button class="btn btn-danger btn-sm mr-2" onclick="unregister('{{ $crs->IDS }}','{{ $data['student']->ic }}')">
                                                        <i class="fa fa-user-times">
                                                        </i>
                                                        Un-Register
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    @else
                                        <td>

                                        </td>
                                    @endif
                                @else
                                    <td>

                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="tfoot-themed">
                            <tr>
                                
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var error = "{{ $data['error'] ?? null}}";
    
    if (error != '') {
        alert(error);
    }
</script>