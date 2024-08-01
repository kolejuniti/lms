@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : '')))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Schedule Report</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Schedule Report</li>
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
                <h3 class="card-title">Schedule Report</h3>
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
                      <thead style="background-color: darkcyan">
                        <tr>
                          <th style="text-align: center; width: 10%; border: 1px solid black;">
                          Lecturer
                          </th>
                          <th style="text-align: center; width: 10%; border: 1px solid black;">
                          Course
                          </th>
                          <th style="text-align: center; width: 5%; border: 1px solid black;">
                          Session
                          </th>
                          <th style="text-align: center; width: 5%; border: 1px solid black;">
                          Group
                          </th>
                          <th style="text-align: center; width: 5%; border: 1px solid black;">
                          Meeting Hour
                          </th>
                          <th style="text-align: center; width: 5%; border: 1px solid black;">
                          Meeting Hour Used
                          </th>
                          <th style="text-align: center; width: 5%; border: 1px solid black;">
                          Meeting Hour Left
                          </th>
                        </tr>
                      </thead>
                      <tbody id="table">
                        @foreach ($data['lecturer'] as $key => $lct)
                            @php
                                // Calculate total number of groups for the lecturer to determine rowspan for the lecturer cell
                                $lecturerRowSpan = 0;
                                foreach ($data['subject'][$key] as $subKey => $subject) {
                                    $lecturerRowSpan += count($data['group'][$key][$subKey]);
                                }
                                $lecturerRowSpan = $lecturerRowSpan > 0 ? $lecturerRowSpan : 1;
                                $lecturerPrinted = false;
                            @endphp
                            @foreach ($data['subject'][$key] as $subKey => $subject)
                                @php
                                    // Calculate the number of groups for each subject to determine rowspan for the subject cell
                                    $subjectGroupCount = count($data['group'][$key][$subKey]);
                                    $subjectRowSpan = $subjectGroupCount > 0 ? $subjectGroupCount : 1;
                                @endphp
                                @foreach ($data['group'][$key][$subKey] as $groupKey => $group)
                                    <tr>
                                        @if (!$lecturerPrinted)
                                            <td style="text-align: center; border: 1px solid black;" rowspan="{{ $lecturerRowSpan }}">
                                                {{ $lct->name }}
                                            </td>
                                            @php
                                                $lecturerPrinted = true;
                                            @endphp
                                        @endif
                                        @if ($groupKey === 0)
                                            <td style="text-align: center; border: 1px solid black;" rowspan="{{ $subjectRowSpan }}">
                                                {{ $subject->course_name }}
                                            </td>
                                            <td style="text-align: center; border: 1px solid black;" rowspan="{{ $subjectRowSpan }}">
                                                {{ $subject->session }}
                                            </td>
                                        @endif
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $group->group_name }}
                                        </td>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $subject->meeting_hour }}
                                        </td>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $data['detail'][$key][$subKey][$groupKey]->total_hours ?? 0 }}
                                        </td>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $data['hour_left'][$key][$subKey][$groupKey] }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                    
                      <tfoot style="background-color: darkcyan">
                      </tfoot>
                    </table>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        {{-- <div class="row">
          <div class="card mb-3" id="stud_info">
            <div class="card-body">
                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="form-group">
                            <table id="secondTable" class="w-100 table display margin-top-10 w-p100 table-layout: fixed;" style="background-color: darkcyan">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="text-align: center; border: 1px solid black;">
                                            Pecahan Pelajar Semester 1
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align: center; border: 1px solid black;">
                                            INTAKE
                                        </th>
                                        <th style="text-align: center; border: 1px solid black;">
                                            HOLDING
                                        </th>
                                        <th style="text-align: center; border: 1px solid black;">
                                            KULIAH
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @php
                                  $totalKuliah = 0;
                                  $totalHolding = 0;
                                  @endphp
                                  @foreach($data['sessions'] as $key => $ses)
                                    <tr>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $ses->SessionName }}
                                        </td>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $data['holding'][$key] }}
                                        </td>
                                        <td style="text-align: center; border: 1px solid black;">
                                            {{ $data['kuliah'][$key] }}
                                      </td>
                                    </tr>
                                    @php
                                    $totalKuliah += $data['kuliah'][$key];

                                    $totalHolding += $data['holding'][$key];
                                    @endphp
                                  @endforeach
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td style="text-align: center; border: 1px solid black;">JUMLAH</td>
                                    <td style="text-align: center; border: 1px solid black;">
                                      {{ $totalHolding }}
                                    </td>
                                    <td style="text-align: center; border: 1px solid black;">
                                      {{ $totalKuliah }}
                                    </td>
                                  </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div> --}}
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
                extend: 'print',
                text: 'Print',
                orientation: 'landscape', // Set the orientation to landscape
                customize: function(win) {
                  var today = new Date();
                  var dd = String(today.getDate()).padStart(2, '0');
                  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                  var yyyy = today.getFullYear();

                  today = dd + '/' + mm + '/' + yyyy;

                  var body = $(win.document.body);

                  // Clone the main table and ensure the footer is shown
                  var mainTable = $('#table_dismissed').clone();
                  mainTable.find('tfoot').show();

                  // Also clone the additional table you want to include
                  var additionalTable = $('#secondTable').clone();
                  additionalTable.find('tfoot').show();

                  // Clear the body first
                  body.html('');

                  // Append the cloned tables
                  body.append(`<h1>LAPORAN BILANGAN PELAJAR SETAKAT ${today}</h1>`); // Optional: Add a title or any additional content
                  body.append(mainTable); // Append the main table
                  body.append('<h2>Pecahan Pelajar Semester 1</h2>'); // Optional: Add subtitles or descriptions
                  body.append(additionalTable); // Append the additional table

                  // Additional customizations can be done here, like adjusting styles for print
                  body.find('table').addClass('print-table').css({
                      'border-collapse': 'collapse',
                      'width': '100%'
                  });
                  body.find('th, td').css({
                      'border': '1px solid black',
                      'padding': '8px'
                  });
                  body.find('.print-table').css({
                      'margin-bottom': '20px'
                  });

                  
                }
            },
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
