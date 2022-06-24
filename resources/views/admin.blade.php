@extends('layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card col-md-12">
        <div class="card-header">
          <h3 class="card-title">Lecturers</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
              <div class="row">
                <div class="card-body p-0">
                  <table id="complex_header" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info" data-ordering="false">
                      <thead>
                          <tr>
                              <th style="width: 1%">
                                  No.
                              </th>
                              <th style="width: 30%">
                                  Lecturer Name
                              </th>
                              <th style="width: 15%">
                                  IC
                              </th>
                              <th style="width: 20%">
                                  Email
                              </th>
                              <th>
                                  User Type
                              </th>
                              <th style="width: 15%">
                              </th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($users as $key=>$user)
                        <tr>
                          <td style="width: 1%">
                              {{$key+1}}
                          </td>
                          <td style="width: 30%">
                              {{ $user->name }}
                          </td>
                          <td style="width: 15%">
                            {{ $user->ic }}
                          </td>
                          <td style="width: 20%">
                            {{ $user->email }}
                          </td>
                          <td>
                            {{ $user->usrtype }}
                          </td>
                          <td class="project-actions text-right" >
                            <a class="btn btn-info btn-sm pr-2" href="/admin/{{ $user->id }}/edit">
                                <i class="ti-pencil-alt">
                                </i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $user->ic }}')">
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
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteMaterial(ic){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/admin/delete') }}",
                  method   : 'DELETE',
                  data 	 : {ic:ic},
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
