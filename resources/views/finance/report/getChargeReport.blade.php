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
            <div>{{  $rgs->amount }}</div>
            @php
              $totalNewALL += $rgs->amount;
            @endphp
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="7" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{  number_format($totalNewALL, 2) }}
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
            <div>{{  $rgs->amount }}</div>
            @php
              $totalOldALL += $rgs->amount;
            @endphp
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="7" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{  number_format($totalOldALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['newStudentTotals'])) ? $data['newStudentTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['newStudentTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['oldStudentTotals'])) ? $data['oldStudentTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['oldStudentTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
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
          {{ $rgs->type }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
            @php
              $totalDebitALL += $rgs->amount;
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
                  {{  number_format($totalDebitALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['debitTotals'])) ? $data['debitTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['debitTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
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
          {{ $rgs->type }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalFineALL, 2) }}
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
          {{ $rgs->type }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalOtherALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>
  
<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-3 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
    <b>Other Type</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th>
                    TYPE
                </th>
                <th>
                    TOTAL
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['otherCharge'] as $key => $chg)
        <tr>
          <td>
            {{ $key+1 }}
          </td>
          <td>
            {{ $chg->name }}
          </td>
          <td>
            {{ (!empty($data['other'])) ? $data['otherTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['otherTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
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
          {{ $rgs->student_id }}
          </td>
          <td>
          {{ $rgs->progname }}
          </td>
          <td>
          {{ $rgs->reduction_id }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
            @php
              $totalcreditFeeALL1 += $rgs->amount;
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
                  {{  number_format($totalcreditFeeALL1, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['creditFeeOldTotals'])) ? $data['creditFeeOldTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['creditFeeOldTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

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
          {{ $rgs->reduction_id }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalcreditFeeALL2, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['creditFeeGradTotals'])) ? $data['creditFeeGradTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['creditFeeGradTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

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
          {{ $rgs->reduction_id }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalcreditFineALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['creditFineOldTotals'])) ? $data['creditFineOldTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['creditFineOldTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

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
          {{ $rgs->reduction_id }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalcreditFineALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['creditFineGradTotals'])) ? $data['creditFineGradTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['creditFineGradTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

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
          {{ $rgs->reduction_id }}
          </td>
          <td>
          {{ $rgs->remark }}
          </td>
          <td>
            <div>{{  $rgs->amount }}</div>
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
                  {{  number_format($totalcreditDiscountALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<div class="row justify-content-center">
  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
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
                    PROGRAM
                </th>
                <th>
                    QUOTE
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['program'] as $key => $prg)
        <tr>
          <td>
            {{ $prg->program_ID }}
          </td>
          <td>
            {{ $prg->progcode }}
          </td>
          <td>
            {{ (!empty($data['creditDiscountTotals'])) ? $data['creditDiscountTotals'][$key] : 0}}
          </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
              <td colspan="2" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum($data['creditDiscountTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>