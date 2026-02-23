<!-- new student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>New Student</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalNewALL = 0;
        @endphp
        @foreach ($data['newStudent'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalNewALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalNewALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- old student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Old Student</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalOldALL = 0;
        @endphp
        @foreach ($data['oldStudent'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalOldALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalOldALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Summary: New Student & Old Student combined --}}
<div class="card mb-3">
  <div class="card-header">
    <b>Summary by Program</b>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered table-sm table-hover mb-0" style="font-size: 0.88rem;">
      <thead class="thead-dark">
        <tr>
          <th rowspan="2" class="align-middle text-center" style="width: 5%">No. Program</th>
          <th rowspan="2" class="align-middle text-center" style="width: 8%">Program</th>
          <th colspan="2" class="text-center" style="background-color: #17a2b8; color: #fff;">Student Quote (RM)</th>
        </tr>
        <tr>
          <th class="text-center" style="background-color: #138496; color: #fff; width: 10%">New Student</th>
          <th class="text-center" style="background-color: #117a8b; color: #fff; width: 10%">Old Student</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td class="text-center">{{ $prg->program_ID }}</td>
          <td class="font-weight-bold">{{ $prg->progcode }}</td>
          <td class="text-right">{{ number_format((!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0, 2) }}</td>
          <td class="text-right">{{ number_format((!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="table-secondary font-weight-bold">
          <td colspan="2" class="text-center">TOTAL</td>
          <td class="text-right">{{ number_format(array_sum($data['newStudentTotals']), 2) }}</td>
          <td class="text-right">{{ number_format(array_sum($data['oldStudentTotals']), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

{{-- ===== GRAND PROGRAMME SUMMARY DASHBOARD ===== --}}
<div class="card mb-4" style="border: 2px solid #343a40;">
  <div class="card-header" style="background: linear-gradient(135deg, #343a40, #495057); color: #fff;">
    <b>Programme Summary Dashboard</b>
    <small class="ml-2 text-white-50">(All charges grouped by programme)</small>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-sm table-hover mb-0" style="font-size: 0.8rem; min-width: 1100px;">
        <thead>
          <tr>
            <th rowspan="2" class="align-middle text-center" style="background:#343a40; color:#fff; width:5%;">#</th>
            <th rowspan="2" class="align-middle text-center" style="background:#343a40; color:#fff; width:8%;">Program</th>
            <th colspan="2" class="text-center" style="background:#17a2b8; color:#fff;">Student Fee (RM)</th>
            <th colspan="3" class="text-center" style="background:#dc3545; color:#fff;">Debit Note (RM)</th>
            <th colspan="1" class="text-center" style="background:#fd7e14; color:#fff;">Summons/Fine (RM)</th>
            <th colspan="2" class="text-center" style="background:#6f42c1; color:#fff;">Credit Note – Fee (RM)</th>
            <th colspan="2" class="text-center" style="background:#20c997; color:#fff;">Credit Note – Fine (RM)</th>
            <th colspan="1" class="text-center" style="background:#6c757d; color:#fff;">Credit Note – Discount (RM)</th>
          </tr>
          <tr>
            <th class="text-center" style="background:#138496; color:#fff;">New</th>
            <th class="text-center" style="background:#117a8b; color:#fff;">Old</th>
            <th class="text-center" style="background:#c82333; color:#fff;">Debit</th>
            <th class="text-center" style="background:#a71d2a; color:#fff;">Correction</th>
            <th class="text-center" style="background:#881524; color:#fff;">Correction Insentif/Tabung</th>
            <th class="text-center" style="background:#e8590c; color:#fff;">Fine</th>
            <th class="text-center" style="background:#5a32a3; color:#fff;">Active &amp; Withdraw</th>
            <th class="text-center" style="background:#4c1d8a; color:#fff;">Graduation</th>
            <th class="text-center" style="background:#199d76; color:#fff;">Active &amp; Withdraw</th>
            <th class="text-center" style="background:#168a64; color:#fff;">Graduation</th>
            <th class="text-center" style="background:#545b62; color:#fff;">Discount</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data['program'] as $key => $prg)
          <tr>
            <td class="text-center">{{ $prg->program_ID }}</td>
            <td class="font-weight-bold">{{ $prg->progcode }}</td>
            <td class="text-right">{{ number_format((!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['debitTotals'])) ? $data['debitTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['debitCorrectionTotals'])) ? $data['debitCorrectionTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['debitCorrectionIncentifTotals'])) ? $data['debitCorrectionIncentifTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">-</td>
            <td class="text-right">{{ number_format((!empty($data['creditFeeOldTotals'])) ? $data['creditFeeOldTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['creditFeeGradTotals'])) ? $data['creditFeeGradTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['creditFineOldTotals'])) ? $data['creditFineOldTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['creditFineGradTotals'])) ? $data['creditFineGradTotals'][$key] : 0, 2) }}</td>
            <td class="text-right">{{ number_format((!empty($data['creditDiscountTotals'])) ? $data['creditDiscountTotals'][$key] : 0, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr style="background:#dee2e6; font-weight:bold;">
            <td colspan="2" class="text-center">TOTAL</td>
            <td class="text-right">{{ number_format(array_sum($data['newStudentTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['oldStudentTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['debitTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['debitCorrectionTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['debitCorrectionIncentifTotals']), 2) }}</td>
            <td class="text-right">-</td>
            <td class="text-right">{{ number_format(array_sum($data['creditFeeOldTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['creditFeeGradTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['creditFineOldTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['creditFineGradTotals']), 2) }}</td>
            <td class="text-right">{{ number_format(array_sum($data['creditDiscountTotals']), 2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- debit -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Debit Note</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <th>
            Claim
          </th>
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalDebitALL = 0;
        @endphp
        @foreach ($data['debit'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <td>
            {{ $rgs->type }}
          </td>
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalDebitALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="10" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalDebitALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Debit Note per-program breakdown is in the Grand Summary Dashboard above --}}

<!-- debit correction (non-insentif/tabung) -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Debit Note – Correction</b>
    <!-- <small class="text-muted ml-2">(correction = 1, remark not insentif / tabung)</small> -->
  </div>
  <div class="card-body p-0">
    <table id="myTableDebitCorrection" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">No.</th>
          <th>Date</th>
          <th>No. Resit</th>
          <th>Name</th>
          <th>No.KP</th>
          <th>No.Matric</th>
          <th>Program</th>
          <th>Semester</th>
          <th>Claim</th>
          <th>Remark</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @php $totalDebitCorrectionALL = 0; @endphp
        @foreach ($data['debitCorrection'] as $key => $rgs)
        <tr>
          <td>{{ $key+1 }}</td>
          <td>{{ $rgs->date }}</td>
          <td>{{ $rgs->ref_no }}</td>
          <td>{{ $rgs->name }}</td>
          <td>{{ $rgs->student_ic }}</td>
          <td>{{ $rgs->no_matric }}</td>
          <td>{{ $rgs->progname }}</td>
          <td>{{ $rgs->semester_id }}</td>
          <td>{{ $rgs->type }}</td>
          <td>{{ $rgs->remark }}</td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php $totalDebitCorrectionALL += $rgs->amount; @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="10" style="text-align: center">TOTAL</td>
          <td>{{ number_format($totalDebitCorrectionALL, 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- debit correction incentif/tabung -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Debit Note – Correction Insentif / Tabung</b>
    <!-- <small class="text-muted ml-2">(correction = 1, remark like insentif / tabung)</small> -->
  </div>
  <div class="card-body p-0">
    <table id="myTableDebitIncentif" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">No.</th>
          <th>Date</th>
          <th>No. Resit</th>
          <th>Name</th>
          <th>No.KP</th>
          <th>No.Matric</th>
          <th>Program</th>
          <th>Semester</th>
          <th>Claim</th>
          <th>Remark</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @php $totalDebitIncentifALL = 0; @endphp
        @foreach ($data['debitCorrectionIncentif'] as $key => $rgs)
        <tr>
          <td>{{ $key+1 }}</td>
          <td>{{ $rgs->date }}</td>
          <td>{{ $rgs->ref_no }}</td>
          <td>{{ $rgs->name }}</td>
          <td>{{ $rgs->student_ic }}</td>
          <td>{{ $rgs->no_matric }}</td>
          <td>{{ $rgs->progname }}</td>
          <td>{{ $rgs->semester_id }}</td>
          <td>{{ $rgs->type }}</td>
          <td>{{ $rgs->remark }}</td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php $totalDebitIncentifALL += $rgs->amount; @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="10" style="text-align: center">TOTAL</td>
          <td>{{ number_format($totalDebitIncentifALL, 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- fine -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Summons / Fine</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <th>
            Claim
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalFineALL = 0;
        @endphp
        @foreach ($data['fine'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <td>
            {{ $rgs->type }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalFineALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalFineALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- other -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Others</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <th>
            Claim
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalOtherALL = 0;
        @endphp
        @foreach ($data['other'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <td>
            {{ $rgs->type }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalOtherALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalOtherALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Other Type summary --}}
<div class="card mb-3">
  <div class="card-header">
    <b>Others – Breakdown by Type</b>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered table-sm table-hover mb-0" style="font-size: 0.88rem;">
      <thead class="thead-dark">
        <tr>
          <th style="width:5%" class="text-center">No.</th>
          <th>Type</th>
          <th class="text-right" style="width:15%">Total (RM)</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data['otherCharge'] as $key => $chg)
        <tr>
          <td class="text-center">{{ $key+1 }}</td>
          <td>{{ $chg->name }}</td>
          <td class="text-right">{{ number_format((!empty($data['other'])) ? $data['otherTotals'][$key] : 0, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="table-secondary font-weight-bold">
          <td colspan="2" class="text-center">TOTAL</td>
          <td class="text-right">{{ number_format(array_sum($data['otherTotals']), 2) }}</td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<!-- creditFee -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Credit Note Active & Withdraw Student (Fee)</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Student ID
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <!-- <th>
            Claim
          </th> -->
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalcreditFeeALL1 = 0;
        @endphp
        @foreach ($data['creditFeeOld'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->student_id ? str_pad($rgs->student_id, strlen($rgs->student_id) + 1, '1', STR_PAD_LEFT) : '' }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <!-- <td>
            {{ $rgs->reduction_id }}
          </td> -->
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalcreditFeeALL1 += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="10" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalcreditFeeALL1, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Credit Note Active & Withdraw (Fee) per-program breakdown is in the Grand Summary Dashboard --}}

<!-- creditFee -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Credit Note Graduation Student (Fee)</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <!-- <th>
            Claim
          </th> -->
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalcreditFeeALL2 = 0;
        @endphp
        @foreach ($data['creditFeeGrad'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <!-- <td>
            {{ $rgs->reduction_id }}
          </td> -->
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalcreditFeeALL2 += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalcreditFeeALL2, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Credit Note Graduation (Fee) per-program breakdown is in the Grand Summary Dashboard --}}

<!-- creditFine -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Credit Note Active & Withdraw Student (Fine)</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <!-- <th>
            Claim
          </th> -->
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalcreditFineALL = 0;
        @endphp
        @foreach ($data['creditFineOld'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <!-- <td>
            {{ $rgs->reduction_id }}
          </td> -->
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalcreditFineALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalcreditFineALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Credit Note Active & Withdraw (Fine) per-program breakdown is in the Grand Summary Dashboard --}}

<!-- creditFine -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Credit Note Graduation Student (Fine)</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <!-- <th>
            Claim
          </th> -->
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalcreditFineALL = 0;
        @endphp
        @foreach ($data['creditFineGrad'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <!-- <td>
            {{ $rgs->reduction_id }}
          </td> -->
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalcreditFineALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalcreditFineALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Credit Note Graduation (Fine) per-program breakdown is in the Grand Summary Dashboard --}}

<!-- creditDiscount -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
    <b>Credit Note (Discount)</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
        <tr>
          <th style="width: 1%">
            No.
          </th>
          <th>
            Date
          </th>
          <th>
            No. Resit
          </th>
          <th>
            Name
          </th>
          <th>
            No.KP
          </th>
          <th>
            No.Matric
          </th>
          <th>
            Program
          </th>
          <th>
            Semester
          </th>
          <!-- <th>
            Claim
          </th> -->
          <th>
            Remark
          </th>
          <th>
            Amount
          </th>
        </tr>
      </thead>
      <tbody id="table">
        @php
        $totalcreditDiscountALL = 0;
        @endphp
        @foreach ($data['creditDiscount'] as $key => $rgs)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $rgs->date }}
          </td>
          <td>
            {{ $rgs->ref_no }}
          </td>
          <td>
            {{ $rgs->name }}
          </td>
          <td>
            {{ $rgs->student_ic }}
          </td>
          <td>
            {{ $rgs->no_matric }}
          </td>
          <td>
            {{ $rgs->progname }}
          </td>
          <td>
            {{ $rgs->semester_id }}
          </td>
          <!-- <td>
            {{ $rgs->reduction_id }}
          </td> -->
          <td>
            {{ $rgs->remark }}
          </td>
          <td>
            <div>{{ $rgs->amount }}</div>
            @php
            $totalcreditDiscountALL += $rgs->amount;
            @endphp
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" style="text-align: center">
            TOTAL
          </td>
          <td>
            {{ number_format($totalcreditDiscountALL, 2) }}
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

{{-- Credit Note Discount per-program breakdown is in the Grand Summary Dashboard --}}