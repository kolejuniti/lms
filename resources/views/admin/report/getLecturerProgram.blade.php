@if(count($data['grouped_lecturers']) > 0)
<div class="card mb-3">
    <div class="card-header">
        <h4 class="card-title mb-0" style="float: left;">Lecturer Program Report</h4>
        <div class="card-tools" style="float: right;">
            <button type="button" class="btn btn-success btn-sm mr-2" onclick="exportToExcel()">
                <i class="fa fa-file-excel-o"></i> Export Excel
            </button>
            <button type="button" class="btn btn-info btn-sm" onclick="printReport()">
                <i class="fa fa-print"></i> Print
            </button>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="lecturer_report_table" class="table table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 18%">Program</th>
                        <th style="width: 35%">Course</th>
                        <th style="width: 25%">Lecturer</th>
                        <th style="width: 12%">IC</th>
                        <th style="width: 10%">Session</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['grouped_lecturers'] as $program => $lecturers)
                        @foreach ($lecturers as $index => $lecturer)
                        <tr>
                            @if($index == 0)
                                <td rowspan="{{ count($lecturers) }}" class="program-cell">{{ $program }}</td>
                            @endif
                            <td class="course-cell">{{ $lecturer->course_code }} - {{ $lecturer->course }}</td>
                            <td>{{ $lecturer->lecturer }}</td>
                            <td class="text-center">{{ $lecturer->ic }}</td>
                            <td class="text-center">{{ $lecturer->session }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Ensure full width utilization */
.card {
    width: 100%;
    margin: 0;
}

/* Card header styling */
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.25rem;
}

.card-header .d-flex {
    width: 100% !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.card-tools {
    margin-left: auto !important;
    display: flex !important;
    align-items: center !important;
    flex-shrink: 0 !important;
}

.card-title {
    margin-bottom: 0 !important;
    flex-grow: 1 !important;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

#lecturer_report_table {
    width: 100% !important;
    table-layout: fixed;
    margin-bottom: 0;
}

.program-cell {
    vertical-align: middle !important;
    text-align: center;
    font-weight: bold;
    border-right: 2px solid #000000 !important;
    word-wrap: break-word;
}

.course-cell {
    word-wrap: break-word;
    overflow-wrap: break-word;
    font-size: 0.9em;
}

/* Better column sizing */
#lecturer_report_table th,
#lecturer_report_table td {
    padding: 8px 12px;
    vertical-align: middle;
    border: 1px solid #000000 !important;
}

#lecturer_report_table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: center;
    border-bottom: 2px solid #000000 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #lecturer_report_table {
        font-size: 0.85em;
    }
    
    .program-cell {
        font-size: 0.8em;
    }
}

@media print {
    .btn, .card-header .d-flex > div {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        margin: 0 !important;
    }
    .card-header {
        background: none !important;
        border: none !important;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .card-body {
        padding: 0 !important;
    }
    #lecturer_report_table {
        font-size: 11px;
        width: 100% !important;
    }
    .program-cell {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
    }
    .table-responsive {
        overflow: visible !important;
    }
}
</style>

<script>
window.exportToExcel = function() {
    const table = document.getElementById('lecturer_report_table');
    const rows = table.querySelectorAll('tr');
    
    let csv = [];
    
    // Add headers
    csv.push(['Program', 'Course', 'Lecturer', 'IC', 'Session']);
    
    // Process data rows
    @foreach ($data['grouped_lecturers'] as $program => $lecturers)
        @foreach ($lecturers as $index => $lecturer)
            csv.push([
                @if($index == 0) 
                    '{{ addslashes($program) }}'
                @else 
                    ''
                @endif,
                '{{ addslashes($lecturer->course_code) }} - {{ addslashes($lecturer->course) }}',
                '{{ addslashes($lecturer->lecturer) }}',
                '{{ $lecturer->ic }}',
                '{{ addslashes($lecturer->session) }}'
            ]);
        @endforeach
    @endforeach
    
    // Convert to CSV string
    let csvString = csv.map(row => row.map(cell => '"' + cell + '"').join(',')).join('\n');
    
    // Create and download file
    const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'lecturer_program_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

window.printReport = function() {
    window.print();
};
</script>

@else
<div class="card mb-3">
    <div class="card-body">
        <div class="text-center">
            <h5>No data found for the selected sessions.</h5>
        </div>
    </div>
</div>
@endif 