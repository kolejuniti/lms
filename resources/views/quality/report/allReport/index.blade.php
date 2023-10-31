@extends('../layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">All Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">All Report</li>
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
                <h3 class="card-title">All Lecturer Report</h3> <a href="{{ route('export-table') }}" class="btn btn-primary">Export to Excel</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                {{-- <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                      <label class="form-label" for="name">Date</label>
                      <input type="date" class="form-control" id="date" name="date">
                      </div>
                  </div>
                </div> --}}
                <div class="card-body" style="width: 100%; overflow-x: auto;">
                    <table id="table_dismissed" class="w-100 table display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%;">
                      <thead>
                        <tr>
                          <th style="text-align: center; border: 1px solid black;">
                          Faculty
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Lecturer
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Subject
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Quiz
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Test
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Assignment
                          </th>
                          <th style="text-align: center; border: 1px solid black;">
                          Usage
                          </th>
                        </tr>
                      </thead>
                      <tbody id="table">
                        @foreach($data['faculty'] as $facultyKey => $facultyValue)
                            <?php $facultyRowCount = 0; ?>
                            @foreach($data['lecturer'][$facultyKey] as $nameKey => $nameValue)
                                <?php $facultyRowCount += count($data['course'][$facultyKey][$nameKey]); ?>
                            @endforeach

                            <?php $isFacultyDisplayed = false; ?>
                            @foreach($data['lecturer'][$facultyKey] as $nameKey => $nameValue)
                                <?php $isNameDisplayed = false; ?>
                                @foreach($data['course'][$facultyKey][$nameKey] as $courseKey => $courseValue)
                                    <tr>
                                        @if(!$isFacultyDisplayed)
                                            <td rowspan="{{ $facultyRowCount }}" style="border: 1px solid black;">{{ $facultyValue->facultyname }}</td>
                                            <?php $isFacultyDisplayed = true; ?>
                                        @endif
                                        @if(!$isNameDisplayed)
                                            <td rowspan="{{ count($data['course'][$facultyKey][$nameKey]) }}" style="border: 1px solid black;">{{ $nameValue->name }}</td>
                                            <?php $isNameDisplayed = true; ?>
                                        @endif
                                        <td style="border: 1px solid black; text-align: center">{{ $courseValue->course_name }}</td>
                                        <td style="border: 1px solid black; text-align: center">{{ $data['quiz'][$facultyKey][$nameKey][$courseKey] }}</td>
                                        <td style="border: 1px solid black; text-align: center">{{ $data['test'][$facultyKey][$nameKey][$courseKey] }}</td>
                                        <td style="border: 1px solid black; text-align: center">{{ $data['assignment'][$facultyKey][$nameKey][$courseKey] }}</td>
                                        <td style="border: 1px solid black; text-align: center">{{ $data['usage'][$facultyKey][$nameKey][$courseKey] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                      </tbody>
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

<script>
  $(document).ready( function () {
      $('#table_dismissed').DataTable({
        dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
        paging: false,

        buttons: [
            {
              text: 'Excel',
              action: function () {
                // get the HTML table to export
                const table = document.getElementById("table_dismissed");
                
                // create a new Workbook object
                const wb = XLSX.utils.book_new();
                
                // add a new worksheet to the Workbook object
                const ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                
                // trigger the download of the Excel file
                XLSX.writeFile(wb, "exported-data.xlsx");
              }
            }
        ],

      });

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

      // Remove the cells that are hidden
      $("#table_dismissed td:first-child:hidden").remove();
        } );
</script>

<script type="text/javascript">

/*$(document).on('change', '#date', function(){

    var date = $(this).val();

    getReport(date);

});

function getReport(date)
{

  $('#table_dismissed').DataTable().destroy();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/student/report/getStudentReport') }}",
            method   : 'POST',
            data 	 : {date: date},
            beforeSend:function(xhr){
              $("#table_dismissed").LoadingOverlay("show", {
                image: `<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                  <rect x="0" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                  <rect x="8" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                  <rect x="16" y="10" width="4" height="10" fill="#333" opacity="0.2">
                  <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
                  </rect>
                </svg>`,
                background:"rgba(255,255,255, 0.3)",
                imageResizeFactor : 1,    
                imageAnimation : "2000ms pulse" , 
                imageColor: "#019ff8",
                text : "Please wait...",
                textResizeFactor: 0.15,
                textColor: "#019ff8",
                textColor: "#019ff8"
              });
              $("#table_dismissed").LoadingOverlay("hide");
            },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#table_dismissed').removeAttr('hidden');
                $('#table_dismissed').html(data);
                
                $('#table_dismissed').DataTable();
                //window.location.reload();
            }
        });
  
}*/



 window.onload = mergeCells;
 

</script>
@endsection
