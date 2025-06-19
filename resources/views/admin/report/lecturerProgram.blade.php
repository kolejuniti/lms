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
      <div class="row">
        <div class="col-md-12">
          <div id="results-container">
            <!-- Results will be populated here -->
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
        $('#results-container').html('<div class="text-center p-4"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
        
        $.ajax({
            url: '{{ route("admin.getLecturerProgram") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                session: sessions
            },
            success: function(data) {
                console.log('Response received');
                
                // Insert the HTML response directly
                $('#results-container').html(data);
                
                // No DataTables initialization needed since we have merged cells
                // Custom export functions are included in the response
                
                $('#find_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Find');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                let errorMessage = 'An error occurred while fetching data. Please try again.';
                
                if (xhr.status === 404) {
                    errorMessage = 'Route not found. Please check if the route is properly defined.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please check the server logs.';
                }
                
                $('#results-container').html('<div class="alert alert-danger p-4">' + errorMessage + '</div>');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
                $('#find_btn').prop('disabled', false).html('<i class="fa fa-search"></i> Find');
            }
        });
    };

    window.clearFilter = function() {
        $('#session_filter').val(null).trigger('change');
        $('#results-container').empty();
    };
    
    // Functions ready
    console.log('Lecturer Program report ready');
});
</script>

@endsection 