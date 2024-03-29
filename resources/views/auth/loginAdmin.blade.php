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

	<style>
		#gear {
		  /* This transition applies to the transform property, making changes smooth over 0.5 seconds */
		  transition: transform 0.5s ease-in-out;
		  /* Ensures that the gear rotates around its center */
		  transform-origin: center;
		  /* Keeps the gear from moving on the page */
		  transform-box: fill-box;
		}
	  
		#gear:hover {
		  /* This is the actual rotation animation on hover */
		  transform: rotate(360deg);
		}

	
		@keyframes pencilHoverAnimation {
		
			25% {
				transform: translate(-1000px, 500px);
			}
			
		
		}

		@keyframes writeMotion {
		
		25%, 75% {
			transform: translate(-1000px, 500px) rotate(10deg); /* Pencil moves up to simulate writing */
		}
		75% {
			transform: translate(-1000px, 500px) rotate(-10deg); /* Pencil moves up to simulate writing */
		}
		}

		svg #pencil {
		/* Assuming the pencil is vertical before animation, we set the origin to the bottom center */
		transform-origin: center; 
		}

		svg:hover #pencil {
		/* On hover, apply the sequence of moving to the center, rotating, and writing */
		animation: pencilHoverAnimation 2s ease-in-out forwards, writeMotion 1s 0.5s infinite;
		}

	  </style>

</head>
@php
	Auth::logout();

	$usertype = ['Admin', 'Pendaftar', 'PendaftarAkademik', 'Kewangan', 'Bendahari', 'Others', 'Cooperation', 'UnitiResources'];
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
										<path id="gear" d="M1480 1052 l0 -58 -56 -24 c-31 -12 -76 -38 -100 -56 -24 -19 -49
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
										<path id="pencil" d="M1913 1593 c-53 -11 -53 -9 -53 -530 l0 -483 56 -113 c61 -123 79
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
										<div id="line"></div>	
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
										<g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M682 1848 c-9 -9 -12 -82 -12 -265 l0 -253 25 -12 c21 -9 28 -8 45 7
										19 17 20 31 20 232 l0 213 685 0 685 0 0 -415 0 -415 -497 -2 -498 -3 -3 -33
										c-3 -27 1 -34 23 -42 35 -13 1004 -14 1039 0 l26 10 0 483 c0 362 -3 486 -12
										495 -17 17 -1509 17 -1526 0z"/>
										<path d="M1417 1662 c-10 -10 -17 -32 -17 -49 0 -28 -4 -31 -37 -38 -53 -10
										-113 -45 -129 -77 -38 -73 1 -135 105 -165 l61 -18 0 -47 c0 -46 0 -47 -27
										-38 -51 15 -60 21 -71 46 -14 28 -66 34 -76 8 -21 -54 29 -113 117 -139 54
										-16 57 -18 57 -49 0 -37 15 -56 45 -56 30 0 45 19 45 56 0 31 3 33 57 49 77
										23 123 68 123 123 0 33 -6 45 -35 71 -19 16 -54 35 -77 41 -24 6 -49 13 -55
										15 -16 5 -18 95 -3 95 26 0 77 -30 93 -54 29 -44 77 -29 77 25 0 47 -66 100
										-143 114 -34 7 -37 10 -37 40 0 34 -21 65 -44 65 -7 0 -20 -8 -29 -18z m-17
										-212 c0 -44 -5 -47 -55 -26 -44 19 -44 33 -2 51 50 22 57 19 57 -25z m149
										-161 c42 -15 39 -34 -9 -53 -22 -9 -43 -16 -45 -16 -3 0 -5 18 -5 40 0 44 7
										47 59 29z"/>
										<path d="M1788 1669 c-21 -12 -24 -49 -6 -67 8 -8 50 -12 123 -12 113 0 135 6
										135 40 0 38 -32 50 -136 50 -54 0 -107 -5 -116 -11z"/>
										<path d="M965 1384 c-16 -8 -87 -71 -156 -140 -72 -71 -134 -124 -145 -124
										-38 0 -94 -22 -145 -56 l-52 -34 -41 19 c-92 44 -341 89 -379 69 -16 -8 -17
										-33 -15 -361 l3 -352 48 -3 c54 -4 125 15 217 56 l63 28 306 -48 c330 -53 429
										-57 536 -24 28 9 142 73 255 144 211 131 231 150 200 188 -15 17 -260 20 -438
										4 -129 -12 -160 -4 -205 49 -21 25 -27 43 -27 79 0 53 6 65 108 205 39 54 76
										114 81 132 33 120 -102 227 -214 169z m101 -82 c6 -4 16 -17 23 -29 15 -29 10
										-37 -82 -162 -85 -116 -106 -161 -107 -229 0 -110 64 -191 174 -223 19 -6 83
										-5 161 2 72 6 152 9 179 7 l50 -3 -129 -80 c-136 -83 -195 -104 -301 -105 -52
										0 -611 84 -621 94 -6 5 37 189 68 292 15 47 29 71 63 102 42 39 120 72 170 72
										14 0 69 48 156 135 134 134 160 151 196 127z m-766 -303 c41 -11 78 -23 83
										-28 12 -12 -17 -193 -51 -314 l-27 -99 -65 -27 c-36 -15 -77 -31 -92 -35 l-28
										-6 0 271 0 271 53 -7 c28 -4 86 -16 127 -26z"/>
										</g>										
										@elseif ($ut == 'Bendahari')
										<g transform="translate(0.000000,167.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1470 1613 c-41 -65 -47 -91 -26 -126 9 -16 15 -30 14 -31 -2 -1 -11
										-7 -20 -14 -14 -11 -23 -10 -55 6 -82 41 -153 35 -277 -24 -45 -21 -75 -29
										-80 -23 -16 15 -62 10 -79 -9 -21 -23 -22 -66 -1 -86 23 -24 71 -20 95 8 11
										13 53 39 92 57 137 63 247 47 260 -38 7 -44 37 -77 83 -93 59 -21 130 26 141
										93 13 85 123 101 260 38 39 -18 81 -44 92 -57 24 -28 72 -32 95 -8 22 21 20
										67 -2 87 -21 19 -63 22 -78 7 -7 -7 -34 2 -80 24 -124 59 -195 65 -277 24 -32
										-16 -41 -17 -55 -6 -9 7 -19 13 -21 15 -1 1 4 12 13 25 9 12 16 32 16 43 0 21
										-53 119 -70 130 -5 3 -24 -16 -40 -42z"/>
										<path d="M977 1228 c-9 -7 -66 -141 -125 -298 l-108 -285 -36 -5 -37 -5 17
										-35 c9 -19 39 -58 67 -86 153 -154 424 -116 528 74 l28 51 -38 3 -38 3 -108
										285 c-59 157 -113 291 -120 298 -10 10 -16 10 -30 0z m103 -345 c47 -124 87
										-230 88 -234 2 -5 -78 -9 -178 -9 -142 0 -181 3 -177 13 2 6 42 112 88 234 46
										123 86 223 89 223 3 0 43 -102 90 -227z"/>
										<path d="M2007 1228 c-9 -7 -65 -141 -124 -298 l-108 -285 -38 -3 -38 -3 28
										-51 c104 -190 375 -228 528 -74 28 28 58 67 67 86 l17 35 -37 5 -36 5 -108
										284 c-59 157 -113 291 -121 298 -10 11 -16 11 -30 1z m102 -341 c46 -122 86
										-228 88 -234 4 -10 -35 -13 -177 -13 -100 0 -180 4 -178 9 2 4 41 110 88 234
										47 125 87 227 90 227 3 0 43 -100 89 -223z"/>
										<path d="M1418 1153 c-36 -5 -37 -31 -3 -71 l25 -30 0 -376 0 -375 -29 -15
										c-40 -21 -79 -70 -91 -116 l-11 -40 -68 0 c-91 0 -121 -21 -121 -86 l0 -44
										385 0 385 0 0 44 c0 65 -30 86 -121 86 l-68 0 -11 40 c-12 46 -51 95 -91 116
										l-29 15 0 375 0 376 25 30 c37 43 33 64 -12 72 -40 6 -120 6 -165 -1z"/>
										</g>
										@elseif ($ut == 'Others')
										<g transform="translate(0.000000,244.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1259 2327 c-139 -39 -250 -159 -279 -299 l-11 -58 74 -72 c81 -79
										132 -169 143 -251 7 -52 19 -60 113 -77 251 -45 490 192 443 440 -35 190 -174
										316 -360 326 -45 2 -96 -1 -123 -9z"/>
										<path d="M598 1921 c-31 -10 -72 -28 -91 -40 -52 -32 -116 -106 -145 -169 -24
										-50 -27 -69 -27 -162 0 -93 3 -112 27 -162 71 -152 250 -247 410 -219 131 23
										243 106 296 219 24 51 27 69 27 162 0 99 -2 110 -33 172 -41 84 -120 158 -200
										189 -79 30 -190 35 -264 10z"/>
										<path d="M1197 1523 c-3 -10 -8 -39 -12 -65 -7 -55 -61 -167 -103 -218 l-30
										-35 77 -36 c102 -48 179 -114 239 -203 72 -107 93 -176 99 -323 l6 -123 40 0
										c111 0 359 54 465 100 l42 19 0 225 c0 126 -5 248 -11 278 -41 195 -211 356
										-409 388 -36 5 -140 10 -232 10 -150 0 -167 -2 -171 -17z"/>
										<path d="M475 1130 c-205 -33 -359 -175 -410 -376 -12 -48 -15 -110 -13 -294
										l3 -233 65 -19 c247 -72 549 -107 768 -88 182 16 358 56 450 104 l32 17 0 237
										c0 182 -4 252 -15 297 -41 157 -172 289 -335 340 -43 14 -102 18 -275 21 -121
										1 -242 -1 -270 -6z"/>
										</g>
										@elseif ($ut == 'Cooperation')
										<g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M482 1558 c-190 -338 -232 -415 -232 -429 0 -9 10 -25 22 -36 37 -32
										127 -83 148 -83 19 0 60 36 60 52 0 17 16 7 43 -27 26 -32 27 -39 17 -69 -6
										-19 -8 -46 -4 -60 8 -32 51 -76 75 -76 13 0 19 -7 19 -24 0 -34 34 -73 77 -87
										19 -6 49 -27 65 -45 24 -27 38 -34 67 -34 31 0 40 -5 49 -26 7 -14 28 -32 47
										-40 31 -12 39 -12 70 0 19 8 38 21 40 29 5 11 20 10 83 -7 96 -24 139 -18 191
										30 21 19 48 34 60 34 26 0 81 51 81 74 0 9 7 16 15 16 33 0 74 31 86 65 11 28
										18 35 40 35 41 0 81 52 78 101 -3 34 2 42 42 78 l46 40 22 -29 c12 -17 30 -30
										41 -30 21 0 150 75 162 94 4 6 8 18 8 27 0 10 -67 136 -149 280 -172 307 -160
										296 -272 229 -72 -43 -90 -70 -70 -108 6 -12 11 -23 11 -25 0 -14 -61 -12
										-142 5 -75 16 -118 19 -223 15 -140 -6 -186 -16 -226 -53 -23 -22 -29 -23 -78
										-14 -69 13 -126 47 -111 65 6 8 10 26 8 42 -2 22 -17 36 -73 70 -108 67 -111
										65 -193 -79z m179 25 l36 -24 -140 -248 -140 -249 -49 31 c-27 17 -51 34 -54
										39 -3 4 57 118 132 252 153 272 133 253 215 199z m1147 -204 c73 -130 132
										-240 132 -245 0 -5 -24 -23 -53 -40 l-54 -31 -136 243 c-75 133 -137 246 -137
										251 0 13 103 74 111 65 3 -4 65 -113 137 -243z m-440 80 c76 -17 106 -19 139
										-12 l43 10 94 -166 95 -166 -49 -48 -49 -48 -115 86 c-130 97 -240 166 -343
										219 -70 35 -73 35 -112 21 -22 -7 -57 -32 -78 -56 -57 -64 -153 -102 -153 -61
										0 21 121 201 144 214 55 31 258 34 384 7z m-612 -24 c26 -14 55 -25 65 -25 48
										0 49 -10 8 -72 -37 -59 -44 -79 -40 -123 6 -76 164 -47 243 45 51 58 66 58
										165 5 143 -76 418 -272 428 -306 9 -28 -12 -59 -40 -59 -13 0 -84 37 -159 81
										-132 79 -172 92 -164 52 2 -13 212 -151 253 -166 17 -6 -13 -56 -38 -62 -18
										-5 -52 10 -137 60 -113 66 -142 74 -148 43 -2 -12 27 -34 100 -77 57 -33 107
										-62 113 -64 6 -2 5 -14 -2 -30 -18 -38 -43 -35 -156 18 -103 48 -122 53 -130
										32 -9 -23 4 -33 91 -72 45 -21 82 -41 82 -44 0 -3 -10 -12 -22 -20 -26 -16
										-81 -16 -137 -1 -36 9 -40 13 -31 30 25 46 0 119 -47 139 -20 8 -29 20 -31 38
										-3 37 -29 74 -62 86 -19 7 -30 20 -34 39 -15 74 -103 104 -157 52 -27 -26 -27
										-26 -38 -6 -17 32 -74 47 -111 28 -30 -15 -31 -14 -65 22 -19 20 -35 41 -35
										46 0 10 180 336 186 336 1 0 24 -11 50 -25z m-68 -437 c19 -19 14 -48 -14 -85
										-26 -34 -51 -42 -72 -21 -18 18 -14 48 11 85 23 34 53 43 75 21z m174 -5 c10
										-9 18 -22 18 -29 0 -23 -134 -194 -151 -194 -21 0 -49 26 -49 45 0 21 132 195
										149 195 8 0 23 -7 33 -17z m92 -109 c26 -25 19 -46 -36 -122 -53 -72 -79 -87
										-106 -60 -20 20 -14 48 25 102 72 100 87 111 117 80z m84 -120 c12 -8 22 -22
										22 -31 0 -25 -70 -113 -90 -113 -17 0 -50 34 -50 51 0 11 79 109 88 109 4 0
										18 -7 30 -16z"/>
										</g>
										@elseif ($ut == 'UnitiResources')
										<g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)"
										fill="#000000" stroke="none">
										<path d="M1027 2176 c-80 -30 -157 -112 -178 -190 -16 -57 -6 -163 19 -211 50
										-98 145 -155 257 -155 83 0 141 23 198 78 66 63 82 104 82 207 0 80 -3 95 -27
										136 -30 51 -80 99 -131 126 -43 22 -170 28 -220 9z"/>
										<path d="M405 2011 c-113 -29 -179 -117 -179 -236 0 -143 92 -235 237 -235
										165 0 272 148 226 313 -13 47 -70 112 -122 138 -44 23 -118 32 -162 20z"/>
										<path d="M1730 2011 c-79 -26 -151 -93 -169 -158 -46 -165 61 -313 226 -313
										145 0 237 92 237 235 0 100 -42 173 -126 216 -42 21 -131 32 -168 20z"/>
										<path d="M674 1500 c-38 -15 -81 -68 -93 -114 -14 -58 -15 -537 0 -589 14 -51
										63 -104 107 -117 20 -5 63 -10 95 -10 l57 0 0 -249 c0 -241 1 -249 23 -282 12
										-18 38 -44 56 -56 32 -22 44 -23 206 -23 162 0 174 1 206 23 18 12 44 38 56
										56 22 33 23 41 23 282 l0 249 58 0 c81 0 126 15 161 55 48 55 52 90 49 388 -3
										302 -7 320 -71 368 l-32 24 -440 2 c-242 1 -449 -2 -461 -7z"/>
										<path d="M69 1427 c-65 -43 -69 -64 -69 -330 0 -201 3 -244 16 -274 24 -49 58
										-67 134 -73 l65 -5 5 -212 c5 -194 7 -214 26 -240 38 -52 76 -63 212 -63 134
										0 170 9 212 54 23 25 25 37 28 135 l4 108 -48 18 c-64 24 -127 84 -153 145
										-21 49 -22 62 -19 373 3 278 5 326 20 355 l16 32 -207 0 c-200 0 -209 -1 -242
										-23z"/>
										<path d="M1760 1408 c18 -40 20 -68 20 -358 0 -309 -1 -316 -24 -365 -29 -63
										-90 -119 -156 -142 l-52 -18 4 -107 c3 -97 5 -109 28 -134 42 -45 78 -54 212
										-54 136 0 174 11 212 63 19 26 21 46 26 240 l5 212 65 5 c76 6 110 24 134 73
										13 30 16 73 16 274 0 266 -4 287 -69 330 -33 22 -43 23 -237 23 l-203 0 19
										-42z"/>
										</g>
										@endif
										
										</svg>
									</a>
									<div class="p-3 items-align-center">
										@if ($ut == 'PendaftarAkademik')
										Pendaftar Akademik
										@elseif ($ut == 'UnitiResources')
										Uniti Resources
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