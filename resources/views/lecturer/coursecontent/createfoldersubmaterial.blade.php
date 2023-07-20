@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Create Folder</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Home</li>
                <li class="breadcrumb-item" aria-current="page">Material Gallery</li>
                <li class="breadcrumb-item" aria-current="page">Create Folder</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Material Folder</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="/lecturer/content/material/sub/store/{{ $dirid }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label" for="chapter">Sub Chapter No.</label>
                          <input type="number" class="form-control" id="chapter" placeholder="Enter chapter no i.e : 1" name="chapter" value="{{ $chapter->ChapterNo }}" min="{{ $chapter->ChapterNo }}.0" max="{{ $chapter->ChapterNo }}.9" step="any">
                          <span class="text-danger">@error('chapter')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="name">Folder Name</label>
                          <input type="text" class="form-control" id="name" placeholder="Enter folder name i.e : Full name" name="name" value="{{ old('name') }}">
                          <span class="text-danger">@error('name')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="ic">Password (OPTIONAL)</label>
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password">
                        <span class="text-danger">@error('pass')
                          {{ $message }}
                        @enderror</span>
                        <span class="text-danger">@error('conpass')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                    <div class="col-md-6" id="pass_confirm" hidden>
                      <div class="form-group">
                        <label class="form-label" for="ic">Confirm Password</label>
                        <input type="password" class="form-control" id="conpass" name="conpass" placeholder="Enter Confirm Password">
                      </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script type="text/javascript">

$(document).on('keypress', '#pass', function(){

    document.getElementById('pass_confirm').hidden = false;

    document.getElementById('pass_confirm').required = true;

})


var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }

</script>
@endsection
