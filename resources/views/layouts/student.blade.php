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
  <link rel="stylesheet" href="https://unpkg.com/css-skeletons@1.0.3/css/css-skeletons.min.css" />
  
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/customCSS.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customLayoutCSS.css') }}">
  
  @stack('styles')
  
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
            
            <!-- Student Messages Button -->
            <li class="messaging-dropdown d-flex align-items-center">
              <button class="messaging-btn" onclick="toggleMessagingPanel()">
                <i data-feather="message-circle" style="color: #4f81c7;"></i>
                <div class="pulse-wave"></div>
                <span id="total-student-messages-count" class="badge hidden">0</span>
              </button>
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
              {{-- <li>
                <a href="{{ Storage::disk('linode')->url('classschedule/index.htm') }}" target="_blank">
                  <i data-feather="layout"></i><span>Old Timetable</span>
                </a>
              </li> --}}
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

                  @php
                  $range = DB::table('tblslip_period')->first();
                  $now = now();
                  $program_list = DB::table('tblslip_program')->pluck('program_id')->toArray();
                  $session_list = DB::table('tblslip_session')->pluck('session_id')->toArray();
                  $semester_list = DB::table('tblslip_semester')->pluck('semester_id')->toArray();
                  @endphp
                  
                  @if($now >= $range->Start && $now <= $range->End && in_array(Auth::guard('student')->user()->program, $program_list) && in_array(Auth::guard('student')->user()->session, $session_list) && in_array(Auth::guard('student')->user()->semester, $semester_list))
                  <!-- Exam Slip Link -->
                  <li>
                    <a id="examSlipLink" href="#" target="_blank">Slip Exam</a>
                  </li>
                  @endif
                  {{-- <li>
                    <a href="{{ asset('storage/memo/2025.01.07 - Memo 01 Ketetapan Mod Pengajian Kuliah Kolej UNITI bagi Sesi 20242025-II.pdf') }}" target="_blank">
                      <span>Memo</span>
                    </a>
                  </li> --}}
                  <li>
                    <a href="{{ asset('storage/takwim/Takwim Akademik Kolej UNITI Semester I Sesi 20252026 (Kemasukan Jun) - Edaran Pelajar.pdf') }}" target="_blank">
                      <span>Takwim Uniti (June)</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ asset('storage/takwim/Takwim Akademik Kolej UNITI Semester I Sesi 20252026 (Kemasukan September) - Edaran Pelajar.pdf') }}" target="_blank">
                      <span>Takwim Uniti (September)</span>
                    </a>
                  </li>
                  <li>
                    <a href="{{ asset('storage/takwim/Takwim Akademik Kolej UNITI Semester I Sesi 20252026 (Kemasukan November) - Edaran Pelajar.pdf') }}" target="_blank">
                      <span>Takwim Uniti (November)</span>
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
                  <li><a href="#" onclick="getMessage('FN', 'FN')">UKP (Student Finance)<span id="ukp-count" class="count-circle">0</span></a></li>
                  <li><a href="#" onclick="getMessage('RGS', 'RGS')">KRP (Registration)<span id="krp-count" class="count-circle">0</span></a></li>
                  <li><a href="#" onclick="getMessage('HEA', 'HEA')">HEP <span id="hep-count" class="count-circle hidden">0</span></a></li>
                </ul>
              </li>
              
              <!-- Quick Student Messages (Sidebar) -->
              <li class="treeview">
                <a href="#">
                  <i data-feather="users"></i>
                  <span>Student Messages</span>
                  <span id="sidebar-student-messages-count" class="count-circle hidden">0</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu treeview-menu-visible">
                  <li>
                    <div class="sidebar-quick-search">
                      <input type="text" id="sidebar-student-search" placeholder="Quick search..." class="form-control form-control-sm">
                      <div id="sidebar-search-results" class="sidebar-search-results"></div>
                    </div>
                  </li>
                  <li>
                    <div id="sidebar-recent-conversations" class="sidebar-conversations">
                      <!-- Recent conversations will be loaded here -->
                    </div>
                  </li>
                  <li>
                    <a href="#" onclick="toggleMessagingPanel()" class="view-all-messages">
                      <i data-feather="message-square"></i>
                      <span>View All Messages</span>
                    </a>
                  </li>
                </ul>
              </li>

              
              <li>
                <a href="/yuran-pengajian" class="">
                  <i data-feather="credit-card"></i><span>Payment</span>
                </a>
              </li>
              {{-- <li>
                <a href="{{ asset('storage/finals_schedule/Jadual Pengawasan Peperiksaan Akhir UNITI Semester II Sesi 20242025 (Kemasukan Mac).pdf') }}" target="_blank">
                  <i data-feather="file-text"></i><span>Finals Timetable</span>
                </a>
              </li> --}}
              
              <!-- Mini Games -->
              <li class="treeview">
                <a href="#">
                  <i data-feather="gamepad-2"></i>
                  <span>Mini Games</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-right pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu treeview-menu-visible">
                  <li>
                    <a href="{{ route('student.games.lobby') }}" 
                      class="{{ (route('student.games.lobby') == Request::url()) ? 'active' : ''}}">
                      <i data-feather="users"></i> Game Lobby
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('student.games.tictactoe') }}" 
                      class="{{ (route('student.games.tictactoe') == Request::url()) ? 'active' : ''}}">
                      <i data-feather="grid-3x3"></i> Tic Tac Toe
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('student.games.connectfour') }}" 
                      class="{{ (route('student.games.connectfour') == Request::url()) ? 'active' : ''}}">
                      <i data-feather="circle"></i> Connect Four
                    </a>
                  </li>
                </ul>
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
    
    <!-- Student Messaging Panel (Facebook-like) -->
    <div id="messaging-panel" class="messaging-panel">
      <div class="messaging-panel-header">
        <div class="messaging-header-content">
          <h3><i data-feather="message-circle"></i> Messages</h3>
          <button class="close-messaging-btn" onclick="toggleMessagingPanel()">
            <i data-feather="x"></i>
          </button>
        </div>
        <div class="messaging-search-container">
          <div class="messaging-search-box">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" id="messaging-student-search" placeholder="Search students..." class="messaging-search-input">
            <div id="messaging-search-results" class="messaging-search-results"></div>
          </div>
        </div>
      </div>
      
      <div class="messaging-panel-body">
        <!-- Recent Conversations -->
        <div class="messaging-section">
          <h4 class="messaging-section-title">Recent Conversations</h4>
          <div id="messaging-conversations" class="messaging-conversations">
            <div class="messaging-empty-state">
              <i data-feather="message-square"></i>
              <p>No conversations yet</p>
              <span>Search for students to start chatting!</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Messaging Panel Overlay -->
    <div id="messaging-overlay" class="messaging-overlay" onclick="toggleMessagingPanel()"></div>
    
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
  <style>
    /* Messaging Button Styles */
    .messaging-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      padding: 8px;
      border-radius: 50%;
      transition: background-color 0.2s ease;
    }
    
    .messaging-btn:hover {
      background-color: rgba(79, 129, 199, 0.1);
    }
    
    .messaging-btn .badge {
      position: absolute;
      top: 0;
      right: 0;
      background: #ff4757;
      color: white;
      border-radius: 50%;
      min-width: 18px;
      height: 18px;
      font-size: 11px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid white;
    }
    
    .messaging-btn .badge.hidden {
      display: none;
    }

    /* Messaging Panel Styles */
    .messaging-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.3);
      z-index: 999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    
    .messaging-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .messaging-panel {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      height: 100vh;
      background: white;
      z-index: 1000;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
      transition: right 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    
    .messaging-panel.active {
      right: 0;
    }

    .messaging-panel-header {
      background: #4f81c7;
      color: white;
      padding: 20px;
      border-bottom: 1px solid #e1e8ed;
    }
    
    .messaging-header-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 15px;
    }
    
    .messaging-header-content h3 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .close-messaging-btn {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      padding: 5px;
      border-radius: 50%;
      transition: background-color 0.2s ease;
    }
    
    .close-messaging-btn:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .messaging-search-container {
      position: relative;
    }
    
    .messaging-search-box {
      position: relative;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 20px;
      padding: 8px 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .search-icon {
      width: 16px;
      height: 16px;
      color: #666;
    }
    
    .messaging-search-input {
      flex: 1;
      border: none;
      outline: none;
      background: transparent;
      font-size: 14px;
      color: #333;
    }
    
    .messaging-search-input::placeholder {
      color: #666;
    }

    .messaging-search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      max-height: 250px;
      overflow-y: auto;
      z-index: 1001;
      display: none;
      margin-top: 8px;
    }
    
    .messaging-search-results.active {
      display: block;
    }

    .messaging-panel-body {
      flex: 1;
      overflow-y: auto;
      padding: 0;
    }

    .messaging-section {
      padding: 20px;
    }
    
    .messaging-section-title {
      font-size: 16px;
      font-weight: 600;
      color: #333;
      margin: 0 0 15px 0;
    }

    .messaging-conversations {
      /* Remove max-height to allow full expansion */
    }
    
    .messaging-empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #666;
    }
    
    .messaging-empty-state i {
      width: 48px;
      height: 48px;
      margin-bottom: 15px;
      color: #ccc;
    }
    
    .messaging-empty-state p {
      font-size: 16px;
      font-weight: 500;
      margin: 0 0 5px 0;
    }
    
    .messaging-empty-state span {
      font-size: 14px;
      color: #999;
    }

    /* Search Result Items */
    .messaging-search-result-item {
      padding: 12px 15px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s ease;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .messaging-search-result-item:hover {
      background-color: #f8f9fa;
    }
    
    .messaging-search-result-item:last-child {
      border-bottom: none;
    }
    
    .messaging-search-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #4f81c7, #667eea);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 16px;
      flex-shrink: 0;
    }
    
    .messaging-search-details {
      flex: 1;
      min-width: 0;
    }
    
    .messaging-search-name {
      font-weight: 600;
      font-size: 14px;
      color: #333;
      margin-bottom: 2px;
    }
    
    .messaging-search-info {
      font-size: 12px;
      color: #666;
    }

    /* Conversation Items */
    .messaging-conversation-item {
      display: flex;
      align-items: center;
      padding: 12px 0;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s ease;
      gap: 12px;
    }
    
    .messaging-conversation-item:hover {
      background-color: #f8f9fa;
      padding-left: 8px;
      padding-right: 8px;
      margin-left: -8px;
      margin-right: -8px;
      border-radius: 8px;
    }
    
    .messaging-conversation-item:last-child {
      border-bottom: none;
    }
    
    .messaging-conversation-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, #4f81c7, #667eea);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 18px;
      flex-shrink: 0;
      position: relative;
    }
    
    .messaging-conversation-avatar.online::after {
      content: '';
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 12px;
      height: 12px;
      background: #2ed573;
      border: 2px solid white;
      border-radius: 50%;
    }
    
    .messaging-conversation-details {
      flex: 1;
      min-width: 0;
    }
    
    .messaging-conversation-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 4px;
    }
    
    .messaging-conversation-name {
      font-weight: 600;
      font-size: 15px;
      color: #333;
    }
    
    .messaging-conversation-time {
      font-size: 12px;
      color: #999;
    }
    
    .messaging-conversation-preview {
      font-size: 13px;
      color: #666;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .messaging-conversation-preview.unread {
      font-weight: 600;
      color: #333;
    }
    
    .messaging-conversation-meta {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .messaging-unread-badge {
      background: #4f81c7;
      color: white;
      border-radius: 50%;
      min-width: 20px;
      height: 20px;
      font-size: 11px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .messaging-panel {
        width: 100%;
        right: -100%;
      }
    }

    /* Sidebar Quick Messaging Styles */
    .sidebar-quick-search {
      padding: 10px 15px;
    }
    
    .sidebar-quick-search .form-control-sm {
      font-size: 12px;
      padding: 6px 10px;
      border-radius: 15px;
      border: 1px solid #ddd;
    }
    
    .sidebar-search-results {
      position: absolute;
      top: 100%;
      left: 15px;
      right: 15px;
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      max-height: 200px;
      overflow-y: auto;
      z-index: 1000;
      display: none;
      margin-top: 5px;
    }
    
    .sidebar-search-results.active {
      display: block;
    }
    
    .sidebar-search-item {
      padding: 8px 12px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .sidebar-search-item:hover {
      background-color: #f8f9fa;
    }
    
    .sidebar-search-item:last-child {
      border-bottom: none;
    }
    
    .sidebar-search-avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #4f81c7;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 11px;
      flex-shrink: 0;
    }
    
    .sidebar-search-info {
      flex: 1;
      min-width: 0;
    }
    
    .sidebar-search-name {
      font-weight: 600;
      font-size: 12px;
      color: #333;
      margin-bottom: 1px;
    }
    
    .sidebar-search-details {
      font-size: 10px;
      color: #666;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .sidebar-conversations {
      max-height: 250px;
      overflow-y: auto;
      padding: 0 15px;
    }
    
    .sidebar-conversation-item {
      display: flex;
      align-items: center;
      padding: 8px 0;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
      transition: all 0.2s ease;
      gap: 8px;
    }
    
    .sidebar-conversation-item:hover {
      background-color: #f8f9fa;
      margin: 0 -8px;
      padding: 8px 8px;
      border-radius: 6px;
    }
    
    .sidebar-conversation-item:last-child {
      border-bottom: none;
    }
    
    .sidebar-conversation-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #4f81c7;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 12px;
      flex-shrink: 0;
      position: relative;
    }
    
    .sidebar-conversation-avatar.online::after {
      content: '';
      position: absolute;
      bottom: -1px;
      right: -1px;
      width: 10px;
      height: 10px;
      background: #2ed573;
      border: 2px solid white;
      border-radius: 50%;
    }
    
    .sidebar-conversation-details {
      flex: 1;
      min-width: 0;
    }
    
    .sidebar-conversation-name {
      font-weight: 600;
      font-size: 12px;
      color: #333;
      margin-bottom: 2px;
    }
    
    .sidebar-conversation-preview {
      font-size: 11px;
      color: #666;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .sidebar-conversation-preview.unread {
      font-weight: 600;
      color: #333;
    }
    
    .sidebar-conversation-unread {
      background: #4f81c7;
      color: white;
      border-radius: 50%;
      min-width: 16px;
      height: 16px;
      font-size: 9px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .sidebar-empty-conversations {
      padding: 15px 0;
      text-align: center;
      color: #666;
      font-size: 11px;
    }
    
    .view-all-messages {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #4f81c7 !important;
      font-weight: 500;
      transition: all 0.2s ease;
    }
    
    .view-all-messages:hover {
      color: #3d6bb3 !important;
      background-color: rgba(79, 129, 199, 0.1);
    }
    
    .view-all-messages i {
      width: 14px;
      height: 14px;
    }
  </style>

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
    let previousHepCount = 0;
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
          } else if (type === 'HEP') {
            if (previousHepCount !== count) {
              totalCount = totalCount - previousHepCount + count;
              previousHepCount = count;
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
      updateMessageCount('HEP', 'hep-count');
    }, 1000);
    
    window.Laravel = {
      sessionUserId: 'STUDENT',
      currentStudentIc: '{{ Auth::guard("student")->user()->ic ?? "" }}'
    };
    
    // Student messaging functionality
    let searchTimeout;
    let currentStudentChat = null;
    let messagingPanelOpen = false;
    
    // Toggle messaging panel
    function toggleMessagingPanel() {
      const panel = document.getElementById('messaging-panel');
      const overlay = document.getElementById('messaging-overlay');
      
      messagingPanelOpen = !messagingPanelOpen;
      
      if (messagingPanelOpen) {
        panel.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        // Load conversations when panel opens
        loadStudentConversations();
      } else {
        panel.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        // Clear search when closing
        const searchInput = document.getElementById('messaging-student-search');
        const searchResults = document.getElementById('messaging-search-results');
        if (searchInput) searchInput.value = '';
        if (searchResults) searchResults.classList.remove('active');
      }
    }
    
    // Initialize student messaging
    document.addEventListener('DOMContentLoaded', function() {
      // Main messaging panel search
      const searchInput = document.getElementById('messaging-student-search');
      const searchResults = document.getElementById('messaging-search-results');
      
      // Sidebar quick search
      const sidebarSearchInput = document.getElementById('sidebar-student-search');
      const sidebarSearchResults = document.getElementById('sidebar-search-results');
      
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          clearTimeout(searchTimeout);
          const query = this.value.trim();
          
          if (query.length < 2) {
            searchResults.classList.remove('active');
            return;
          }
          
          searchTimeout = setTimeout(() => {
            searchStudents(query);
          }, 300);
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
          if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
          }
          
          // Also handle sidebar search results
          if (sidebarSearchInput && sidebarSearchResults && 
              !sidebarSearchInput.contains(e.target) && !sidebarSearchResults.contains(e.target)) {
            sidebarSearchResults.classList.remove('active');
          }
        });
              }
        
        // Sidebar search functionality
        if (sidebarSearchInput) {
          sidebarSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
              sidebarSearchResults.classList.remove('active');
              return;
            }
            
            searchTimeout = setTimeout(() => {
              searchStudentsForSidebar(query);
            }, 300);
          });
        }
        
        // Load existing conversations
        loadStudentConversations();
        loadSidebarConversations();
      
              // Set up periodic refresh for conversations when panel is open
        setInterval(() => {
          if (messagingPanelOpen) {
            loadStudentConversations();
          }
          // Always refresh sidebar conversations
          loadSidebarConversations();
        }, 5000);
      
      // Keyboard shortcuts
      document.addEventListener('keydown', function(e) {
        // Close messaging panel with Escape key
        if (e.key === 'Escape' && messagingPanelOpen) {
          toggleMessagingPanel();
        }
      });
    });
    
    function searchStudents(query) {
      fetch('/all/student/search', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ search: query })
      })
      .then(response => response.json())
      .then(students => {
        const searchResults = document.getElementById('messaging-search-results');
        
        if (students.length === 0) {
          searchResults.innerHTML = '<div class="messaging-search-result-item">No students found</div>';
        } else {
          searchResults.innerHTML = students.map(student => `
            <div class="messaging-search-result-item" onclick="startStudentChat('${student.ic}', '${student.name}')">
              <div class="messaging-search-avatar">
                ${student.name.charAt(0).toUpperCase()}
              </div>
              <div class="messaging-search-details">
                <div class="messaging-search-name">${student.name}</div>
                <div class="messaging-search-info">${student.no_matric} • ${student.progname}</div>
              </div>
            </div>
          `).join('');
        }
        
        searchResults.classList.add('active');
      })
      .catch(error => {
        console.error('Error searching students:', error);
      });
    }
    
    function searchStudentsForSidebar(query) {
      fetch('/all/student/search', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ search: query })
      })
      .then(response => response.json())
      .then(students => {
        const searchResults = document.getElementById('sidebar-search-results');
        
        if (students.length === 0) {
          searchResults.innerHTML = '<div class="sidebar-search-item">No students found</div>';
        } else {
          searchResults.innerHTML = students.map(student => `
            <div class="sidebar-search-item" onclick="startStudentChatFromSidebar('${student.ic}', '${student.name}')">
              <div class="sidebar-search-avatar">
                ${student.name.charAt(0).toUpperCase()}
              </div>
              <div class="sidebar-search-info">
                <div class="sidebar-search-name">${student.name}</div>
                <div class="sidebar-search-details">${student.no_matric}</div>
              </div>
            </div>
          `).join('');
        }
        
        searchResults.classList.add('active');
      })
      .catch(error => {
        console.error('Error searching students:', error);
      });
    }
    
    function startStudentChat(studentIc, studentName) {
      // Hide search results and clear search
      const searchResults = document.getElementById('messaging-search-results');
      const searchInput = document.getElementById('messaging-student-search');
      if (searchResults) searchResults.classList.remove('active');
      if (searchInput) searchInput.value = '';
      
      // Close messaging panel
      toggleMessagingPanel();
      
      // Check if TextBox component is available
      if (window.textBoxComponent) {
        currentStudentChat = studentIc;
        window.textBoxComponent.openStudentChat(studentIc, studentName);
      } else {
        // Fallback - load the chat in a modal or new window
        openStudentChatModal(studentIc, studentName);
      }
    }
    
    function startStudentChatFromSidebar(studentIc, studentName) {
      // Hide sidebar search results and clear search
      const sidebarSearchResults = document.getElementById('sidebar-search-results');
      const sidebarSearchInput = document.getElementById('sidebar-student-search');
      if (sidebarSearchResults) sidebarSearchResults.classList.remove('active');
      if (sidebarSearchInput) sidebarSearchInput.value = '';
      
      // Check if TextBox component is available
      if (window.textBoxComponent) {
        currentStudentChat = studentIc;
        window.textBoxComponent.openStudentChat(studentIc, studentName);
      } else {
        // Fallback - load the chat in a modal or new window
        openStudentChatModal(studentIc, studentName);
      }
    }
    
    function openStudentChatModal(studentIc, studentName) {
      // Create a modal for student chat
      const modal = document.createElement('div');
      modal.innerHTML = `
        <div class="modal fade" id="studentChatModal" tabindex="-1">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Chat with ${studentName}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div id="student-chat-container" data-student-ic="${studentIc}" data-student-name="${studentName}"></div>
              </div>
            </div>
          </div>
        </div>
      `;
      
      document.body.appendChild(modal);
      
      // Show the modal
      const modalElement = new bootstrap.Modal(document.getElementById('studentChatModal'));
      modalElement.show();
      
      // Clean up when modal is closed
      document.getElementById('studentChatModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
      });
    }
    
    function loadStudentConversations() {
      fetch('/all/student/conversations')
        .then(response => response.json())
        .then(conversations => {
          const container = document.getElementById('messaging-conversations');
          
          if (conversations.length === 0) {
            container.innerHTML = `
              <div class="messaging-empty-state">
                <i data-feather="message-square"></i>
                <p>No conversations yet</p>
                <span>Search for students to start chatting!</span>
              </div>
            `;
          } else {
            container.innerHTML = conversations.map(conv => {
              const lastMessage = conv.last_message;
              const student = conv.student;
              const unreadCount = conv.unread_count;
              
              // Format time
              const messageTime = lastMessage && lastMessage.datetime ? 
                new Date(lastMessage.datetime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '';
              
              // Format message preview
              let messagePreview = 'No messages yet';
              if (lastMessage) {
                if (lastMessage.message && lastMessage.message.trim()) {
                  messagePreview = lastMessage.message;
                } else if (lastMessage.image_url) {
                  messagePreview = '📷 Photo';
                }
              }
              
              return `
                <div class="messaging-conversation-item" onclick="startStudentChat('${student.ic}', '${student.name}')">
                  <div class="messaging-conversation-avatar online">
                    ${student.name.charAt(0).toUpperCase()}
                  </div>
                  <div class="messaging-conversation-details">
                    <div class="messaging-conversation-header">
                      <div class="messaging-conversation-name">${student.name}</div>
                      <div class="messaging-conversation-time">${messageTime}</div>
                    </div>
                    <div class="messaging-conversation-preview ${unreadCount > 0 ? 'unread' : ''}">
                      ${messagePreview}
                    </div>
                  </div>
                  <div class="messaging-conversation-meta">
                    ${unreadCount > 0 ? `<div class="messaging-unread-badge">${unreadCount}</div>` : ''}
                  </div>
                </div>
              `;
            }).join('');
          }
          
          // Re-initialize feather icons
          if (window.feather) {
            feather.replace();
          }
          
          // Update total unread count
          const totalUnread = conversations.reduce((sum, conv) => sum + conv.unread_count, 0);
          const countElement = document.getElementById('total-student-messages-count');
          if (totalUnread > 0) {
            countElement.textContent = totalUnread;
            countElement.classList.remove('hidden');
          } else {
            countElement.classList.add('hidden');
          }
        })
        .catch(error => {
          console.error('Error loading conversations:', error);
        });
    }
    
    function loadSidebarConversations() {
      fetch('/all/student/conversations')
        .then(response => response.json())
        .then(conversations => {
          const container = document.getElementById('sidebar-recent-conversations');
          
          if (conversations.length === 0) {
            container.innerHTML = `
              <div class="sidebar-empty-conversations">
                No recent conversations
              </div>
            `;
          } else {
            // Show only the first 3 conversations for the sidebar
            const recentConversations = conversations.slice(0, 3);
            
            container.innerHTML = recentConversations.map(conv => {
              const lastMessage = conv.last_message;
              const student = conv.student;
              const unreadCount = conv.unread_count;
              
              // Format message preview
              let messagePreview = 'No messages yet';
              if (lastMessage) {
                if (lastMessage.message && lastMessage.message.trim()) {
                  messagePreview = lastMessage.message;
                } else if (lastMessage.image_url) {
                  messagePreview = '📷 Photo';
                }
              }
              
              return `
                <div class="sidebar-conversation-item" onclick="startStudentChatFromSidebar('${student.ic}', '${student.name}')">
                  <div class="sidebar-conversation-avatar online">
                    ${student.name.charAt(0).toUpperCase()}
                  </div>
                  <div class="sidebar-conversation-details">
                    <div class="sidebar-conversation-name">${student.name}</div>
                    <div class="sidebar-conversation-preview ${unreadCount > 0 ? 'unread' : ''}">
                      ${messagePreview}
                    </div>
                  </div>
                  ${unreadCount > 0 ? `<div class="sidebar-conversation-unread">${unreadCount}</div>` : ''}
                </div>
              `;
            }).join('');
          }
          
          // Update sidebar unread count
          const totalUnread = conversations.reduce((sum, conv) => sum + conv.unread_count, 0);
          const sidebarCountElement = document.getElementById('sidebar-student-messages-count');
          if (totalUnread > 0) {
            sidebarCountElement.textContent = totalUnread;
            sidebarCountElement.classList.remove('hidden');
          } else {
            sidebarCountElement.classList.add('hidden');
          }
        })
        .catch(error => {
          console.error('Error loading sidebar conversations:', error);
        });
    }
    
    // Function to be called by TextBox component for student messaging
    function getStudentMessage(ic, type) {
      if (type === 'STUDENT_TO_STUDENT') {
        return fetch('/all/student/getMessages', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ recipient_ic: ic })
        }).then(response => response.json());
      } else {
        // Fallback to original getMessage for departments
        return fetch('/all/massage/user/getMassage', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ ic: ic, type: type })
        }).then(response => response.json());
      }
    }
    
    // Function to send student message
    function sendStudentMessage(recipientIc, message, imageFile) {
      const formData = new FormData();
      formData.append('recipient_ic', recipientIc);
      formData.append('message', message || '');
      if (imageFile) {
        formData.append('image', imageFile);
      }
      
      return fetch('/all/student/sendMessage', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      }).then(response => response.json());
    }
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
  {{-- <script src="{{ asset('assets/assets/vendor_components/select2/dist/js/select2.full.js') }}"></script> --}}
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
  {{-- <script src="http://spp3.intds.com.my/assets/js/formplugins/select2/select2.bundle.js"></script> --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="{{ asset('assets/src/js/pages/component-animations-css3.js')}}"></script>
  
  @yield('content')
</body>
</html>