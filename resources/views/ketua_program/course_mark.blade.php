@extends((Auth::user()->usrtype == "AR") ? 'layouts.pendaftar_akademik' : 'layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Course</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Course</li>
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
          <div class="box">
            <div class="card-header mb-4">
              <h3 class="card-title">Course List</h3>
            </div>
            <div class="box-body">
              <div class="table-responsive">
                <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="complex_header" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info" data-ordering="false">
                        <thead>
                          <tr>
                            <th style="width: 1%">
                              No.
                            </th>
                            <th>
                              Subject
                            </th>
                            <th>
                              Code
                            </th>
                            <th>
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($course as $key=>$crs)
                          <tr>
                            <td style="width: 1%">
                                {{$key+1}}
                            </td>
                            <td>
                              {{ $crs->course_name }}
                            </td>
                            <td>
                              {{ $crs->course_code }}
                            </td>
                            <td class="project-actions text-right" >
                              <a class="btn btn-warning btn-sm mr-2" href="/KP/{{ $crs->id }}/assessment">
                                  <i class="ti-ruler-pencil">
                                  </i>
                                  Assessment
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
    </div>
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteMaterial(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/KP/delete') }}",
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
