@extends('layouts.student.student')

<style>
/* ===== Report Details Modern Styling ===== */

/* Profile Header Card - Matching Dashboard Style */
.report-profile-card {
    background: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
    color: white;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(67, 97, 238, 0.3);
}

.report-profile-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: url('images/svg-icon/color-svg/custom-30.svg');
    background-position: right bottom;
    background-size: auto 100%;
    opacity: 0.15;
}

.report-profile-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.profile-content {
    position: relative;
    z-index: 1;
    padding: 2.5rem;
}

.student-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    margin-right: 1.5rem;
}

.student-info h1 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.student-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-top: 1rem;
}

.student-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
    opacity: 0.95;
}

.student-meta-item i {
    font-size: 1.1rem;
    opacity: 0.8;
}

/* Modern Title Styling */
.modern-title {
    font-weight: 700;
    margin: 0;
    color: #1a1a2e;
    font-size: 1.15rem;
    position: relative;
    padding-left: 1rem;
    letter-spacing: -0.3px;
}

.modern-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 1.5rem;
    width: 4px;
    background: linear-gradient(to bottom, #4cc9f0, #4361ee);
    border-radius: 4px;
}

/* Assessment Section Cards */
.assessment-section {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    animation: slideUp 0.5s ease forwards;
    opacity: 0;
}

.assessment-section:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.assessment-section:nth-child(1) { animation-delay: 0.1s; }
.assessment-section:nth-child(2) { animation-delay: 0.2s; }
.assessment-section:nth-child(3) { animation-delay: 0.3s; }
.assessment-section:nth-child(4) { animation-delay: 0.4s; }
.assessment-section:nth-child(5) { animation-delay: 0.5s; }
.assessment-section:nth-child(6) { animation-delay: 0.6s; }

/* Section Headers with Gradient Accents */
.section-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.section-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
}

.section-header.quiz-header::before {
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.section-header.test-header::before {
    background: linear-gradient(90deg, #f093fb, #f5576c);
}

.section-header.assignment-header::before {
    background: linear-gradient(90deg, #4facfe, #00f2fe);
}

.section-header.midterm-header::before {
    background: linear-gradient(90deg, #43e97b, #38f9d7);
}

.section-header.extra-header::before {
    background: linear-gradient(90deg, #fa709a, #fee140);
}

.section-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    margin-right: 1rem;
}

.section-icon.quiz { background: linear-gradient(135deg, #667eea, #764ba2); }
.section-icon.test { background: linear-gradient(135deg, #f093fb, #f5576c); }
.section-icon.assignment { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.section-icon.midterm { background: linear-gradient(135deg, #43e97b, #38f9d7); }
.section-icon.extra { background: linear-gradient(135deg, #fa709a, #fee140); }

.section-title-wrap {
    display: flex;
    align-items: center;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
    letter-spacing: -0.3px;
}

.section-subtitle {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.2rem;
}

.percentage-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
}

.percentage-badge.quiz { background: rgba(102, 126, 234, 0.12); color: #667eea; }
.percentage-badge.test { background: rgba(245, 87, 108, 0.12); color: #f5576c; }
.percentage-badge.assignment { background: rgba(79, 172, 254, 0.12); color: #4facfe; }
.percentage-badge.midterm { background: rgba(67, 233, 123, 0.12); color: #2e7d32; }
.percentage-badge.extra { background: rgba(250, 112, 154, 0.12); color: #fa709a; }

/* Modern Table Styling */
.assessment-table-wrap {
    padding: 1.5rem;
}

.assessment-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.assessment-table thead th {
    background: linear-gradient(135deg, #f8f9fc 0%, #eef0f5 100%);
    padding: 1rem 1.25rem;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #5a5f7d;
    border-bottom: 2px solid #e9ecef;
    text-align: left;
}

.assessment-table thead th:first-child {
    border-radius: 10px 0 0 0;
}

.assessment-table thead th:last-child {
    border-radius: 0 10px 0 0;
}

.assessment-table tbody tr {
    transition: all 0.2s ease;
}

.assessment-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.04);
}

.assessment-table tbody td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f2f5;
    color: #4a4a68;
    font-size: 0.95rem;
}

.assessment-table tbody tr:last-child td {
    border-bottom: none;
}

/* Row Number Badge */
.row-number {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #f0f2f5, #e9ecef);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    color: #5a5f7d;
}

/* Mark Display */
.mark-display {
    font-weight: 700;
    font-size: 1rem;
    color: #1a1a2e;
}

.mark-display.pending {
    color: #adb5bd;
    font-style: italic;
    font-weight: 500;
}

/* Summary Row Styling */
.summary-row {
    background: linear-gradient(135deg, #fafbff 0%, #f5f7ff 100%) !important;
}

.summary-row td {
    padding: 1.25rem 1.25rem !important;
    border-top: 2px dashed #e9ecef !important;
}

.formula-display {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    display: inline-block;
    border: 1px solid #e9ecef;
}

.overall-percentage {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    color: white;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.overall-percentage i {
    margin-right: 0.5rem;
}

/* Page Header Styling */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: -0.5px;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    font-size: 0.9rem;
}

.breadcrumb-item a {
    color: #4361ee;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #3a0ca3;
}

.breadcrumb-item.active {
    color: #6c757d;
}

/* Back Button */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    color: #4a4a68;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.back-btn:hover {
    background: #4361ee;
    color: white;
    border-color: #4361ee;
    transform: translateX(-5px);
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

/* Print Button */
.print-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.print-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .profile-content {
        padding: 1.5rem;
    }
    
    .student-avatar {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .student-info h1 {
        font-size: 1.35rem;
    }
    
    .student-meta {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .assessment-table-wrap {
        padding: 1rem;
        overflow-x: auto;
    }
    
    .assessment-table thead th,
    .assessment-table tbody td {
        padding: 0.75rem;
        font-size: 0.85rem;
    }
}

/* Empty State */
.empty-assessment {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-assessment-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #f0f2f5, #e9ecef);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: #adb5bd;
}

.empty-assessment h5 {
    font-weight: 600;
    color: #4a4a68;
    margin-bottom: 0.5rem;
}

/* MathJax Formula Styling */
.mjx-chtml {
    font-size: 1rem !important;
}

/* Summary Stats Cards */
.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.quiz::before { background: linear-gradient(90deg, #667eea, #764ba2); }
.stat-card.test::before { background: linear-gradient(90deg, #f093fb, #f5576c); }
.stat-card.assignment::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.stat-card.midterm::before { background: linear-gradient(90deg, #43e97b, #38f9d7); }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    margin-bottom: 1rem;
}

.stat-icon.quiz { background: linear-gradient(135deg, #667eea, #764ba2); }
.stat-icon.test { background: linear-gradient(135deg, #f093fb, #f5576c); }
.stat-icon.assignment { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.stat-icon.midterm { background: linear-gradient(135deg, #43e97b, #38f9d7); }

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}
</style>

@section('main')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Page Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="me-auto">
                    <h4 class="page-title">Student Assessment Report</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Assessment</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Report Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="javascript:history.back()" class="back-btn">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a>
                    <button onclick="window.print()" class="print-btn">
                        <i class="mdi mdi-printer"></i> Print Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Student Profile Card -->
            <div class="row">
                <div class="col-12">
                    <div class="report-profile-card">
                        <div class="profile-content">
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="student-info">
                                    <h1>{{ $student->name }}</h1>
                                    <div class="student-meta">
                                        <div class="student-meta-item">
                                            <i class="mdi mdi-card-account-details-outline"></i>
                                            <span><strong>IC:</strong> {{ $student->ic }}</span>
                                        </div>
                                        <div class="student-meta-item">
                                            <i class="mdi mdi-school-outline"></i>
                                            <span><strong>Matric No:</strong> {{ $student->no_matric }}</span>
                                        </div>
                                        <div class="student-meta-item">
                                            <i class="mdi mdi-check-circle-outline"></i>
                                            <span><strong>Status:</strong> {{ $student->status }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Sections -->
            <div class="row">
                <div class="col-12">
                    
                    <!-- QUIZ Section -->
                    @if ($percentagequiz != "")
                    <div class="assessment-section">
                        <div class="section-header quiz-header">
                            <div class="section-title-wrap">
                                <div class="section-icon quiz">
                                    <i class="mdi mdi-help-circle-outline"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Quiz Assessment</h3>
                                    <p class="section-subtitle">{{ count($quiz) }} quiz(zes) completed</p>
                                </div>
                            </div>
                            <span class="percentage-badge quiz">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentagequiz }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_quiz">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quiz as $keys=>$qz)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $qz->title }}</strong></td>
                                        <td>{{ $qz->duration }}</td>
                                        <td><span class="mark-display">{{ $qz->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($quizlist[$keys]->final_mark) ? 'pending' : '' }}">
                                                {{ $quizlist[$keys]->final_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="3" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $markquiz }}} \over \text{Total: {{ $totalquiz }}} } \times {{ $percentagequiz }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_allquiz }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- TEST Section -->
                    @if ($percentagetest != "")
                    <div class="assessment-section">
                        <div class="section-header test-header">
                            <div class="section-title-wrap">
                                <div class="section-icon test">
                                    <i class="mdi mdi-clipboard-text-outline"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Test Assessment</h3>
                                    <p class="section-subtitle">{{ count($test) }} test(s) completed</p>
                                </div>
                            </div>
                            <span class="percentage-badge test">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentagetest }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_test">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($test as $keys=>$ts)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $ts->title }}</strong></td>
                                        <td>{{ $ts->duration }}</td>
                                        <td><span class="mark-display">{{ $ts->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($testlist[$keys]->final_mark) ? 'pending' : '' }}">
                                                {{ $testlist[$keys]->final_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="3" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $marktest }}} \over \text{Total: {{ $totaltest }}} } \times {{ $percentagetest }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_alltest }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- TEST 2 Section -->
                    @if ($percentagetest2 != "")
                    <div class="assessment-section">
                        <div class="section-header test-header">
                            <div class="section-title-wrap">
                                <div class="section-icon test">
                                    <i class="mdi mdi-clipboard-check-outline"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Test 2 Assessment</h3>
                                    <p class="section-subtitle">{{ count($test) }} test(s) completed</p>
                                </div>
                            </div>
                            <span class="percentage-badge test">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentagetest2 }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_test2">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Duration</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($test as $keys=>$ts)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $ts->title }}</strong></td>
                                        <td>{{ $ts->duration }}</td>
                                        <td><span class="mark-display">{{ $ts->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($testlist[$keys]->final_mark) ? 'pending' : '' }}">
                                                {{ $testlist[$keys]->final_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="3" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $marktest }}} \over \text{Total: {{ $totaltest }}} } \times {{ $percentagetest }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_alltest }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- ASSIGNMENT Section -->
                    @if ($percentageassign != "")
                    <div class="assessment-section">
                        <div class="section-header assignment-header">
                            <div class="section-title-wrap">
                                <div class="section-icon assignment">
                                    <i class="mdi mdi-file-document-edit-outline"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Assignment Assessment</h3>
                                    <p class="section-subtitle">{{ count($assign) }} assignment(s) submitted</p>
                                </div>
                            </div>
                            <span class="percentage-badge assignment">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentageassign }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_assign">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Deadline</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assign as $keys=>$qz)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $qz->title }}</strong></td>
                                        <td>{{ $qz->deadline }}</td>
                                        <td><span class="mark-display">{{ $qz->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($assignlist[$keys]->final_mark) ? 'pending' : '' }}">
                                                {{ $assignlist[$keys]->final_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="3" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $markassign }}} \over \text{Total: {{ $totalassign }}} } \times {{ $percentageassign }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_allassign }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- MIDTERM Section -->
                    @if ($percentagemidterm != "")
                    <div class="assessment-section">
                        <div class="section-header midterm-header">
                            <div class="section-title-wrap">
                                <div class="section-icon midterm">
                                    <i class="mdi mdi-book-open-page-variant"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Midterm Assessment</h3>
                                    <p class="section-subtitle">{{ count($midterm) }} midterm exam(s)</p>
                                </div>
                            </div>
                            <span class="percentage-badge midterm">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentagemidterm }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_midterm">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($midterm as $keys=>$qz)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $qz->title }}</strong></td>
                                        <td><span class="mark-display">{{ $qz->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($midtermlist[$keys]->final_mark) ? 'pending' : '' }}">
                                                {{ $midtermlist[$keys]->final_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="2" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $markmidterm }}} \over \text{Total: {{ $totalmidterm }}} } \times {{ $percentagemidterm }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_allmidterm }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- EXTRA Section -->
                    @if ($percentageextra != "")
                    <div class="assessment-section">
                        <div class="section-header extra-header">
                            <div class="section-title-wrap">
                                <div class="section-icon extra">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Extra Assessment</h3>
                                    <p class="section-subtitle">{{ count($extra) }} extra assessment(s)</p>
                                </div>
                            </div>
                            <span class="percentage-badge extra">
                                <i class="mdi mdi-percent me-1"></i> {{ $percentageextra }}% Weight
                            </span>
                        </div>
                        <div class="assessment-table-wrap">
                            <table class="assessment-table" id="table_projectprogress_extra">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No.</th>
                                        <th>Title</th>
                                        <th>Total Mark</th>
                                        <th>Your Mark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($extra as $keys=>$qz)
                                    <tr>
                                        <td><span class="row-number">{{ $keys+1 }}</span></td>
                                        <td><strong>{{ $qz->title }}</strong></td>
                                        <td><span class="mark-display">{{ $qz->total_mark }}</span></td>
                                        <td>
                                            <span class="mark-display {{ !isset($extralist[$keys]->total_mark) ? 'pending' : '' }}">
                                                {{ $extralist[$keys]->total_mark ?? 'Pending' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="summary-row">
                                        <td colspan="2" class="text-end">
                                            <strong>Total Marks by Percentage</strong>
                                        </td>
                                        <td>
                                            <div class="formula-display">
                                                $${ \text{Mark: {{ $markextra }}} \over \text{Total: {{ $totalextra }}} } \times {{ $percentageextra }}\%$$
                                            </div>
                                        </td>
                                        <td>
                                            <span class="overall-percentage">
                                                <i class="mdi mdi-star"></i> {{ $total_allextra }}%
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Empty State - If no assessments available -->
                    @if ($percentagequiz == "" && $percentagetest == "" && $percentagetest2 == "" && $percentageassign == "" && $percentagemidterm == "" && $percentageextra == "")
                    <div class="assessment-section">
                        <div class="empty-assessment">
                            <div class="empty-assessment-icon">
                                <i class="mdi mdi-clipboard-text-off-outline"></i>
                            </div>
                            <h5>No Assessment Data Available</h5>
                            <p class="text-muted">Assessment percentages have not been configured for this course yet. Please contact your lecturer or course coordinator for more information.</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </section>
    </div>
</div>

<!-- MathJax for Formula Rendering -->
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

<!-- DataTables Initialization -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all DataTables
    const tableIds = [
        'table_projectprogress_quiz',
        'table_projectprogress_test',
        'table_projectprogress_test2',
        'table_projectprogress_assign',
        'table_projectprogress_midterm',
        'table_projectprogress_extra'
    ];
    
    tableIds.forEach(function(tableId) {
        initializeAssessmentTable(tableId);
    });
});

function initializeAssessmentTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    if ($.fn.dataTable.isDataTable('#' + tableId)) {
        return false;
    }
    
    $('#' + tableId).DataTable({
        responsive: true,
        paging: false,
        searching: false,
        info: false,
        ordering: false,
        dom: 't',
        language: {
            emptyTable: "No data available"
        }
    });
}

// Print functionality enhancement
window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>

<!-- Print Styles -->
<style>
@media print {
    .back-btn,
    .print-btn,
    .content-header .breadcrumb,
    .sidebar-wrapper,
    .main-header {
        display: none !important;
    }
    
    .content-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
    }
		  
    .report-profile-card {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        box-shadow: none !important;
    }
    
    .assessment-section {
        break-inside: avoid;
        page-break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .section-icon,
    .percentage-badge,
    .overall-percentage {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@stop
