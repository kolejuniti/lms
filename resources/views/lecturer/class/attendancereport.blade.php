@extends((isset($guess)) ? (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "ADM" ? 'layouts.admin' : (Auth::user()->usrtype == "DN" ? 'layouts.dekan' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : 'layouts.lecturer'))))) : 'layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }

    div.dt-buttons {
    float: right;
    margin-left:10px;
    }
</style>

<!-- Add XLSX Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Report</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>



      <!-- Main content -->
      <section class="content">

        @if(Auth::user()->usrtype == "ADM")
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="box-body">
                <div>
                  <table>
                    <tr>
                      <td style="width: 150px;"><strong>Lecturer Name</strong></td>
                      <td >:</td>
                      <td>{{ $details->lecturer_name }}</td>
                    </tr>
                    <tr>
                      <td style="width: 150px;"><strong>Course Name</strong></td>
                      <td>:</td>
                      <td>{{ $details->course_name }}</td>
                    </tr>
                    <tr>
                      <td style="width: 150px;"><strong>Course Code</strong></td>
                      <td>:</td>
                      <td>{{ $details->course_code }}</td>
                    </tr>
                    <tr>
                      <td style="width: 150px;"><strong>Session</strong></td>
                      <td>:</td>
                      <td>{{ $details->SessionName }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif

        <div class="row">
          <div class="col-12">
            @if(count($groups) > 0)
              @foreach ($groups as $ky => $grp)
              <div class="box">
                <div class="card-header mb-4">
                  <h3 class="card-title">Student List : Group {{ $grp->group_name }}</h3>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                    <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                      <div class="row">
                        <div id = "status">
                          <div class="col-sm-12">
                            <table id="myTable{{$grp->group_name}}" class="w-100 table table-bordered display margin-top-10 w-p100 table-layout: fixed;" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                              <script>
                                // Wait for the DOM to be ready before running the script
                                $(document).ready(function() {
                                    // Initialize DataTable for the table with ID 'myTable{{$grp->group_name}}'
                                    $('#myTable{{$grp->group_name}}').DataTable({
                                        // Set the DOM structure: 'l' for length changing input, 'B' for buttons, 'f' for filtering input, 'r' for processing display, 't' for the table, 'i' for table info, 'p' for pagination control
                                        dom: 'lBfrtip',
                                        // Set 'paging' to false to disable pagination
                                        paging: false,
                                        // Define buttons to add to the table
                                        buttons: [
                                          {
                                            text: 'Excel',
                                            action: function () {
                                              // get the HTML table to export
                                              const table = document.getElementById("myTable{{$grp->group_name}}");
                                              
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
                                });
                              </script>
                              <thead>
                                <tr>
                                  <th style="text-align: center" rowspan="2">
                                    No.
                                  </th>
                                  <th style="text-align: center" rowspan="2">
                                    Name
                                  </th>
                                  <th style="text-align: center" rowspan="2">
                                    IC
                                  </th>
                                  <th style="text-align: center" rowspan="2">
                                    Matric No.
                                  </th>
                                  <th style="text-align: center" rowspan="2">
                                    Group Name
                                  </th>
                                  @foreach ($list[$ky] as $key=>$ls)
                                  <th>
                                    <div style="display: flex; justify-content: center; align-items: center;">
                                        <span style="margin-right: 5px;">{{ $ls->classdate }}</span>
                                        <span style="margin-right: 5px;">-</span>
                                        @if($ls->classend != null)
                                        <span style="margin-right: 5px;">{{ $ls->classend }}</span>
                                        @else
                                        <span>NONE</span>
                                        @endif
                                    </div>
                                  </th>                                
                                  @endforeach
                                </tr>
                                <tr>
                                  @foreach ($list[$ky] as $key=>$ls)
                                  <th style="text-align: center">
                                    @if($ls->classtype == 1)
                                      <span >Class
                                    @elseif($ls->classtype == 2)
                                      <span >Replacement</span>
                                    @else
                                      <span >None</span>
                                    @endif
                                  </th>
                                  @endforeach
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($students[$ky] as $key => $std)
                                <tr>
                                  <td style="text-align: center">
                                      {{ $key+1 }}
                                  </td>
                                  <td style="text-align: center">
                                    <a class="btn btn-success btn-sm mr-2">{{ $std->name }}</a>
                                  </td>
                                  <td style="text-align: center">
                                    <span >{{ $std->ic }}</span>
                                  </td>
                                  <td style="text-align: center">
                                    <span >{{ $std->no_matric }}</span>
                                  </td>
                                  <td style="text-align: center">
                                    <span >{{ $std->group_name }}</span>
                                  </td>
                                    @foreach ($list[$ky] as $keys => $ls)
                                      <td style="text-align: center">
                                        <span >{{ $status[$ky][$key][$keys] }}</span>
                                      </td>
                                    @endforeach
                                </tr>
                                @endforeach
                              </tbody>
                              @if(!isset($guess))
                              <tfoot>
                                <tr>
                                  <th colspan="5" style="text-align: right">
                                    Total Hours:
                                  </th>
                                  @php
                                    $totalHours = 0;
                                  @endphp
                                  @foreach ($list[$ky] as $key=>$ls)
                                    @php
                                      $start = \Carbon\Carbon::parse($ls->classdate);
                                      $end = \Carbon\Carbon::parse($ls->classend);
                                      $diffInHours = $end->diffInHours($start);
                                      $totalHours += $diffInHours;
                                    @endphp
                                    <th style="text-align: center">
                                      {{ $diffInHours }} hrs
                                    </th>
                                  @endforeach
                                </tr>
                                <tr>
                                  <th colspan="5" style="text-align: right">
                                    Overall Total Hours:
                                  </th>
                                  <th colspan="{{ count($list[$ky]) }}" style="text-align: center">
                                    {{ $totalHours }} hrs
                                  </th>
                                </tr>
                                <tr>
                                  <th>
                                    
                                  </th>
                                  <th>
                                   
                                  </th>
                                  <th>
                                    
                                  </th>
                                  <th>
                                    
                                  </th>
                                  <th>
                                    
                                  </th>
                                  @foreach ($list[$ky] as $key=>$ls)
                                  <th style="text-align: center">
                                    <a class="btn btn-info btn-sm" href="/lecturer/class/attendance/edit?from={{ $ls->classdate }}&&to={{ $ls->classend }}&&group={{ $ls->groupid }}&&name={{ $ls->groupname }}">
                                      <i class="ti-trash">
                                      </i>
                                      Edit
                                    </a>
                                    <a class="btn btn-danger btn-sm mt-2" href="#" onclick="deleteMaterial('{{ $ls->classdate }}', '{{ $ls->classend }}', '{{ $ls->groupid }}', '{{ $ls->groupname }}')" data-order="">
                                      <i class="ti-trash">
                                      </i>
                                      Delete
                                    </a>
                                  </th>
                                  @endforeach
                                </tr>
                              </tfoot>
                              @else
                              <tfoot>
                                <tr>
                                  <th colspan="5" style="text-align: right">
                                    Total Hours:
                                  </th>
                                  @php
                                    $totalHours = 0;
                                  @endphp
                                  @foreach ($list[$ky] as $key=>$ls)
                                    @php
                                      $start = \Carbon\Carbon::parse($ls->classdate);
                                      $end = \Carbon\Carbon::parse($ls->classend);
                                      $diffInHours = $end->diffInHours($start);
                                      $totalHours += $diffInHours;
                                    @endphp
                                    <th style="text-align: center">
                                      {{ $diffInHours }} hrs
                                    </th>
                                  @endforeach
                                </tr>
                                <tr>
                                  <th colspan="5" style="text-align: right">
                                    Overall Total Hours:
                                  </th>
                                  <th colspan="{{ count($list[$ky]) }}" style="text-align: center">
                                    {{ $totalHours }} hrs
                                  </th>
                                </tr>
                                <tr>
                                  <th>
                                    
                                  </th>
                                  <th>
                                   
                                  </th>
                                  <th>
                                    
                                  </th>
                                  <th>
                                    
                                  </th>
                                  <th>
                                    
                                  </th>
                                  @foreach ($list[$ky] as $key=>$ls)
                                  <th>
                                  
                                  </th>
                                  @endforeach
                                </tr>
                              </tfoot>
                              @endif
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <div class="box bg-danger">
                <div class="box-body d-flex p-0">
                  <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                    <div class="row">
                      <div class="col-12 col-xl-12">
                        <h1 class="mb-0 fw-600">No Group available.</h1>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>
      </section>
        <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
    var selected_group = "";
    var selected_quiz = "{{ request()->quiz }}";
    

    $(document).on('change', '#group', function(e) {
        selected_group = $(e.target).val();

        getGroup(selected_group,selected_quiz);
    });

    function getGroup(group,quiz)
    {

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/quiz/getStatus') }}",
            method   : 'POST',
            data 	 : {group: group,quiz: quiz },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
                //$('#lecturer-selection-div').removeAttr('hidden');
                //$('#lecturer').selectpicker('refresh');
      
                //$('#chapter').removeAttr('hidden');
                    $('#status').html(data);
                    $('#myTable').DataTable();
                    //$('#group').selectpicker('refresh');
            }
        });

    }

    function deleteMaterial(from,to,group,name){     
      Swal.fire({
      title: "Are you sure?",
      text: "This will be permanent",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
      if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('/lecturer/class/attendance/deleteAttendance') }}",
                    method   : 'POST',
                    data 	 : {from:from, to:to, group:group, name:name},
                    error:function(err){
                        alert("Error");
                        console.log(err);
                    },
                    success  : function(data){
                        alert(data.message);
                        window.location.reload();
                    }
                });
            }
        });
    }

</script>
@stop