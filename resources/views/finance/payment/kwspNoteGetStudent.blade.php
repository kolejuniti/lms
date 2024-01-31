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
            <b>Payment Note Details</b>
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
            {{-- <div class="row">
                <div class="form-group">
                    <b>No. Vouchar</b>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-6" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" class="form-control" name="date" id="date">
                    </div>
                </div>
                <div class="col-md-6" id="discount-card">
                    <div class="form-group">
                    <label class="form-label" for="discount">Discount</label>
                    <select class="form-select" id="discount" name="discount">
                        <option value="" selected>-</option>
                        <option value="10%">10%</option>
                        <option value="20%">20%</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="method-card">
                    <div class="form-group">
                    <label class="form-label" for="method">Payment Method</label>
                    <select class="form-select" id="method" name="method">
                        <option value="" selected>-</option>
                        @foreach ($data['method'] as $mt)
                        <option value="{{ $mt->id }}">{{ $mt->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="col-md-6" id="bank-card">
                    <div class="form-group">
                    <label class="form-label" for="bank">Bank</label>
                    <select class="form-select" id="bank" name="bank">
                        <option value="" selected>-</option>
                        @foreach ($data['bank'] as $bk)
                        <option value="{{ $bk->id }}">{{ $bk->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" id="document-card">
                    <div class="form-group">
                        <label class="form-label" for="nodoc">Document No.</label>
                        <input type="text" class="form-control" onkeypress="return event.charCode != 32" name="nodoc" id="nodoc">
                    </div>
                </div>
                <div class="col-md-6" id="amount-card">
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
                        <label class="form-label">Payment Note List</label>
                        <table class="w-100 table table-bordered display margin-top-10 w-p100" id="kwsp_table">
                            <thead id="kwsp_list">
                                <tr>
                                    <th style="width: 1%">
                                        No.
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                    <th>
                                        Payment Method
                                    </th>
                                    <th>
                                        Bank
                                    </th>
                                    <th>
                                        Document No.
                                    </th>
                                    <th>
                                        Discount
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table">
                                @foreach ($data['list'] as $key=> $lst)
                                  <tr>
                                    <td style="width: 1%">
                                      {{ $key+1 }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $lst->date }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $lst->method }}
                                    </td>
                                    <th style="width: 15%">
                                      {{ $lst->bank }}
                                    </td>
                                    <th style="width: 20%">
                                      {{ $lst->document_no }}
                                    </td>
                                    <th style="width: 20%">
                                      {{ $lst->discount }}
                                    </td>
                                    <th style="width: 20%">
                                      {{ $lst->amount }}
                                    </td>
                                    <th>
                                        <a class="btn btn-danger btn-sm" href="#" onclick="deletedtl('{{ $lst->id }}', '{{ $lst->student_ic }}')">
                                            <i class="ti-trash">
                                            </i>
                                            Delete
                                        </a>
                                    </td>
                                  </tr>
                                @endforeach 
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
