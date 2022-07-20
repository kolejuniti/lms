@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Profile</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Extra</li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
          <h3 class="card-title">Course Summary</h3>

          <!--<div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>-->
        </div>
        <div class="card-body p-0">
          <!-- //asset('storage/coursesummary/'.$course->progcode.'/'.str_replace(" ","_", $course->course_code).'.pdf') -->
          <iframe src="{{ Storage::disk('linode')->url('coursesummary/'.$course->progcode.'/'.str_replace(" ","_", $course->course_code).'.pdf') }}" width="100%" height="1000" style="border:1px solid black;">
          </iframe>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>
@endsection
