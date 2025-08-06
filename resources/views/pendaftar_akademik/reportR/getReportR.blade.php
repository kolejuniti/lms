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
                    $offerDate = Carbon\Carbon::parse($rgs->date_offer);
                    $today = Carbon\Carbon::now();
                    $days = $today->greaterThan($offerDate) ? $offerDate->diffInDays($today) : 'Within Offer Date';
                @endphp
                @if($days === 'Within Offer Date')
                    style="background-color: #007bff; color: #fff;"
                @elseif($days <= 10)
                    style="background-color: #28a745; color: #fff;"
                @elseif($days > 10 && $days <= 30)
                    style="background-color: #ffc107; color: #000;"
                @elseif($days > 30)
                    style="background-color: #dc3545; color: #fff;"
                @endif
            @elseif($rgs->status == 14)
                style="background-color: #fd7e14; color: #fff;"
            @endif
            >
            @if($rgs->status == 1 && $rgs->date_offer)
                @php
                    $offerDate = Carbon\Carbon::parse($rgs->date_offer);
                    $today = Carbon\Carbon::now();
                    $daysPassed = $today->greaterThan($offerDate) ? $offerDate->diffInDays($today) : 'Within Offer Date';
                @endphp
                {{ $daysPassed }}
            @elseif($rgs->status == 2)
                Registered
            @elseif($rgs->status == 14)
                Rejected
            @else
                Others
            @endif
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
              <td><span class="clickable-count" data-category="below5" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5active'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below5" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5others'] }}</span></td>
            </tr>
            <tr>
              <td>5-9 days<br><small class="text-muted">({{ $data['dateRanges']['below10']['start'] }} - {{ $data['dateRanges']['below10']['end'] }})</small></td>
              <td><span class="clickable-count" data-category="below10" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10active'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below10" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below10others'] }}</span></td>
            </tr>
            <tr>
              <td>10-14 days<br><small class="text-muted">({{ $data['dateRanges']['below15']['start'] }} - {{ $data['dateRanges']['below15']['end'] }})</small></td>
              <td><span class="clickable-count" data-category="below15" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15active'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below15" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below15others'] }}</span></td>
            </tr>
            <tr>
              <td>15-19 days<br><small class="text-muted">({{ $data['dateRanges']['below20']['start'] }} - {{ $data['dateRanges']['below20']['end'] }})</small></td>
              <td><span class="clickable-count" data-category="below20" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20active'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below20" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below20others'] }}</span></td>
            </tr>
            <tr>
              <td>20-24 days<br><small class="text-muted">({{ $data['dateRanges']['below25']['start'] }} - {{ $data['dateRanges']['below25']['end'] }})</small></td>
              <td><span class="clickable-count" data-category="below25" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25active'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below25" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below25others'] }}</span></td>
            </tr>
            <tr>
              <td>25-29 days<br><small class="text-muted">({{ $data['dateRanges']['below30']['start'] }} - {{ $data['dateRanges']['below30']['end'] }})</small></td>
              <td><span class="clickable-count" data-category="below30" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30convert'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30active'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="below30" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below30others'] }}</span></td>
            </tr>
            <tr>
              <td>≥30 days<br><small class="text-muted">({{ $data['dateRanges']['above30']['start'] }})</small></td>
              <td><span class="clickable-count" data-category="above30" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30convert'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30active'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="above30" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['above30others'] }}</span></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">
                TOTAL STUDENTS
              </td>
              <td><span class="clickable-count" data-category="total" data-status="total" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['student']->count() }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="willregister" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5willregister'] + $data['below10willregister'] + $data['below15willregister'] + $data['below20willregister'] + $data['below25willregister'] + $data['below30willregister'] + $data['above30willregister'] }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="kiv" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5KIV'] + $data['below10KIV'] + $data['below15KIV'] + $data['below20KIV'] + $data['below25KIV'] + $data['below30KIV'] + $data['above30KIV'] }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="convert" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5convert'] + $data['below10convert'] + $data['below15convert'] + $data['below20convert'] + $data['below25convert'] + $data['below30convert'] + $data['above30convert'] }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="active" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5active'] + $data['below10active'] + $data['below15active'] + $data['below20active'] + $data['below25active'] + $data['below30active'] + $data['above30active'] }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="rejected" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5rejected'] + $data['below10rejected'] + $data['below15rejected'] + $data['below20rejected'] + $data['below25rejected'] + $data['below30rejected'] + $data['above30rejected'] }}</span></td>
              <td><span class="clickable-count" data-category="total" data-status="others" style="cursor: pointer; color: #007bff; text-decoration: underline;">{{ $data['below5others'] + $data['below10others'] + $data['below15others'] + $data['below20others'] + $data['below25others'] + $data['below30others'] + $data['above30others'] }}</span></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <!-- /.card-body -->
</div>

<!-- Custom CSS for aging report modal -->
<style>
  .clickable-count:hover {
    background-color: #e6f3ff !important;
    border-radius: 3px;
    padding: 2px 4px;
    text-decoration: none !important;
    font-weight: bold;
  }
  
  .modal-xl {
    max-width: 90% !important;
  }
  
  #modalStudentTable {
    font-size: 0.9em;
  }
  
  #modalStudentTable th {
    background-color: #343a40 !important;
    color: white !important;
    border-color: #343a40 !important;
    font-weight: 600;
    text-align: center;
  }
  
  #modalStudentTable td {
    vertical-align: middle;
    border-color: #dee2e6;
  }
  
  #modalStudentTable tbody tr:hover {
    background-color: #f8f9fa;
  }
  
  .modal-header {
    background-color: #007bff;
    color: white;
  }
  
  .modal-header .btn-close {
    filter: brightness(0) invert(1);
  }
  
  #modalCategoryTitle {
    color: #495057;
    font-weight: 600;
    margin-bottom: 5px;
  }
  
  #modalStudentCount {
    color: #6c757d;
    font-size: 0.9em;
  }
  
  .spinner-border {
    color: #007bff;
  }
</style>

<!-- Student List Modal -->
<div class="modal fade" id="studentListModal" tabindex="-1" aria-labelledby="studentListModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentListModalLabel">Student List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="modalLoadingSpinner" class="text-center" style="display: none;">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Loading students...</p>
        </div>
        
        <div id="modalErrorMessage" class="alert alert-danger" style="display: none;"></div>
        
        <div id="studentListContainer" style="display: none;">
          <div class="mb-3">
            <h6 id="modalCategoryTitle"></h6>
            <p class="text-muted mb-0" id="modalStudentCount"></p>
          </div>
          
          <div class="table-responsive">
            <table id="modalStudentTable" class="table table-striped table-bordered">
              <thead class="table-dark">
                <tr>
                  <th style="width: 5%">No.</th>
                  <th style="width: 20%">Name</th>
                  <th style="width: 15%">IC No.</th>
                  <th style="width: 15%">Matric No.</th>
                  <th style="width: 12%">Phone</th>
                  <th style="width: 10%">Gender</th>
                  <th style="width: 15%">Program</th>
                  <th style="width: 8%">Session</th>
                </tr>
              </thead>
              <tbody id="modalStudentTableBody">
                <!-- Student data will be populated here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="exportModalStudents" style="display: none;">
          <i class="fa fa-download"></i> Export to Excel
        </button>
      </div>
    </div>
  </div>
</div>

