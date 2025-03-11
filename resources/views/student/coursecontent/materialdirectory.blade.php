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
    
    .material-card:hover .folder-icon-background {
        transform: scale(1.1);
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.15) 0%, rgba(114, 9, 183, 0.1) 100%);
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
    
    .chapter-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: rgba(114, 9, 183, 0.8);
        color: white;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        z-index: 3;
        box-shadow: 0 3px 10px rgba(114, 9, 183, 0.3);
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
                                <li class="breadcrumb-item active" aria-current="page">Chapter Folders</li>
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
                            <h4 class="box-title">Course Chapters</h4>
                        </div>
                        <div class="box-body">
                            <div id="material-div">          
                                <div id="material-directory" class="row">
                                    <!-- Back Folder -->
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="material-card back-folder">
                                            <a href="/student/content/{{ Session::get('CourseID') }}" class="text-decoration-none d-block">
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
                                                        <i class="ti ti-more-alt me-1"></i> Back to Main
                                                    </h5>
                                                </div>
                                            </a>
                                            <div class="content-overlay"></div>
                                        </div>
                                    </div>

                                    <!-- Chapter Folders -->
                                    @foreach ($mat_directory as $i => $fold)
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="material-card position-relative">
                                            <div class="chapter-badge">
                                                Chapter {{ $fold->ChapterNo }}
                                            </div>
                                            
                                            @if($fold->Password != null)
                                            <div class="password-icon">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                            @endif
                                            
                                            <a href="/student/content/material/sub/{{ $fold->DrID }}" class="text-decoration-none d-block">
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
                                                        {{ $fold->DrName }}
                                                    </h5>
                                                </div>
                                            </a>
                                            <div class="content-overlay"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    <!-- Empty State -->
                                    @if(count($mat_directory) == 0)
                                    <div class="col-12">
                                        <div class="empty-state">
                                            <svg width="150" height="150" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 20px;">
                                                <path opacity="0.2" d="M4 21h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8.46l-2-2H4c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2z" fill="#f72585"/>
                                                <path d="M20 6h-8l-2-2H4C2.9 4 2 4.9 2 6v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V6h5.17l2 2H20v10zm-8-4h2v2h-2zm-4 0h2v2H8zM12 8h2v2h-2zm4 0h2v2h-2z" fill="#7209b7"/>
                                            </svg>
                                            <h3 class="mb-3">No chapters available</h3>
                                            <p class="text-muted mb-4">There are currently no chapters in this course.<br>Please check back later for content.</p>
                                        </div>
                                    </div>
                                    @endif
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
    // Add animation class to cards when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.material-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Make sure the entire card is clickable
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                const link = this.querySelector('a');
                if (link) {
                    window.location.href = link.getAttribute('href');
                }
            });
        });
    });
</script>
@stop