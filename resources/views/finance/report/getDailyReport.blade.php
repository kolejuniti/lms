<!--pre registration -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Pre Registration</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalPreALL = 0;
      @endphp
      @foreach ($data['preRegister'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['preMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['preMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['preMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalPre = 0;
            @endphp
          @foreach ($data['preDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalPre += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalPre, 2) }}</div>
            @php
              $totalPreALL += $totalPre;
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
                  {{  number_format($totalPreALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

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
              <th style="width: 15%">
                  Name
              </th>
              <th style="width: 5%">
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
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
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['newStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['newStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['newStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalNew = 0;
            @endphp
          @foreach ($data['newStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalNew += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalNew, 2) }}</div>
            @php
              $totalNewALL += $totalNew;
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
              <th style="width: 15%">
                  Name
              </th>
              <th style="width: 5%">
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
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
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['oldStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['oldStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['oldStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalOld = 0;
            @endphp
          @foreach ($data['oldStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalOld += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalOld, 2) }}</div>
            @php
              $totalOldALL += $totalOld;
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
                  {{  number_format($totalOldALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- withdraw student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Withdraw Student</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalWithdrawALL = 0;
      @endphp
      @foreach ($data['withdrawStudent'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['withdrawStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['withdrawStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['withdrawStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalWithdraw = 0;
            @endphp
          @foreach ($data['withdrawStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalWithdraw += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalWithdraw, 2) }}</div>
            @php
              $totalWithdrawALL += $totalWithdraw;
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
                  {{  number_format($totalWithdrawALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- graduate student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Graduate Student</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalGraduateALL = 0;
      @endphp
      @foreach ($data['graduateStudent'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['graduateStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['graduateStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['graduateStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalGraduate = 0;
            @endphp
          @foreach ($data['graduateStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalGraduate += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalGraduate, 2) }}</div>
            @php
              $totalGraduateALL += $totalGraduate;
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
                  {{  number_format($totalGraduateALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- fail student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Failed Student</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalFailALL = 0;
      @endphp
      @foreach ($data['failStudent'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['failStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['failStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['failStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalFail = 0;
            @endphp
          @foreach ($data['failStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalFail += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalFail, 2) }}</div>
            @php
              $totalFailALL += $totalFail;
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
                  {{  number_format($totalFailALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- expulsion student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Expulsion Student</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalExpulsionALL = 0;
      @endphp
      @foreach ($data['expulsionStudent'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['expulsionStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['expulsionStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['expulsionStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalExpulsion = 0;
            @endphp
          @foreach ($data['expulsionStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalExpulsion += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{  number_format($totalExpulsion, 2) }}</div>
            @php
              $totalExpulsionALL += $totalExpulsion;
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
                  {{  number_format($totalExpulsionALL, 2) }}
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
    <b>Pre Registration</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['preTotals'])) ? $data['preTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['preTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newTotals'])) ? $data['newTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
              {{ (!empty($data['oldTotals'])) ? $data['oldTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
    <b>Withdraw</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['withdrawTotals'])) ? $data['withdrawTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['withdrawTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
      <b>Graduate</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['graduateTotals'])) ? $data['graduateTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['graduateTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
      <b>Fail</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['failTotals'])) ? $data['failTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['failTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
  <div class="card col-md-2 mb-3" id="stud_info" style="margin-right: 2%">
    <div class="card-header mx-auto">
      <b>Expulsion</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 1%">
                    No.
                </th>
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['expulsionTotals'])) ? $data['expulsionTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['expulsionTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

{{-- <!-- hostel student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Hostel Collection</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
          <tr>
              <th >
                  No.
              </th>
              <th >
                  Date
              </th>
              <th >
                  No. Resit
              </th>
              <th >
                  Method
              </th>
              <th >
                  Bank
              </th>
              <th >
                  No. Document
              </th>
              <th >
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['hostel'] as $key => $rgs)
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
          {{ $rgs->method }}
          </td>
          <td>
          {{ $rgs->bank }}
          </td>
          <td>
          {{ $rgs->no_document }}
          </td>
          <td>
          {{ $rgs->amount }}
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="6" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum(array_column((array) $data['hostel'], 'amount')), 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- convo student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Convocation Collection</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
          <tr>
              <th >
                  No.
              </th>
              <th >
                  Date
              </th>
              <th >
                  No. Resit
              </th>
              <th >
                  Method
              </th>
              <th >
                  Bank
              </th>
              <th >
                  No. Document
              </th>
              <th >
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['convo'] as $key => $rgs)
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
          {{ $rgs->method }}
          </td>
          <td>
          {{ $rgs->bank }}
          </td>
          <td>
          {{ $rgs->no_document }}
          </td>
          <td>
          {{ $rgs->amount }}
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="6" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum(array_column((array) $data['convo'], 'amount')), 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- fine student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Fine Collection</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
          <tr>
              <th >
                  No.
              </th>
              <th >
                  Date
              </th>
              <th >
                  No. Resit
              </th>
              <th>
                  Type
              </th>
              <th >
                  Method
              </th>
              <th >
                  Bank
              </th>
              <th >
                  No. Document
              </th>
              <th >
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
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
          {{ $rgs->type }}
          </td>
          <td>
          {{ $rgs->method }}
          </td>
          <td>
          {{ $rgs->bank }}
          </td>
          <td>
          {{ $rgs->no_document }}
          </td>
          <td>
          {{ $rgs->amount }}
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="6" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{ number_format(array_sum(array_column((array) $data['fine'], 'amount')), 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div> --}}

<!-- hostel student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Hostel Collection</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalHostelALL = 0;
      @endphp
      @foreach ($data['hostel'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['hostelStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['hostelStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['hostelStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['hostelStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalHostel = 0;
            @endphp
          @foreach ($data['hostelStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalHostel += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalHostel, 2) }}</div>
            @php
              $totalHostelALL += $totalHostel;
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
                  {{ number_format($totalHostelALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- convo student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Convocation Collection</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalConvoALL = 0;
      @endphp
      @foreach ($data['convo'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['convoStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['convoStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['convoStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['convoStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalConvo = 0;
            @endphp
          @foreach ($data['convoStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalConvo += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalConvo, 2) }}</div>
            @php
              $totalConvoALL += $totalConvo;
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
                  {{ number_format($totalConvoALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- fine student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Fine Collection</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
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
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['fineStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['fineStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['fineStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['fineStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalFine = 0;
            @endphp
          @foreach ($data['fineStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalFine += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalFine, 2) }}</div>
            @php
              $totalFineALL += $totalFine;
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
                  {{ number_format($totalFineALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- other student -->
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
              <th style="width: 15%">
                  Name
              </th>
              <th style="width: 5%">
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
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
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['otherStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['otherStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['otherStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['otherStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalOther = 0;
            @endphp
          @foreach ($data['otherStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalOther += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalOther, 2) }}</div>
            @php
              $totalOtherALL += $totalOther;
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
                  {{ number_format($totalOtherALL, 2) }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<!-- excess student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Excess Student</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalExcessALL = 0;
      @endphp
      @foreach ($data['excess'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['excessStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['excessStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['excessStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['excessStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalExcess = 0;
            @endphp
          @foreach ($data['excessStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalExcess += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalExcess, 2) }}</div>
            @php
              $totalExcessALL += $totalExcess;
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
                  {{ number_format($totalExcessALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newexcessTotals'])) ? $data['newexcessTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newexcessTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldexcessTotals'])) ? $data['oldexcessTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldexcessTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- Insentif student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Insentif Naik Semester</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalInsentifALL = 0;
      @endphp
      @foreach ($data['Insentif'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['InsentifStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalInsentif = 0;
            @endphp
          @foreach ($data['InsentifStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalInsentif += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalInsentif, 2) }}</div>
            @php
              $totalInsentifALL += $totalInsentif;
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
                  {{ number_format($totalInsentifALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newInsentifTotals'])) ? $data['newInsentifTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newInsentifTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldInsentifTotals'])) ? $data['oldInsentifTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldInsentifTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- InsentifMco student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Insentif Mco / Insentif Khas UNITI</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalInsentifMcoALL = 0;
      @endphp
      @foreach ($data['InsentifMco'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['InsentifMcoStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifMcoStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifMcoStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['InsentifMcoStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalInsentifMco = 0;
            @endphp
          @foreach ($data['InsentifMcoStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalInsentifMco += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalInsentifMco, 2) }}</div>
            @php
              $totalInsentifMcoALL += $totalInsentifMco;
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
                  {{ number_format($totalInsentifMcoALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newInsentifMcoTotals'])) ? $data['newInsentifMcoTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newInsentifMcoTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldInsentifMcoTotals'])) ? $data['oldInsentifMcoTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldInsentifMcoTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- Cov19 student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Covid 19 / Frontliners Discount</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalCov19ALL = 0;
      @endphp
      @foreach ($data['Cov19'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['Cov19StudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['Cov19StudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['Cov19StudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['Cov19StudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalCov19 = 0;
            @endphp
          @foreach ($data['Cov19StudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalCov19 += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalCov19, 2) }}</div>
            @php
              $totalCov19ALL += $totalCov19;
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
                  {{ number_format($totalCov19ALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newCov19Totals'])) ? $data['newCov19Totals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newCov19Totals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldCov19Totals'])) ? $data['oldCov19Totals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldCov19Totals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- iNed student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Insentif Pendidikan iNed</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totaliNedALL = 0;
      @endphp
      @foreach ($data['iNed'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['iNedStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['iNedStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['iNedStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['iNedStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totaliNed = 0;
            @endphp
          @foreach ($data['iNedStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totaliNed += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totaliNed, 2) }}</div>
            @php
              $totaliNedALL += $totaliNed;
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
                  {{ number_format($totaliNedALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newiNedTotals'])) ? $data['newiNedTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newiNedTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldiNedTotals'])) ? $data['oldiNedTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldiNedTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- tabungkhas student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Tabung Khas</b>
  </div>
  <div class="card-body p-0">
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
                  No.Matric
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  No. Resit
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Method
              </th>
              <th style="width: 5%">
                  Bank
              </th>
              <th style="width: 5%">
                  No. Document
              </th>
              <th style="width: 5%">
                  Amount
              </th>
              <th style="width: 5%">
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totaltabungkhasALL = 0;
      @endphp
      @foreach ($data['tabungkhas'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->name }}
          </td>
          <td>
          {{ $rgs->no_matric }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->ref_no }}
          </td>
          <td>
          @foreach ($data['tabungkhasStudDetail'][$key] as $mth)
            <div>{{ $mth->type }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['tabungkhasStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totaltabungkhas = 0;
            @endphp
          @foreach ($data['tabungkhasStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totaltabungkhas += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totaltabungkhas, 2) }}</div>
            @php
              $totaltabungkhasALL += $totaltabungkhas;
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
                  {{ number_format($totaltabungkhasALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newtabungkhasTotals'])) ? $data['newtabungkhasTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newtabungkhasTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldtabungkhasTotals'])) ? $data['oldtabungkhasTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldtabungkhasTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>

<!-- sponsorship student -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Sponsorship</b>
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
                  Id Payment
              </th>
              <th>
                  Method
              </th>
              <th>
                  Bank
              </th>
              <th>
                  No. Document
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Total
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @php
      $totalsponsorALL = 0;
      @endphp
      @foreach ($data['sponsor'] as $key => $rgs)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $rgs->date }}
          </td>
          <td>
          {{ $rgs->sponsor_id }}
          </td>
          <td>
          @foreach ($data['sponsorStudMethod'][$key] as $mth)
            <div>{{ $mth->method }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['sponsorStudMethod'][$key] as $mth)
            <div>{{ $mth->bank }}</div>
          @endforeach
          </td>
          <td>
          @foreach ($data['sponsorStudMethod'][$key] as $mth)
            <div>{{ $mth->no_document }}</div>
          @endforeach
          </td>
          <td>
            @php
              $totalsponsor = 0;
            @endphp
          @foreach ($data['sponsorStudDetail'][$key] as $mth)
            <div>{{ $mth->amount }}</div>
            @php
              $totalsponsor += $mth->amount;
            @endphp
          @endforeach
          </td>
          <td>
            <div>{{ number_format($totalsponsor, 2) }}</div>
            @php
              $totalsponsorALL += $totalsponsor;
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
                  {{ number_format($totalsponsorALL, 2) }}
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['newsponsorTotals'])) ? $data['newsponsorTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['newsponsorTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- pecahan -->
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
                <th style="width: 5%">
                    PROGRAM
                </th>
                <th style="width: 5%">
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
            {{ (!empty($data['oldsponsorTotals'])) ? $data['oldsponsorTotals'][$key] : 0}}
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
                  {{ number_format(array_sum($data['oldsponsorTotals']), 2) }}
              </td>
            </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>

  <!-- new student -->
  <div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Allowance Payment</b>
    </div>
    <div class="card-body p-0">
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
                    No.Matric
                </th>
                <th style="width: 5%">
                    Date
                </th>
                <th style="width: 5%">
                    Amount
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @php
        $totalAllowance = 0;
        @endphp
        @foreach ($data['allowance'] as $key => $alw)
          <tr>
            <td>
            {{ $key+1 }}
            </td>
            <td>
            {{ $alw->student }}
            </td>
            <td>
            {{ $alw->no_matric }}
            </td>
            <td>
            {{ $alw->date }}
            </td>
            <td>
              <div>{{ $alw->amount }}</div>
              @php
                $totalAllowance += $alw->amount;
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
                    {{  number_format($totalAllowance, 2) }}
                </td>
              </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>