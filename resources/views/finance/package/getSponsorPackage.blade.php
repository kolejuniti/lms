<div class="col-12">
  <div class="box">
    <div class="card-header">
    <h3 class="card-title d-flex">Sponsor List</h3>
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
                        Student Name
                        </th>
                        <th>
                        No. IC
                        </th>
                        <th>
                        Program
                        </th>
                        <th>
                        Intake
                        </th>
                        <th>
                        Semester
                        </th>
                        <th>
                        Package PTPTN
                        </th>
                        <th>
                        Payment Method
                        </th>
                        <th>
                        Amount
                        </th>
                        <th>
                        Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['sponsorPackage'] as $key => $spn)
                    <tr>
                        <td>
                            {{ $key+1 }}
                        </td>
                        <td>
                            {{ $spn->student }}
                        </td>
                        <td>
                            {{ $spn->ic }}
                        </td>
                        <td>
                            {{ $spn->progcode }}
                        </td>
                        <td>
                            {{ $spn->SessionName }}
                        </td>
                        <td>
                            {{ $spn->semester }}
                        </td>
                        <td>
                            {{ $spn->package }}
                        </td>
                        <td>
                            {{ $spn->type }}
                        </td>
                        <td>
                            {{ $spn->amount }}
                        </td>
                        <td>
                            <a class="btn btn-info btn-sm pr-2" href="#" onclick="getEditPackage('{{ $spn->id }}')">
                                <i class="ti-pencil-alt">
                                </i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm pr-2" href="#" onclick="deletePackage('{{ $spn->id }}')">
                                <i class="ti-pencil-alt">
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