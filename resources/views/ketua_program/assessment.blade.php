@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Asessment</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Asessment</li>
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
            <div class="col-12">
              <form action="/KP/{{ $data['course'] }}/update/marks" method="POST">
              @csrf
              @method('PATCH')
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of Assessments</h3>
                    <div class="pull-right">
                      <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                          <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Assessments
                      </a>
                  </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 10%">Assessments</th>
                          <th style="width: 1%">Mark Percentage (%)</th>
                          <th style="width: 1%">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data['classmark'] as $key=>$datas)
                        <tr>
                          <td>{{ $datas->assessment }}</td>
                          <td>
                            <div>
                              <input type="number" class="form-control" id="mark_{{ $datas->id }}" name="marks[]" value="{{ $datas->mark_percentage }}" min="0" max="100">
                            </div>
                          </td>
                          <td>
                            <a class="btn btn-warning btn-sm" href="#" onclick="deleteMark('{{ $datas->id }}')">
                              <i class="ti-trash">
                              </i>
                              delete
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        
                      </tfoot>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="form-group pull-right">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
              <!-- Modal -->
              <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- modal content-->
                    <div class="modal-content" id="getModal">
                        <form action="/KP/{{ $data['course'] }}/insert/marks" method="post" role="form" enctype="multipart/form-data">
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
                                    <label class="form-label" for="assessment">Asessmesnt List</label>
                                    <select class="form-select" id="assessment" name="assessment">
                                    <option value="-" selected disabled>-</option> 
                                    @foreach ($data['assessment'] as $as)
                                    <option value="{{ $as }}">{{ $as }}</option> 
                                    @endforeach
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
            </div>
            <!-- /.col -->
          </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>

<script type="text/javascript">

  function deleteMark(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/KP/'. $data['course'] .'/delete/marks') }}",
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
