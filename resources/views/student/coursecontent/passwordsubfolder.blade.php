@extends('layouts.student.student')

@section('main')

<style>
    :root {
        --primary-color: #4361ee;
        --hover-color: #3f37c9;
        --accent-color: #4cc9f0;
        --folder-color: #f72585;
        --folder-hover: #7209b7;
        --card-bg: #ffffff;
        --bg-light: #f5f7ff;
        --text-color: #333;
        --card-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
        --hover-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }
    
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
    
    .content-wrapper {
        background-color: var(--bg-light);
    }
    
    .password-card {
        background-color: var(--card-bg);
        border-radius: 20px;
        border: none;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
        overflow: hidden;
    }
    
    .password-card-header {
        background: linear-gradient(135deg, var(--folder-color) 0%, var(--folder-hover) 100%);
        color: white;
        padding: 20px 25px;
        border: none;
        position: relative;
    }
    
    .password-card-header h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        z-index: 2;
        position: relative;
    }
    
    .password-card-header::before {
        content: "";
        position: absolute;
        top: -10%;
        right: -10%;
        width: 40%;
        height: 150%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(25deg);
    }
    
    .password-card-body {
        padding: 30px 25px;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 10px;
        display: block;
    }
    
    .form-control {
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 12px 18px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }
    
    .password-card-footer {
        background-color: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 20px 25px;
    }
    
    .btn-primary-custom {
        background: linear-gradient(45deg, var(--folder-color), var(--folder-hover));
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        color: white;
        box-shadow: 0 8px 15px rgba(247, 37, 133, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(247, 37, 133, 0.3);
        background: linear-gradient(45deg, var(--folder-hover), var(--folder-color));
    }
    
    .lock-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(114, 9, 183, 0.05) 100%);
        border-radius: 50%;
        margin: 0 auto 25px;
    }
    
    .lock-icon svg {
        fill: var(--folder-color);
        width: 40px;
        height: 40px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title mb-1">Protected Content</h3>
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item"><a href="/student/content">Material Gallery</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Password Required</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="password-card">
                            <div class="password-card-header">
                                <h3>Password Protected Folder</h3>
                            </div>
                            
                            <div class="password-card-body text-center">
                                <div class="lock-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                                    </svg>
                                </div>
                                
                                <p class="mb-4">This folder is password protected. Please enter the password to access its contents.</p>
                                
                                <form action="/student/content/material/sub/password/{{ $dir }}" method="POST">
                                    @csrf
                                    <div class="form-group text-start">
                                        <label class="form-label" for="pass">Password</label>
                                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter password to access content">
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary-custom">
                                            <i class="fa fa-unlock me-2"></i> Unlock Content
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
    // Add animation when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.password-card');
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });

    // Handle alert messages
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
        Swal.fire({
            title: "Access Denied",
            text: msg,
            icon: "error",
            confirmButtonColor: "#f72585",
            confirmButtonText: "Try Again"
        });
    }
</script>
@endsection