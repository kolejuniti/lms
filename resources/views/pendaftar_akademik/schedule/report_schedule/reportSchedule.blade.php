@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : '')))

@section('main')
<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h2 class="page-title fw-bold text-primary mb-2">Lecturer Schedule Overview</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Schedule Report</li>
            </ol>
          </nav>
        </div>
        <div class="actions-btn">
          <button id="print-btn" class="btn btn-primary me-2"><i class="fas fa-print me-2"></i>Print</button>
          <button id="excel-btn" class="btn btn-success"><i class="fas fa-file-excel me-2"></i>Export to Excel</button>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
              <div class="card-header bg-gradient-primary text-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <h3 class="card-title m-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i>Lecturer Schedule</h3>
                  <div class="schedule-legend d-flex ms-4">
                    <div class="d-flex align-items-center me-3">
                      <div class="legend-box bg-success me-2"></div>
                      <span>Hours Available</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="legend-box bg-danger me-2"></div>
                      <span>Hours Depleted</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card-body">
                <div class="table-responsive">
                  <table id="schedule-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center bg-light">Lecturer</th>
                        <th class="text-center bg-light">Course</th>
                        <th class="text-center bg-light">Session</th>
                        <th class="text-center bg-light">Group</th>
                        <th class="text-center bg-light">Meeting Hour</th>
                        <th class="text-center bg-light">Meeting Hour Used</th>
                        <th class="text-center bg-light">Meeting Hour Left</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data['lecturer'] as $key => $lct)
                        @php
                          // Calculate total number of groups for the lecturer to determine rowspan for the lecturer cell
                          $lecturerRowSpan = 0;
                          foreach ($data['subject'][$key] as $subKey => $subject) {
                            $lecturerRowSpan += count($data['group'][$key][$subKey]);
                          }
                          $lecturerRowSpan = $lecturerRowSpan > 0 ? $lecturerRowSpan : 1;
                          $lecturerPrinted = false;
                        @endphp
                        
                        @foreach ($data['subject'][$key] as $subKey => $subject)
                          @php
                            // Calculate the number of groups for each subject to determine rowspan for the subject cell
                            $subjectGroupCount = count($data['group'][$key][$subKey]);
                            $subjectRowSpan = $subjectGroupCount > 0 ? $subjectGroupCount : 1;
                          @endphp
                          
                          @foreach ($data['group'][$key][$subKey] as $groupKey => $group)
                            @php
                              $hoursLeft = $data['hour_left'][$key][$subKey][$groupKey];
                              $rowClass = $hoursLeft > 0 ? 'row-available' : 'row-depleted';
                            @endphp
                            
                            <tr class="{{ $rowClass }}">
                              @if (!$lecturerPrinted)
                                <td class="text-center align-middle fw-bold" rowspan="{{ $lecturerRowSpan }}">
                                  {{ $lct->name }}
                                </td>
                                @php
                                  $lecturerPrinted = true;
                                @endphp
                              @endif
                              
                              @if ($groupKey === 0)
                                <td class="text-center align-middle" rowspan="{{ $subjectRowSpan }}">
                                  <div class="d-flex flex-column">
                                    <span class="badge bg-primary mb-1">{{ $subject->course_code }}</span>
                                    <span class="fw-semibold">{{ $subject->course_name }}</span>
                                  </div>
                                </td>
                                <td class="text-center align-middle fw-bold" rowspan="{{ $subjectRowSpan }}">
                                  {{ $subject->session }}
                                </td>
                              @endif
                              
                              <td class="text-center align-middle">
                                {{ $group->group_name }}
                              </td>
                              <td class="text-center align-middle fw-bold">
                                {{ $subject->meeting_hour }}
                              </td>
                              <td class="text-center align-middle">
                                {{ $data['detail'][$key][$subKey][$groupKey]->total_hours ?? 0 }}
                              </td>
                              <td class="text-center align-middle fw-bold hours-left-cell">
                                <span class="hours-badge {{ $hoursLeft > 0 ? 'hours-available' : 'hours-depleted' }}">
                                  {{ $hoursLeft }}
                                </span>
                              </td>
                            </tr>
                          @endforeach
                        @endforeach
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<style>
  /* Custom styling for the schedule table */
  .legend-box {
    width: 20px;
    height: 20px;
    border-radius: 4px;
  }
  
  #schedule-table th, #schedule-table td {
    vertical-align: middle;
  }
  
  .bg-gradient-primary {
    background: linear-gradient(135deg, #2c91e9 0%, #1976d2 100%);
  }
  
  .row-available {
    background-color: rgba(9, 165, 53, 0.459);
  }
  
  .row-depleted {
    background-color: rgba(216, 6, 27, 0.779);
  }
  
  .hours-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: bold;
    min-width: 40px;
  }
  
  .hours-available {
    background-color: rgba(40, 167, 69, 0.8);
    color: white;
  }
  
  .hours-depleted {
    background-color: rgba(220, 53, 69, 0.8);
    color: white;
  }
  
  .badge.bg-primary {
    background-color: #0060b5 !important;
    padding: 5px 8px;
    font-size: 0.75rem;
  }
  
  .card {
    transition: all 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }
  
  .breadcrumb-item + .breadcrumb-item::before {
    content: ">";
  }
</style>

<script>
// Initialize DataTable and other functionality
$(document).ready(function() {
  // Initialize DataTable with enhanced features
  const scheduleTable = $('#schedule-table').DataTable({
    dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>rt<"d-flex justify-content-between align-items-center"<"d-flex align-items-center"i><"d-flex align-items-center"p>>',
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    paging: true,
    searching: true,
    ordering: true,
    responsive: true,
    buttons: [
      {
        extend: 'print',
        text: 'Print',
        className: 'btn btn-primary',
        orientation: 'landscape',
        customize: function(win) {
          const today = new Date().toLocaleDateString();
          const body = $(win.document.body);
          
          body.css('font-family', 'Arial, Helvetica, sans-serif');
          
          body.prepend(`
            <div style="text-align: center; margin-bottom: 20px;">
              <h1 style="margin-bottom: 5px;">Lecturer Schedule Report</h1>
              <h3>Generated on ${today}</h3>
            </div>
          `);
          
          body.find('table')
            .addClass('compact')
            .css({
              'font-size': '11px',
              'border-collapse': 'collapse',
              'width': '100%'
            });
            
          body.find('td, th')
            .css({
              'border': '1px solid #ddd',
              'padding': '8px',
              'text-align': 'center'
            });
            
          body.find('.row-available')
            .css({
              'background-color': '#d4edda',
              'color': '#155724'
            });
            
          body.find('.row-depleted')
            .css({
              'background-color': '#f8d7da',
              'color': '#721c24'
            });
        }
      },
      {
        extend: 'excel',
        text: 'Excel',
        className: 'btn btn-success'
      }
    ]
  });
  
  // Handle print button click
  $('#print-btn').on('click', function() {
    scheduleTable.button('0').trigger();
  });
  
  // Handle excel button click
  $('#excel-btn').on('click', function() {
    scheduleTable.button('1').trigger();
  });
  
  // Add tooltip to hours badges
  $('.hours-badge').each(function() {
    const hours = parseInt($(this).text().trim());
    if (hours > 0) {
      $(this).attr('title', 'Hours available');
    } else {
      $(this).attr('title', 'No hours left');
    }
  });
  
  // Enable tooltips
  $('[title]').tooltip();
  
  // Add hover effect to rows
  $('#schedule-table tbody tr').hover(
    function() {
      $(this).css('background-color', $(this).hasClass('row-available') ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)');
    },
    function() {
      $(this).css('background-color', $(this).hasClass('row-available') ? 'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)');
    }
  );
  
  // Log initialization
  console.log('Lecturer schedule table initialized successfully');
});
</script>
@endsection