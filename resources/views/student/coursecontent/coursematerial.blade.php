@extends('layouts.student.student')

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
    
    .folder-icon-container, .file-icon-container {
        transition: all 0.4s ease;
        position: relative;
        z-index: 2;
    }
    
    .folder-icon {
        transition: all 0.4s ease;
        fill: var(--folder-color);
        cursor: pointer;
    }
    
    .file-icon {
        transition: all 0.4s ease;
        fill: var(--file-color);
        cursor: pointer;
    }
    
    .material-card:hover .folder-icon {
        fill: var(--folder-hover);
        transform: scale(1.05);
    }
    
    .material-card:hover .file-icon {
        fill: var(--file-hover);
        transform: scale(1.05);
    }
    
    .icon-background {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s ease;
    }
    
    .folder-icon-background {
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.1) 0%, rgba(114, 9, 183, 0.05) 100%);
    }
    
    .file-icon-background {
        background: linear-gradient(135deg, rgba(255, 94, 91, 0.1) 0%, rgba(214, 40, 40, 0.05) 100%);
    }
    
    .material-card:hover .folder-icon-background {
        transform: scale(1.1);
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.15) 0%, rgba(114, 9, 183, 0.1) 100%);
    }
    
    .material-card:hover .file-icon-background {
        transform: scale(1.1);
        background: linear-gradient(135deg, rgba(255, 94, 91, 0.15) 0%, rgba(214, 40, 40, 0.1) 100%);
    }
    
    .content-name {
        font-weight: 600;
        color: var(--text-color);
        margin-top: 15px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 16px;
        transition: all 0.3s ease;
        padding: 0 15px;
    }
    
    .material-card:hover .content-name {
        color: var(--primary-color);
    }
    
    .btn-info-floating {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border: none;
        transition: all 0.3s ease;
        background: var(--accent-color);
        color: white;
        z-index: 11;
        margin-right: 8px;
    }
    
    .btn-info-floating:hover {
        opacity: 1;
        transform: scale(1.1);
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
    
    .content-overlay {
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
    
    .material-card:hover .content-overlay {
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
    
    .back-folder {
        background-color: rgba(76, 201, 240, 0.15);
    }
    
    .back-folder .folder-icon {
        fill: #4cc9f0;
    }
    
    .back-folder:hover .folder-icon {
        fill: #3a0ca3;
    }
    
    .file-extension-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: var(--file-color);
        color: white;
        padding: 2px 8px;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: bold;
        border-radius: 8px 0 16px 0;
    }
    
    .video-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        height: 100%;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
        position: relative;
        background-color: var(--card-bg);
    }
    
    .video-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--hover-shadow);
    }
    
    .video-container {
        position: relative;
        width: 100%;
        padding-top: 60%;
    }
    
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .video-actions {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        z-index: 5;
    }
    
    .video-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 25%;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 2;
    }
    
    .video-card:hover .video-overlay {
        opacity: 1;
    }
    
    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .custom-modal.show {
        opacity: 1;
        visibility: visible;
    }
    
    .custom-modal-content {
        background-color: white;
        border-radius: 16px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        transform: translateY(20px);
        transition: all 0.3s ease;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }
    
    .custom-modal.show .custom-modal-content {
        transform: translateY(0);
    }
    
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-weight: 600;
        font-size: 20px;
        color: var(--text-color);
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #aaa;
        transition: all 0.2s ease;
    }
    
    .modal-close:hover {
        color: var(--text-color);
    }
    
    .modal-body {
        margin-bottom: 20px;
    }
    
    .modal-body-centered-text p {
        text-align: center;
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .cke_chrome {
        border: 1px solid #eee;
        border-radius: 8px;
        box-shadow: 0 0 0 #eee;
        overflow: hidden;
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 60px;
        text-align: center;
        background: white;
        border-radius: 20px;
        margin: 20px 0;
        box-shadow: var(--card-shadow);
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
                    <div class="d-inline-block align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item"><a href="/student/content">Material Gallery</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Content</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="box-title">Course Materials</h4>
                        </div>
                        <div class="box-body">
                            <div id="material-div">
                                <form action="/student/content/material/sub/content/upload/{{ $dirid }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div id="material-directory" class="row">
                                        <!-- Back Folder -->
                                        <div class="col-md-3 col-sm-6 mb-4">
                                            <div class="material-card back-folder">
                                                <a href="/student/content/material/sub/prev/{{ $prev }}" class="text-decoration-none d-block">
                                                    <div class="text-center p-4">
                                                        <div class="folder-icon-container">
                                                            <div class="icon-background folder-icon-background">
                                                                <svg class="folder-icon" width="80" height="80" viewBox="0 0 309.27 309.27">
                                                                    <path d="m260.94 43.491h-135.3s-18.324-28.994-28.994-28.994h-48.323c-10.67 0-19.329 8.65-19.329 19.329v222.29c0 10.67 8.659 19.329 19.329 19.329h212.62c10.67 0 19.329-8.659 19.329-19.329v-193.29c0-10.67-8.659-19.329-19.329-19.329z" fill="#D0994B"/>
                                                                    <path d="M28.994,72.484h251.279v77.317H28.994V72.484z" fill="#E4E7E7"/>
                                                                    <path d="m19.329 91.814h270.61c10.67 0 19.329 8.65 19.329 19.329l-19.329 164.3c0 10.67-8.659 19.329-19.329 19.329h-231.95c-10.67 0-19.329-8.659-19.329-19.329l-19.329-164.3c0-10.68 8.659-19.329 19.329-19.329z" fill="#F4B459"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h5 class="content-name">
                                                            <i class="ti ti-more-alt me-1"></i> Back to Previous
                                                        </h5>
                                                    </div>
                                                </a>
                                                <div class="content-overlay"></div>
                                            </div>
                                        </div>

                                        <!-- Files -->
                                        @foreach ($classmaterial as $key => $mats)
                                        <div class="col-md-3 col-sm-6 mb-4">
                                            <div class="material-card position-relative">
                                                <a href="{{ Storage::disk('linode')->url($mats) }}" target="_blank" class="text-decoration-none d-block">
                                                    <div class="text-center p-4">
                                                        <div class="file-icon-container">
                                                            <div class="icon-background file-icon-background">
                                                                <svg class="file-icon" width="80" height="80" viewBox="0 0 512 512" xml:space="preserve">
                                                                    <path d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" fill="#E2E5E7"/>
                                                                    <path d="m384 128h96l-128-128v96c0 17.6 14.4 32 32 32z" fill="#B0B7BD"/>
                                                                    <polygon points="480 224 384 128 480 128" fill="#CAD1D8"/>
                                                                    <path d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z" fill="#F15642"/>
                                                                    <g fill="#fff">
                                                                        <path d="m101.74 303.15c0-4.224 3.328-8.832 8.688-8.832h29.552c16.64 0 31.616 11.136 31.616 32.48 0 20.224-14.976 31.488-31.616 31.488h-21.36v16.896c0 5.632-3.584 8.816-8.192 8.816-4.224 0-8.688-3.184-8.688-8.816v-72.032zm16.88 7.28v31.872h21.36c8.576 0 15.36-7.568 15.36-15.504 0-8.944-6.784-16.368-15.36-16.368h-21.36z"/>
                                                                        <path d="m196.66 384c-4.224 0-8.832-2.304-8.832-7.92v-72.672c0-4.592 4.608-7.936 8.832-7.936h29.296c58.464 0 57.184 88.528 1.152 88.528h-30.448zm8.064-72.912v57.312h21.232c34.544 0 36.08-57.312 0-57.312h-21.232z"/>
                                                                        <path d="m303.87 312.11v20.336h32.624c4.608 0 9.216 4.608 9.216 9.072 0 4.224-4.608 7.68-9.216 7.68h-32.624v26.864c0 4.48-3.184 7.92-7.664 7.92-5.632 0-9.072-3.44-9.072-7.92v-72.672c0-4.592 3.456-7.936 9.072-7.936h44.912c5.632 0 8.96 3.344 8.96 7.936 0 4.096-3.328 8.704-8.96 8.704h-37.248v0.016z"/>
                                                                    </g>
                                                                    <path d="m400 432h-304v16h304c8.8 0 16-7.2 16-16v-16c0 8.8-7.2 16-16 16z" fill="#CAD1D8"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h5 class="content-name">
                                                            {{ basename($mats) }}
                                                        </h5>
                                                    </div>
                                                </a>
                                                
                                                <div class="file-extension-icon">
                                                    {{ pathinfo(storage_path($mats), PATHINFO_EXTENSION) }}
                                                </div>
                                                
                                                <div class="content-overlay"></div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <!-- YouTube Videos -->
                                        @if($url != null)
                                            @foreach($url as $key => $ul)
                                                @php
                                                    $originalURL = $ul->url;
                                                    $search = 'https://www.youtube.com/watch?v=';
                                                    $replace = 'https://www.youtube.com/embed/';
                                                    $newURL = str_replace($search, $replace, $originalURL);
                                                @endphp
                                                <div class="col-md-3 col-sm-6 mb-4">
                                                    <div class="video-card">
                                                        <div class="video-container">
                                                            <iframe src="{{ $newURL }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                            <div class="video-overlay"></div>
                                                        </div>
                                                        <div class="video-actions">
                                                            <button type="button" class="btn btn-info-floating" id="infoButton{{ $key }}" data-bs-toggle="tooltip" title="View Description">
                                                                <i class="fa fa-info"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Description Modal -->
                                                    <div class="custom-modal" id="descriptionModal{{ $key }}">
                                                        <div class="custom-modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Video Description</h5>
                                                                <button type="button" class="modal-close" id="closeModal{{ $key }}">&times;</button>
                                                            </div>
                                                            <div class="modal-body modal-body-centered-text">
                                                                <p>{!! $ul->description !!}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            const infoButton{{ $key }} = document.getElementById('infoButton{{ $key }}');
                                                            const descriptionModal{{ $key }} = document.getElementById('descriptionModal{{ $key }}');
                                                            const closeModal{{ $key }} = document.getElementById('closeModal{{ $key }}');
                                                            
                                                            if (infoButton{{ $key }} && descriptionModal{{ $key }} && closeModal{{ $key }}) {
                                                                infoButton{{ $key }}.addEventListener('click', function() {
                                                                    descriptionModal{{ $key }}.classList.add('show');
                                                                });
                                                                
                                                                closeModal{{ $key }}.addEventListener('click', function() {
                                                                    descriptionModal{{ $key }}.classList.remove('show');
                                                                });
                                                                
                                                                // Close modal when clicking outside content
                                                                descriptionModal{{ $key }}.addEventListener('click', function(e) {
                                                                    if (e.target === descriptionModal{{ $key }}) {
                                                                        descriptionModal{{ $key }}.classList.remove('show');
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    </script>
                                                </div>
                                            @endforeach
                                        @endif
                                        
                                        <!-- Empty State -->
                                        @if(count($classmaterial) == 0 && ($url == null || count($url) == 0))
                                        <div class="col-12">
                                            <div class="empty-state">
                                                <svg width="150" height="150" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 20px;">
                                                    <path opacity="0.2" d="M4 21h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8.46l-2-2H4c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2z" fill="#f72585"/>
                                                    <path d="M20 6h-8l-2-2H4C2.9 4 2 4.9 2 6v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10zm-8-4h2v2h-2zm-4 0h2v2H8zM12 8h2v2h-2zm4 0h2v2h-2z" fill="#7209b7"/>
                                                </svg>
                                                <h3 class="mb-3">No materials yet</h3>
                                                <p class="text-muted mb-4">This folder is currently empty.<br>Please check back later for materials.</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Add animation class to cards when page loads
        const cards = document.querySelectorAll('.material-card, .video-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Make sure the entire card is clickable
        const materialCards = document.querySelectorAll('.material-card');
        materialCards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Only navigate if the click isn't on one of the action buttons
                if (!e.target.closest('.btn-floating') && !e.target.closest('.collapse')) {
                    const link = this.querySelector('a');
                    if (link) {
                        if (link.getAttribute('target') === '_blank') {
                            window.open(link.getAttribute('href'), '_blank');
                        } else {
                            window.location.href = link.getAttribute('href');
                        }
                    }
                }
            });
        });
    });
    
    function deleteUrl(id) {     
        Swal.fire({
            title: "Are you sure?",
            text: "This link will be permanently deleted",
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
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('student/content/material/sub/url/delete') }}",
                    method: 'DELETE',
                    data: {id: id},
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
                            text: "Your link has been deleted.",
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
</script>
@stop