@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : '')))

@section('main')
<!-- Content wrapper -->
<div class="content-wrapper">
  <div class="container-full">
    <!-- Content Header -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h2 class="page-title fw-bold text-primary mb-2">Schedule Overview</h2>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
              <li class="breadcrumb-item"><a href="#"><i class="fa fa-home"></i> Home</a></li>
              <li class="breadcrumb-item">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Schedule Report</li>
            </ol>
          </nav>
        </div>
        {{-- <div class="actions-btn">
          <button id="print-btn" class="btn btn-primary me-2"><i class="fa fa-print me-2"></i>Print</button>
          <button id="excel-btn" class="btn btn-success"><i class="fa fa-file-excel me-2"></i>Export to Excel</button>
        </div> --}}
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
                  <h3 class="card-title m-0 fw-bold">
                    <i class="fa fa-calendar-alt me-2"></i>Room Schedule
                  </h3>
                  <div class="schedule-legend d-flex ms-4">
                    <div class="d-flex align-items-center me-3">
                      <div class="legend-box bg-success me-2"></div>
                      <span>Available</span>
                    </div>
                    <div class="d-flex align-items-center">
                      <div class="legend-box bg-danger me-2"></div>
                      <span>Occupied</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card-body">
                <div class="table-responsive">
                  <table id="schedule-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center bg-light" style="width: 10%;">Room</th>
                        <th class="text-center bg-light" style="width: 8%;">Day</th>
                        @foreach($data['time'] as $time)
                        <th class="text-center bg-light">{{ $time }}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['room'] as $key => $room)
                        @foreach($data['days'] as $key2 => $day)
                          <tr>
                            @if($key2 == 1)
                              <td class="text-center align-middle fw-bold" rowspan="{{ count($data['days']) }}">{{ $room->name }}</td>
                            @endif
                            <td class="text-center align-middle fw-bold">{{ $day }}</td>

                            @foreach($data['time'] as $key3 => $t)
                              <td class="text-center p-0" 
                                  @if(isset($data['times'][$key][$key2][$key3]) && $data['times'][$key][$key2][$key3])
                                    onclick="getEvent('{{ $data['times'][$key][$key2][$key3]->id }}')" 
                                    style="cursor: pointer;"
                                  @endif>
                                <div class="time-slot {{ isset($data['times'][$key][$key2][$key3]) && $data['times'][$key][$key2][$key3] ? 'occupied' : 'available' }}">
                                  {{ isset($data['times'][$key][$key2][$key3]) && $data['times'][$key][$key2][$key3] ? 'Occupied' : 'Free' }}
                                </div>
                              </td>
                            @endforeach
                          </tr>
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

<!-- Event Details Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" id="getModal">
      <!-- Content will be loaded here via AJAX -->
    </div>
  </div>
</div>

<style>
  .legend-box {
    width: 20px;
    height: 20px;
    border-radius: 4px;
  }
  
  .time-slot {
    padding: 10px;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-weight: 500;
    width: 100%;
    height: 100%;
  }
  
  .time-slot.available {
    background-color: rgba(40, 167, 69, 0.8);
    color: white;
  }
  
  .time-slot.occupied {
    background-color: rgba(220, 53, 69, 0.8);
    color: white;
  }
  
  td[onclick] {
    cursor: pointer;
  }
  
  td[onclick]:hover .time-slot {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  #schedule-table th, #schedule-table td {
    vertical-align: middle;
  }
  
  .bg-gradient-primary {
    background: linear-gradient(135deg, #2c91e9 0%, #1976d2 100%);
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
// Define getEvent function in the global scope so it's accessible from onclick attributes
function getEvent(id) {
  $.ajax({
    url: "{{ url('AR/schedule/scheduleReport2/getEventDetails') }}",
    type: "GET",
    data: {
      id: id,
      _token: "{{ csrf_token() }}"
    },
    success: function(data) {
      $('#getModal').html(data);
      $('#uploadModal').modal('show');
    }
  });
}

$(document).ready(function() {
  // Initialize DataTable with enhanced features
  const scheduleTable = $('#schedule-table').DataTable({
    dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex"f>>rt<"d-flex justify-content-between align-items-center"<"d-flex align-items-center"i><"d-flex align-items-center"p>>',
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    paging: false,
    searching: true,
    ordering: false,
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
              <h1 style="margin-bottom: 5px;">Room Schedule Report</h1>
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
            
          body.find('.time-slot.available')
            .css({
              'background-color': '#d4edda',
              'color': '#155724'
            });
            
          body.find('.time-slot.occupied')
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
  
  // Merge room cells 
  function mergeCells() {
    let table = document.getElementById("schedule-table");
    let rows = table.rows;
    let lastValue = "";
    let lastCounter = 1;
    let lastRow = 0;
    
    for (let i = 1; i < rows.length; i++) {
      const firstCell = rows[i].cells[0];
      if (firstCell) {
        let thisValue = firstCell.innerHTML;
        if (thisValue == lastValue) {
          lastCounter++;
          rows[lastRow].cells[0].rowSpan = lastCounter;
          firstCell.style.display = "none";
        } else {
          firstCell.style.display = "table-cell";
          lastValue = thisValue;
          lastCounter = 1;
          lastRow = i;
        }
      }
    }
  }
  
  // Handle print button click
  $('#print-btn').on('click', function() {
    scheduleTable.button('0').trigger();
  });
  
  // Handle excel button click
  $('#excel-btn').on('click', function() {
    scheduleTable.button('1').trigger();
  });
  
  // Run merge cells function
  mergeCells();
  
  // Enable tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Add console log to help debug
  console.log('Schedule table initialized successfully');
});
</script>
@endsection