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
            <b>Voucher Details</b>
        </div>
        @if(Session::has('success'))
          <div class="form-group">
              <div class="alert alert-success">
                  <span>{{ Session::get('success') }}</span>
              </div>
          </div>
        @elseif(Session::has('error'))
            <div class="form-group">
                <div class="alert alert-danger">
                    <span>{{ Session::get('error') }}</span>
                </div>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <div class="form-group">
                    <b>No. Vouchar</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="from">From</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="from" id="from">
                    </div>
                </div>
                <div class="col-md-3" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="to">To</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="to" id="to">
                    </div>
                </div> 
            </div>
            <div class="col-md-6" hidden>
                <input type="text" class="form-control" name="ic" id="ic" value="{{ $data['student']->ic }}">
            </div> 
            <div class="row">
                <div class="col-md-2" id="amount-card">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount (RM)</label>
                        <input type="number" class="form-control" name="amount" id="amount">
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="add('{{ $data['student']->ic }}')">Add</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="form-group mt-3">
                        <label class="form-label">Vouchar List</label>
                        <table class="w-100 table table-bordered display margin-top-10 w-p100" id="voucher_table">
                            <thead id="voucher_list">
                                <tr>
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th>
                                        No. Vouchar
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['voucher'] as $key=> $vcr)
                                  <tr>
                                    <td style="width: 1%">
                                      {{ $key+1 }}
                                    </td>
                                    <th>
                                      {{ $vcr->no_voucher }}
                                    </td>
                                    <th>
                                      {{ $vcr->amount }}
                                    </td>
                                    <th>
                                      {{ $vcr->name }}
                                    </td>
                                    <th>
                                        @if($vcr->name != 'SAH')
                                        <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('{{ $vcr->id }}')" {{ ($vcr->name === 'SAH' ? 'disabled' : '')  }}>
                                            <i class="ti-trash">
                                            </i>
                                            Delete
                                        </a>
                                        @endif
                                    </td>
                                  </tr>
                                @endforeach 
                                  <tfoot>
                                    <tr>
                                        <td style="width: 1%">
                                        
                                        </td>
                                        <td style="width: 15%">
                                        TOTAL AMOUNT  :
                                        </td>
                                        <td style="width: 15%">
                                        {{ $data['sum'] }}
                                        </td>
                                        <td style="width: 30%">
                                        
                                        </td>
                                        <td>
                                    
                                        </td>
                                    </tr>
                                  </tfoot>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
