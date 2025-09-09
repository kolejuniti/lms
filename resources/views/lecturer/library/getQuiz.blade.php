<div id="quiz-library" class="container-fluid">
    <div class="row g-4">
        @foreach ($quiz as $key => $qz)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="quiz-card h-100 position-relative overflow-hidden">
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                        <span class="badge {{ $qz->date_from != null ? 'bg-success text-white' : 'bg-secondary text-white' }} px-3 py-2 rounded-pill shadow">
                            {{ $qz->statusname }}
                        </span>
                    </div>

                    <!-- Session Badge -->
                    <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill shadow">
                            <i class="fa fa-calendar me-1"></i>{{ $qz->SessionName }}
                        </span>
                    </div>

                    <!-- Card Header -->
                    <div class="quiz-card-header p-4 text-center">
                        <div class="quiz-icon mb-3">
                            @if ($qz->date_from != null)
                                <i class="fa fa-question-circle text-primary" style="font-size: 4rem;"></i>
                            @else
                                <i class="fa fa-download text-secondary" style="font-size: 4rem;"></i>
                            @endif
                        </div>
                        <h4 class="quiz-title fw-bold text-dark mb-3 line-clamp-2">
                            {{ $qz->title }}
                        </h4>
                    </div>

                    <!-- Card Body -->
                    <div class="quiz-card-body px-4 py-4">
                        @if ($qz->date_from != null)
                            <!-- Online Quiz Info -->
                            <div class="quiz-info mb-4">
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <i class="fa fa-clock text-muted me-2"></i>
                                    <span class="text-muted fw-medium">
                                        {{ sprintf("%0d hour %02d minute", floor($qz->duration / 60), $qz->duration % 60) }}
                                    </span>
                                </div>
                                <div class="quiz-type-badge text-center">
                                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                                        <i class="fa fa-wifi me-1"></i>ONLINE QUIZ
                                    </span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="text-center">
                                <a href="/lecturer/quiz/{{ Session::get('CourseID') }}/create?quizid={{ $qz->id }}&REUSE=1" 
                                   class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow hover-lift text-white fw-bold" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Reuse Quiz"
                                   style="text-decoration: none; border: none; background: linear-gradient(45deg, #007bff, #0056b3);">
                                    <i class="fa fa-recycle me-2"></i>Reuse Quiz
                                </a>
                            </div>
                        @else
                            <!-- Offline Quiz Info -->
                            <div class="quiz-info mb-4">
                                <div class="quiz-type-badge text-center mb-3">
                                    <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">
                                        <i class="fa fa-download me-1"></i>OFFLINE QUIZ
                                    </span>
                                </div>
                                <p class="text-muted text-center mb-0">
                                    Download and view offline content
                                </p>
                            </div>

                            <!-- Action Button -->
                            <div class="text-center">
                                <a href="{{ Storage::disk('linode')->url($qz->content) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-lg px-4 py-3 rounded-pill shadow hover-lift fw-bold" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Download Quiz"
                                   style="text-decoration: none; border: 2px solid #007bff; color: #007bff;">
                                    <i class="fa fa-download me-2"></i>Download
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Card Hover Effect -->
                    <div class="quiz-card-overlay"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.quiz-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    min-height: 420px;
    display: flex;
    flex-direction: column;
}

.quiz-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border-color: #007bff;
}

.quiz-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 1px solid #e9ecef;
    border-radius: 20px 20px 0 0;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.quiz-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
}

.quiz-title {
    font-size: 1.2rem;
    line-height: 1.4;
    color: #2d3748;
    font-weight: 700 !important;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3rem;
}

.hover-lift {
    transition: all 0.3s ease !important;
}

.hover-lift:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 15px 30px rgba(0, 123, 255, 0.4) !important;
}

.quiz-icon {
    transition: transform 0.3s ease;
}

.quiz-card:hover .quiz-icon {
    transform: scale(1.15);
}

.quiz-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.02) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 20px;
    pointer-events: none;
}

.quiz-card:hover .quiz-card-overlay {
    opacity: 1;
}

/* Button enhancements */
.btn {
    font-weight: 600 !important;
    letter-spacing: 0.5px;
    transition: all 0.3s ease !important;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
    border: none !important;
    color: white !important;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085) !important;
    transform: translateY(-2px);
}

.btn-outline-primary {
    border: 2px solid #007bff !important;
    color: #007bff !important;
    background: transparent !important;
}

.btn-outline-primary:hover {
    background: #007bff !important;
    color: white !important;
    border-color: #007bff !important;
    transform: translateY(-2px);
}

/* Badge improvements */
.badge {
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.5px;
}

@media (max-width: 576px) {
    .quiz-card {
        margin-bottom: 1.5rem;
    }
    
    .quiz-title {
        font-size: 1.1rem;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1rem;
    }
    
    .quiz-icon i {
        font-size: 3rem !important;
    }
}

/* Fix for button visibility */
.btn-lg {
    padding: 12px 30px !important;
    font-size: 1.1rem !important;
    min-height: 50px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
</style>