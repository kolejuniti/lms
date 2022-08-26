
@extends('layouts.lecturer.lecturer')

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
    @if(session()->has('message'))
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <div class="form-group">
            <div class="alert alert-success">
                <span>{{ session()->get('message') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Attendance</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Attendance</li>
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
                <h3 class="card-title">Attendance List</h3>
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
                              <th >
                                Date
                              </th>
                              <th>
                                Group
                              </th>
                              <th>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($list as $key => $lst)
                            <tr>
                              <td style="width: 1%">
                                  {{ $key+1 }}
                              </td>
                              <td>
                                  {{ $lst->classdate }}
                              </td>
                              <td>
                                  {{ $lst->groupname }}
                              </td>
                              <td class="project-actions text-right" >
                                <a class="btn btn-info btn-sm btn-sm" href="/lecturer/class/attendance/report/{{ $lst->classdate }}/{{ $lst->groupid }}?name={{ $lst->groupname }}">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Report
                                </a>
                                <a class="btn btn-danger btn-sm btn-sm" href="#" onclick="deleteAttendance('{{ $lst->classdate }}','{{ $lst->groupid }}','{{ $lst->groupname }}')">
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
    
    </div>
</div>
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready( function () {
    $('#myTable').DataTable();
} );

function deleteAttendance(date,group,name)
{
  Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
      if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/class/attendance/deletAttendance') }}",
                    method   : 'POST',
                    data 	 : {date:date, group:group, name:name},
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