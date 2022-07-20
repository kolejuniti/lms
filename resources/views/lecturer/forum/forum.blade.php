@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Forum</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Online Class</li>
                <li class="breadcrumb-item active" aria-current="page">Forum</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

		<!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title">
                            {{ $course->course_name }}
                        </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                        <div>
                        @if($TopicID != '')
                            <h5><b>Topik : {{ $TopicID }}</b></h5>
                        @else
                        <h5><b>Topik :</b></h5>
                        @endif
                        </div>
                        <div class="row">
                            <p class="col-md-2">
                            Name
                            </p>
                            <p class="col-md-6">
                            Comments
                            </p>
                            <p class="col-md-2 pull-right">
                            Date/Time
                            </p>
                        </div>
                        <table class="table" id="reftable">
                            <?php //include_once 'TableForum.php';?>
                            @include('lecturer.forum.TableForum')
                        </table>
                        <script type='text/javascript'>
                        var table = $('#reftable');
                        // refresh every 5 seconds
                        var refresher = setInterval(function(){
                            table.load("lecturer.forum.TableForum");
                        }, 5000);
                            setTimeout(function() {
                            clearInterval(refresher);
                        }, 1800000);
                        </script>
                        </div>
                        <div class="card-body">
                        <?php
                        if($TopicID != ''){
                        ?>
                        <form action="/lecturer/forum/{{ Session::get('CourseIDS') }}/topic/insert?tpcID={{ $TopicID }}" method="post" role="form">
                            @csrf
                            @method('POST')
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Comment Inputss</label>
                                    <textarea id="upforumtxt" name="upforum" class="mt-2" rows="10" cols="80" placeholder="Enter ...">
                                    </textarea>
                                </div>   
                              </div>
                            </div>
                            <div class="float-right">
                            <input class="btn btn-block btn-primary float-right" name="addfrm" type="submit" value="Send">
                            </div>
                        </form>
                        <?php
                        }
                        else{
                        ?>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Comment Input</label>
                                    <textarea id="upforumtxt" class="mt-2" rows="10" cols="80" placeholder="Enter ...">
                                    </textarea>
                                </div>   
                              </div>
                            </div>
                            <div class="float-right">
                              <input class="btn btn-block btn-primary float-right" name="addfrm" type="submit" value="Send">
                            </div>
                        <?php
                        }
                        ?>
                        </div>
                            <!-- /.card-body -->
                    </div>
                            <!-- /.card -->
                    </div>
                    <div class="col-md-3">
                    <div class="card">
                        <div class="card-header bg-gradient-primary">
                        <h3 class="card-title"><b>Discussion</b></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Topic</th>
                                <th style="width: 40px">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                             @if(isset($topic))
                                @if(!empty($topic))
                                    @foreach ($topic as $key => $tpc)
                                        <tr class="col-md-6">
                                            <td>{{ $key+1 }}</a></td>
                                            <td><a href="/lecturer/forum/{{ Session::get('CourseIDS') }}?TopicID={{ $tpc->TopicID }}">{{ $tpc->TopicName }}</td>
                                            <td><a class="btn btn-danger btn-sm" href="#"><i class="ti-trash"></i>Delete</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr class="col-md-6">
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                                @endif
                            @else
                                <tr class="col-md-6">
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                            <!-- modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                              <div class="modal-body">
                                <form action="/lecturer/forum/{{ Session::get('CourseIDS') }}/insert" method="post" role="form" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                  <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="inputTitle" id="inputTitle" class="form-control">
                                  </div>
                                  <div >
                                    <input type="submit" name="addtopic" class="btn btn-app bg-gradient-primary float-right" value="submit">
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="card-footer">
                        <a type="button" class="btn btn-app bg-gradient-primary float-right" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-plus-circle"></i> Add Topic
                        </a>
                        </div> 
                    </div>
                    <!-- /.card-body -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
            </div>
        </section>
          <!-- /.content -->
    </div>
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
<script type="text/javascript">

  $(document).ready(function(){

  "use strict";
      ClassicEditor
      .create( document.querySelector( '#upforumtxt' ),{ height: '25em' } )
      .then(newEditor =>{editor = newEditor;})
      .catch( error => { console.log( error );});

  })

  function deleteAnnouncement(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('lecturer/class/announcement/list/delete') }}",
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

</script>
@endsection
