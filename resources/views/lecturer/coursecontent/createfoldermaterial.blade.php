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
    
    .folder-icon {
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
                    <h3 class="page-title mb-1">Create Material Folder</h3>
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item">Material Gallery</li>
                                <li class="breadcrumb-item active" aria-current="page">Create Material Folder</li>
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
                                <svg class="folder-icon" viewBox="0 0 309.27 309.27">
                                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                                </svg>
                                <h4 class="form-card-title">Create Material Folder</h4>
                            </div>
                            
                            <form action="/lecturer/content/material/store/{{ request()->dir }}" method="POST">
                                @csrf
                                <div class="form-card-body">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label class="form-label" for="chapter">Chapter No.</label>
                                                <input type="number" class="form-control" id="chapter" placeholder="Enter chapter no i.e : 1" name="chapter" value="{{ old('chapter') }}" min="1">
                                                <span class="text-danger">@error('chapter'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Folder Name</label>
                                                <input type="text" class="form-control" id="name" placeholder="Enter folder name i.e : Full name" name="name" value="{{ old('name') }}">
                                                <span class="text-danger">@error('name'){{ $message }}@enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="ic">Password (OPTIONAL)</label>
                                                <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter Password">
                                                <span class="text-danger">
                                                    @error('pass'){{ $message }}@enderror
                                                    @error('conpass'){{ $message }}@enderror
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="pass_confirm" hidden>
                                            <div class="form-group">
                                                <label class="form-label" for="ic">Confirm Password</label>
                                                <input type="password" class="form-control" id="conpass" name="conpass" placeholder="Enter Confirm Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-card-footer">
                                    <button type="submit" class="submit-btn float-end mb-4">Create Folder</button>
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
    // Show password confirmation field when password is being entered
    $(document).on('keypress', '#pass', function(){
        document.getElementById('pass_confirm').hidden = false;
        document.getElementById('pass_confirm').required = true;
    });
    
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