
@extends('layouts.student.student')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Practical</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Practical</li>
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
                <h3 class="card-title">Practical List</h3>
              </div>
              <div class="box-body">
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
                              <th style="width: 15%">
                                Name
                              </th>
                              <th style="width: 5%">
                                Matric No.
                              </th>
                              <th style="width: 20%">
                                Submission Date
                              </th>
                              <th style="width: 15%">
                                Attachment
                              </th>
                              <th style="width: 10%">
                                Status Submission
                              </th>
                              <th style="width: 5%">
                                Marks
                              </th>
                              <th style="width: 20%">
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($practical as $key => $qz)
                            @foreach ($status as $sts)
                            @php
                              if(empty($sts))
                              {
                                $alert = "badge bg-danger";
                              }else{
                                $alert = "badge bg-success";
                              }
                            @endphp
                            <tr>
                              <td style="width: 1%">
                                  {{ $key+1 }}
                              </td>
                              <td style="width: 15%">
                                <span class="{{ $alert }}">{{ Session::get('StudInfos')->name }}</span>
                              </td>
                              <td style="width: 5%">
                                <span class="{{ $alert }}">{{ Session::get('StudInfos')->no_matric }}</span>
                              </td>
                              <td style="width: 20%">
                                    {{ empty($sts) ? '-' : $sts->subdate }}
                              </td>
                              <td style="width: 5%">
                                  @if (empty($sts))
                                    -
                                  @else
                                    <a href="{{ Storage::disk('linode')->url($sts->content) }}"><i class="fa fa-file-pdf-o fa-3x"></i></a>
                                  @endif
                              </td>
                              <td>
                                  @if (empty($sts))
                                    -
                                  @else
                                    @if ($sts->status_submission == 2)
                                      <span class="badge bg-danger">Late</span>
                                    @else
                                      <span class="badge bg-success">Submit</span>
                                    @endif
                                  @endif
                              </td>
                              <td>
                                    {{ empty($sts) ? '-' : $sts->final_mark }}
                              </td>                          
                              
                              <td class="project-actions text-center" >
                                @if (empty($sts))
                                <a class="btn btn-success btn-sm mr-2" href="/student/practical/{{ Session::get('CourseIDS') }}/{{ request()->practical }}/view">
                                  <i class="ti-user">
                                  </i>
                                  Answer
                                </a>
                                @elseif (!empty($sts))
                                <a class="btn btn-success btn-sm mr-2" href="/student/practical/{{ request()->practical }}/{{ Session::get('StudInfos')->ic }}/result">
                                  <i class="ti-user">
                                  </i>
                                  Result
                                </a>
                                @endif
                                
                              </td>
                            </tr>
                            @endforeach                            
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
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready( function () {
    $('#myTable').DataTable();
} );

    function deleteMaterial(dir){     
        Swal.fire({
			title: "Are you sure?",
			text: "This will be permanent",
			showCancelButton: true,
			confirmButtonText: "Yes, delete it!"
		}).then(function(res){
			
			if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/content/delete') }}",
                    method   : 'DELETE',
                    data 	 : {dir:dir},
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
@stop