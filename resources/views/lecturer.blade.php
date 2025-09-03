@extends('layouts.ketua_program')

@section('main')

<style>
    :root {
        --primary-color: #4361ee;
        --hover-color: #3f37c9;
        --accent-color: #4cc9f0;
        --folder-color: #f72585;
        --folder-hover: #7209b7;
        --file-color: #ff5e5b;
        --file-hover: #d62828;
        --link-color: #06d6a0;
        --link-hover: #079676;
        --card-bg: #ffffff;
        --bg-light: #f5f7ff;
        --text-color: #333;
        --card-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
        --hover-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
        --accent-shadow: 0 8px 25px rgba(76, 201, 240, 0.25);
    }
    
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .content-wrapper {
        background-color: var(--bg-light);
        background-image: 
            radial-gradient(circle at 25% 10%, rgba(67, 97, 238, 0.05) 0%, transparent 80%),
            radial-gradient(circle at 80% 80%, rgba(76, 201, 240, 0.05) 0%, transparent 80%);
        padding-bottom: 40px;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        color: white;
        border-radius: 20px;
        padding: 25px 35px;
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(76, 201, 240, 0.2);
    }
    
    .page-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -30%;
        width: 80%;
        height: 200%;
        background: rgba(255, 255, 255, 0.08);
        transform: rotate(30deg);
    }
    
    .page-header::after {
        content: "";
        position: absolute;
        bottom: -50%;
        left: -30%;
        width: 80%;
        height: 200%;
        background: rgba(255, 255, 255, 0.05);
        transform: rotate(-20deg);
    }
    
    .breadcrumb {
        background: transparent;
        padding: 0;
        z-index: 2;
        position: relative;
    }
    
    .breadcrumb-item a, .breadcrumb-item {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #fff;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #fff;
        font-weight: 600;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .content-card {
        background-color: var(--card-bg);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 25px;
        transform: translateY(20px);
        opacity: 0;
    }
    
    .content-card:hover {
        box-shadow: var(--hover-shadow);
        transform: translateY(-8px) scale(1.01);
    }
    
    .form-control {
        border-radius: 12px;
        padding: 12px 18px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }
    
    .form-select {
        border-radius: 12px;
        padding: 12px 18px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        height: auto;
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
    }
    
    /* Navigation tabs vertical centering */
    .nav-tabs {
        display: flex;
        align-items: center;
        padding: 0;
        margin-bottom: 1.5rem;
        border-bottom: none;
    }
    
    .nav-tabs .nav-item {
        display: flex;
        align-items: center;
    }
    
    .nav-tabs .nav-link {
        display: flex;
        align-items: center;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 600;
        color: var(--text-color);
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .nav-tabs .nav-link.active {
        color: white;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
    }
    
    .nav-tabs .nav-link i, 
    .nav-tabs .nav-link span {
        display: inline-flex;
        align-items: center;
    }
    
    .nav-tabs .nav-link span {
        margin-left: 8px;
    }
    
    .search-wrapper {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--card-shadow);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    
    .search-wrapper:hover {
        box-shadow: var(--hover-shadow);
    }
    
    .course-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 32em;
        border: none;
        transform: translateY(20px);
        opacity: 0;
        background: white;
    }
    
    .course-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--hover-shadow);
    }
    
    .course-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }
    
    .course-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    .course-card:hover .course-image img {
        transform: scale(1.1);
    }
    
    .fx-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(67, 97, 238, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .course-card:hover .fx-overlay {
        opacity: 1;
    }
    
    .fx-info {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .course-view-btn {
        background: white;
        color: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .course-view-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.25);
    }
    
    .course-body {
        padding: 25px;
    }
    
    .course-title {
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 15px;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    
    .course-card:hover .course-title {
        color: var(--primary-color);
    }
    
    .course-detail {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
        color: #64748b;
    }
    
    .course-detail strong {
        color: var(--text-color);
        min-width: 70px;
    }
    
    .badge-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 12px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
    }
    
    .search-icon {
        color: var(--primary-color);
    }
    
    .input-group-text {
        background: transparent;
        border: none;
    }
    
    /* Fun animations */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .float-animation {
        animation: float 6s ease-in-out infinite;
    }
    
    .pulse-animation {
        animation: pulse 3s ease-in-out infinite;
    }
    
    /* Confetti effect */
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #f72585;
        opacity: 0.7;
        animation: confetti-fall 5s ease-in-out infinite;
        z-index: 1;
    }
    
    .confetti:nth-child(2n) {
        background-color: #4cc9f0;
        width: 12px;
        height: 12px;
    }
    
    .confetti:nth-child(3n) {
        background-color: #06d6a0;
        width: 8px;
        height: 8px;
    }
    
    .confetti:nth-child(4n) {
        background-color: #ffd166;
        width: 15px;
        height: 15px;
    }
    
    @keyframes confetti-fall {
        0% { transform: translateY(-100px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(500px) rotate(360deg); opacity: 0; }
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title mb-1">Welcome to Your Dashboard</h3>
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Home</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="search-wrapper">
                <div id="panel-1" class="panel">
                    <ul class="nav nav-tabs nav-bordered mb-4">
                        <li class="nav-item">
                            <a class="nav-link active mb-4" id="v-pills-courselist-tab" controller="" table-data="table_courselist" data-bs-toggle="tab" data-toggle="pill" href="#v-pills-courselist" role="tab" aria-controls="v-pills-courselist" aria-selected="true">
                                <i class="fa fa-graduation-cap"></i>
                                <span>My Courses</span>
                            </a>
                        </li>
                    </ul>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Search Courses</label>
                                <div class="input-group">
                                    <input id="search-txt" placeholder="Search by course name or code..." type="text" class="form-control" autocomplete="off" aria-invalid="false" value="">
                                    <span class="input-group-text">
                                        <i class="fa fa-search search-icon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                              
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Session</label>
                                <select id="session" class="form-select">
                                    <option selected value="" disabled>Select Session</option>
                                    @foreach ($sessions as $ses)
                                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                                    @endforeach
                                </select>
                                <label id="coursestatus_error" class="text-danger small error-field"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="courselist">
                <div class="row">
                    @foreach ($data as $key)
                    <div class="col-md-4 mb-2 course-item">
                        <div class="course-card">
                            <div class="course-image">
                                <img 
                                src="{{ asset('assets/images/course_image/' . $key->progcode . '/' . str_replace(' ', '_', $key->course_code) . '.jpg') ?? asset('assets/images/uniti.jpg') }}" 
                                onerror="this.onerror=null;this.src='{{ asset('assets/images/uniti.jpg') }}';" 
                                alt="{{ $key->course_name }}">                              
                                <div class="fx-overlay">
                                    <ul class="fx-info">
                                        <li>
                                            <a href="/lecturer/{{ $key->id }}?session={{ $key->SessionID }}" class="course-view-btn">
                                                <i class="fa fa-paper-plane"></i> View Course
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="course-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="course-title">{{ ucwords($key->course_name) }}</h5>
                                    <span class="badge-active">ACTIVE</span>
                                </div>
                                <div class="course-detail">
                                    <strong>Code:</strong> {{ ucwords($key->course_code) }}
                                                                    assets/images/course_image/{{ $key->progcode}}/{{ str_replace(' ', '_', $key->course_code) }}
                                </div>
                                <div class="course-detail">
                                    <strong>Session:</strong> {{ ucwords($key->SessionName) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>

<script type="text/javascript">
    // Search and filter functionality
    var search_input = "";
    var selected_session = "";
    
    $(document).on('keyup', '#search-txt', async function(e){
        search_input = $(e.target).val();
        await getCourseList(search_input, selected_session);
    });
    
    $(document).on('change', '#session', async function(e){
        selected_session = $(e.target).val();
        await getCourseList(search_input, selected_session);
    });
    
    function getCourseList(search, session) {
        return $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('lecturer/course/filter') }}",
            method: 'POST',
            data: {search: search, session: session},
            error: function(err){
                Swal.fire({
                    title: "Error",
                    text: "Something went wrong while fetching courses",
                    icon: "error"
                });
                console.log(err);
            },
            success: function(data){
                $('#courselist').html(data);
                animateCards();
            }
        });
    }
    
    // Add animation to cards
    function animateCards() {
        const cards = document.querySelectorAll('.course-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });
    }
    
    // Create confetti effect
    function createConfetti() {
        const container = document.querySelector('.content-wrapper');
        const confettiCount = 20;
        
        for (let i = 0; i < confettiCount; i++) {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            
            // Random positioning
            confetti.style.left = Math.random() * 100 + 'vw';
            
            // Random animation duration and delay
            confetti.style.animationDuration = (Math.random() * 3 + 3) + 's';
            confetti.style.animationDelay = Math.random() * 5 + 's';
            
            container.appendChild(confetti);
            
            // Remove confetti after animation
            setTimeout(() => {
                confetti.remove();
            }, 8000);
        }
    }
    
    // Run animations when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Create initial confetti
        createConfetti();
        
        // Set interval for occasional confetti
        setInterval(createConfetti, 10000);
        
        // Animate cards on load
        animateCards();
        
        // Add toast notification on load
        setTimeout(() => {
            Swal.fire({
                title: "Welcome Back!",
                text: "You have courses waiting for you",
                icon: "success",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }, 1000);
    });
</script>

@endsection