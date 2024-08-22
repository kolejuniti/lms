<div class="card mb-3" id="stud_info">
  <div class="card-header">
  <b>Students Report R1</b>
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
                  Status
              </th>
              <th style="width: 5%">
                  EA
              </th>
              <th style="width: 5%">
                  Type
              </th>
              <th style="width: 5%">
                  Reference
              </th>
              <th style="width: 5%">
                  Amount
              </th>
          </tr>
      </thead>
      <tbody id="table">
      @foreach ($data['studentR1'] as $key => $rgs)
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
          {{ $rgs->sex }}
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
          {{ $rgs->status }}
          </td>
          <td>
          {{ $rgs->ea }}
          </td>
          <td>
          {{ $data['resultR1'][$key]->group_alias }}
          </td>
          <td>
          {{ $data['quaR1'][$key] }}
          </td>
          <td>
          {{ $data['resultR1'][$key]->amount }}
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
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="form-group">
                    <table class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align:center">
                                    Pecahan Pelajar Mengikut Jantina
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center">
                                    lelaki
                                </th>
                                <th style="text-align:center">
                                    Perempuan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">
                                    {{ $data['R1M'] }}
                                </td>
                                <td style="text-align:center">
                                    {{ $data['R1F'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Students Report R2</b>
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
                    Status
                </th>
                <th style="width: 5%">
                    EA
                </th>
                <th style="width: 5%">
                    Type
                </th>
                <th style="width: 5%">
                    Reference
                </th>
                <th style="width: 5%">
                    Amount
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['studentR2'] as $key => $rgs)
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
            {{ $rgs->sex }}
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
            {{ $rgs->status }}
            </td>
            <td>
            {{ $rgs->ea }}
            </td>
            <td>
            {{ $data['resultR2'][$key]->group_alias }}
            </td>
            <td>
            {{ $data['quaR2'][$key] }}
            </td>
            <td>
            {{ $data['resultR2'][$key]->amount }}
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
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="form-group">
                    <table class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align:center">
                                    Pecahan Pelajar Mengikut Jantina
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center">
                                    lelaki
                                </th>
                                <th style="text-align:center">
                                    Perempuan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">
                                    {{ $data['R2M'] }}
                                </td>
                                <td style="text-align:center">
                                    {{ $data['R2F'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Students Report Withdraw</b>
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
                    Status
                </th>
                <th style="width: 5%">
                    EA
                </th>
                <th style="width: 5%">
                    Type
                </th>
                <th style="width: 5%">
                    Reference
                </th>
                <th style="width: 5%">
                    Amount
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['withdraw'] as $key => $rgs)
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
            {{ $rgs->sex }}
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
            {{ $rgs->status }}
            </td>
            <td>
            {{ $rgs->ea }}
            </td>
            <td>
            {{ $data['resultWithdraw'][$key]->group_alias }}
            </td>
            <td>
            {{ $data['quaW'][$key] }}
            </td>
            <td>
            {{ $data['resultWithdraw'][$key]->amount }}
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
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="form-group">
                    <table class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align:center">
                                    Pecahan Pelajar Mengikut Jantina
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center">
                                    lelaki
                                </th>
                                <th style="text-align:center">
                                    Perempuan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">
                                    {{ $data['WM'] }}
                                </td>
                                <td style="text-align:center">
                                    {{ $data['WF'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3" id="stud_info">
    <div class="card-header">
    <b>Students Report Not Active</b>
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
                    Status
                </th>
                <th style="width: 5%">
                    EA
                </th>
                <th style="width: 5%">
                    Type
                </th>
                <th style="width: 5%">
                    Reference
                </th>
                <th style="width: 5%">
                    Amount
                </th>
            </tr>
        </thead>
        <tbody id="table">
        @foreach ($data['notActive'] as $key => $rgs)
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
            {{ $rgs->sex }}
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
            {{ $rgs->status }}
            </td>
            <td>
            {{ $rgs->ea }}
            </td>
            <td>
            {{ $data['resultNA'][$key]->group_alias }}
            </td>
            <td>
            {{ $data['quaNA'][$key] }}
            </td>
            <td>
            {{ $data['resultNA'][$key]->amount }}
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
    <div class="card-body">
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="form-group">
                    <table class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th colspan="2" style="text-align:center">
                                    Pecahan Pelajar Mengikut Jantina
                                </th>
                            </tr>
                            <tr>
                                <th style="text-align:center">
                                    lelaki
                                </th>
                                <th style="text-align:center">
                                    Perempuan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:center">
                                    {{ $data['NAM'] }}
                                </td>
                                <td style="text-align:center">
                                    {{ $data['NAF'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>