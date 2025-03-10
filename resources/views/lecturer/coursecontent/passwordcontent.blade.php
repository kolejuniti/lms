@extends('layouts.lecturer.lecturer')

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
    }
    
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .content-wrapper {
        background-color: var(--bg-light);
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
    
    .form-card {
        background-color: var(--card-bg);
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .form-card:hover {
        box-shadow: var(--hover-shadow);
    }
    
    .form-card-header {
        background: linear-gradient(135deg, var(--folder-color) 0%, var(--folder-hover) 100%);
        color: white;
        padding: 20px 30px;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }
    
    .form-card-header::before {
        content: "";
        position: absolute;
        top: -30%;
        right: -10%;
        width: 60%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(30deg);
    }
    
    .form-card-title {
        font-weight: 600;
        margin: 0;
        position: relative;
        z-index: 2;
    }
    
    .form-card-body {
        padding: 30px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
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
    
    .text-danger {
        display: block;
        margin-top: 8px;
        font-size: 14px;
    }
    
    .form-card-footer {
        background-color: rgba(245, 247, 250, 0.5);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 20px 30px;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.15);
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.25);
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .lock-icon {
        width: 40px;
        height: 40px;
        margin-right: 15px;
        fill: white;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title mb-1">Check Password</h3>
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Material Gallery</li>
                                <li class="breadcrumb-item active" aria-current="page">Check Password</li>
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
                    <div class="col-12">
                        <div class="form-card">
                            <div class="form-card-header d-flex align-items-center">
                                <svg class="lock-icon" viewBox="0 0 24 24">
                                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zM9 8V6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9z" fill="white"/>
                                </svg>
                                <h4 class="form-card-title">Please Enter Password For Locked Folder</h4>
                            </div>
                            
                            <form action="/lecturer/content/material/sub/content/password/{{ $dir }}" method="POST">
                                @csrf
                                <div class="form-card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="pass">Password</label>
                                                <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-card-footer">
                                    <button type="submit" class="submit-btn float-end mb-4">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
    // Session alert handling
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
        Swal.fire({
            title: "Notification",
            text: msg,
            icon: "info",
            confirmButtonText: "Got it"
        });
    }
    
    // Animation for form card appearance
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const formCard = document.querySelector('.form-card');
            if (formCard) {
                formCard.style.opacity = '1';
                formCard.style.transform = 'translateY(0)';
            }
        }, 100);
    });
</script>

@endsection