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
                        <p>Pre Register Payment &nbsp;: &nbsp;&nbsp; {{ optional($data['balancePRE']->first())->payment }}</p>
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
            </div>
        </div>
    </div>

    <div class="card mb-3" id="stud_info">
        <div class="card-header">
        <b>Payment Details</b>
        </div>
        <div class="card-body">
            <div class="table-responsive mb-2">
                <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                  <div class="row">
                    <div class="card-body p-0">
                      <table  class="w-100 table table-bordered display margin-top-10 w-p100" style="width: 100%;" role="grid" aria-describedby="complex_header_info" data-ordering="false">
                          <thead>
                              <tr>
                                  <th style="width: 5%">
                                      Date
                                  </th>
                                  <th style="width: 5%">
                                      Session
                                  </th>
                                  <th style="width: 5%">
                                      Semester
                                  </th>
                                  <th style="width: 5%">
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                @php
                                    echo date("Y-m-d");
                                @endphp
                              </td>
                              <td>
                                {{ $data['student']->session_name }}
                              </td>
                              <td>
                                {{ $data['student']->semester }}
                              </td>
                              <td class="project-actions text-right" >
                                <a class="btn btn-info btn-sm pr-2" onclick="register('{{ $data['student']->ic }}')">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Register
                                </a>
                              </td>
                            </tr>
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
              </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <b>Add Claims</b>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive mb-2">
                    <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                      <div class="row">
                        <div class="card-body p-0">
                          <table  class="w-100 table table-bordered display margin-top-10 w-p100" style="width: 100%;" role="grid" aria-describedby="complex_header_info" data-ordering="false">
                              <thead>
                                  <tr>
                                      <th style="width: 10%">
                                          About
                                      </th>
                                      <th style="width: 10%">
                                          Price Per Unit
                                      </th>
                                      <th style="width: 10%">
                                          Unit
                                      </th>
                                      <th style="width: 5%">
                                      </th>
                                  </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <div class="col-md-6" id="method-card">
                                        <div class="form-group">
                                        <select class="form-select" id="claim" name="claim">
                                            <option value="" selected disabled>-</option>
                                            @foreach ($data['claim'] as $clm)
                                            <option value="{{ $clm->id }}">{{ $clm->name }}</option>
                                            @endforeach
                                        </select>

                                        {{--<input class="form-select" list="claim-list" id="claim" name="claim" />
                                        <datalist id="claim-list">
                                            @foreach ($data['claim'] as $clm)
                                            <option value="{{ $clm->id }}">{{ $clm->name }}</option>
                                            @endforeach
                                        </datalist>--}}
                                        </div>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <input type="number" class="form-control" id="price" placeholder="0" name="price">
                                        </div>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <input type="number" class="form-control" id="unit" placeholder="0" name="unit">
                                        </div>
                                    </div>
                                  </td>
                                  <td class="project-actions text-right" >
                                    <a class="btn btn-info btn-sm pr-2" onclick="add('{{ $data['student']->ic }}')">
                                        <i class="ti-pencil-alt">
                                        </i>
                                        Add
                                    </a>
                                  </td>
                                </tr>
                              </tbody>
                          </table>
                        </div>
                        <!-- /.card-body -->
                      </div>
                    </div>
                </div> 
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="idclaim" id="idclaim">
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">List of claims</label>
                        <table id="claim_list" class="table table-striped projects display dataTable">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
