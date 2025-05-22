<div class="row mt-3 d-flex">
  <div class="col-md-12 mb-3">
    <div class="pull-right">
      <button id="exportBtn" class="btn btn-success">Export to Excel</button>
    </div>
  </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Report Information</b>
    </div>
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="form-group">
                    <p>Total Student By Month &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['totalAll']->total_student }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Total Payment By Weeks</b>
    </div>
    <div class="small text-muted px-3 py-2">
        Note: Weeks shown follow the calendar date per week (Sunday to Saturday)
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 20%">
                    Week (Date Range)
                </th>
                <th style="width: 15%">
                    Month
                </th>
                <th style="width: 15%">
                    Total By Weeks
                </th>
                <th style="width: 15%">
                    Total by Cumulative
                </th>
                <th style="width: 15%">
                    Total by Convert
                </th>
                <th style="width: 15%">
                    Balance Student
                </th>
                <th style="width: 15%">
                    Student Registered
                </th>
                <th style="width: 15%">
                    Student Rejected
                </th>
                <th style="width: 15%">
                    Student Offered
                </th>
                <th style="width: 15%">
                    Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i>
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @php
        $total_allW = 0;
        $total_allC = 0;
        $total_allC2 = 0;
        $total_allB = 0;
        $total_allR = 0;
        $total_allO = 0;
        $total_allK = 0;
        $total_allT = 0;
        @endphp
        @foreach ($data['dateRange'] as $key => $week)
          <tr>
            <td>
              <b>{{ $week['week'] }}</b> ({{ \Carbon\Carbon::parse(reset($week['days']))->format('j F Y') }} - {{ \Carbon\Carbon::parse(end($week['days']))->format('j F Y') }})
            </td>
            <td>
            {{ $week['month'] }}
            </td>
            <td>
            {{ $data['totalWeek'][$key]->total_week }}
            </td>
            <td>
            {{ $data['countedPerWeek'][$key] }}
            </td>
            <td>
            {{ $data['totalConvert'][$key] }}
            </td>
            <td>
            {{ $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key] }}
            </td>
            <td>
            {{ $data['registeredPerWeek'][$key] }}
            </td>
            <td>
            {{ $data['rejectedPerWeek'][$key] }}
            </td>
            <td>
            {{ $data['offeredPerWeek'][$key] }}
            </td>
            <td>
            {{ $data['KIVPerWeek'][$key] }}
            </td>
          </tr>
          @php
          $total_allW += $data['totalWeek'][$key]->total_week;
          $total_allC = $data['countedPerWeek'][$key];
          $total_allC2 += $data['totalConvert'][$key];
          $total_allB += $data['totalWeek'][$key]->total_week - $data['totalConvert'][$key];
          $total_allR += $data['registeredPerWeek'][$key];
          $total_allO += $data['rejectedPerWeek'][$key];
          $total_allK += $data['offeredPerWeek'][$key];
          $total_allT += $data['KIVPerWeek'][$key];
          @endphp
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center">
                    TOTAL
                </td>
                <td>
                    {{  $total_allW }}
                </td>
                <td>
                    {{  $total_allC }}
                </td>
                <td>
                    {{  $total_allC2 }}
                </td>
                <td>
                    {{  $total_allB }}
                </td>
                <td>
                    {{  $total_allR }}
                </td>
                <td>
                    {{  $total_allO }}
                </td>
                <td>
                    {{  $total_allK }}
                </td>
                <td>
                    {{  $total_allT }}
                </td>
              </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Total Payment By Days</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable2" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">
                    Date
                </th>
                <th style="width: 15%">
                    Total By Days
                </th>
                <th style="width: 15%">
                    Total by Cumulative
                </th>
                <th style="width: 15%">
                    Total by Convert
                </th>
                <th style="width: 15%">
                    Balance Student
                </th>
                <th style="width: 15%">
                    Student Registered
                </th>
                <th style="width: 15%">
                    Student Rejected
                </th>
                <th style="width: 15%">
                    Student Offered
                </th>
                <th style="width: 15%">
                    Student KIV <i class="fa fa-info-circle text-info" data-toggle="tooltip" data-placement="top" title="Students whose current date has passed their offered date"></i>
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @php
        $total_allD = 0;
        $total_allQ = 0;
        $total_allZ = 0;
        $total_allB = 0;
        $total_allR = 0;
        $total_allO = 0;
        $total_allK = 0;
        $total_allT = 0;
        @endphp
        @foreach ($data['dateRange'] as $key => $week)
        @foreach ($data['week'][$key] as $key2 => $day)
        <tr>
          <td>
            {{ $day }}
          </td>
          <td>
            {{ $data['totalDay'][$key][$key2]->total_day }}
          </td>
          <td>
            {{ $data['countedPerDay'][$key][$key2] }}
          </td>
          <td>
            {{ $data['totalConvert2'][$key][$key2] }}
          </td>
          <td>
            {{ $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2] }}
          </td>
          <td>
            {{ $data['registeredPerDay'][$key][$key2] }}
          </td>
          <td>
            {{ $data['rejectedPerDay'][$key][$key2] }}
          </td>
          <td>
            {{ $data['offeredPerDay'][$key][$key2] }}
          </td>
          <td>
            {{ $data['KIVPerDay'][$key][$key2] }}
          </td>
        </tr>
        @php
          $total_allD += $data['totalDay'][$key][$key2]->total_day;
          $total_allQ = $data['countedPerDay'][$key][$key2];
          $total_allZ += $data['totalConvert2'][$key][$key2];
          $total_allB += $data['totalDay'][$key][$key2]->total_day - $data['totalConvert2'][$key][$key2];
          $total_allR += $data['registeredPerDay'][$key][$key2];
          $total_allO += $data['rejectedPerDay'][$key][$key2];
          $total_allK += $data['offeredPerDay'][$key][$key2];
          $total_allT += $data['KIVPerDay'][$key][$key2];
        @endphp
        @endforeach
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1" style="text-align: center">
                    TOTAL
                </td>
                <td>
                    {{  $total_allD }}
                </td>
                <td>
                    {{  $total_allQ }}
                </td>
                <td>
                    {{  $total_allZ }}
                </td>
                <td>
                    {{  $total_allB }}
                </td>
                <td>
                    {{  $total_allR }}
                </td>
                <td>
                    {{  $total_allO }}
                </td>
                <td>
                    {{  $total_allK }}
                </td>
                <td>
                    {{  $total_allT }}
                </td>
              </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
</div>

<script>

  $(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#exportBtn').on('click', function(e) {
      e.preventDefault();
      printReport2();
    });
  });

  function printReport2() {
    var from = $('#from').val();
    var to = $('#to').val();
    var url = "{{ url('pendaftar/student/reportR2/getStudentReportR2?excel=true') }}";

    window.location.href = `${url}&from=${from}&to=${to}`;
  }
  </script>