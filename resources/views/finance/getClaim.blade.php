<div class="col-12">
  <div class="box">
    <div class="card-header">
    <h3 class="card-title d-flex">Registered Claims</h3>
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
                        Claim Name
                        </th>
                        <th style="width: 5%">
                        Price Per Unit
                        </th>
                        <th style="width: 5%">
                        Program
                        </th>
                        <th style="width: 5%">
                        Intake
                        </th>
                        <th style="width: 5%">
                        Semester
                        </th>
                        <th style="width: 20%">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $keys=>$dt)
                        <tr>
                            <td>
                            {{ $keys+1 }}
                            </td>
                            <td >
                            {{ $dt->name }}
                            </td>
                            <td >
                            {{ $dt->pricePerUnit }}
                            </td>
                            <td>
                            {{ $dt->progname }}
                            </td>
                            <td>
                            {{ $dt->SessionName }}
                            </td>
                            <td>
                            {{ $dt->semester_name }}
                            </td>
                            <td class="project-actions text-right" style="text-align: center;">
                              <a class="btn btn-info btn-sm pr-2" href="#" onclick="updatePackage('{{ $dt->id }}')">
                                  <i class="ti-pencil-alt">
                                  </i>
                                  Edit
                              </a>
                              <a class="btn btn-danger btn-sm" href="#" onclick="deletePackage('{{ $dt->id }}')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
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