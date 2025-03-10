@extends('layouts.lecturer.lecturer')

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
    
    .material-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border-radius: 16px;
        overflow: hidden;
        background-color: var(--card-bg);
        box-shadow: var(--card-shadow);
        height: 100%;
        border: none;
        position: relative;
        backdrop-filter: blur(10px);
        cursor: pointer;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .material-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--hover-shadow);
    }
    
    .folder-icon-container {
        transition: all 0.4s ease;
        position: relative;
        z-index: 2;
    }
    
    .folder-icon {
        transition: all 0.4s ease;
        fill: var(--folder-color);
        cursor: pointer;
    }
    
    .material-card:hover .folder-icon {
        fill: var(--folder-hover);
        transform: scale(1.05);
    }
    
    .folder-icon-background {
        width: 110px;
        height: 110px;
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(114, 9, 183, 0.05) 100%);
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
    }
    
    .material-card:hover .folder-icon-background {
        transform: scale(1.1);
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.15) 0%, rgba(114, 9, 183, 0.1) 100%);
    }
    
    .folder-name {
        font-weight: 600;
        color: var(--text-color);
        margin-top: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .material-card:hover .folder-name {
        color: var(--primary-color);
    }
    
    .btn-floating {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border: none;
        transition: all 0.3s ease;
        background: white;
        z-index: 11;
    }
    
    .btn-floating:hover {
        transform: translateX(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
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
    
    .btn-new-folder {
        background: linear-gradient(45deg, #f72585, #7209b7);
        border: none;
        box-shadow: 0 8px 20px rgba(247, 37, 133, 0.3);
        border-radius: 50px;
        padding: 12px 28px;
        transition: all 0.3s ease;
        font-weight: 600;
        color: white;
        position: relative;
        z-index: 5;
    }
    
    .btn-new-folder:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(247, 37, 133, 0.4);
        background: linear-gradient(45deg, #f72585, #560bad);
    }
    
    .content-wrapper {
        background-color: var(--bg-light);
    }
    
    .empty-state {
        padding: 60px;
        text-align: center;
        background: white;
        border-radius: 20px;
        margin: 20px 0;
        box-shadow: var(--card-shadow);
    }
    
    .rename-input {
        border-radius: 12px;
        padding: 10px 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
        font-size: 14px;
    }
    
    .rename-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }
    
    .password-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(114, 9, 183, 0.8);
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 3;
        box-shadow: 0 3px 10px rgba(114, 9, 183, 0.3);
    }
    
    .folder-actions {
        position: absolute;
        top: 15px;
        right: 15px;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .material-card:hover .folder-actions {
        opacity: 1;
        transform: translateX(0);
    }
    
    .folder-actions .btn-floating {
        margin: 5px 0;
    }
    
    .folder-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 16px;
        background: linear-gradient(to right,
            rgba(255,255,255,0) 50%,
            rgba(255,255,255,0.6) 85%,
            rgba(255,255,255,0.85) 100%);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
    }
    
    .material-card:hover .folder-overlay {
        opacity: 1;
    }
    
    .box {
        background: transparent;
        box-shadow: none;
        border-radius: 20px;
    }
    
    .box-body {
        padding: 1.5rem;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title mb-1">Material Gallery</h3>
                    <p class="mb-0">{{  $course->course_name }} ({{ $course->course_code }})</p>
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active" aria-current="page">Material Gallery</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div>
                    <button id="newFolder" class="btn btn-new-folder">
                        <i class="fa fa-plus me-2"></i>New Folder
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div id="material-div">
                                <div id="material-directory" class="row">
                                    @if(count($folder) == 0)
                                    <div class="col-12">
                                        <div class="empty-state">
                                            <svg width="150" height="150" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 20px;">
                                                <path opacity="0.2" d="M4 21h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8.46l-2-2H4c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2z" fill="#f72585"/>
                                                <path d="M20 6h-8l-2-2H4C2.9 4 2 4.9 2 6v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10zm-8-4h2v2h-2zm-4 0h2v2H8zM12 8h2v2h-2zm4 0h2v2h-2z" fill="#7209b7"/>
                                            </svg>
                                            <h3 class="mb-3">No folders yet</h3>
                                            <p class="text-muted mb-4">This is where your course materials will appear.<br>Start by creating your first folder.</p>
                                            <button id="emptyStateNewFolder" class="btn btn-new-folder">
                                                <i class="fa fa-plus me-2"></i>Create New Folder
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @foreach ($folder as $i => $fold)
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="material-card position-relative">
                                            <a href="/lecturer/content/material/{{ $fold->DrID }}" class="text-decoration-none d-block">
                                                <div class="text-center p-4">
                                                    <div class="folder-icon-container">
                                                        <div class="folder-icon-background">
                                                            <svg class="folder-icon" width="80" height="80" viewBox="0 0 309.27 309.27">
                                                                <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                                                <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                                                <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    @if($fold->Password != null)
                                                    <div class="password-icon">
                                                        <i class="fa fa-lock"></i>
                                                    </div>
                                                    @endif
                                                    <h5 class="folder-name px-2">
                                                        {{ ($fold->newDrName != null) ? $fold->newDrName : $fold->DrName }}
                                                    </h5>
                                                </div>
                                            </a>
                                            
                                            <div class="folder-overlay"></div>
                                            
                                            <div class="folder-actions">
                                                <button data-bs-toggle="collapse" data-bs-target="#rename-material-{{ $fold->DrID }}" 
                                                    aria-expanded="false" aria-controls="rename-material-{{ $fold->DrID }}"
                                                    class="btn btn-floating" data-bs-toggle="tooltip" title="Rename">
                                                    <i class="mdi mdi-pencil text-primary"></i>
                                                </button>
                                                    
                                                <button onclick="deleteMaterial('{{ $fold->DrID }}')" 
                                                    class="btn btn-floating" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="mdi mdi-delete text-danger"></i>
                                                </button>
                                            </div>

                                            <form method="post" name="form-rename" id="form-rename" class="px-3 pb-3"> 
                                                <div id="rename-material-{{ $fold->DrID }}" class="collapse input-group mb-0" data-bs-parent="#material-directory">
                                                    <input type="text" class="form-control rename-input" id="test-{{ $fold->DrID }}" 
                                                        value="{{ ($fold->newDrName != null) ? $fold->newDrName : $fold->DrName }}"> 
                                                    <div class="input-group-append ms-2">
                                                        <button class="btn btn-floating btn-light" type="button" onclick="renameMaterial('{{ $fold->DrID }}')">
                                                            <i class="fa fa-check text-success"></i>
                                                        </button>
                                                        <button class="btn btn-floating btn-light ms-1" data-bs-toggle="collapse" 
                                                            data-bs-target="#rename-material-{{ $fold->DrID }}" aria-expanded="false">
                                                            <i class="fa fa-times text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
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
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    $(document).on('click', '#newFolder, #emptyStateNewFolder', function() {
        location.href = "/lecturer/content/{{ $course_id }}/create";
    })

    function deleteMaterial(dir) {     
        Swal.fire({
            title: "Are you sure?",
            text: "This folder will be permanently deleted",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            borderRadius: "15px"
        }).then(function(res) {
            if (res.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: "Deleting...",
                    text: "Please wait",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('lecturer/content/delete') }}",
                    method: 'DELETE',
                    data: {dir: dir},
                    error: function(err) {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong.",
                            icon: "error"
                        });
                        console.log(err);
                    },
                    success: function(data) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your folder has been deleted.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    }

    function renameMaterial(dir) {
        var name = document.getElementById('test-'+dir).value;
        
        if (!name.trim()) {
            Swal.fire({
                title: "Error!",
                text: "Folder name cannot be empty",
                icon: "error",
                timer: 1500
            });
            return;
        }
        
        // Show loading state
        Swal.fire({
            title: "Renaming...",
            text: "Please wait",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('lecturer/content/rename') }}",
            method: 'POST',
            data: {dir: dir, name: name},
            error: function(err) {
                Swal.fire({
                    title: "Error!",
                    text: "Something went wrong.",
                    icon: "error"
                });
                console.log(err);
            },
            success: function(data) {
                Swal.fire({
                    title: "Success!",
                    text: "Folder renamed successfully",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }
    
    // Add animation class to folders when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const folders = document.querySelectorAll('.material-card');
        folders.forEach((folder, index) => {
            setTimeout(() => {
                folder.style.opacity = '1';
                folder.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Make sure the entire folder card is clickable
        folders.forEach(folder => {
            folder.addEventListener('click', function(e) {
                // Only navigate if the click isn't on one of the action buttons
                if (!e.target.closest('.btn-floating') && !e.target.closest('.collapse')) {
                    const link = this.querySelector('a').getAttribute('href');
                    window.location.href = link;
                }
            });
        });
    });
</script>
@stop