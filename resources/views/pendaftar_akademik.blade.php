@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Subject</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Subject</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Subject List</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="program">Program</label>
                    <select class="form-select" id="program" name="program">
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['program'] as $prg)
                      <option value="{{ $prg->id }}">{{ $prg->progname}}</option> 
                      @endforeach
                    </select>
                </div>
              </div class="row">
                <div class="pull-right">
                    <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Course
                    </a>

                    {{-- <a type="button" class="waves-effect waves-light btn btn-success btn-sm" data-toggle="modal" data-target="#uploadModal2">
                      <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Update Course
                    </a> --}}
                </div>
              </div>
          </div>
        </div>
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 20%">
                        Course Name
                    </th>
                    <th style="width: 5%">
                        Course Code
                    </th>
                    <th style="width: 5%">
                        Credit
                    </th>
                    <th style="width: 10%">
                        Prerequisite
                    </th>
                    <th style="width: 5%">
                        Course Level ID
                    </th>
                    <th style="width: 5%">
                        Offer
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['course'] as $key=> $crs)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <td style="width: 20%">
                  {{ $crs->course_name }}
                </td>
                <td style="width: 5%">
                  {{ $crs->course_code }}
                </td>
                <td style="width: 5%">
                  {{ $crs->course_credit }}
                </td>
                <td>
                  {{ $crs->prerequisite }}
                </td>
                <td>
                  {{ $crs->course_level_id }}
                </td>
                <td>
                  {{ $crs->offer }}
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="updateCourse('{{ $crs->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteCourse('{{ $crs->id }}')">
                      <i class="ti-trash">
                      </i>
                      Delete
                  </a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content">
                  <form action="/AR/course/create" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <div class="">
                            <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                      <div class="row col-md-12">
                        <div>
                          <div class="form-group">
                            <label>Course Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Course Code</label>
                            <input type="text" name="code" id="code" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Credit</label>
                            <input type="text" name="credit" id="credit" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="prerequisite">Prerequisite</label>
                              <select class="form-select" id="prerequisite" name="prerequisite">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['courselist'] as $prg)
                                <option value="{{ $prg->sub_id }}">{{ $prg->course_code}} - {{ $prg->course_name}}</option> 
                                @endforeach
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="clid">Course Level ID</label>
                              <select class="form-select" id="clid" name="clid">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['level'] as $clid)
                                <option value="{{ $clid->id }}">{{ $clid->name}}</option> 
                                @endforeach
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="offer">Offer</label>
                              <select class="form-select" id="offer" name="offer">
                                <option value="" selected disabled>-</option>
                                <option value="1">Offered</option>
                                <option value="0">Not Offered</option>
                              </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group pull-right">
                            <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                        </div>
                    </div>
                  </form>
              </div>
          </div>
        </div>

        {{-- <div id="uploadModal2" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content">
                  <form action="/AR/course/create?upt={{ 1 }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <div class="">
                            <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                      <div class="row col-md-12">
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="course">Course</label>
                              <input class="form-select" list="courses" name="course" id="course">
                              <datalist id="courses" name="courses">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['courselist'] as $crs)
                                <option value="{{ $crs->id }}">{{ $crs->course_name}} ({{ $crs->course_code }})</option> 
                                @endforeach
                              </datalist >
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="program2">Program</label>
                              <select class="form-select" id="program2" name="program2[]" style="height: 400px" multiple>
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['program'] as $prg)
                                <option value="{{ $prg->id }}">{{ $prg->progname}}</option> 
                                @endforeach
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="semester">Semester</label>
                              <select class="form-select" id="semester" name="semester">
                              <option value="-" selected disabled>-</option>
                                <option value="1">Semester 1</option> 
                                <option value="2">Semester 2</option> 
                                <option value="3">Semester 3</option> 
                                <option value="4">Semester 4</option> 
                                <option value="5">Semester 5</option> 
                                <option value="6">Semester 6</option> 
                              </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group pull-right">
                            <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                        </div>
                    </div>
                  </form>
              </div>
          </div>
        </div> --}}

        <div id="uploadModal3" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal">
              </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteCourse(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/AR/course/delete') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

  
  function updateCourse(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/course/update') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal').html(data);
                $('#uploadModal3').modal('show');
            }
        });

  }

</script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script type="text/javascript">
    var selected_program = "";

    var url = window.location.href;


    $(document).on('change', '#program', async function(e){
      selected_program = $(e.target).val();

    await getCourse(selected_program);
    })

  function getCourse(program)
  {
    $('#myTable').DataTable().destroy();

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/getCourse') }}",
            method   : 'POST',
            data 	 : {program: program},
            beforeSend:function(xhr){
              $("#myTable").LoadingOverlay("show", {
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
              $("#myTable").LoadingOverlay("hide");
            },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#myTable').removeAttr('hidden');
                $('#myTable').html(data);
                
                $('#myTable').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print'
                  ],
                  float: right;
                });
                //window.location.reload();
            }
        });
  }

  

  </script>
@endsection
