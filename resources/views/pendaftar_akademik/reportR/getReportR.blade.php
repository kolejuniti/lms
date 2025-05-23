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
    <div class="table-container" id="students-table-container">
      <div class="individual-export-buttons mb-2">
        <button class="btn btn-sm btn-info export-excel" data-table="myTable"><i class="fa fa-file-excel-o"></i> Excel</button>
        <button class="btn btn-sm btn-danger export-pdf" data-table="myTable"><i class="fa fa-file-pdf-o"></i> PDF</button>
        <button class="btn btn-sm btn-secondary export-print" data-table="myTable"><i class="fa fa-print"></i> Print</button>
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
                    Date Register
                </th>
                <th style="width: 5%">
                    Date Offer
                </th>
                <th style="width: 5%">
                    Time Lapse After Offer (Days)
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
            {{ $rgs->date_register }}
            </td>
            <td>
            {{ $rgs->date_offer }}
            </td>
            <td
            @if($rgs->status == 1 && $rgs->date_offer)
                @php
                    $days = Carbon\Carbon::parse($rgs->date_offer)->diffInDays(now());
                @endphp
                @if($days <= 10)
                    style="background-color: #28a745; color: #fff;"
                @elseif($days > 10 && $days <= 30)
                    style="background-color: #ffc107; color: #000;"
                @elseif($days > 30)
                    style="background-color: #dc3545; color: #fff;"
                @endif
            @endif
            >
            {{ ($rgs->status == 1 && $rgs->date_offer) ? Carbon\Carbon::parse($rgs->date_offer)->diffInDays(now()) : 'Registered' }}
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
            <tr>
                <td colspan="14" style="text-align: center">
                    TOTAL STUDENTS
                </td>
                <td>
                    {{ $data['student']->count() }}
                </td>
              </tr>
        </tfoot>
      </table>
      </div>
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
    <div class="table-container" id="aging-table-container">
      <div class="individual-export-buttons mb-2">
        <button class="btn btn-sm btn-info export-excel" data-table="aging-table"><i class="fa fa-file-excel-o"></i> Excel</button>
        <button class="btn btn-sm btn-danger export-pdf" data-table="aging-table"><i class="fa fa-file-pdf-o"></i> PDF</button>
        <button class="btn btn-sm btn-secondary export-print" data-table="aging-table"><i class="fa fa-print"></i> Print</button>
      </div>
      <div class="table-responsive">
        <table id="aging-table" class="table table-striped">
          <thead>
            <tr>
              <th style="width: 50%">Days Range</th>
              <th style="width: 50%">Number of Students</th>
              <th style="width: 50%">Number of Students Will Register</th>
              <th style="width: 50%">Number of Students KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i></th>
              <th style="width: 50%">Number of Students Convert</th>
              <th style="width: 50%">Number of Students Active</th>
              <th style="width: 50%">Number of Students Rejected</th>
              <th style="width: 50%">Number of Students Others <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Includes: GAGAL BERHENTI, TARIK DIRI, MENINGGAL DUNIA, TANGGUH, DIBERHENTIKAN, TAMAT PENGAJIAN, TUKAR PROGRAM, GANTUNG, TUKAR KE KUKB, PINDAH KOLEJ, TIDAK TAMAT PENGAJIAN, TAMAT PENGAJIAN (MENINGGAL DUNIA)"></i></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>&lt;5 days<br><small class="text-muted">({{ $data['dateRanges']['below5']['start'] }} - {{ $data['dateRanges']['below5']['end'] }})</small></td>
              <td>{{ $data['below5'] }}</td>
              <td>{{ $data['below5willregister'] }}</td>
              <td>{{ $data['below5KIV'] }}</td>
              <td>{{ $data['below5convert'] }}</td>
              <td>{{ $data['below5active'] }}</td>
              <td>{{ $data['below5rejected'] }}</td>
              <td>{{ $data['below5others'] }}</td>
            </tr>
            <tr>
              <td>5-9 days<br><small class="text-muted">({{ $data['dateRanges']['below10']['start'] }} - {{ $data['dateRanges']['below10']['end'] }})</small></td>
              <td>{{ $data['below10'] }}</td>
              <td>{{ $data['below10willregister'] }}</td>
              <td>{{ $data['below10KIV'] }}</td>
              <td>{{ $data['below10convert'] }}</td>
              <td>{{ $data['below10active'] }}</td>
              <td>{{ $data['below10rejected'] }}</td>
              <td>{{ $data['below10others'] }}</td>
            </tr>
            <tr>
              <td>10-14 days<br><small class="text-muted">({{ $data['dateRanges']['below15']['start'] }} - {{ $data['dateRanges']['below15']['end'] }})</small></td>
              <td>{{ $data['below15'] }}</td>
              <td>{{ $data['below15willregister'] }}</td>
              <td>{{ $data['below15KIV'] }}</td>
              <td>{{ $data['below15convert'] }}</td>
              <td>{{ $data['below15active'] }}</td>
              <td>{{ $data['below15rejected'] }}</td>
              <td>{{ $data['below15others'] }}</td>
            </tr>
            <tr>
              <td>15-19 days<br><small class="text-muted">({{ $data['dateRanges']['below20']['start'] }} - {{ $data['dateRanges']['below20']['end'] }})</small></td>
              <td>{{ $data['below20'] }}</td>
              <td>{{ $data['below20willregister'] }}</td>
              <td>{{ $data['below20KIV'] }}</td>
              <td>{{ $data['below20convert'] }}</td>
              <td>{{ $data['below20active'] }}</td>
              <td>{{ $data['below20rejected'] }}</td>
              <td>{{ $data['below20others'] }}</td>
            </tr>
            <tr>
              <td>20-24 days<br><small class="text-muted">({{ $data['dateRanges']['below25']['start'] }} - {{ $data['dateRanges']['below25']['end'] }})</small></td>
              <td>{{ $data['below25'] }}</td>
              <td>{{ $data['below25willregister'] }}</td>
              <td>{{ $data['below25KIV'] }}</td>
              <td>{{ $data['below25convert'] }}</td>
              <td>{{ $data['below25active'] }}</td>
              <td>{{ $data['below25rejected'] }}</td>
              <td>{{ $data['below25others'] }}</td>
            </tr>
            <tr>
              <td>25-29 days<br><small class="text-muted">({{ $data['dateRanges']['below30']['start'] }} - {{ $data['dateRanges']['below30']['end'] }})</small></td>
              <td>{{ $data['below30'] }}</td>
              <td>{{ $data['below30willregister'] }}</td>
              <td>{{ $data['below30KIV'] }}</td>
              <td>{{ $data['below30convert'] }}</td>
              <td>{{ $data['below30active'] }}</td>
              <td>{{ $data['below30rejected'] }}</td>
              <td>{{ $data['below30others'] }}</td>
            </tr>
            <tr>
              <td>≥30 days<br><small class="text-muted">({{ $data['dateRanges']['above30']['start'] }})</small></td>
              <td>{{ $data['above30'] }}</td>
              <td>{{ $data['above30willregister'] }}</td>
              <td>{{ $data['above30KIV'] }}</td>
              <td>{{ $data['above30convert'] }}</td>
              <td>{{ $data['above30active'] }}</td>
              <td>{{ $data['above30rejected'] }}</td>
              <td>{{ $data['above30others'] }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">
                TOTAL STUDENTS
              </td>
              <td>{{ $data['student']->count() }}</td>
              <td>{{ $data['below5willregister'] + $data['below10willregister'] + $data['below15willregister'] + $data['below20willregister'] + $data['below25willregister'] + $data['below30willregister'] + $data['above30willregister'] }}</td>
              <td>{{ $data['below5KIV'] + $data['below10KIV'] + $data['below15KIV'] + $data['below20KIV'] + $data['below25KIV'] + $data['below30KIV'] + $data['above30KIV'] }}</td>
              <td>{{ $data['below5convert'] + $data['below10convert'] + $data['below15convert'] + $data['below20convert'] + $data['below25convert'] + $data['below30convert'] + $data['above30convert'] }}</td>
              <td>{{ $data['below5active'] + $data['below10active'] + $data['below15active'] + $data['below20active'] + $data['below25active'] + $data['below30active'] + $data['above30active'] }}</td>
              <td>{{ $data['below5rejected'] + $data['below10rejected'] + $data['below15rejected'] + $data['below20rejected'] + $data['below25rejected'] + $data['below30rejected'] + $data['above30rejected'] }}</td>
              <td>{{ $data['below5others'] + $data['below10others'] + $data['below15others'] + $data['below20others'] + $data['below25others'] + $data['below30others'] + $data['above30others'] }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <!-- /.card-body -->
</div>

