<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Students Report</b>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <h6>Column Visibility Options:</h6>
      <div class="form-group column-toggles">
        <div class="mb-2">
          <button type="button" id="show-all-columns" class="btn btn-primary btn-sm">Show All Columns</button>
          <button type="button" id="hide-all-columns" class="btn btn-secondary btn-sm ms-2">Hide All Columns</button>
        </div>
        <div class="row">
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-0" data-column="0" checked>
              <label class="form-check-label" for="col-0">Show No.</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-1" data-column="1" checked>
              <label class="form-check-label" for="col-1">Show Name</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-2" data-column="2" checked>
              <label class="form-check-label" for="col-2">Show No. IC</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-3" data-column="3" checked>
              <label class="form-check-label" for="col-3">Show No. Matric</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-4" data-column="4" checked>
              <label class="form-check-label" for="col-4">Show Phone No.</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-5" data-column="5" checked>
              <label class="form-check-label" for="col-5">Show Intake Session</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-6" data-column="6" checked>
              <label class="form-check-label" for="col-6">Show Date Offer</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-7" data-column="7" checked>
              <label class="form-check-label" for="col-7">Show Program</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-8" data-column="8" checked>
              <label class="form-check-label" for="col-8">Show Qualification</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-9" data-column="9" checked>
              <label class="form-check-label" for="col-9">Show Gender</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-10" data-column="10" checked>
              <label class="form-check-label" for="col-10">Show EA</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-11" data-column="11" checked>
              <label class="form-check-label" for="col-11">Show Type</label>
            </div>
          </div>
          <div class="col-md-3 mb-1">
            <div class="form-check">
              <input type="checkbox" class="form-check-input toggle-column" id="col-12" data-column="12" checked>
              <label class="form-check-label" for="col-12">Show Amount</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
          <tr>
              <th style="width: 1%">
                  No.
              </th>
              <th style="width: 15%">
                  Name
              </th>
              <th style="width: 5%">
                  No. IC
              </th>
              <th style="width: 5%">
                  No.Matric
              </th>
              <th style="width: 5%">
                  Phone No.
              </th>
              <th style="width: 5%">
                  Intake Session
              </th>
              <th style="width: 5%">
                  Date Offer
              </th>
              <th style="width: 5%">
                  Program
              </th>
              <th style="width: 5%">
                  Qualification
              </th>
              <th style="width: 5%">
                  Gender
              </th>
              <th style="width: 5%">
                  EA
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['student'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->ic }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->no_tel }}
          </td>
          <td>
          {{ $rgs->SessionName }}
          </td>
          <td>
          {{ $rgs->date_offer }}
          </td>
          <td>
          {{ $rgs->progcode }}
          </td>
          <td>
          {{ $data['qua'][$key] }}
          </td>
          <td>
          {{ $rgs->sex }}
          </td>
          <td>
          {{ $rgs->ea }}
          </td>
          <td>
          {{ $data['result'][$key]->group_alias }}
          </td>
          <td>
          {{ $data['result'][$key]->amount }}
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          {{-- <tr>
              <td colspan="9" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{  number_format($totalPreALL, 2) }}
              </td>
            </tr> --}}
      </tfoot>
    </table>
    </div>
  </div>
  <!-- /.card-body -->
</div>

<!-- Student Aging Report Card -->
<div class="card mb-3" id="aging_report">
  <div class="card-header">
    <b>Student Aging Report</b>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th style="width: 50%">Days Range</th>
            <th style="width: 50%">Number of Students</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>&lt;5 days</td>
            <td>{{ $data['below5'] }}</td>
          </tr>
          <tr>
            <td>5-9 days</td>
            <td>{{ $data['below10'] }}</td>
          </tr>
          <tr>
            <td>10-14 days</td>
            <td>{{ $data['below15'] }}</td>
          </tr>
          <tr>
            <td>15-19 days</td>
            <td>{{ $data['below20'] }}</td>
          </tr>
          <tr>
            <td>20-24 days</td>
            <td>{{ $data['below25'] }}</td>
          </tr>
          <tr>
            <td>25-29 days</td>
            <td>{{ $data['below30'] }}</td>
          </tr>
          <tr>
            <td>≥30 days</td>
            <td>{{ $data['above30'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

