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
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['allCourse'] as $keys=>$crs)
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