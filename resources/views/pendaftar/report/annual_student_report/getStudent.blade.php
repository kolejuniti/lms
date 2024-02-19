<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Semester 1</b>
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
                  No. IC
              </th>
              <th style="width: 5%">
                  Gender
              </th>
              <th style="width: 5%">
                  Program
              </th>
              <th style="width: 5%">
                  No. Matric
              </th>
              <th style="width: 5%">
                  Session
              </th>
              <th style="width: 5%">
                  Semester
              </th>
              <th style="width: 5%">
                  Status
              </th>
              <th style="width: 5%">
                  Date
              </th>
              <th style="width: 5%">
                  Remark
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['student1'] as $key => $std)
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $std->name }}
          </td>
          <td>
          {{ $std->ic }}
          </td>
          <td>
          {{ $std->gender }}
          </td>
          <td>
          {{ $std->progcode }}
          </td>
          <td>
          {{ $std->no_matric }}
          </td>
          <td>
          {{ $std->session }}
          </td>
          <td>
          {{ $std->semester }}
          </td>
          <td>
          {{ $std->status }}
          </td>
          <td>
          {{ $std->date }}
          </td>
          <td>
          {{ $std->remark }}
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
    <b>Semester 2 And Above</b>
    </div>
    <div class="card-body p-0">
      <table id="myTable2" class="table table-striped projects display dataTable">
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
                    Gender
                </th>
                <th style="width: 5%">
                    Program
                </th>
                <th style="width: 5%">
                    No. Matric
                </th>=
                <th style="width: 5%">
                    Session
                </th>
                <th style="width: 5%">
                    Semester
                </th>
                <th style="width: 5%">
                    Status
                </th>
                <th style="width: 5%">
                    Date
                </th>
                <th style="width: 5%">
                    Remark
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['student2'] as $key => $std)
          <tr>
            <td>
            {{ $key+1 }}
            </td>
            <td>
            {{ $std->name }}
            </td>
            <td>
            {{ $std->ic }}
            </td>
            <td>
            {{ $std->gender }}
            </td>
            <td>
            {{ $std->progcode }}
            </td>
            <td>
            {{ $std->no_matric }}
            </td>
            <td>
            {{ $std->session }}
            </td>
            <td>
            {{ $std->semester }}
            </td>
            <td>
            {{ $std->status }}
            </td>
            <td>
            {{ $std->date }}
            </td>
            <td>
            {{ $std->remark }}
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

