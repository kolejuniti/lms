
@extends((Auth::user()->usrtype == "LCT") ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "ADM" ? 'layouts.admin' : (Auth::user()->usrtype == "DN" ? 'layouts.dekan' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : ''))))))

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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
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
                            <table id="myTable{{$grp->group_name}}" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                              <script>
                                $(document).ready( function () {
                                    $('#myTable{{$grp->group_name}}').DataTable({
                                      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                                      paging: false, // Add this line to disable pagination
                                      buttons: [
                                          { extend: 'copyHtml5', footer: true },
                                          { extend: 'excelHtml5', footer: true },
                                          { extend: 'csvHtml5', footer: true },
                                          { extend: 'pdfHtml5', footer: true }
                                      ],

                                    });
                                } );
                              </script>
                              <thead>
                                <tr>
                                  <th style="text-align: center">
                                    No.
                                  </th>
                                  <th style="text-align: center">
                                    Name
                                  </th>
                                  <th style="text-align: center">
                                    IC
                                  </th>
                                  <th style="text-align: center">
                                    Matric No.
                                  </th>
                                  <th style="text-align: center">
                                    Group Name
                                  </th>
                                  @foreach ($list[$ky] as $key=>$ls)
                                  <th style="text-align: center">
                                    {{ $ls->classdate }}
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
                              
                                  <!-- QUIZ -->

      
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
                                    <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $ls->classdate }}', '{{ $ls->groupid }}', '{{ $ls->groupname }}')" data-order="">
                                      <i class="ti-trash">
                                      </i>
                                      Delete
                                    </a>
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

    function deleteMaterial(date,group,name){     
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
                    data 	 : {date:date, group:group, name:name},
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