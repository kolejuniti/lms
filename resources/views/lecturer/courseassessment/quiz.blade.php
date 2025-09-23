
@extends('layouts.lecturer.lecturer')

@section('main')

<link rel="stylesheet" href="{{ asset('css/customCSS.css') }}">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Quiz</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Quiz</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(session('message'))
      <script>
        alert('{{ session("message") }}');
      </script>
    @endif

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="card-header mb-4">
                <h3 class="card-title">Quiz List</h3>
              </div>
              <div class="box-body">
                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <div class="pull-right">
                            <button id="autoMarkAll" class="waves-effect waves-light btn btn-success btn-sm mr-2">
                                <i class="fa fa-check"></i> <i class="fa fa-magic"></i> &nbsp Auto Mark All
                            </button>
                            <button id="newFolder" class="waves-effect waves-light btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <i class="fa fa-folder"></i> &nbsp New Quiz
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                          <thead>
                            <tr>
                              <th style="width: 1%">
                                No.
                              </th>
                              <th>
                                Title
                              </th>
                              <th>
                                Groups
                              </th>
                              <th>
                                Chapters
                              </th>
                              <th>
                                Duration
                              </th>
                              <th>
                                Date From
                              </th>
                              <th>
                                Date To
                              </th>
                              <th>
                                Status
                              </th>
                              <th>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $key => $dt)
                            <tr>
                              <td style="width: 1%">
                                  {{ $key+1 }}
                              </td>
                              <td>
                                  {{ $dt->title }}
                              </td>
                              <td>
                                  @foreach ($group[$key] as $grp)
                                    Group {{ $grp->groupname }},
                                  @endforeach
                              </td>
                              <td>
                                @foreach ($chapter[$key] as $chp)
                                  Chapter {{ $chp->ChapterNo }} : {{ $chp->DrName }},
                                @endforeach
                              </td>
                              <td>
                                {{ $dt->duration }} minutes
                              </td>
                              <td>
                                {{ $dt->date_from }}
                              </td>
                              <td>
                                {{ $dt->date_to }}
                              </td>
                              <td>
                                {{ $dt->statusname }}
                              </td>
                              <td class="project-actions text-right" >
                                <a class="btn btn-success btn-sm mr-2" href="/lecturer/quiz/{{ Session::get('CourseIDS') }}/{{ $dt->id }}">
                                    <i class="ti-user">
                                    </i>
                                    Students
                                </a>
                                <a class="btn btn-info btn-sm btn-sm mr-2" href="/lecturer/quiz/{{ Session::get('CourseID') }}/create?quizid={{ $dt->id }}">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                @if($dt->statusname == 'published')
                                <a class="btn btn-warning btn-sm btn-sm mr-2" href="#" onclick="getExtend('{{ $dt->id }}')">
                                    <i class="ti-shift-right">
                                    </i>
                                    Extend
                                </a>
                                @endif
                                <a class="btn btn-danger btn-sm" href="#" onclick="deleteQuiz('{{ $dt->id }}')">
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
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
        <!-- /.content -->

        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal">
                  
              </div>
          </div>
        </div>
    
    </div>
</div>
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready( function () {
    $('#myTable').DataTable();
} );

    $(document).on('click', '#newFolder', function() {
        location.href = "/lecturer/quiz/{{ Session::get('CourseID') }}/create";
    })

    $(document).on('click', '#autoMarkAll', function() {
        Swal.fire({
            title: "Auto Mark All Quizzes?",
            text: "This will automatically mark all submitted quizzes that contain only radio button questions. Quizzes with multiple choice or subjective questions will be skipped.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, auto mark them!"
        }).then(function(result) {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Auto marking quizzes in progress',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('lecturer/quiz/automark') }}",
                    method: 'POST',
                    data: {
                        course_id: "{{ Session::get('CourseIDS') }}",
                        session_id: "{{ Session::get('SessionIDS') }}"
                    },
                    error: function(err) {
                        console.log(err);
                        let errorMessage = "An error occurred while auto marking.";
                        if (err.responseJSON && err.responseJSON.message) {
                            errorMessage = err.responseJSON.message;
                        }
                        Swal.fire({
                            title: "Error!",
                            text: errorMessage,
                            icon: "error"
                        });
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: "Success!",
                                text: data.message,
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Warning!",
                                text: data.message,
                                icon: "warning"
                            });
                        }
                    }
                });
            }
        });
    })

    function deleteQuiz(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
      if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/quiz/deletequiz') }}",
                    method   : 'POST',
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

  function getExtend(id)
  {

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/quiz/getextend') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal').html(data);
                $('#uploadModal').modal('show');
            }
        });

  }

</script>
@stop