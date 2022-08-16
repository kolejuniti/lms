@extends('layouts.student')


@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Setting</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Setting</li>
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Setting</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="/student/update" method="POST">
                @csrf
                @method('POST')
                <div class="card-body">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}">
                        <span class="text-danger">@error('email')
                          {{ $message }}
                        @enderror</span>
                        <span class="text-danger">@error('pass')
                          {{ $message }}
                        @enderror</span>
                        <span class="text-danger">@error('conpass')
                          {{ $message }}
                        @enderror</span>
                      </div>
                    </div>
                    <!--<div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="ic">Confirm Password</label>
                        <input type="password" class="form-control" id="conpass" name="conpass" placeholder="Enter Confirm Password">
                      </div>
                    </div>-->
                    <button type="button" id="myButton" class="btn btn-info">Change Password</button>
                    <div id="collapsee">
											<div class="col-md-6 mt-2">
                        <div class="form-group">
                          <label class="form-label" for="ic">New Password</label>
                          <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="ic">Confirm Password</label>
                          <input type="password" class="form-control" id="conpass" name="conpass" placeholder="Enter Confirm Password">
                        </div>
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

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>

<script>
	$(document).ready(function(){
		$("#collapsee").hide();
		$("#myButton").click(function(){
			$("#collapsee").slideToggle(500);
		});
	});
</script>
@endsection
