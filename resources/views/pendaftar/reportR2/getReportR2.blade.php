<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Students Report R1</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable" class="table table-striped projects display dataTable">
        <thead>
            <tr>
                <th style="width: 15%">
                    Week
                </th>
                <th style="width: 15%">
                    Total
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['dateRange'] as $key => $week)
          <tr>
            <td>
            {{ $key+1 }}
            </td>
            <td>
            {{ $data['totalWeek'][$key]->total_week }}
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
    <!-- /.card-body -->
  </div>

  <div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Students Report R1</b>
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
        @endforeach
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
    <!-- /.card-body -->
  </div>