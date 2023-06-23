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


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
@php
	Auth::logout();

	$usertype = ['Admin', 'Pendaftar', 'PendaftarAkademik', 'Kewangan', 'Bendahari', 'Others', 'Cooperation'];
@endphp
	
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
									<img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" height="60em" width="auto" >
									<img src="{{ asset('assets/images/logo_ucms2.png')}}" height="30em" width="auto"  class="">
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

							<div class="row justify-content-center align-items-center" id="user-type">
								@foreach ($usertype as $ut)
								<div class="col-md-3 text-center mb-3 mt-3">
									<a href="#" onclick="openCity('{{ $ut }}')">
										<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="4em" height="4em" viewBox="0 0 233.000000 217.000000" preserveAspectRatio="xMidYMid meet">
										@if ($ut == 'Admin')
										<g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
										<path d="M810 2219 c-30 -5 -88 -25 -128 -45 -233 -114 -332 -395 -222 -632
										33 -71 121 -169 186 -207 38 -22 44 -30 44 -59 0 -33 -2 -34 -52 -46 -29 -7
										-91 -30 -137 -52 -243 -116 -361 -374 -361 -794 l0 -122 39 -35 c81 -74 280
										-120 630 -147 74 -5 182 -10 239 -10 l105 0 -25 19 c-32 26 -158 243 -165 285
										-7 43 18 99 56 127 27 20 31 29 31 69 0 40 -4 49 -31 69 -40 29 -63 84 -54
										128 10 56 137 265 174 287 39 22 97 24 135 4 26 -14 32 -13 67 6 57 31 52 54
										-21 98 -33 20 -96 46 -140 59 -79 22 -80 23 -80 55 0 27 7 36 49 63 59 38 128
										110 163 171 81 139 79 340 -5 482 -65 111 -206 206 -338 228 -72 11 -82 11
										-159 -1z"/>
										<path d="M1480 1052 l0 -58 -56 -24 c-31 -12 -76 -38 -100 -56 -24 -19 -49
										-34 -54 -34 -6 0 -28 11 -50 25 -22 14 -43 25 -48 25 -6 0 -92 -141 -109 -179
										-2 -4 22 -19 52 -36 40 -21 54 -34 49 -45 -12 -29 -16 -135 -6 -176 l9 -41
										-54 -32 c-51 -31 -53 -34 -40 -54 7 -12 31 -52 51 -89 21 -38 42 -68 48 -68 5
										0 29 12 53 26 48 27 47 28 111 -22 23 -17 65 -40 93 -50 l51 -18 0 -63 0 -63
										105 0 105 0 0 60 0 60 56 25 c31 14 79 41 106 61 l50 35 51 -29 52 -29 52 91
										c32 55 49 94 43 100 -5 5 -26 18 -46 30 l-38 21 0 123 -1 123 45 26 c25 14 47
										27 49 29 5 4 -100 184 -108 184 -3 0 -26 -11 -50 -25 -23 -14 -46 -25 -51 -25
										-4 0 -27 16 -51 35 -24 19 -70 44 -101 56 l-58 21 0 59 0 59 -105 0 -105 0 0
										-58z m192 -234 c50 -15 122 -78 151 -132 29 -56 31 -174 3 -230 -46 -92 -139
										-150 -241 -149 -72 0 -119 18 -175 69 -109 98 -119 252 -23 364 67 78 181 110
										285 78z"/>
										</g>
										@elseif ($ut == 'Pendaftar')
										<g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M292 2235 c-35 -15 -62 -63 -62 -110 0 -21 -5 -25 -30 -25 -17 0 -46
										-6 -65 -14 -68 -29 -65 4 -65 -799 0 -765 0 -758 44 -741 15 6 16 70 16 726 0
										477 3 726 10 739 10 18 29 19 693 19 518 0 686 -3 695 -12 9 -9 12 -239 12
										-975 l0 -963 -26 -10 c-15 -6 -280 -10 -683 -10 -551 0 -660 2 -677 14 -17 13
										-19 31 -24 193 l-5 178 -25 0 -25 0 -3 -187 -2 -187 29 -33 29 -33 696 -3
										c765 -3 739 -5 771 58 8 16 15 43 15 60 0 27 3 30 30 30 17 0 46 6 65 14 69
										29 65 -20 63 822 l-3 759 -25 0 -25 0 -3 -749 c-1 -533 -5 -753 -13 -763 -6
										-7 -27 -13 -45 -13 l-34 0 0 895 c0 994 3 942 -65 971 -28 12 -140 14 -645 14
										l-610 0 0 29 c0 63 -24 61 705 61 582 0 663 -2 683 -16 21 -14 22 -22 22 -163
										0 -155 7 -179 44 -165 14 5 16 28 16 170 l0 164 -29 32 -29 33 -694 2 c-585 2
										-698 0 -726 -12z"/>
										<path d="M286 1899 l-26 -20 0 -324 c0 -303 1 -324 19 -346 l19 -24 327 0 327
										0 19 24 c18 22 19 43 19 346 l0 324 -26 20 c-26 20 -38 21 -339 21 -301 0
										-313 -1 -339 -21z m642 -346 l2 -303 -305 0 -305 0 0 298 c0 164 3 302 7 305
										4 4 140 6 302 5 l296 -3 3 -302z"/>
										<path d="M555 1781 c-53 -32 -68 -58 -73 -119 -3 -48 0 -63 21 -93 l24 -35
										-23 -27 c-42 -49 -58 -155 -30 -193 12 -15 18 -17 33 -8 14 8 19 28 23 78 4
										59 9 71 33 92 61 53 157 10 157 -70 0 -35 -1 -36 -38 -36 -41 0 -62 -14 -62
										-41 0 -23 20 -29 90 -29 50 0 61 3 71 22 23 42 -5 164 -45 197 -13 11 -11 17
										11 49 33 50 30 138 -8 178 -52 56 -127 70 -184 35z m133 -77 c48 -54 10 -134
										-63 -134 -69 0 -108 73 -69 128 33 48 92 50 132 6z"/>
										<path d="M1072 1748 c-16 -16 -15 -23 4 -42 22 -22 300 -23 330 -2 15 12 17
										18 8 33 -10 16 -29 18 -170 21 -116 2 -163 -1 -172 -10z"/>
										<path d="M1913 1593 c-53 -11 -53 -9 -53 -530 l0 -483 56 -113 c61 -123 79
										-147 104 -147 27 0 54 38 109 153 l51 107 0 484 c0 582 14 529 -141 532 -57 1
										-114 0 -126 -3z m207 -133 l0 -70 -100 0 -100 0 0 70 0 70 100 0 100 0 0 -70z
										m0 -490 l0 -350 -100 0 -100 0 0 350 0 350 100 0 100 0 0 -350z m-30 -425 c0
										-10 -65 -135 -70 -135 -5 0 -70 125 -70 135 0 3 32 5 70 5 39 0 70 -2 70 -5z"/>
										<path d="M1070 1555 c-10 -12 -10 -18 0 -30 11 -13 40 -15 179 -13 l166 3 0
										25 0 25 -166 3 c-139 2 -168 0 -179 -13z"/>
										<path d="M1070 1355 c-10 -12 -10 -18 0 -30 10 -12 43 -15 176 -15 154 0 163
										1 169 20 3 11 3 24 0 30 -4 6 -70 10 -169 10 -133 0 -166 -3 -176 -15z"/>
										<path d="M264 1035 c-4 -8 -4 -22 0 -30 8 -23 1127 -23 1146 0 7 8 10 22 6 30
										-5 13 -76 15 -576 15 -498 0 -571 -2 -576 -15z"/>
										<path d="M273 863 c-23 -9 -15 -52 10 -58 12 -3 269 -4 571 -3 499 3 550 4
										559 20 8 12 7 21 -2 32 -12 14 -74 16 -570 15 -306 0 -562 -3 -568 -6z"/>
										<path d="M264 665 c-4 -8 -4 -22 0 -30 5 -13 78 -15 576 -15 500 0 571 2 576
										15 4 8 1 22 -6 30 -19 23 -1138 23 -1146 0z"/>
										<path d="M579 519 l-24 -31 -129 1 c-171 3 -166 7 -166 -154 0 -159 -4 -155
										157 -155 154 0 153 -1 153 137 0 94 1 101 26 124 41 38 65 76 58 93 -10 27
										-49 19 -75 -15z m-129 -136 c-24 -27 -46 -52 -50 -56 -4 -4 -23 17 -43 48
										l-37 55 86 0 87 0 -43 -47z m60 -93 l0 -50 -51 0 -51 0 44 50 c24 27 46 49 51
										50 4 0 7 -22 7 -50z m-150 -41 c0 -5 -9 -9 -20 -9 -16 0 -20 7 -20 33 l1 32
										19 -24 c11 -13 20 -27 20 -32z"/>
										<path d="M667 363 c-13 -12 -7 -51 9 -57 9 -3 172 -6 363 -6 287 0 351 3 367
										14 15 12 17 18 8 33 -10 17 -36 18 -376 21 -200 1 -367 -1 -371 -5z"/>
										</g>
										@elseif ($ut == 'PendaftarAkademik')
										<g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1092 1905 c-74 -24 -136 -46 -139 -49 -3 -2 9 -26 27 -51 39 -56 66
										-131 75 -206 l7 -55 93 -31 93 -31 373 114 c205 63 375 116 377 119 3 3 3 7 0
										10 -9 9 -737 225 -755 224 -10 -1 -78 -20 -151 -44z"/>
										<path d="M533 1926 c-108 -35 -205 -125 -244 -224 -17 -43 -23 -78 -23 -143 0
										-77 3 -93 32 -151 38 -78 108 -150 182 -186 49 -24 67 -27 155 -27 88 0 107 3
										157 27 295 138 293 553 -2 686 -68 30 -193 39 -257 18z m233 -111 c106 -52
										161 -151 152 -276 -14 -187 -202 -306 -380 -239 -147 55 -222 233 -159 375 67
										152 237 213 387 140z"/>
										<path d="M1492 1514 l-244 -75 -91 30 c-51 17 -95 31 -100 31 -4 0 -7 -9 -7
										-20 0 -16 7 -20 33 -20 17 0 63 -11 101 -24 68 -23 68 -23 127 -5 119 37 324
										48 425 23 24 -6 24 -5 24 65 0 50 -4 71 -12 70 -7 0 -123 -34 -256 -75z"/>
										<path d="M1459 1420 c-36 -5 -93 -16 -127 -25 l-62 -18 0 -358 c0 -198 1 -359
										3 -359 1 0 38 7 82 15 128 23 377 17 491 -11 l24 -6 0 360 0 359 -60 17 c-62
										18 -204 37 -255 35 -16 -1 -60 -5 -96 -9z"/>
										<path d="M1011 1376 c-50 -96 -136 -172 -234 -206 l-52 -18 -3 -242 c-2 -214
										-1 -242 13 -236 8 3 55 10 103 16 92 12 189 7 312 -16 41 -8 76 -14 78 -14 1
										0 2 162 2 360 l0 359 -52 16 c-29 9 -73 18 -98 21 -45 6 -45 6 -69 -40z"/>
										<path d="M1910 956 c0 -191 -4 -337 -9 -340 -5 -4 -40 2 -78 11 -88 22 -386
										25 -485 4 -65 -13 -68 -15 -68 -42 l0 -29 350 0 350 0 0 365 0 365 -30 0 -30
										0 0 -334z"/>
										<path d="M602 899 c3 -227 5 -253 21 -263 12 -8 22 -8 35 0 15 10 17 34 17
										260 l0 249 -38 3 -37 3 2 -252z"/>
										<path d="M875 652 c-27 -1 -74 -7 -102 -13 -52 -10 -53 -11 -53 -45 l0 -34
										255 0 255 0 0 29 c0 29 -1 29 -92 45 -101 17 -185 23 -263 18z"/>
										</g>
										@elseif ($ut == 'Kewangan')
										<g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1092 1905 c-74 -24 -136 -46 -139 -49 -3 -2 9 -26 27 -51 39 -56 66
										-131 75 -206 l7 -55 93 -31 93 -31 373 114 c205 63 375 116 377 119 3 3 3 7 0
										10 -9 9 -737 225 -755 224 -10 -1 -78 -20 -151 -44z"/>
										<path d="M533 1926 c-108 -35 -205 -125 -244 -224 -17 -43 -23 -78 -23 -143 0
										-77 3 -93 32 -151 38 -78 108 -150 182 -186 49 -24 67 -27 155 -27 88 0 107 3
										157 27 295 138 293 553 -2 686 -68 30 -193 39 -257 18z m233 -111 c106 -52
										161 -151 152 -276 -14 -187 -202 -306 -380 -239 -147 55 -222 233 -159 375 67
										152 237 213 387 140z"/>
										<path d="M1492 1514 l-244 -75 -91 30 c-51 17 -95 31 -100 31 -4 0 -7 -9 -7
										-20 0 -16 7 -20 33 -20 17 0 63 -11 101 -24 68 -23 68 -23 127 -5 119 37 324
										48 425 23 24 -6 24 -5 24 65 0 50 -4 71 -12 70 -7 0 -123 -34 -256 -75z"/>
										<path d="M1459 1420 c-36 -5 -93 -16 -127 -25 l-62 -18 0 -358 c0 -198 1 -359
										3 -359 1 0 38 7 82 15 128 23 377 17 491 -11 l24 -6 0 360 0 359 -60 17 c-62
										18 -204 37 -255 35 -16 -1 -60 -5 -96 -9z"/>
										<path d="M1011 1376 c-50 -96 -136 -172 -234 -206 l-52 -18 -3 -242 c-2 -214
										-1 -242 13 -236 8 3 55 10 103 16 92 12 189 7 312 -16 41 -8 76 -14 78 -14 1
										0 2 162 2 360 l0 359 -52 16 c-29 9 -73 18 -98 21 -45 6 -45 6 -69 -40z"/>
										<path d="M1910 956 c0 -191 -4 -337 -9 -340 -5 -4 -40 2 -78 11 -88 22 -386
										25 -485 4 -65 -13 -68 -15 -68 -42 l0 -29 350 0 350 0 0 365 0 365 -30 0 -30
										0 0 -334z"/>
										<path d="M602 899 c3 -227 5 -253 21 -263 12 -8 22 -8 35 0 15 10 17 34 17
										260 l0 249 -38 3 -37 3 2 -252z"/>
										<path d="M875 652 c-27 -1 -74 -7 -102 -13 -52 -10 -53 -11 -53 -45 l0 -34
										255 0 255 0 0 29 c0 29 -1 29 -92 45 -101 17 -185 23 -263 18z"/>
										</g>
										@elseif ($ut == 'Bendahari')
										<g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1092 1905 c-74 -24 -136 -46 -139 -49 -3 -2 9 -26 27 -51 39 -56 66
										-131 75 -206 l7 -55 93 -31 93 -31 373 114 c205 63 375 116 377 119 3 3 3 7 0
										10 -9 9 -737 225 -755 224 -10 -1 -78 -20 -151 -44z"/>
										<path d="M533 1926 c-108 -35 -205 -125 -244 -224 -17 -43 -23 -78 -23 -143 0
										-77 3 -93 32 -151 38 -78 108 -150 182 -186 49 -24 67 -27 155 -27 88 0 107 3
										157 27 295 138 293 553 -2 686 -68 30 -193 39 -257 18z m233 -111 c106 -52
										161 -151 152 -276 -14 -187 -202 -306 -380 -239 -147 55 -222 233 -159 375 67
										152 237 213 387 140z"/>
										<path d="M1492 1514 l-244 -75 -91 30 c-51 17 -95 31 -100 31 -4 0 -7 -9 -7
										-20 0 -16 7 -20 33 -20 17 0 63 -11 101 -24 68 -23 68 -23 127 -5 119 37 324
										48 425 23 24 -6 24 -5 24 65 0 50 -4 71 -12 70 -7 0 -123 -34 -256 -75z"/>
										<path d="M1459 1420 c-36 -5 -93 -16 -127 -25 l-62 -18 0 -358 c0 -198 1 -359
										3 -359 1 0 38 7 82 15 128 23 377 17 491 -11 l24 -6 0 360 0 359 -60 17 c-62
										18 -204 37 -255 35 -16 -1 -60 -5 -96 -9z"/>
										<path d="M1011 1376 c-50 -96 -136 -172 -234 -206 l-52 -18 -3 -242 c-2 -214
										-1 -242 13 -236 8 3 55 10 103 16 92 12 189 7 312 -16 41 -8 76 -14 78 -14 1
										0 2 162 2 360 l0 359 -52 16 c-29 9 -73 18 -98 21 -45 6 -45 6 -69 -40z"/>
										<path d="M1910 956 c0 -191 -4 -337 -9 -340 -5 -4 -40 2 -78 11 -88 22 -386
										25 -485 4 -65 -13 -68 -15 -68 -42 l0 -29 350 0 350 0 0 365 0 365 -30 0 -30
										0 0 -334z"/>
										<path d="M602 899 c3 -227 5 -253 21 -263 12 -8 22 -8 35 0 15 10 17 34 17
										260 l0 249 -38 3 -37 3 2 -252z"/>
										<path d="M875 652 c-27 -1 -74 -7 -102 -13 -52 -10 -53 -11 -53 -45 l0 -34
										255 0 255 0 0 29 c0 29 -1 29 -92 45 -101 17 -185 23 -263 18z"/>
										</g>
										@elseif ($ut == 'Others')
										<g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1092 1905 c-74 -24 -136 -46 -139 -49 -3 -2 9 -26 27 -51 39 -56 66
										-131 75 -206 l7 -55 93 -31 93 -31 373 114 c205 63 375 116 377 119 3 3 3 7 0
										10 -9 9 -737 225 -755 224 -10 -1 -78 -20 -151 -44z"/>
										<path d="M533 1926 c-108 -35 -205 -125 -244 -224 -17 -43 -23 -78 -23 -143 0
										-77 3 -93 32 -151 38 -78 108 -150 182 -186 49 -24 67 -27 155 -27 88 0 107 3
										157 27 295 138 293 553 -2 686 -68 30 -193 39 -257 18z m233 -111 c106 -52
										161 -151 152 -276 -14 -187 -202 -306 -380 -239 -147 55 -222 233 -159 375 67
										152 237 213 387 140z"/>
										<path d="M1492 1514 l-244 -75 -91 30 c-51 17 -95 31 -100 31 -4 0 -7 -9 -7
										-20 0 -16 7 -20 33 -20 17 0 63 -11 101 -24 68 -23 68 -23 127 -5 119 37 324
										48 425 23 24 -6 24 -5 24 65 0 50 -4 71 -12 70 -7 0 -123 -34 -256 -75z"/>
										<path d="M1459 1420 c-36 -5 -93 -16 -127 -25 l-62 -18 0 -358 c0 -198 1 -359
										3 -359 1 0 38 7 82 15 128 23 377 17 491 -11 l24 -6 0 360 0 359 -60 17 c-62
										18 -204 37 -255 35 -16 -1 -60 -5 -96 -9z"/>
										<path d="M1011 1376 c-50 -96 -136 -172 -234 -206 l-52 -18 -3 -242 c-2 -214
										-1 -242 13 -236 8 3 55 10 103 16 92 12 189 7 312 -16 41 -8 76 -14 78 -14 1
										0 2 162 2 360 l0 359 -52 16 c-29 9 -73 18 -98 21 -45 6 -45 6 -69 -40z"/>
										<path d="M1910 956 c0 -191 -4 -337 -9 -340 -5 -4 -40 2 -78 11 -88 22 -386
										25 -485 4 -65 -13 -68 -15 -68 -42 l0 -29 350 0 350 0 0 365 0 365 -30 0 -30
										0 0 -334z"/>
										<path d="M602 899 c3 -227 5 -253 21 -263 12 -8 22 -8 35 0 15 10 17 34 17
										260 l0 249 -38 3 -37 3 2 -252z"/>
										<path d="M875 652 c-27 -1 -74 -7 -102 -13 -52 -10 -53 -11 -53 -45 l0 -34
										255 0 255 0 0 29 c0 29 -1 29 -92 45 -101 17 -185 23 -263 18z"/>
										</g>
										@elseif ($ut == 'Cooperation')
										<g transform="translate(0.000000,250.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1092 1905 c-74 -24 -136 -46 -139 -49 -3 -2 9 -26 27 -51 39 -56 66
										-131 75 -206 l7 -55 93 -31 93 -31 373 114 c205 63 375 116 377 119 3 3 3 7 0
										10 -9 9 -737 225 -755 224 -10 -1 -78 -20 -151 -44z"/>
										<path d="M533 1926 c-108 -35 -205 -125 -244 -224 -17 -43 -23 -78 -23 -143 0
										-77 3 -93 32 -151 38 -78 108 -150 182 -186 49 -24 67 -27 155 -27 88 0 107 3
										157 27 295 138 293 553 -2 686 -68 30 -193 39 -257 18z m233 -111 c106 -52
										161 -151 152 -276 -14 -187 -202 -306 -380 -239 -147 55 -222 233 -159 375 67
										152 237 213 387 140z"/>
										<path d="M1492 1514 l-244 -75 -91 30 c-51 17 -95 31 -100 31 -4 0 -7 -9 -7
										-20 0 -16 7 -20 33 -20 17 0 63 -11 101 -24 68 -23 68 -23 127 -5 119 37 324
										48 425 23 24 -6 24 -5 24 65 0 50 -4 71 -12 70 -7 0 -123 -34 -256 -75z"/>
										<path d="M1459 1420 c-36 -5 -93 -16 -127 -25 l-62 -18 0 -358 c0 -198 1 -359
										3 -359 1 0 38 7 82 15 128 23 377 17 491 -11 l24 -6 0 360 0 359 -60 17 c-62
										18 -204 37 -255 35 -16 -1 -60 -5 -96 -9z"/>
										<path d="M1011 1376 c-50 -96 -136 -172 -234 -206 l-52 -18 -3 -242 c-2 -214
										-1 -242 13 -236 8 3 55 10 103 16 92 12 189 7 312 -16 41 -8 76 -14 78 -14 1
										0 2 162 2 360 l0 359 -52 16 c-29 9 -73 18 -98 21 -45 6 -45 6 -69 -40z"/>
										<path d="M1910 956 c0 -191 -4 -337 -9 -340 -5 -4 -40 2 -78 11 -88 22 -386
										25 -485 4 -65 -13 -68 -15 -68 -42 l0 -29 350 0 350 0 0 365 0 365 -30 0 -30
										0 0 -334z"/>
										<path d="M602 899 c3 -227 5 -253 21 -263 12 -8 22 -8 35 0 15 10 17 34 17
										260 l0 249 -38 3 -37 3 2 -252z"/>
										<path d="M875 652 c-27 -1 -74 -7 -102 -13 -52 -10 -53 -11 -53 -45 l0 -34
										255 0 255 0 0 29 c0 29 -1 29 -92 45 -101 17 -185 23 -263 18z"/>
										</g>
										@endif
										
										</svg>
									</a>
									<div class="p-3 items-align-center">
										@if ($ut == 'PendaftarAkademik')
										Pendaftar Akademik
										@else
										{{ $ut }}
										@endif
									</div>

									<form method="post" name="form-rename" id="form-rename"> 
										<div id="rename-material-" class="collapse input-group mb-3" data-bs-parent="#material-directory">
											<button class="btn btn-link btn-circle btn-xs " data-bs-toggle="collapse" data-bs-target="#rename-material-" aria-expanded="false" aria-controls="rename-material-">
												<i class="mdi mdi-close text-dark"></i>
											</button> 
											<input type="text" class="form-control" id="test-"> 
											<button class="btn btn-link btn-circle btn-xs" type="button">
												<i class="fa fa-save text-dark"></i>
											</button>  
										</div>
									</form>
								</div>
								@endforeach
							</div>
							
							<div class="p-40" id="login-form" hidden>
								<div class="col-2 text-center mb-3">
									<button type="submit" class="btn btn-info w-p100 mt-10" onclick="back()">back</button>
								  </div>
								<div id="London" class="tabcontent active">
									<form action="{{ route('loginAdmin.custom') }}" method="post">
										@csrf
										<div class="form-group" hidden>
											<div class="input-group mb-3">
												<input type="text" name="usertypes" id="usertypes" class="form-control ps-15 bg-transparent">
											</div>
										</div>
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
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		var student = "{{ (Session::get('StudInfo') != null) ? Session::get('StudInfo') : '' }}"

		$(document).ready( function () {
			if(student != '')
			{
				location.href = "/student";
			}
		} );

		function openCity(usertype) {

		document.getElementById('user-type').hidden = true;

		document.getElementById('login-form').hidden = false;

		$('#usertypes').val(usertype);

		}

		function back()
		{

			document.getElementById('login-form').hidden = true;

			document.getElementById('user-type').hidden = false;

		}

		

	</script>


	<!-- Vendor JS -->
	<script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
	<script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/assets/icons/feather-icons/feather.min.js') }}"></script>	

</body>
</html>