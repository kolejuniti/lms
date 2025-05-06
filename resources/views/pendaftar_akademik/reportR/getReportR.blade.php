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
          <button type="button" id="hide-all-columns" class="btn btn-secondary btn-sm">Hide All Columns</button>
        </div>
        <div class="row">
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="0" checked> Show No.</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="1" checked> Show Name</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="2" checked> Show No. IC</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="3" checked> Show No. Matric</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="4" checked> Show Phone No.</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="5" checked> Show Intake Session</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="6" checked> Show Date Offer</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="7" checked> Show Program</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="8" checked> Show Qualification</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="9" checked> Show Gender</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="10" checked> Show EA</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="11" checked> Show Amount</label></div>
          <div class="col-md-3 mb-1"><label><input type="checkbox" class="toggle-column" data-column="12" checked> Show Type</label></div>
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
                  Amount
              </th>
              <th style="width: 5%">
                  Type
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