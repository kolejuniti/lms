<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Students Report</b>
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
          {{ $rgs->ea }}
          </td>
          <td>
          {{ $data['result'][$key]->group }}
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
  <!-- /.card-body -->
</div>