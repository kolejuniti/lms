<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Students Report</b>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <h6>Toggle Column Visibility:</h6>
      <div class="form-group column-toggles">
        <div class="mb-2">
          <button id="select-all-columns" class="btn btn-primary btn-sm">Select All</button>
          <button id="deselect-all-columns" class="btn btn-secondary btn-sm">Deselect All</button>
        </div>
        <div class="row">
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="0" checked> No.</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="1" checked> Name</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="2" checked> No. IC</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="3" checked> No. Matric</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="4" checked> Phone No.</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="5" checked> Intake Session</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="6" checked> Date Offer</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="7" checked> Program</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="8" checked> Qualification</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="9" checked> Gender</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="10" checked> EA</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="11" checked> Amount</div>
          <div class="col-md-3 mb-1"><input type="checkbox" class="toggle-column" data-column="12" checked> Type</div>
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