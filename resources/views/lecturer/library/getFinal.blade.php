<div id="final-library" class="container-fluid">
    <div class="row g-4">
        @foreach ($final as $ag)
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="quiz-card h-100 position-relative overflow-hidden">
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill shadow">
                            {{ $ag->statusname }}
                        </span>
                    </div>

                    <!-- Card Header -->
                    <div class="quiz-card-header p-4 text-center">
                        <div class="quiz-icon mb-3">
                            <i class="fa fa-graduation-cap text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="quiz-title fw-bold text-dark mb-3 line-clamp-2">
                            {{ $ag->title }}
                        </h4>
                    </div>

                    <!-- Card Body -->
                    <div class="quiz-card-body px-4 py-4">
                        <!-- Final Exam Info -->
                        <div class="quiz-info mb-4">
                            <div class="quiz-type-badge text-center mb-3">
                                <span class="badge bg-danger text-white px-3 py-2 rounded-pill">
                                    <i class="fa fa-file-text me-1"></i>FINAL EXAM
                                </span>
                            </div>
                            <p class="text-muted text-center mb-0">
                                Download final examination materials
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="text-center">
                            <a href="{{ Storage::disk('linode')->url($ag->content) }}" 
                               target="_blank" 
                               class="btn btn-danger btn-lg px-4 py-3 rounded-pill shadow hover-lift text-white fw-bold" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Download Final Exam"
                               style="text-decoration: none; border: none; background: linear-gradient(45deg, #dc3545, #c82333);">
                                <i class="fa fa-download me-2"></i>Download
                            </a>
                        </div>
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
    border-color: #dc3545;
}

.quiz-card-header {
    background: linear-gradient(135deg, #fde7e8 0%, #ffffff 100%);
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
    box-shadow: 0 15px 30px rgba(220, 53, 69, 0.4) !important;
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
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(220, 53, 69, 0.02) 100%);
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

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333) !important;
    border: none !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #bd2130) !important;
    transform: translateY(-2px);
    color: white !important;
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