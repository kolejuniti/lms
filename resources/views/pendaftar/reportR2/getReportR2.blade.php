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
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">
                    Week
                </th>
                <th style="width: 15%">
                    Month
                </th>
                <th style="width: 15%">
                    Total
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @php
        $total_allW = 0;
        @endphp
        @foreach ($data['dateRange'] as $key => $week)
          <tr>
            <td>
            {{ $week['week'] }}
            </td>
            <td>
            {{ $week['month'] }}
            </td>
            <td>
            {{ $data['totalWeek'][$key]->total_week }}
            </td>
          </tr>
          @php
          $total_allW += $data['totalWeek'][$key]->total_week;
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
                    Total
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @php
        $total_allD = 0;
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
        </tr>
        @php
          $total_allD += $data['totalDay'][$key][$key2]->total_day;
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
              </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
</div>