@extends('../layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Registration</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Extra</li>
              <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student Report</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                      <label class="form-label" for="name">Date</label>
                      <input type="date" class="form-control" id="date" name="date">
                      </div>
                  </div>
                </div>
                <div class="card-body">
                    <table id="table_dismissed" class="w-100 table table-bordered display margin-top-10 w-p100 table-layout: fixed;">
                      <thead>
                        <tr>
                          <th colspan="2"></th>
                          <th colspan="2"></th>
                          <th style="text-align: center" colspan="2">Holding</th>
                          <th style="text-align: center" colspan="2">40</th>
                          <th style="text-align: center" colspan="2">40</th>
                          <th style="text-align: center" colspan="2">10</th>
                          <th style="text-align: center" colspan="2">6</th>
                          <th style="text-align: center" colspan="2">2</th>
                          <th style="text-align: center" colspan="2">0</th>
                          <th colspan="2"></th>
                          <th colspan="2"></th>
                          <th colspan="2"></th>
                        </tr>
                        <tr>
                          <th style="text-align: center; width: 1%" rowspan="2">
                          Faculty
                          </th>
                          <th style="text-align: center; width: 20%" rowspan="2">
                          Program
                          </th>
                          <th style="text-align: center; width: 5%" colspan="4">
                          Sem 1
                          </th>
                          <th style="text-align: center; width: 5%" colspan="2">
                          Sem 2
                          </th>
                          <th style="text-align: center; width: 5%" colspan="2">
                          Sem 3
                          </th>
                          <th style="text-align: center; width: 5%" colspan="2">
                          Sem 4
                          </th>
                          <th style="text-align: center; width: 5%" colspan="2">
                          Sem 5
                          </th>
                          <th style="text-align: center; width: 5%" colspan="2">
                          Sem 6
                          </th>
                          <th style="width: 10%; text-align: center" rowspan="2">
                          Active
                          </th>
                          <th style="width: 10%; text-align: center" rowspan="2">
                          Postpone
                          </th>
                          <th style="width: 10%; text-align: center" rowspan="2">
                          Dismissed
                          </th>
                        </tr>
                        <tr>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                          <th>L</th>
                          <th>P</th>
                      </tr>
                      </thead>
                      <tbody id="table">
                        @foreach ($data['program'] as $key=>$prg)
                        <tr>
                          <td style="text-align: center">
                          {{ $prg->facultyname }} <br>
                          {{ $data['sum'][$key] }}
                          </td>
                          <td>
                          {{ $prg->progname }}
                          </td>
                          <td>
                          @foreach ((array) $data['ms1'][$key] as $ms1)
                          {{ $ms1 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms1'][$key] as $ms1)
                            {{ $ms1 }}
                            @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms1'][$key] as $ms1)
                            {{ $ms1 }}
                            @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms1'][$key] as $ms1)
                            {{ $ms1 }}
                            @endforeach
                          </td>
                          <td>
                          @foreach ((array) $data['ms2'][$key] as $ms2)
                          {{ $ms2 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms2'][$key] as $ms2)
                            {{ $ms2 }}
                            @endforeach
                          </td>
                          <td>
                          @foreach ((array) $data['ms3'][$key] as $ms3)
                          {{ $ms3 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms3'][$key] as $ms3)
                            {{ $ms3 }}
                            @endforeach
                          </td>
                          <td>
                          @foreach ((array) $data['ms4'][$key] as $ms4)
                          {{ $ms4 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms4'][$key] as $ms4)
                            {{ $ms4 }}
                            @endforeach
                          </td>
                          <td>
                          @foreach ((array) $data['ms5'][$key] as $ms5)
                          {{ $ms5 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms5'][$key] as $ms5)
                            {{ $ms5 }}
                            @endforeach
                          </td>
                          <td>
                          @foreach ((array) $data['ms6'][$key] as $ms6)
                          {{ $ms6 }}
                          @endforeach
                          </td>
                          <td>
                            @foreach ((array) $data['ms6'][$key] as $ms6)
                            {{ $ms6 }}
                            @endforeach
                          </td>
                          <td style="text-align: center">
                          @foreach ((array) $data['active'][$key] as $active)
                          {{ $active }}
                          @endforeach
                          </td>
                          <td style="text-align: center">
                          @foreach ((array) $data['postpone'][$key] as $postpone)
                          {{ $postpone }}
                          @endforeach
                          </td>
                          <td style="text-align: center">
                          @foreach ((array) $data['dismissed'][$key] as $dismissed)
                          {{ $dismissed }}
                          @endforeach
                          </td>
                        </tr>
                        @endforeach
                        
                      </tbody>
                      <tfoot>
                        <tr>
                          <td>
                            
                          </td>
                          <td >
                            TOTAL STUDENT
                          </td>
                          @php
                            $semester = DB::table('semester')->get();
                          @endphp
                          @foreach ($semester as $sem)
                          @php
                            $total = count(DB::table('students')->where('semester', $sem->id)->get())
                          @endphp
                          <td colspan="2" style="text-align: center">
                            {{ $total }}
                          </td>
                          @endforeach
                          <td style="text-align: center">
                            @php
                              $active = count(DB::table('students')->where([
                                    ['students.status', 2],
                                    //['students.campus_id', 1]
                                    ])->get());
                            @endphp
                            {{ $active }}
                          </td>
                          <td style="text-align: center">
                            @php
                              $postpone = count(DB::table('students')->where([
                                    ['students.status', 2],
                                    ['students.campus_id', 2]
                                    //['students.campus_id', 1]
                                    ])->get());
                            @endphp
                            {{ $postpone }}
                          </td>
                          <td style="text-align: center">
                            @php
                              $dismissed = count(DB::table('students')->where([
                                    ['students.status', 3]
                                    //['students.campus_id', 1]
                                    ])->get());
                            @endphp
                            {{ $dismissed }}
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script type="text/javascript">


$(document).ready(function () {
      var d = new Date();

      var month = d.getMonth()+1;
      var day = d.getDate();

      var output = d.getFullYear() + '/' +
      (month<10 ? '0' : '') + month + '/' +
      (day<10 ? '0' : '') + day;

      $('#table_dismissed').DataTable({
        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
        aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        fixedHeader: true,
        "ordering": false,
        buttons: [
          {
              extend: 'excelHtml5',
              messageTop: output,
              title: 'Excel' + '-' + output,
              text:'Export to excel'
              //Columns to export
              //exportOptions: {
              //     columns: [0, 1, 2, 3,4,5,6]
              // }
          },
          {
              extend: 'pdfHtml5',
              title: 'PDF' + '-' + output,
              text: 'Export to PDF'
              //Columns to export
              //exportOptions: {
              //     columns: [0, 1, 2, 3, 4, 5, 6]
            //  }
          }
        ],
      });

      mergeCells();


     
});


function mergeCells() {
  let db = document.getElementById("table_dismissed");
  let dbRows = db.rows;
  let lastValue = "";
  let lastCounter = 1;
  let lastRow = 0;
  for (let i = 0; i < dbRows.length; i++) {
    let thisValue = dbRows[i].cells[0].innerHTML;
    if (thisValue == lastValue) {
      lastCounter++;
      dbRows[lastRow].cells[0].rowSpan = lastCounter;
      dbRows[i].cells[0].style.display = "none";
    } else {
      dbRows[i].cells[0].style.display = "table-cell";
      lastValue = thisValue;
      lastCounter = 1;
      lastRow = i;
    }
  }
}

window.onload = mergeCells;
 

</script>
@endsection
