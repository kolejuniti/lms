<div class="col-12">
  <div class="box">
    <div class="card-header">
    <h3 class="card-title d-flex">Payment Package List</h3>
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
                        <th>
                        Package
                        </th>
                        <th>
                        Type
                        </th>
                        <th>
                        Semester 1
                        </th>
                        <th>
                        Semester 2
                        </th>
                        <th>
                        Semester 3
                        </th>
                        <th>
                        Semester 4
                        </th>
                        <th>
                        Semester 5
                        </th>
                        <th>
                        Semester 6
                        </th>
                        <th>
                        Program
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data['payment'] as $keys => $pym)
                        <tr>
                            <td>
                            {{ $keys+1 }}
                            </td>
                            <td >
                            {{ $pym->package }}
                            </td>
                            <td >
                            {{ $pym->type }}
                            </td>
                            <td >
                            {{ $pym->semester_1 }}
                            </td>
                            <td >
                            {{ $pym->semester_2 }}
                            </td>
                            <td >
                            {{ $pym->semester_3 }}
                            </td>
                            <td >
                            {{ $pym->semester_4 }}
                            </td>
                            <td >
                            {{ $pym->semester_5 }}
                            </td>
                            <td >
                            {{ $pym->semester_6 }}
                            </td>
                            <td >
                              <a class="btn btn-info btn-sm pr-2" href="#" onclick="getProgram('{{ $pym->id }}')">
                                  <i class="ti-pencil-alt">
                                  </i>
                                  PROGRAM
                              </a>
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