<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
    <title>EduHub - @yield('title')</title>
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
     <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<!-- Style-->  
	<link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
	{{-- <link rel="stylesheet" media="screen, print" href="{{ asset('assets/src/css/datagrid/datatables/datatables.bundle.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ asset('assets/assets/vendor_components/datatable/datatables.css') }}"> --}}
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/css-skeletons@1.0.3/css/css-skeletons.min.css"/> --}}
	<link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />

  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
  </head>

<style>
	
	.unity {
		max-width:170%;
		margin-left:-9px;
		margin-right:-5px;
	}
	.eduhub {
		margin-left:15px;
	}
	.treeview menu-open {
		display: none;
	}
	.light-skin .sidebar-menu > li > .treeview-menu{
		padding-left:6%;
	}

	#treeview-menu-visible {
		margin-left:14px;
	}

	.main-sidebar .sidebar-menu .active{
		color:#019ff8 !important;
		/* color:blue !important; */
	}

	/* custom progres bar */
	.custom-progress {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height:0.3em !important;
		z-index: 9999;
		background-color: #F2F2F2;
	}
	.custom-progress-bar { 
		background-color: #819FF7; 
		background-color:blue;
		width:0%; 
		height:0.3em;
		border-radius: 3px; 
	}
	.custom-percent { 
		position:absolute; 
		display:inline-block; 
		top:3px; 
		left:48%; 
	}

	/* bootstrap select */
	.bootstrap-select .hidden{
		display: none;
	}
	.bootstrap-select div.dropdown-menu.open {
        overflow: hidden;
    }
    .bootstrap-select ul.dropdown-menu.inner{
        max-height: 20em !important;
        overflow-y: auto;
    }

	/* cke editor */
	.ck-editor__editable_inline {
		min-height: 20em;
	}

	.cke_chrome{
      border:1px solid #eee;
      box-shadow: 0 0 0 #eee;
	}

		div.dt-buttons {
	float: right;
	margin-left:10px;
	}

	.modal-backdrop
	{
		opacity:0.5 !important;
	}

	.multinav-scroll {
		height: 97%; /* or whatever height you prefer */
		overflow-y: auto; /* Enables vertical scrolling */
		overflow-x: hidden; /* Hides horizontal scrollbar */
	}

	.spinner-overlay {
  display: none; /* Hidden by default */
  position: fixed; /* Sit on top of the page content */
  width: 100%; /* Full width (cover the whole page) */
  height: 100%; /* Full height (cover the whole page) */
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5); /* Black background with opacity */
  z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
  cursor: pointer; /* Add a pointer on hover */
}

.spinner {
  margin: auto;
  position: absolute;
  top: 0; bottom: 0; left: 0; right: 0;
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
	
.count-circle {
	background-color: red;      /* Red background */
	color: white;               /* White text */
	border-radius: 50%;         /* Makes the element a circle */
	padding: 5px 10px;          /* Padding to make the circle look good */
	font-size: 12px;            /* Font size */
	min-width: 24px;            /* Minimum width to keep a circular shape */
	text-align: center;         /* Center the text */
	line-height: 1;             /* Align the text vertically */
	position: absolute;         /* Position to adjust its placement */
	top: -5px;                  /* Adjust top positioning */
	right: -10px;               /* Adjust right positioning */
}

li a {
	position: relative; /* Parent positioning so the count is correctly placed */
}
</style>

<div id="loading-spinner" class="spinner-overlay">
    <div class="spinner"></div>
  </div>


<div class='custom-progress'>
	<div class='custom-progress-bar' id='custom-progress-bar1'></div>
	<div class='custom-percent' id='custom-percent1'></div>
	<input type="hidden" id="custom_progress_width" value="0">
</div>

@php
	$theme = DB::table('user_setting')->where('user_ic', Auth::user()->ic)->first();
@endphp
<body class="hold-transition {{ (empty($theme->theme) ? 'light' : $theme->theme) }}-skin sidebar-mini theme-primary fixed sidebar-collapse">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <header class="main-header">
	<div class="d-flex align-items-center logo-box justify-content-start">	
		<!-- Logo -->
		<a href="{{ url('finance') }}" class="logo">
		  <!-- logo-->
		  <div class="logo-mini w-30">
			  <span class="light-logo"><img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="logo" class="unity"></span>
			  <span class="dark-logo"><img src="{{ asset('assets/images/logo-letter-white.png') }}" alt="logo"></span>
		  </div>
		  <div class="logo-lg">
			  <span class="light-logo"><img src="{{ asset('assets/images/logo_ucms2.png') }}" alt="logo" class="eduhub"></span>
			  <span class="dark-logo"><img src="{{ asset('assets/images/logo_ucms2.png') }}" alt="logo" class="eduhub"></span>
		  </div>
		</a>	
	</div>   
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <div class="app-menu">
		<ul class="header-megamenu nav">
			<li class="btn-group nav-item">
				<a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light ms-0" 
				data-toggle-status="true" data-toggle="push-menu" role="button">
					<i data-feather="menu"></i>
			    </a>
			</li>
			<li class="btn-group d-lg-inline-flex d-none">
				<div class="app-menu">
					<div class="search-bx mx-5">
						<form>
							<div class="input-group">
							  <input type="search" class="form-control" placeholder="Search">
							  <div class="input-group-append">
								<button class="btn" type="submit" id="button-addon3"><i class="icon-Search"><span class="path1"></span><span class="path2"></span></i></button>
							  </div>
							</div>
						</form>
					</div>
				</div>
			</li>
		</ul> 
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">
			<li class="btn-group d-md-inline-flex d-none">
              <a href="javascript:void(0)" title="skin Change" class="waves-effect skin-toggle waves-light">
			  	<label class="switch">
					<input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
					<span class="switch-on" onclick="clickFn('dark')"><i data-feather="moon"></i></span>
					<span class="switch-off" onclick="clickFn('light')"><i data-feather="sun"></i></span>
				</label>
			  </a>				
            </li>
			<li class="dropdown notifications-menu btn-group">
				<a href="#" class="waves-effect waves-light btn-primary-light svg-bt-icon bg-transparent" data-bs-toggle="dropdown" title="Notifications">
					<i data-feather="bell"></i>
					<div class="pulse-wave"></div>
			    </a>
				<ul class="dropdown-menu animated bounceIn">
				  <li class="header">
					<div class="p-20">
						<div class="flexbox">
							<div>
								<h4 class="mb-0 mt-0">Notifications</h4>
							</div>
							<div>
								<a href="#" class="text-danger">Clear All</a>
							</div>
						</div>
					</div>
				  </li>
				  <li>
					<!-- inner menu: contains the actual data -->
					<ul class="menu sm-scrol">
					  <li>
						<a href="#">
						  <i class="fa fa-users text-info"></i> Curabitur id eros quis nunc suscipit blandit.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-warning text-warning"></i> Duis malesuada justo eu sapien elementum, in semper diam posuere.
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-users text-danger"></i> Donec at nisi sit amet tortor commodo porttitor pretium a erat.
						</a>
					  </li>
					</ul>
				  </li>
				  <li class="footer">
					  <a href="#">View all</a>
				  </li>
				</ul>
			</li>
			
			
			<!-- User Account-->
			<li class="dropdown user user-menu">
				<a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
					<div class="d-flex pt-1 align-items-center">
						<div class="text-end me-10">
							<p class="pt-5 fs-14 mb-0 fw-700"></p>
							<small class="fs-10 mb-0 text-uppercase text-mute"></small>
						</div>
						<img src="{{ (Auth::user()->image != null) ? Storage::disk('linode')->url(Auth::user()->image) : asset('assets/images/avatar/avatar-13.png') }}" class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
					</div>
				</a>
			</li>		  
			
        </ul>
      </div>
    </nav>
  </header>
  
  <aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative"> 
	  	<div class="multinav">
		  <div class="multinav-scroll" style="height: 97%;">	
			  <!-- sidebar menu-->
			  <ul class="sidebar-menu" data-widget="tree">
			  <li>
				<a href="{{ route('finance.dashboard') }}"><i data-feather="home"></i><span>Dashboard</span></a>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="user-check"></i><span>Student Information</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('pendaftar.student.edit') }}" class="{{ (route('pendaftar.student.edit') == Request::url()) ? 'active' : ''}}">Student Info</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="users"></i><span>Student</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('pendaftar') }}" class="{{ (route('pendaftar') == Request::url()) ? 'active' : ''}}">Student List</a></li>
					<li><a href="{{ route('pendaftar.student.status') }}" class="{{ (route('pendaftar.student.status') == Request::url()) ? 'active' : ''}}">Update Status</a></li>
					<li><a href="{{ route('finance.student.blockList') }}" class="{{ (route('finance.student.blockList') == Request::url()) ? 'active' : ''}}">Blocked List</a></li>
					<li><a href="{{ route('pendaftar.student.studentreport') }}" class="{{ (route('pendaftar.student.studentreport') == Request::url()) ? 'active' : ''}}">Student Report</a></li>
					<li><a href="{{ route('pendaftar.student.internationalReport') }}" class="{{ (route('pendaftar.student.internationalReport') == Request::url()) ? 'active' : ''}}">International Student Report</a></li>
					<li><a href="{{ route('pendaftar_akademik.leave') }}" class="{{ (route('pendaftar_akademik.leave') == Request::url()) ? 'active' : ''}}">Student Leave</a></li>
					<li><a href="{{ route('pendaftar_akademik.semester') }}" class="{{ (route('pendaftar_akademik.semester') == Request::url()) ? 'active' : ''}}">Student Semester</a></li>
					<li><a href="{{ route('pendaftar.student.result') }}" class="{{ (route('pendaftar.student.result') == Request::url()) ? 'active' : ''}}">Student Result</a></li>
					<li><a href="{{ route('pendaftar.student.reportR') }}" class="{{ (route('pendaftar.student.reportR') == Request::url()) ? 'active' : ''}}">Student Report R/R1/R2</a></li>
					<li><a href="{{ route('all.student.announcements') }}" class="{{ (route('all.student.announcements') == Request::url()) ? 'active' : ''}}">Annoucement</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="folder"></i><span>Claims</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance') }}" class="{{ (route('finance') == Request::url()) ? 'active' : ''}}">Claims Library</a></li>
					<li><a href="{{ route('claimpackage') }}" class="{{ (route('claimpackage') == Request::url()) ? 'active' : ''}}">Claims Package</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="package"></i><span>Package</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.package.incentive') }}" class="{{ (route('finance.package.incentive') == Request::url()) ? 'active' : ''}}">Incentives</a></li>
					<li><a href="{{ route('finance.package.tabungkhas') }}" class="{{ (route('finance.package.tabungkhas') == Request::url()) ? 'active' : ''}}">Tabung Khas</a></li>
					<li><a href="{{ route('finance.package.insentifkhas') }}" class="{{ (route('finance.package.insentifkhas') == Request::url()) ? 'active' : ''}}">Insentif Khas</a></li>
					<li><a href="{{ route('finance.package.payment') }}" class="{{ (route('finance.package.payment') == Request::url()) ? 'active' : ''}}">Payment</a></li>
					<li><a href="{{ route('finance.package.voucher') }}" class="{{ (route('finance.package.voucher') == Request::url()) ? 'active' : ''}}">Voucher</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="dollar-sign"></i><span>Payment</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.payment') }}" class="{{ (route('finance.payment') == Request::url()) ? 'active' : ''}}">Pre-Registration</a></li>
					<li><a href="{{ route('finance.payment.claim') }}" class="{{ (route('finance.payment.claim') == Request::url()) ? 'active' : ''}}">Claims</a></li>
					<li><a href="{{ route('finance.payment.tuition') }}" class="{{ (route('finance.payment.tuition') == Request::url()) ? 'active' : ''}}">Daily Payment</a></li>
					<li><a href="{{ route('finance.payment.other') }}" class="{{ (route('finance.payment.other') == Request::url()) ? 'active' : ''}}">Other Payment</a></li>
					<li><a href="{{ route('finance.payment.incentive') }}" class="{{ (route('finance.payment.incentive') == Request::url()) ? 'active' : ''}}">Incentive Payment</a></li>
					<li><a href="{{ route('finance.payment.refund') }}" class="{{ (route('finance.payment.refund') == Request::url()) ? 'active' : ''}}">Refund</a></li>
					<li><a href="{{ route('finance.payment.KWSPrefund') }}" class="{{ (route('finance.payment.KWSPrefund') == Request::url()) ? 'active' : ''}}">KWSP Refund / Discount</a></li>
					<li><a href="{{ route('finance.payment.cancel') }}" class="{{ (route('finance.payment.cancel') == Request::url()) ? 'active' : ''}}">Cancel Transaction</a></li>
					<li><a href="{{ route('treasurer.payment.credit') }}" class="{{ (route('treasurer.payment.credit') == Request::url()) ? 'active' : ''}}">Credit Note</a></li>
					<li><a href="{{ route('treasurer.payment.debit') }}" class="{{ (route('treasurer.payment.debit') == Request::url()) ? 'active' : ''}}">Debit Note</a></li>
					<li><a href="{{ route('allowance.payment') }}" class="{{ (route('allowance.payment') == Request::url()) ? 'active' : ''}}">Allowance Payment</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="gift"></i><span>Sponsorship</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('sponsorship.library') }}" class="{{ (route('sponsorship.library') == Request::url()) ? 'active' : ''}}">Sponsor Library</a></li>
					<li><a href="{{ route('sponsorship.payment') }}" class="{{ (route('sponsorship.payment') == Request::url()) ? 'active' : ''}}">Sponsor Payment</a></li>
					<li><a href="{{ route('finance.package.sponsorPackage') }}" class="{{ (route('finance.package.sponsorPackage') == Request::url()) ? 'active' : ''}}">Sponsor Package</a></li>
					<li><a href="{{ route('finance.payment.report') }}" class="{{ (route('finance.payment.report') == Request::url()) ? 'active' : ''}}">Sponsor payment Report</a></li>
					<li><a href="{{ route('finance.payment.PReport') }}" class="{{ (route('finance.payment.PReport') == Request::url()) ? 'active' : ''}}">Sponsor PTPTN Report</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="clipboard"></i><span>Report</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.statement') }}" class="{{ (route('finance.statement') == Request::url()) ? 'active' : ''}}">Student Account Statement</a></li>
					<li><a href="{{ route('finance.receiptList') }}" class="{{ (route('finance.receiptList') == Request::url()) ? 'active' : ''}}">Receipt Report</a></li>
					<li><a href="{{ route('finance.dailyReport') }}" class="{{ (route('finance.dailyReport') == Request::url()) ? 'active' : ''}}">Daily Report</a></li>
					<li><a href="{{ route('finance.chargeReport') }}" class="{{ (route('finance.chargeReport') == Request::url()) ? 'active' : ''}}">Charge Report</a></li>
					<li><a href="{{ route('finance.arrearsReport') }}" class="{{ (route('finance.arrearsReport') == Request::url()) ? 'active' : ''}}">Debt & Payment Report</a></li>
					<li><a href="{{ route('finance.urReport') }}" class="{{ (route('finance.urReport') == Request::url()) ? 'active' : ''}}">UR Report</a></li>
					<li><a href="{{ route('finance.agingReport') }}" class="{{ (route('finance.agingReport') == Request::url()) ? 'active' : ''}}">Student Aging Report</a></li>
					<li><a href="{{ route('finance.programAgingReport') }}" class="{{ (route('finance.programAgingReport') == Request::url()) ? 'active' : ''}}">Program Aging Report</a></li>
					<li><a href="{{ route('finance.statusAgingReport') }}" class="{{ (route('finance.statusAgingReport') == Request::url()) ? 'active' : ''}}">Status Aging Report</a></li>
					<li><a href="{{ route('finance.studentArrearsReport') }}" class="{{ (route('finance.studentArrearsReport') == Request::url()) ? 'active' : ''}}">Student Arrears Report</a></li>
					<li><a href="{{ route('finance.graduateReport') }}" class="{{ (route('finance.graduateReport') == Request::url()) ? 'active' : ''}}">Graduate Report</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="shopping-bag"></i><span>Voucher</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.voucher.student') }}" class="{{ (route('finance.voucher.student') == Request::url()) ? 'active' : ''}}">Student</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="file-text"></i><span>Debt Management</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.claimLog') }}" class="{{ (route('finance.claimLog') == Request::url()) ? 'active' : ''}}">Student Payment Claim Log</a></li>
					<li><a href="{{ route('finance.collectionReport') }}" class="{{ (route('finance.collectionReport') == Request::url()) ? 'active' : ''}}">Expectation Collection Report</a></li>
					<li><a href="{{ route('finance.collectionExpectReport') }}" class="{{ (route('finance.collectionExpectReport') == Request::url()) ? 'active' : ''}}">Collection VS Expectation Payment Report</a></li>
					<li><a href="{{ route('finance.monthlyPayment') }}" class="{{ (route('finance.monthlyPayment') == Request::url()) ? 'active' : ''}}">Student Monthly Payment Report</a></li>
					<li><a href="{{ route('finance.ctosReport') }}" class="{{ (route('finance.ctosReport') == Request::url()) ? 'active' : ''}}">CTOS Report</a></li>
					<li><a href="{{ route('finance.arrearNotice') }}" class="{{ (route('finance.arrearNotice') == Request::url()) ? 'active' : ''}}">Arrear Notice</a></li>
					<li><a href="{{ route('finance.studentCtos') }}" class="{{ (route('finance.studentCtos') == Request::url()) ? 'active' : ''}}">Student CTOSs</a></li>
					<li><a href="{{ route('finance.authorizeTranscript') }}" class="{{ (route('finance.authorizeTranscript') == Request::url()) ? 'active' : ''}}">Autorization Transcript</a></li>
					<li><a href="{{ route('finance.studentRemarks') }}" class="{{ (route('finance.studentRemarks') == Request::url()) ? 'active' : ''}}">Student Remark</a></li>
					<li><a href="{{ route('finance.discountReport') }}" class="{{ (route('finance.discountReport') == Request::url()) ? 'active' : ''}}">Discount Report</a></li>
				</ul>
			  </li>
			  <li class="treeview">
				<a href="#"><i data-feather="box"></i><span>Assets</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li><a href="{{ route('finance.vehicleRecord') }}" class="{{ (route('finance.vehicleRecord') == Request::url()) ? 'active' : ''}}">Vehicle & Service Records</a></li>
					<li><a href="{{ route('finance.asset.vehicleRecord.vehicleReport') }}" class="{{ (route('finance.asset.vehicleRecord.vehicleReport') == Request::url()) ? 'active' : ''}}">Vehicle Report</a></li>
				</ul>
			  </li>
			  <li>
				<a href="/all/massage/user">
					<i data-feather="message-square"></i>
					<span>Message</span> 
					<span id="count" class="count-circle">0</span>
				</a>
			  </li>
			  <script>
				 $(document).ready(function() {
					function fetchMessageCount() {
						$.ajax({
							url: '{{ route("all.massage.student.countMassageAdmin") }}',
							type: 'GET',
							success: function(response) {
								if(response.count > 0) {

									$('#count').text(response.count);
									$('#count').show();

								} else {
									
									$('#count').hide();
									
								}
							},
							error: function() {
								console.error('Failed to fetch message count');
							}
						});
					}

					// Fetch the count every 30 seconds
					setInterval(fetchMessageCount, 30000);

					// Initial fetch when page loads
					fetchMessageCount();
				});
			  </script>
			  <li>
				<a href="{{ route('posting.staff') }}" class="{{ (route('posting.staff') == Request::url()) ? 'active' : ''}}"><i data-feather="tv"></i><span>Posting</span></a>
			  </li> 

			  <li>
				<a href="{{ route('all.pdf.export.index') }}" class="{{ (route('all.pdf.export.index') == Request::url()) ? 'active' : ''}}"><i data-feather="file-text"></i><span>PDF Export</span></a>
			  </li> 
		  </div>
		</div>
    </section>
  </aside>

  
	
  <!-- BEGIN Page Content -->
  @yield('main')	
	
	
	
  

  <footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
		 
		</ul>
    </div>
	  &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://eduhub.intds.com.my">EduHub</a>
  </footer>
  <!-- Side panel -->   
  <!-- quick_user_toggle -->
  <div class="modal modal-right fade" id="quick_user_toggle" tabindex="-1">
	  <div class="modal-dialog">
		<div class="modal-content slim-scroll3">
		  <div class="modal-body p-30 bg-white">
			<div class="d-flex align-items-center justify-content-between pb-30">
				<h4 class="m-0">User Profile
				<small class="text-fade fs-12 ms-5"></small></h4>
				<a href="#" class="btn btn-icon btn-danger-light btn-sm no-shadow" data-bs-dismiss="modal">
					<span class="fa fa-close"></span>
				</a>
			</div>
            <div>
                <div class="d-flex flex-row">
                    <div class=""><img src="{{ (Auth::user()->image != null) ? Storage::disk('linode')->url(Auth::user()->image) : asset('assets/images/avatar/avatar-13.png') }}" alt="user" class="rounded bg-danger-light w-150" width="100"></div>
                    <div class="ps-20">
                        <h5 class="mb-0"></h5>
                        <p class="my-5 text-fade"></p>
                        <a href="mailto:">
							<span class="icon-Mail-notification me-5 text-success"><span class="path1"></span><span class="path2"></span></span> 
						</a>
                    </div>
                </div>
			</div>
              <div class="dropdown-divider my-30"></div>
              {{-- <div>
                <div class="d-flex align-items-center mb-30">
                    <div class="me-15 bg-primary-light h-50 w-50 l-h-60 rounded text-center">
                          <span class="icon-Library fs-24"><span class="path1"></span><span class="path2"></span></span>
                    </div>
                    <div class="d-flex flex-column fw-500">
                        <a href="extra_profile.html" class="text-dark hover-primary mb-1 fs-16">My Profile</a>
                        <span class="text-fade">Account settings and more</span>
                    </div>
                </div>
                
              </div> --}}
			  <div>
				  <div class="col-sm-12 d-flex justify-content-center">
				  	  <a href="/lecturer/setting" type="button" class="waves-effect waves-light btn btn-secondary btn-rounded mb-5" style="margin-right:10px;"><i class="mdi mdi-account-edit"></i> Edit</a>
					  <a href="{{ route('logout') }}" type="button" class="waves-effect waves-light btn btn-secondary btn-rounded mb-5"
					  onclick="event.preventDefault();
					  document.getElementById('logout-form').submit();">
					  <i class="mdi mdi-logout"></i>{{ __('Logout') }}</a>
		
					<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
					@csrf
					</form>
			  	  </div>
              </div>
              <div class="dropdown-divider my-30"></div>
              
		  </div>
		</div>
	  </div>
  </div>
  <!-- /quick_user_toggle --> 
  
  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>	
	
</div>
<!-- ./wrapper -->
	
			
<!-- Page Content overlay -->
<!-- Vendor JS -->

<script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>



<script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/assets/icons/feather-icons/feather.min.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js') }}"></script>

{{-- <script src="{{ asset('assets/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script> --}}
<script src="{{ asset('assets/assets/vendor_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>	

<script src="{{ asset('assets/assets/vendor_components/full-calendar/moment.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/full-calendar/fullcalendar.min.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/OwlCarousel2/dist/owl.carousel.js') }}"></script> 

<script src="{{ asset('assets/assets/vendor_components/nestable/jquery.nestable.js') }}"></script> 


<script src="{{ asset('assets/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/iCheck/icheck.min.js') }}"></script>


<!-- edulearn App -->
<script src="{{ asset('assets/src/js/demo.js') }}"></script>
<script src="{{ asset('assets/src/js/template.js') }}"></script>
{{-- <script src="{{ asset('assets/src/js/pages/dashboard.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/src/js/pages/demo.calendar.js') }}"></script> --}}
<script src="{{ asset('assets/src/js/pages/owl-slider.js') }}"></script>

	
	
<script src="{{ asset('assets/src/js/pages/advanced-form-element.js') }}"></script>

<script src="{{ asset('assets/assets/vendor_components/datatable/datatables.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script src="http://spp3.intds.com.my/assets/js/formplugins/select2/select2.bundle.js"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>


	
<script src="{{ asset('assets/src/js/pages/component-animations-css3.js')}}"></script>
	

{{-- 
<script src="{{ asset('assets/src/js/datagrid/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/src/js/datagrid/datatables/datatables.export.js') }}"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script> 

<script src="{{ asset('assets/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>	
<script src="{{ asset('assets/assets/vendor_components/select2/dist/js/select2.full.js')}}"></script>
 --}}

 <script>

	function clickFn(event) {
	
		var theme = event;
		
		//alert(event);
	
		return $.ajax({
				headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
				url      : "{{ url('lecturer/update/theme') }}",
				method   : 'POST',
				data 	 : {theme: theme},
				error:function(err){
					alert("Error");
					console.log(err);
				},
				success  : function(data){
					
					//$('#lecturer-selection-div').removeAttr('hidden');
					//$('#lecturer').selectpicker('refresh');
		  
					//$('#chapter').removeAttr('hidden');
						//$('#status').html(data);
						//$('#myTable').DataTable();
						//$('#group').selectpicker('refresh');
				}
			});
	
	}
	
	</script>

@yield('content')



</body>
</html>
  
