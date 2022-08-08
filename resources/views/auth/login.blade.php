<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
    <title>EDUHUB - Log in </title>
  
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
	  
	<!-- Style-->  
	<link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">

</head>
	
<body class="hold-transition theme-primary bg-img" style="background-image: url({{ asset('assets/images/auth-bg/bg-16.jpg') }})">
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">	
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-5 col-12">
						<div class="bg-white rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								{{-- <h2 class="text-primary fw-600">Let's Get Started</h2> --}}
								<div class="container mb-5">
									<img src="{{ asset('assets/images/logo-letter.png')}}" height="60em" width="auto" >
									<img src="{{ asset('assets/images/logo-dark-text.png')}}" height="30em" width="auto"  class="">
								</div>
								<p class="mb-0 text-fade p-3 pb-0 ">Sign in to continue.</p>							
							</div>
							@if(session()->has('message'))
							<div class="container-fluid mt-2">
								<div class="row justify-content-center">
									<!-- left column -->
									<div class="col-md-5">
										<div class="form-group">
											<div class="alert alert-danger" style="text-align: center">
												<span>{{ session()->get('message') }}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
							<div class="p-40">
								<ul class="nav nav-tabs nav-bordered mb-4 center">
									<li class="nav-item col-6" style="text-align:center">
										<a class="nav-link active"  onclick="openCity(event, 'London')" id="v-pills-institutiongroup-tab" controller="getinstitutiongroup" table-data="table_institutiongroup" data-bs-toggle="tab" data-toggle="pill" href="#v-pills-institutiongroup" role="tab" aria-controls="v-pills-institutiongroup" aria-selected="false">
											<div class=" d-md-block">
												<i class="fa fa-group"></i>
												<span class="hidden-sm-down ml-1">&nbsp Staff/Lecturer</span>
											</div>
										</a>
									</li>
									<li class="nav-item col-6" style="text-align:center">
										<a class="nav-link" onclick="openCity(event, 'student')" id="v-pills-institutiontype-tab" controller="getinstitutiontype" table-data="table_institutiontype" data-bs-toggle="tab" data-toggle="pill" href="#v-pills-institutiontype" role="tab" aria-controls="v-pills-institutiontype" aria-selected="false">
											<div class=" d-md-block ">
												<i class="fa fa-address-card-o"></i>
												<span class="hidden-sm-down ml-1">&nbsp Students</span>
											</div>
										</a>
									</li>
								</ul>

								<div id="London" class="tabcontent active">
									<form action="{{ route('login.custom') }}" method="post">
										@csrf
										<div class="form-group">
											<div class="input-group mb-3">
												<span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
												<input type="text" name="email" class="form-control ps-15 bg-transparent" placeholder="Email">
											</div>
											@if ($errors->has('email'))
												<span class="text-danger">{{ $errors->first('email') }}</span>
											@endif
										</div>
										<div class="form-group">
											<div class="input-group mb-3">
												<span class="input-group-text  bg-transparent"><i class="text-fade ti-lock"></i></span>
												<input type="password" name="password" class="form-control ps-15 bg-transparent" placeholder="Password">
											</div>
											@if ($errors->has('password'))
												<span class="text-danger">{{ $errors->first('password') }}</span>
											@endif
										</div>
										  <div class="row">
											<div class="col-6">
											  <div class="checkbox">
												<input type="checkbox" id="basic_checkbox_1" name="remember" value="1">
												<label for="basic_checkbox_1">Remember Me</label>
											  </div>
											</div>
											<!-- /.col -->
											<div class="col-6">
											 <div class="fog-pwd text-end">
												<a href="javascript:void(0)" class="text-primary fw-500 hover-primary"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
											  </div>
											</div>
											@if($errors->any())
											<div class="col-12">
												<div class="mb-5">
													<hr>
													<span class="text-danger">{{$errors->first('message')}}</span>
												</div>
											</div>
											@endif
											<!-- /.col -->
											<div class="col-12 text-center">
											  <button type="submit" class="btn btn-primary w-p100 mt-10">SIGN IN</button>
											</div>
											<!-- /.col -->
										  </div>
									</form>
								</div>
								
								<div id="student" class="tabcontent" hidden>
									<form action="{{ route('login.student.custom') }}" method="post">
										@csrf
										<div class="form-group">
											<div class="input-group mb-3">
												<span class="input-group-text bg-transparent"><i class="text-fade ti-user"></i></span>
												<input type="text" name="ic" class="form-control ps-15 bg-transparent" placeholder="Ic">
											</div>
											@if ($errors->has('ic'))
												<span class="text-danger">{{ $errors->first('ic') }}</span>
											@endif
										</div>
										<div class="form-group">
											<div class="input-group mb-3">
												<span class="input-group-text  bg-transparent"><i class="text-fade ti-lock"></i></span>
												<input type="password" name="password" class="form-control ps-15 bg-transparent" placeholder="Password">
											</div>
											@if ($errors->has('password'))
												<span class="text-danger">{{ $errors->first('password') }}</span>
											@endif
										</div>
										  <div class="row">
											<div class="col-6">
											  <div class="checkbox">
												<input type="checkbox" id="basic_checkbox_1" name="remember" value="1">
												<label for="basic_checkbox_1">Remember Me</label>
											  </div>
											</div>
											<!-- /.col -->
											<div class="col-6">
											 <div class="fog-pwd text-end">
												<a href="javascript:void(0)" class="text-primary fw-500 hover-primary"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
											  </div>
											</div>
											@if($errors->any())
											<div class="col-12">
												<div class="mb-5">
													<hr>
													<span class="text-danger">{{$errors->first('message')}}</span>
												</div>
											</div>
											@endif
											<!-- /.col -->
											<div class="col-12 text-center">
											  <button type="submit" class="btn btn-primary w-p100 mt-10">SIGN IN</button>
											</div>
											<!-- /.col -->
										  </div>
									</form>
								</div>
									
								<!--<div class="text-center">
									<p class="mt-15 mb-0 text-fade">Don't have an account? <a href="#" class="text-primary ms-5">Sign Up</a></p>
								</div>
								
								<div class="text-center">
								  <p class="mt-20 text-fade">- Sign With -</p>
								  <p class="gap-items-2 mb-0">
									  <a class="waves-effect waves-circle btn btn-social-icon btn-circle btn-facebook-light" href="#"><i class="fa fa-facebook"></i></a>
									  <a class="waves-effect waves-circle btn btn-social-icon btn-circle btn-twitter-light" href="#"><i class="fa fa-twitter"></i></a>
									  <a class="waves-effect waves-circle btn btn-social-icon btn-circle btn-instagram-light" href="#"><i class="fa fa-instagram"></i></a>
									</p>	
								</div>-->
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>

		function openCity(evt, cityName) {
		// Declare all variables
		var i, tabcontent, tablinks;

		// Get all elements with class="tabcontent" and hide them
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		// Get all elements with class="tablinks" and remove the class "active"
		tablinks = document.getElementsByClassName("nav-link");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}

		// Show the current tab, and add an "active" class to the button that opened the tab
		document.getElementById(cityName).style.display = "block";
		document.getElementById(cityName).hidden = false;
		evt.currentTarget.className += " active";
		}

	</script>


	<!-- Vendor JS -->
	<script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
	<script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/assets/icons/feather-icons/feather.min.js') }}"></script>	

</body>
</html>