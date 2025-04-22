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
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Vendors Style-->
  <link rel="stylesheet" href="{{ asset('assets/src/css/vendors_css.css') }}">
  
  <!-- Style-->  
  <link rel="stylesheet" href="{{ asset('assets/src/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/src/css/skin_color.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/customCSS.css') }}">
  <link rel="stylesheet" href="{{ asset('css/customLayoutCSS.css') }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
  <!-- Progress Bar -->
  <div class='custom-progress'>
    <div class='custom-progress-bar' id='custom-progress-bar1'></div>
    <div class='custom-percent' id='custom-percent1'></div>
    <input type="hidden" id="custom_progress_width" value="0">
  </div>

  <div class="wrapper">
    <div id="loader"></div>
    
    <!-- Header -->
    <header class="main-header">
      <div class="d-flex align-items-center logo-box justify-content-start">	
        <!-- Logo -->
        <a href="{{ url('student') }}" class="logo">
          <!-- logo-->
          <div class="logo-mini w-50">
            <span class="light-logo"><img src="{{ asset('assets/images/logo/Kolej-UNITI.png')}}" alt="logo" class="unity"></span>
            <span class="dark-logo"><img src="{{ asset('assets/images/logo-letter-white.png') }}" alt="logo"></span>
          </div>
          <div class="logo-lg d-flex align-items-center">
            <span class="light-logo">
              <span class="ucms-text-black">U</span><span class="ucms-text-orange">CMS</span>
            </span>
            <span class="dark-logo">
              <span class="ucms-text-white">U</span><span class="ucms-text-orange">CMS</span>
            </span>
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
            <!-- Dark Mode Toggle -->
            <li class="btn-group d-md-inline-flex d-none">
              <a href="javascript:void(0)" title="Toggle Theme" class="waves-effect skin-toggle waves-light me-4">
                <label class="switch">
                  <input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
                  <span>
                    <i data-feather="moon" class="switch-on"></i>
                    <i data-feather="sun" class="switch-off"></i>
                  </span>
                </label>
              </a>				
            </li>
            
            <!-- Notifications -->
            <li class="notification-dropdown d-flex align-items-center">
              <button class="notification-btn" onclick="toggleNotificationDropdown()">
                <i data-feather="bell" style="color: #4f81c7;"></i>
                <div class="pulse-wave"></div>
                @if(auth()->guard('student')->check() && auth()->guard('student')->user()->unreadNotifications && auth()->guard('student')->user()->unreadNotifications->count() > 0)
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
                  @if(auth()->guard('student')->check())
                    <a href="{{ route('notifications.clear') }}" class="clear-all">Clear All</a>
                  @endif
                </div>
                
                <!-- Notification List -->
                <ul class="notification-dropdown-list">
                  @if(auth()->guard('student')->check() && auth()->guard('student')->user()->unreadNotifications)
                    @forelse(auth()->guard('student')->user()->unreadNotifications as $notification)
                      <li>
                        <a href="{{ $notification->data['url'] ?? '#' }}"
                          onclick="markNotificationAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}'); return false;">
                          <i class="fa {{ $notification->data['icon'] ?? 'fa-info-circle' }}"
                            style="color: {{ $notification->data['icon_color'] ?? '#4f81c7' }};"></i>
                          {{ $notification->data['message'] ?? 'No message provided.' }}
                          <br>
                          <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                      </li>
                    @empty
                      <li>
                        <a href="#">No new notifications</a>
                      </li>
                    @endforelse
                  @else
                    <li>
                      <a href="#">No notifications available</a>
                    </li>
                  @endif
                </ul>
                
                <!-- Footer -->
                <div class="notification-dropdown-footer">
                  @if(auth()->guard('student')->check())
                    <a href="{{ route('notifications.index') }}">View all notifications</a>
                  @else
                    <a href="{{ route('login') }}">Login to view notifications</a>
                  @endif
                </div>
              </div>
            </li>
            
            <!-- User Account-->
            <li class="dropdown user user-menu">
              <a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" 
                title="User Profile" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
                <div class="d-flex pt-1 align-items-center">
                  <div class="text-end me-10">
                    <p class="pt-5 fs-14 mb-0 fw-700">{{ Session::get('User')->name ?? '' }}</p>
                    <small class="fs-10 mb-0 text-uppercase text-mute">student</small>
                  </div>
                  <img src="{{ Storage::disk('linode')->url('storage/student_image/' . Session::get('User')->ic . '.jpg') }}" 
                    class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
                </div>
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    
    <!-- Sidebar -->
    <aside class="main-sidebar">
      <section class="sidebar position-relative"> 
        <div class="multinav">
          <div class="multinav-scroll" style="height: 97%;">	
            <!-- Sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
              <li>
                <a href="{{ route('studentDashboard') }}" class="{{ (route('studentDashboard') == Request::url()) ? 'active' : ''}}">
                  <i data-feather="home"></i><span>Dashboard</span>
                </a>
              </li>
              <li>
                <a href="{{ route('student') }}" class="{{ (route('student') == Request::url()) ? 'active' : ''}}">
                  <i data-feather="bookmark"></i><span>Course</span>
                </a>
              </li>
              <li>
                <a href="{{ Storage::disk('linode')->url('classschedule/index.htm') }}" target="_blank">
                  <i data-feather="layout"></i><span>Old Timetable</span>
                </a>
              </li>
              <li>
                <a href="AR/schedule/scheduleTable/{{ Auth::guard('student')->user()->ic }}?type=std" target="_blank">
                  <i data-feather="calendar"></i><span>Timetable</span>
                </a>
              </li>
              
              <!-- Student Affairs Dropdown -->
              <li class="treeview">
                <a href="#">
                  <i data-feather="users"></i><span>Student Affairs</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
                  <li>
                    <a href="{{ route('student.affair.statement') }}" 
                      class="{{ (route('student.affair.statement') == Request::url()) ? 'active' : ''}}">
                      Statement
                    </a>
                  </li>
                  
                  @php
                  $range = DB::table('tblresult_period')->first();
                  $now = now();
                  $block_status = Auth::guard('student')->user()->block_status;
                  $program_list = DB::table('tblresult_program')->pluck('program_id')->toArray();
                  $session_list = DB::table('tblresult_session')->pluck('session_id')->toArray();
                  $semester_list = DB::table('tblresult_semester')->pluck('semester_id')->toArray();
                  @endphp

                  @if($now >= $range->Start && $now <= $range->End && in_array(Auth::guard('student')->user()->program, $program_list) && in_array(Auth::guard('student')->user()->session, $session_list) && in_array(Auth::guard('student')->user()->semester, $semester_list) && $block_status == 0)
                  <li>
                    <a href="{{ route('student.affair.result') }}" 
                      class="{{ (route('student.affair.result') == Request::url()) ? 'active' : ''}}">
                      Result
                    </a>
                  </li>
                  @endif
                  
                  <!-- Exam Slip Link -->
                  <li>
                    <a id="examSlipLink" href="#" target="_blank">Slip Exam</a>
                  </li>
                  <li>
                    <a href="{{ asset('storage/memo/2025.01.07 - Memo 01 Ketetapan Mod Pengajian Kuliah Kolej UNITI bagi Sesi 20242025-II.pdf') }}" target="_blank">
                      <span>Memo</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ asset('storage/takwim/Takwim Akademik Intake Mac Semester II-20242025 (Kolej UNITI) - Edaran Pelajar.pdf') }}" target="_blank">
                      <span>Takwim Uniti</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ asset('storage/takwim/Takwim Akademik Semester II-20242025 (UiTM) - Edaran Pelajar.pdf') }}" target="_blank">
                      <span>Takwim UiTM</span>
                    </a>
                  </li>
                </ul>
              </li>
              
              <!-- Messages Dropdown -->
              <li class="treeview">
                <a href="#">
                  <i data-feather="message-square"></i>
                  <span>Messages</span>
                  <span id="total-messages-count" class="count-circle hidden">0</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu treeview-menu-visible" id="treeview-menu-visible">
                  <li><a href="#" onclick="getMessage('FN')">UKP <span id="ukp-count" class="count-circle">0</span></a></li>
                  <li><a href="#" onclick="getMessage('RGS')">KRP <span id="krp-count" class="count-circle">0</span></a></li>
                  <li><a href="#">HEP</a></li>
                </ul>
              </li>
              
              <li>
                <a href="/yuran-pengajian" class="">
                  <i data-feather="credit-card"></i><span>Payment</span>
                </a>
              </li>
              <li>
                <a href="{{ asset('storage/finals_schedule/Jadual Pengawasan Peperiksaan Akhir UNITI Intake September 20242025-I.pdf') }}" target="_blank">
                  <i data-feather="file-text"></i><span>Finals Timetable</span>
                </a>
              </li>
            </ul>
            
            <!-- Sidebar Widget -->
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

    <!-- Modal Structure -->
    <div id="overlay" style="display: none;">
      <div id="blockAlertModal">
        <div class="warning-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="orange" viewBox="0 0 24 24">
            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm1 17.5h-2v-2h2v2zm0-4.5h-2v-7h2v7z"/>
          </svg>
        </div>
        <h3>Anda mempunyai tunggakan yang perlu dijelaskan, sila semak penyata kewangan anda.</h3>
        <button onclick="closeModal()">OK</button>
      </div>
    </div>
    
    <!-- Main Content -->
    @yield('main')
    
    <!-- Footer -->
    <footer class="main-footer">
      <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
        </ul>
      </div>
      &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://eduhub.intds.com.my">EduHub</a>
    </footer>
    
    <!-- Quick User Toggle Modal -->
    <div class="modal modal-right fade" id="quick_user_toggle" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content slim-scroll3">
          <div class="modal-body p-30 bg-white">
            <div class="d-flex align-items-center justify-content-between pb-30">
              <h4 class="m-0">User Profile
                <small class="text-fade fs-12 ms-5"></small>
              </h4>
              <a href="#" class="btn btn-icon btn-danger-light btn-sm no-shadow" data-bs-dismiss="modal">
                <span class="fa fa-close"></span>
              </a>
            </div>
            
            <div>
              <div class="d-flex flex-row">
                <div class="">
                  <img src="{{ Storage::disk('linode')->url('storage/student_image/' . Session::get('User')->ic . '.jpg') }}" 
                    alt="user" class="rounded bg-danger-light w-150" width="100">
                </div>
                <div class="ps-20">
                  <h5 class="mb-0">{{ Session::get('User')->name ?? '' }}</h5>
                  <p class="my-5 text-fade">Student</p>
                  <a href="mailto:{{ Session::get('User')->email }}">
                    <span class="icon-Mail-notification me-5 text-success">
                      <span class="path1"></span>
                      <span class="path2">{{ Session::get('User')->email }}</span>
                    </span> 
                  </a>
                </div>
              </div>
            </div>
            
            <div class="dropdown-divider my-30"></div>
            
            <div>
              <div class="col-sm-12 d-flex justify-content-center">
                <a href="/student/setting" type="button" class="waves-effect waves-light btn btn-secondary btn-rounded mb-5" style="margin-right:10px;">
                  <i class="mdi mdi-account-edit"></i> Edit
                </a>
                <a href="{{ route('logout') }}" type="button" class="waves-effect waves-light btn btn-secondary btn-rounded mb-5"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="mdi mdi-logout"></i>{{ __('Logout') }}
                </a>
                
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
    
    <!-- Control Sidebar Background -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->
  
  <div id="app">
    <example-component></example-component>
  </div>
  
  <!-- Scripts -->
  <script>
    function toggleNotificationDropdown() {
      const dropdown = document.getElementById('notificationDropdown');
      dropdown.classList.toggle('active');
    }
    
    // Close the dropdown if the user clicks outside
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
    
    // Exam slip modal
    document.addEventListener('DOMContentLoaded', function() {
      if (document.getElementById('examSlipLink')) {
        document.getElementById('examSlipLink').addEventListener('click', function(event) {
          event.preventDefault(); // Prevent default navigation
          
          var block_status = {{ $block_status ?? 0 }}; // Fetch the block status from PHP
          
          if (block_status === 1) {
            // Show the overlay and modal
            document.getElementById('overlay').style.display = 'flex';
          } else {
            // Redirect to the link if block_status is 0
            window.open("/AR/student/getSlipExam?student={{ Auth::guard('student')->user()->ic }}", "_blank");
          }
        });
      }
    });
    
    function closeModal() {
      document.getElementById('overlay').style.display = 'none';
    }
    
    // Message count updates
    let previousUkpCount = 0;
    let previousKrpCount = 0;
    let totalCount = 0;
    
    function updateMessageCount(type, elementId) {
      fetch(`/all/massage/student/countMessage?type=${type}`) // Replace with your API endpoint
        .then(response => response.json())
        .then(data => {
          const count = data.count;
          const element = document.getElementById(elementId);
          
          if (count === 0) {
            element.classList.add('hidden');
          } else {
            element.classList.remove('hidden');
            element.innerText = count;
          }
          
          // Check for changes in count and update total accordingly
          if (type === 'FN') {
            if (previousUkpCount !== count) {
              totalCount = totalCount - previousUkpCount + count;
              previousUkpCount = count;
            }
          } else if (type === 'RGS') {
            if (previousKrpCount !== count) {
              totalCount = totalCount - previousKrpCount + count;
              previousKrpCount = count;
            }
          }
          
          // Update total count display
          const totalElement = document.getElementById('total-messages-count');
          if (totalCount === 0) {
            totalElement.classList.add('hidden');
          } else {
            totalElement.classList.remove('hidden');
            totalElement.innerText = totalCount;
          }
        })
        .catch(error => console.error('Error:', error));
    }
    
    // Update counts every second
    setInterval(() => {
      updateMessageCount('FN', 'ukp-count');
      updateMessageCount('RGS', 'krp-count');
    }, 1000);
    
    window.Laravel = {
      sessionUserId: 'STUDENT'
    };
  </script>
  
  <!-- Vendor and App JS -->
  <script src="{{ mix('js/app.js') }}"></script>
  <script src="{{ asset('assets/src/js/vendors.min.js') }}"></script>
  <script src="{{ asset('assets/src/js/pages/chat-popup.js') }}"></script>
  <script src="{{ asset('assets/assets/icons/feather-icons/feather.min.js') }}"></script>
  <script src="{{ asset('assets/assets/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js') }}"></script>
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
  
  <!-- App JS -->
  <script src="{{ asset('assets/src/js/demo.js') }}"></script>
  <script src="{{ asset('assets/src/js/template.js') }}"></script>
  <script src="{{ asset('assets/src/js/pages/owl-slider.js') }}"></script>
  <script src="{{ asset('assets/src/js/pages/advanced-form-element.js') }}"></script>
  <script src="{{ asset('assets/assets/vendor_components/datatable/datatables.min.js') }}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
  <script src="http://spp3.intds.com.my/assets/js/formplugins/select2/select2.bundle.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="{{ asset('assets/src/js/pages/component-animations-css3.js')}}"></script>
  
  @yield('content')
</body>
</html>