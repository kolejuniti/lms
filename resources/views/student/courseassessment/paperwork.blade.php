
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
                <h4 class="page-title">Paperwork</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Paperwork</li>
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
                <h3 class="card-title">Paperwork List</h3>
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
                                Title
                              </th>
                              <th style="width: 5%">
                                Creator
                              </th>
                              <th style="width: 10%">
                                Groups
                              </th>
                              <th style="width: 20%">
                                Chapters
                              </th>
                              <th style="width: 15%">
                                Attachment
                              </th>
                              <th style="width: 5%">
                                Deadline
                              </th>
                              <th style="width: 20%">
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $key => $dt)
                            <tr>
                              <td style="width: 1%">
                                  {{ $key+1 }}
                              </td>
                              <td style="width: 15%">
                                  {{ $dt->title }}
                              </td>
                              <td style="width: 5%">
                                  {{ $dt->addby }}
                              </td>
                              <td style="width: 10%">
                                  @foreach ($group[$key] as $grp)
                                    Group {{ $grp->groupname }},
                                  @endforeach
                              </td>
                              <td>
                                @foreach ($chapter[$key] as $chp)
                                  Chapter {{ $chp->ChapterNo }} : {{ $chp->DrName }},
                                @endforeach
                              </td>
                              <td class="align-items-center">
                                <a href="{{ Storage::disk('linode')->url($dt->content) }}"><i class="fa fa-file-pdf-o fa-3x"></i></a>
                              </td>
                              <td>
                                {{ $dt->deadline }}
                              </td>
                              <td class="project-actions text-right" >
                                <a class="btn btn-success btn-sm mr-2" href="/student/paperwork/{{ Session::get('CourseIDS') }}/{{ $dt->id }}">
                                    <i class="ti-user">
                                    </i>
                                    Students
                                </a>
                                <a class="btn btn-info btn-sm btn-sm mr-2" href="#">
                                    <i class="ti-pencil-alt">
                                    </i>
                                    Edit
                                </a>
                                <a class="btn btn-danger btn-sm" href="#">
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

</script>
@stop