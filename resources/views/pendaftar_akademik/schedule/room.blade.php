@extends('layouts.pendaftar_akademik')

@section('main')

@if(session('success'))
<script>
  alert('{{ session("success") }}')
</script>
@endif

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Lecture Room</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Lecture Room</li>
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
          <h3 class="card-title">Lecture Room's List</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
                <div class="pull-right">
                    <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Room
                    </a>
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
                    <th>
                        Room Name
                    </th>
                    <th>
                        Start Time
                    </th>
                    <th>
                        End Time
                    </th>
                    <th>
                        Capacity
                    </th>
                    <th>
                        Total Hour Per Day
                    </th>
                    <th>
                        Projector
                    </th>
                    <th>
                        Weekend
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['roomList'] as $key=> $rm)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <td>
                  {{ $rm->name }}
                </td>
                <td>
                  {{ (new DateTime($rm->start))->format('h:i A') }}
                </td>
                <td>
                  {{ (new DateTime($rm->end))->format('h:i A') }}
                </td>
                <td>
                  {{ $rm->capacity }}
                </td>
                <td>
                  {{ $rm->total_hour }}
                </td>
                <td>
                  {{ $rm->projector }}
                </td>
                <td>
                  @if($rm->weekend == 0 )
                  No
                  @else
                  Yes
                  @endif
                </td>
                <td>
                  {!! $rm->description !!}
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="updateRoom('{{ $rm->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteRoom('{{ $rm->id }}')">
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
                  <form action="/AR/schedule/room/create" method="post" role="form" enctype="multipart/form-data">
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
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Start Time</label>
                            <input type="time" name="start" id="start" class="form-control" value="{{ old('start') }}">
                            @error('start')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>End Time</label>
                            <input type="time" name="end" id="end" class="form-control" value="{{ old('end') }}">
                            @error('end')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Capacity</label>
                            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity') }}">
                            @error('capacity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Hour Per Day</label>
                            <input type="number" name="t_hour" id="t_hour" class="form-control" value="{{ old('t_hour') }}">
                            @error('t_hour')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Projector</label>
                            <input type="number" name="projector" id="projector" class="form-control" value="{{ old('projector') }}">
                            @error('projector')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="weekend">Weekend</label>
                              <select class="form-select" id="weekend" name="weekend">
                              <option value="" selected disabled>-</option>
                                <option value="0">No</option>
                                <option value="1">Yes</option> 
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Description</label>
                            <textarea name="desc" id="desc" class="form-control" rows="4" cols="50">{{ old('desc') }}</textarea>
                            @error('desc')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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

        <div id="uploadModal2" class="modal" class="modal fade" role="dialog">
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

  function deleteRoom(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/AR/schedule/room/delete') }}",
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

  function updateRoom(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/schedule/room/update') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal').html(data);
                $('#uploadModal2').modal('show');
            }
        });

  }
  

  </script>
@endsection
