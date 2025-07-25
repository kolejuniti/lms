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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
  </head>
  
<link rel="stylesheet" href="{{ asset('css/customCSS.css') }}">

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
</style>

<style>
	/* test */

	/* notifications.css */

/* Container for the entire dropdown */
.notification-dropdown {
    position: relative;
    display: inline-block;
}

/* The bell icon button */
.notification-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    position: relative;
    padding: 0.5rem;
    transition: background-color 0.2s;
}
.notification-btn:hover {
    background-color: rgba(0,0,0,0.05);
    border-radius: 50%;
}

/* The feather icon (bell) */
.notification-btn i {
    font-size: 1.5rem; /* Adjust if using Font Awesome or a different icon library */
    color: #4f81c7;     /* Adjust color to match your theme */
}

/* Optional wave effect behind the bell */
.pulse-wave {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 10px;
    height: 10px;
    background: rgba(79,129,199,0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

/* The unread badge */
.notification-btn .badge {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
    background-color: #f44336; /* red */
    color: #fff;
    border-radius: 50%;
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
}

/* The dropdown panel */
.notification-dropdown-content {
    display: none; /* Hidden by default */
    position: absolute;
    right: 0;
    margin-top: 0.5rem;
    background: #fff;
    width: 250px;
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    z-index: 9999;

    /* Start in an invisible/mini state for the pop-up animation */
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

/* Keyframes for pop-up animation */
@keyframes dropdownPopUp {
    0% {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Show the dropdown with animation when active */
.notification-dropdown-content.active {
    display: block; /* Make it visible */
    animation: dropdownPopUp 0.3s ease forwards;
}

/* Header styling */
.notification-dropdown-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #eee;
}
.notification-dropdown-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}
.notification-dropdown-header .clear-all {
    color: #f44336; /* red */
    text-decoration: none;
    font-size: 14px;
}

/* Notification list area */
.notification-dropdown-list {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 200px; /* scroll if too long */
    overflow-y: auto;
}
.notification-dropdown-list li {
    border-bottom: 1px solid #f2f2f2;
}
.notification-dropdown-list li a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
}
.notification-dropdown-list li a:hover {
    background-color: #f5f5f5;
}

/* Footer styling */
.notification-dropdown-footer {
    padding: 10px;
    text-align: center;
    border-top: 1px solid #eee;
}
.notification-dropdown-footer a {
    color: #4f81c7;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
}
</style>


<div class='custom-progress'>
	<div class='custom-progress-bar' id='custom-progress-bar1'></div>
	<div class='custom-percent' id='custom-percent1'></div>
	<input type="hidden" id="custom_progress_width" value="0">
</div>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed sidebar-collapse">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <header class="main-header">
	<div class="d-flex align-items-center logo-box justify-content-start">	
		<!-- Logo -->
		<a href="{{ url('student') }}" class="logo">
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
					<span class="switch-on"><i data-feather="moon"></i></span>
					<span class="switch-off"><i data-feather="sun"></i></span>
				</label>
			  </a>				
            </li>
			<li class="notification-dropdown mt-3">
				<!-- Notification button -->
				<button class="notification-btn" onclick="toggleNotificationDropdown()">
				  <i data-feather="bell" style="color: #4f81c7; font-size: 5rem;"></i>
				  <div class="pulse-wave"></div>
				  @if(auth()->guard('student')->check() && auth()->guard('student')->user()->unreadNotifications->count() > 0)
					<span class="badge">
					  {{ auth()->guard('student')->user()->unreadNotifications->count() }}
					</span>
				  @endif
				</button>
				
				<!-- Dropdown panel -->
				<div class="notification-dropdown-content" id="notificationDropdown">
				  <!-- Header -->
				  <div class="notification-dropdown-header">
					<h4>Notifications</h4>
					<a href="{{ route('notifications.clear') }}" class="clear-all">Clear All</a>
				  </div>
				
				  <!-- Notification List -->
				  <ul class="notification-dropdown-list">
					@forelse(auth()->guard('student')->user()->unreadNotifications as $notification)
					  <li>
						<a href="{{ $notification->data['url'] ?? '#' }}"
						   onclick="markNotificationAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}'); return false;">
						  <!-- Use inline style to set the icon's color dynamically -->
						  <i class="fa {{ $notification->data['icon'] ?? 'fa-info-circle' }}"
							 style="color: {{ $notification->data['icon_color'] ?? '#4f81c7' }};"></i>
						  {{ $notification->data['message'] ?? 'No message provided.' }}
						  <br>
						  <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
						</a>
					  </li>
					@empty
					  <li>
						<a href="#">No notifications</a>
					  </li>
					@endforelse
				  </ul>
				
				  <!-- Footer -->
				  <div class="notification-dropdown-footer">
					<a href="{{ route('notifications.index') }}">View all</a>
				  </div>
				</div>
			</li>
			{{-- <li class="dropdown notifications-menu btn-group">
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
					inner menu: contains the actual data
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
			</li> --}}
			
			
			<!-- User Account-->
			<li class="dropdown user user-menu">
				<a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
					<div class="d-flex pt-1 align-items-center">
						<div class="text-end me-10">
							<p class="pt-5 fs-14 mb-0 fw-700"></p>
							<small class="fs-10 mb-0 text-uppercase text-mute"></small>
						</div>
						<img src="{{ Storage::disk('linode')->url('storage/student_image/' . Session::get('User')->ic . '.jpg') }}" class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
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
            <a href="/studentDashboard"><i data-feather="home"></i><span>Home</span></a>
          </li>
          <li>
            <a href="/student/{{ Session::get('CourseID') }}" class=""><i data-feather="archive"></i><span>Course Summary</span></a>
          </li>
          <li>
            <a href="/student/content/{{ Session::get('CourseID') }}" class=""><i data-feather="airplay"></i><span>Course Content</span></a>
          </li> 
          <li class="treeview">
				<a href=""><i data-feather="book-open"></i><span>Course Assessment</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<li class="treeview">
						<a href="#"><span>Quiz</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
						<li><a href="/student/quiz/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Online</a></li>
						<li><a href="/student/quiz2/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Offline</a></li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><span>Test</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
						<li><a href="/student/test/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Online</a></li>
						<li><a href="/student/test2/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Offline</a></li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><span>Test 2</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
						<li><a href="/student/test3/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Online</a></li>
						<li><a href="/student/test4/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Offline</a></li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><span>Assignment</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
						<li><a href="/student/assign/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Online</a></li>
						<li><a href="/student/assign2/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Offline</a></li>
						</ul>
					</li>
					{{-- <li><a href="/student/test/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Test</a></li>
					<li><a href="/student/assign/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Assignment</a></li> --}}
					<li><a href="/student/midterm/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Midterm</a></li>
					{{-- <li><a href="/student/final/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Final</a></li>
					<li><a href="/student/paperwork/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Paperwork</a></li> --}}
					<li><a href="/student/practical/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Practical</a></li>
					{{-- <li><a href="/student/other/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class="">Lain-Lain</a></li>
					<li><a href="/student/report/{{ Session::get('CourseID') }}" class="">Report</a></li> --}}
				</ul>
          </li>
          <li class="treeview">
				<a href="#"><i data-feather="video"></i><span>Class</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
				<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
					<!--<li><a href="/student/class/schedule" class="">Manage Scedule</a></li>-->
					<li><a href="https://uniti.edu.my/wp-content/uploads/2022/08/index.htm" target="_blank" class="">Scedule</a></li>
					<li class="treeview">
						<a href="#"><span>Online Class</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
							<li><a href="/student/class/onlineclass/list" class="">List Class</a></li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><span>Announcement</span>
						</a>
						<ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
							<li><a href="/student/class/announcement/list" class="">List Announcement</a></li>
						</ul>
					</li>
				</ul>
          </li>
		  <li>
			<a href="/student/forum/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class=""><i data-feather="message-square"></i><span>Forum</span></a>
		  </li>
		  <li>
			<a href="/student/warning/{{ Session::get('CourseID') }}?session={{ Session::get('SessionID') }}" class=""><i data-feather="alert-circle"></i><span>Warning Letter</span></a>
		  </li>
          <!--<li class="treeview">
				<a href="#"><i data-feather="users"></i><span>Group</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
				</a>
          </li>-->
			  </ul>
			  <div class="sidebar-widgets">
				  <div class="mx-25 mb-30 pb-20 side-bx bg-primary-light rounded20">
					<div class="text-center">
						<img src="{{ asset('assets/images/svg-icon/color-svg/custom-24.svg') }}" class="sideimg p-5" alt="">
						<h4 class="title-bx text-primary">Best Education Platform</h4>
					</div>
				  </div>
			  </div>
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
                    <div class=""><img src="{{ Storage::disk('linode')->url('storage/student_image/' . Session::get('User')->ic . '.jpg') }}" alt="user" class="rounded bg-danger-light w-150" width="100"></div>
                    <div class="ps-20">
                        <h5 class="mb-0"></h5>
                        <p class="my-5 text-fade"></p>
                        <a href="mailto:">
							<span class="icon-Mail-notification me-5 text-success"><span class="path1"></span><span class="path2">{{ Session::get('User')->email }}</span></span> 
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
				  	  <a href="/student/setting" type="button" class="waves-effect waves-light btn btn-secondary btn-rounded mb-5" style="margin-right:10px;"><i class="mdi mdi-account-edit"></i> Edit</a>
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
@yield('content')	
			
<!-- Page Content overlay -->
<!-- Vendor JS -->

<script>
	function toggleNotificationDropdown() {
	  const dropdown = document.getElementById('notificationDropdown');
	  dropdown.classList.toggle('active');
	}
  
	// (Optional) Close the dropdown if the user clicks outside
	document.addEventListener('click', function(event) {
	  const dropdown = document.getElementById('notificationDropdown');
	  const button = document.querySelector('.notification-btn');
  
	  // If the click is not on the button or inside the dropdown, close it
	  if (!button.contains(event.target) && !dropdown.contains(event.target)) {
		dropdown.classList.remove('active');
	  }
	});

	function markNotificationAndRedirect(notificationId, redirectUrl) {
	// Send AJAX request to mark the notification as read
	fetch('/notifications/mark-read/' + notificationId, {
		method: 'POST',
		headers: {
		'Content-Type': 'application/json',
		'X-CSRF-TOKEN': '{{ csrf_token() }}'
		}
	})
	.then(response => {
		// After marking as read, redirect the user to the intended URL
		window.location.href = redirectUrl;
	})
	.catch(error => {
		console.error('Error marking notification as read:', error);
		// If there's an error, still navigate to the URL
		window.location.href = redirectUrl;
	});
	}

  </script>

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


<script src="http://spp3.intds.com.my/assets/js/formplugins/select2/select2.bundle.js"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-builder.min.js"></script>


	
<script src="{{ asset('assets/src/js/pages/component-animations-css3.js')}}"></script>
	
{{-- 
<script src="{{ asset('assets/src/js/datagrid/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/src/js/datagrid/datatables/datatables.export.js') }}"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script> 

<script src="{{ asset('assets/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js')}}"></script>	
<script src="{{ asset('assets/assets/vendor_components/select2/dist/js/select2.full.js')}}"></script>
 --}}

 @yield('javascript')	




</body>
</html>
  
