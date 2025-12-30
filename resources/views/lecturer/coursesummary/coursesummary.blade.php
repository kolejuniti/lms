@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Course Summary</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Home</li>
                <li class="breadcrumb-item active" aria-current="page">Course Summary</li>
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
        <ul class="nav nav-tabs nav-bordered mb-4 center">
          @foreach ($program as $prg)
          <li class="nav-item col-3" style="text-align:center">
            <a class="nav-link" data-toggle="tab" href="#{{ $prg->progcode }}">
              <div class=" d-md-block">
                <i class="fa fa-group"></i>
                <span class="hidden-sm-down ml-1">&nbsp {{ $prg->progcode }}</span>
              </div>
            </a>
          </li>
          @endforeach
        </ul>
        <div class="card-body p-0">
          <div class="tab-content">
            @foreach ($summary as $sum)
            <div id="{{ $sum->progcode }}" class="tab-pane">
              <!-- //asset('storage/coursesummary/'.$course->progcode.'/'.str_replace(" ","_", $course->course_code).'.pdf') -->

              @if ($sum->pdfUrl)
              <iframe src="{{ $sum->pdfUrl }}" width="100%" height="1000" style="border:1px solid black;">
              </iframe>
              @elseif (isset($sum->storageError))
              <div class=" d-flex justify-content-center align-items-center box-header bg-danger-light" style="height:20em; flex-direction: column;">
                <h3 class="text-danger">Storage Error</h3>
                <p class="text-muted">{{ $sum->storageError }}</p>
              </div>
              @else
              <div class=" d-flex justify-content-center align-items-center box-header bg-secondary-light" style="height:20em">
                <h1 class="text-muted ">-- Course Summary not set --</h1>
              </div>
              @endif


            </div>
            @endforeach
          </div>
        </div>
        <div class="card-body p-0">


        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>
@endsection