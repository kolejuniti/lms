<!-- voucher -->
<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Voucher List</b>
  </div>
  <div class="card-body p-0">
    <table id="myTable" class="table table-striped projects display dataTable">
      <thead>
          <tr>
              <th style="width: 1%">
                  No.
              </th>
              <th style="width: 15%">
                  Student Name
              </th>
              <th style="width: 5%">
                  Student Ic
              </th>
              <th style="width: 5%">
                  Date Add
              </th>
              <th style="width: 5%">
                  No. Voucher
              </th>
              <th style="width: 5%">
                  Status
              </th>
              <th style="width: 5%">
                  Staff Add
              </th>
              <th style="width: 5%">
                  Redeem Date
              </th>
              <th style="width: 5%">
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['voucher'] as $key => $vcr)
        @if($vcr->status == 'SAH')
        <tr>
          <td>
          {{ $key+1 }}
          </td>
          <td>
          {{ $vcr->student }}
          </td>
          <td>
          {{ $vcr->student_ic }}
          </td>
          <td>
          {{ $vcr->add_date }}
          </td>
          <td>
          {{ $vcr->no_voucher }}
          </td>
          <td>
          {{ $vcr->status }}
          </td>
          <td>
          {{ $vcr->staff }}
          </td>
          <td>
          {{ $vcr->redeem_date }}
          </td>
          <td>
          {{ $vcr->amount }}
          </td>
        </tr>
        @endif
      @endforeach
      </tbody>
      <tfoot>
          <tr>
              <td colspan="8" style="text-align: center">
                  TOTAL
              </td>
              <td>
                  {{  $data['sum'] }}
              </td>
            </tr>
      </tfoot>
    </table>
  </div>
  <!-- /.card-body -->
</div>