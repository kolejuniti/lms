@extends('layouts.student')

@section('title', 'Student ID Card')

@section('main')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Student ID</h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('studentDashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Student</li>
                                <li class="breadcrumb-item active" aria-current="page">Student ID</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Digital Student ID Card</h3>
                            <button id="download-btn" class="btn btn-primary float-end">
                                <i class="fa fa-download"></i> Save as Image
                            </button>
                        </div>
                        <div class="box-body d-flex justify-content-center">
                            
                            <!-- ID Card Container -->
                            <div id="id-card-container" style="
                                width: 325px; 
                                height: 516px; 
                                border-radius: 12px;
                                background: #f8f8f8;
                                position: relative;
                                overflow: hidden;
                                box-shadow: 0 10px 20px rgba(0,0,0,0.3);
                                font-family: 'Inter', sans-serif;
                                color: {{ ($studentInfo->facultyid ?? 1) == 3 ? 'black' : 'white' }};
                                text-align: center;
                                padding: 0;
                            ">
                                <img src="https://ku-storage-object.ap-south-1.linodeobjects.com/storage/faculty_id/{{ $studentInfo->facultyid ?? 1 }}.jpg"
                                     alt="Student card background"
                                     crossorigin="anonymous"
                                     style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">

                                <div style="position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column;">
                                    
                                    <!-- Photo Section -->
                                    <div style="margin-top: 180px; margin-bottom: 15px;">
                                        <img src="https://ap-south-1.linodeobjects.com/ku-storage-object/storage/student_image/{{ $studentInfo->ic }}.jpg" 
                                             alt="Student Photo" 
                                             onerror="this.src='{{ asset('assets/images/avatar/avatar-1.png') }}'"
                                             crossorigin="anonymous"
                                             style="width: 100px; height: 138px; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    </div>
                                    
                                    <!-- Details Section -->
                                    <div style="bottom: 32px; display: flex; flex-direction: column; align-items: center; padding: 0 20px;">
                                        <div style="min-height: 40px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; text-transform: uppercase; line-height: 1.2; margin-bottom: 12px; text-shadow: {{ ($studentInfo->facultyid ?? 1) == 3 ? 'none' : '2px 2px 4px rgba(0,0,0,0.8)' }};">
                                            {{ $studentInfo->name ?? 'STUDENT NAME' }}
                                        </div>
                                        <div style="font-size: 20px; font-weight: 700; margin-bottom: 1px; text-shadow: {{ ($studentInfo->facultyid ?? 1) == 3 ? 'none' : '2px 2px 4px rgba(0,0,0,0.8)' }};">
                                            {{ $studentInfo->progcode ?? 'PROG' }} - {{ $studentInfo->no_matric ?? 'MATRIC' }}
                                        </div>
                                        <div style="font-size: 12px; font-weight: 500; text-shadow: {{ ($studentInfo->facultyid ?? 1) == 3 ? 'none' : '2px 2px 4px rgba(0,0,0,0.8)' }};">
                                            Sah sehingga {{ date('Y') + 3 }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.getElementById('download-btn').addEventListener('click', function() {
        var card = document.getElementById('id-card-container');
        html2canvas(card, {
            scale: 1.5,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff'
        }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'student-id-{{ $studentInfo->no_matric ?? "card" }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
</script>
@endsection
