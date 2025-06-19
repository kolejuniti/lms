@extends('layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Lecturer Program Report</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Report</li>
                            <li class="breadcrumb-item active" aria-current="page">Lecturer Program</li>
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lecturer Program Report</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Filter Options</b>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label class="form-label">Session:</label>
                          <select class="form-control select2" multiple="multiple" data-placeholder="Select Sessions" id="session_filter">
                              @foreach($data['session'] as $session)
                                  <option value="{{ $session->SessionID }}">{{ $session->SessionName }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4 d-flex align-items-end justify-content-end">
                        <div class="form-group">
                          <button type="button" class="btn btn-primary mr-2" id="find_btn" onclick="getLecturerProgram()">
                              <i class="fa fa-search"></i> Find
                          </button>
                          <button type="button" class="btn btn-warning" onclick="clearFilter()">
                              <i class="fa fa-refresh"></i> Clear
                          </button>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>

      
      <!-- Results Section -->
      <div class="row" id="results_section" style="display: none;">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Report Results</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm mr-2" onclick="exportToExcel()">
                    <i class="fa fa-file-excel-o"></i> Export Excel
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="exportToPDF()">
                    <i class="fa fa-file-pdf-o"></i> Export PDF
                </button>
              </div>
            </div>
            <div class="card-body p-0">
                              <div class="table-responsive">
                  <table id="lecturer_program_table" class="table table-striped projects" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Program</th>
                        <th>Course</th>
                        <th>Lecturer</th>
                        <th>IC</th>
                        <th>Session</th>
                      </tr>
                    </thead>
                    <tbody id="table_body">
                      <!-- Data will be populated via AJAX -->
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<style>
/* Make Select2 multi-select expand vertically */
.select2-container--default .select2-selection--multiple {
    min-height: 38px;
    height: auto !important;
    padding: 3px 5px;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: block;
    padding: 0;
    width: 100%;
    min-height: 26px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    margin: 2px 5px 2px 0;
    padding: 3px 8px;
    display: inline-block;
    border: 1px solid #aaa;
    border-radius: 4px;
    background-color: #e4e4e4;
    font-size: 90%;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    margin-right: 5px;
    margin-left: 3px;
}

.select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
    margin-top: 2px;
    min-width: 150px;
}

/* Ensure proper spacing and wrapping */
.select2-selection__rendered {
    line-height: 1.5 !important;
}

.select2-container {
    width: 100% !important;
}
</style>

<script>
$(document).ready(function() {
    // Initialize Select2 with custom configuration
    $('.select2').select2({
        placeholder: "Select Sessions",
        allowClear: true,
        width: '100%',
        tags: false,
        tokenSeparators: [',', ' '],
        escapeMarkup: function(markup) {
            return markup;
        }
    });
    
    // Define functions in global scope inside document ready
    window.getLecturerProgram = function() {
    const sessions = $('#session_filter').val();
    
    if (!sessions || sessions.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please select at least one session!'
        });
        return;
    }

    // Show loading
    $('#find_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
    
    console.log('Sending AJAX request with sessions:', sessions);
    console.log('URL:', '{{ route("admin.getLecturerProgram") }}');
    
    $.ajax({
        url: '{{ route("admin.getLecturerProgram") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            session: sessions
        },
        timeout: 30000, // 30 second timeout
        beforeSend: function() {
            console.log('AJAX request starting...');
        },
        success: function(response) {
            console.log('AJAX Success - Response:', response);
            populateTable(response);
            $('#results_section').show();
            $('#find_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Find');
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error Details:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            
            let errorMessage = 'An error occurred while fetching data. Please try again.';
            
            if (xhr.status === 404) {
                errorMessage = 'Route not found. Please check if the route is properly defined.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please check the server logs.';
            } else if (status === 'timeout') {
                errorMessage = 'Request timed out. Please try again.';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
            $('#find_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Find');
        },
        complete: function() {
            console.log('AJAX request completed');
        }
    });
};

    window.populateTable = function(data) {
        let tableBody = $('#table_body');
        tableBody.empty();
        
                // Data population now handled in the foolproof initialization below
    
                    // Foolproof DataTable initialization based on web research
        console.log('Table body children count:', tableBody.children().length);
        
        // Complete cleanup first
        if ($.fn.DataTable.isDataTable('#lecturer_program_table')) {
            $('#lecturer_program_table').DataTable().clear().destroy();
            $('#lecturer_program_table').empty();
        }
        
        // Rebuild table structure completely
        $('#lecturer_program_table').html(`
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Course</th>
                    <th>Lecturer</th>
                    <th>IC</th>
                    <th>Session</th>
                </tr>
            </thead>
            <tbody id="table_body">
            </tbody>
        `);
        
        // Re-populate the new tbody
        tableBody = $('#table_body');
        if (data.program && data.lecturer) {
            data.program.forEach(function(program, index) {
                if (data.lecturer[index] && data.lecturer[index].length > 0) {
                    data.lecturer[index].forEach(function(lecturer, lecturerIndex) {
                        let row = `
                            <tr>
                                <td>${lecturerIndex === 0 ? program.progname : ''}</td>
                                <td>${lecturer.course_code} - ${lecturer.course}</td>
                                <td>${lecturer.lecturer}</td>
                                <td>${lecturer.ic}</td>
                                <td>${lecturer.session}</td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                } else {
                    let row = `
                        <tr>
                            <td>${program.progname}</td>
                            <td colspan="4" class="text-center text-muted">No lecturers assigned</td>
                        </tr>
                    `;
                    tableBody.append(row);
                }
            });
        } else {
            tableBody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted">No data found</td>
                </tr>
            `);
        }
        
        // Only initialize DataTable if there's actual data rows (not colspan rows)
        let dataRows = tableBody.find('tr').filter(function() {
            return $(this).find('td[colspan]').length === 0;
        });
        
        if (dataRows.length > 0) {
            try {
                setTimeout(function() {
                    $('#lecturer_program_table').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        pageLength: 25,
                        destroy: true,
                        autoWidth: false,
                        language: {
                            emptyTable: "No data available in table"
                        },
                        columnDefs: [
                            { targets: [0], width: "20%" },
                            { targets: [1], width: "25%" },
                            { targets: [2], width: "25%" },
                            { targets: [3], width: "15%" },
                            { targets: [4], width: "15%" }
                        ]
                    });
                }, 200);
            } catch (error) {
                console.error('DataTable initialization error:', error);
            }
        }
};

window.clearFilter = function() {
    $('#session_filter').val(null).trigger('change');
    $('#results_section').hide();
    $('#table_body').empty();
};

    window.exportToExcel = function() {
        // Simple export functionality without DataTables buttons
        if ($('#table_body tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'No data to export!'
            });
            return;
        }
        
        // Basic CSV export
        let csv = [];
        let headers = ['Program', 'Course', 'Lecturer', 'IC', 'Session'];
        csv.push(headers.join(','));
        
        $('#table_body tr').each(function() {
            let row = [];
            $(this).find('td').each(function() {
                row.push('"' + $(this).text().replace(/"/g, '""') + '"');
            });
            csv.push(row.join(','));
        });
        
        let csvString = csv.join('\n');
        let blob = new Blob([csvString], { type: 'text/csv' });
        let url = window.URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'lecturer_program_report.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    };

    window.exportToPDF = function() {
        if ($('#table_body tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'No data to export!'
            });
            return;
        }
        
        // Simple print functionality
        window.print();
    };
    
    // Add debug to check if functions are defined
    console.log('Functions defined:', {
        getLecturerProgram: typeof window.getLecturerProgram,
        clearFilter: typeof window.clearFilter,
        exportToExcel: typeof window.exportToExcel,
        exportToPDF: typeof window.exportToPDF
    });
});
</script>

@endsection 