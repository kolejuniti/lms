@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : '')))


@section('main')

<!-- Content Header (Page header) -->
<style>
  #loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
  }
  
  .loading-spinner {
    text-align: center;
    padding: 20px;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
</style>

<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Analysis Student R</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Analysis Student R</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if($errors->any())
        <div class="form-group">
            <div class="alert alert-success">
              <span>{{$errors->first()}} </span>
            </div>
        </div>
      @endif
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Loading Overlay -->
      <div id="loading-overlay" class="d-none">
        <div class="loading-spinner">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Loading data...</p>
        </div>
      </div>
      
      <!-- /.card-header -->
      <div class="card card-primary">
        <div class="card-header">
          <b>Search Student</b>
          <button id="printButton" class="btn btn-primary">Print / PDF</button>
        </div>
        <div class="card-body">
          <!-- Number of Tables Selector -->
          <div class="row mb-4">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label fw-bold text-primary" for="table_count">
                  <i class="mdi mdi-table-multiple me-2"></i>Number of Tables
                </label>
                <select class="form-control form-select" id="table_count" name="table_count">
                  <option value="">Select number of tables...</option>
                  <option value="1">1 Table</option>
                  <option value="2">2 Tables</option>
                  <option value="3">3 Tables</option>
                  <option value="4">4 Tables</option>
                  <option value="5">5 Tables</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Dynamic Date Range Containers -->
          <div id="date-ranges-container" class="row">
            <!-- Dynamic content will be inserted here -->
          </div>

          <div id="form-student">
            
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>



  <script type="text/javascript">
    var from = '';
    var to = '';

    // Handle dropdown change for number of tables
    $(document).on('change', '#table_count', function(e) {
      const tableCount = parseInt($(e.target).val());
      generateDateRanges(tableCount);
    });

    // Generate dynamic date range boxes
    function generateDateRanges(count) {
      const container = $('#date-ranges-container');
      container.empty();

      if (count > 0) {
        // Add a title for the date ranges section
        container.append(`
          <div class="col-12 mb-3">
            <h5 class="text-secondary">
              <i class="mdi mdi-calendar-range me-2"></i>Date Ranges for ${count} Table${count > 1 ? 's' : ''}
            </h5>
            <hr class="my-2">
          </div>
        `);

        // Create date range boxes
        for (let i = 1; i <= count; i++) {
          const colClass = count <= 2 ? 'col-md-6' : count <= 3 ? 'col-md-4' : 'col-md-3';
          
          container.append(`
            <div class="${colClass} mb-4">
              <div class="card border-primary" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0,123,255,0.15);">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 15px 15px 0 0;">
                  <h6 class="mb-0">
                    <i class="mdi mdi-table me-2"></i>Table ${i}
                  </h6>
                </div>
                <div class="card-body p-3">
                  <div class="form-group mb-3">
                    <label class="form-label fw-bold text-success" for="from_${i}">
                      <i class="mdi mdi-calendar-start me-1"></i>FROM
                    </label>
                    <input type="date" class="form-control date-input" id="from_${i}" name="from_${i}" 
                           style="border-radius: 10px; border: 2px solid #28a745;">
                  </div>
                  <div class="form-group">
                    <label class="form-label fw-bold text-danger" for="to_${i}">
                      <i class="mdi mdi-calendar-end me-1"></i>TO
                    </label>
                    <input type="date" class="form-control date-input" id="to_${i}" name="to_${i}"
                           style="border-radius: 10px; border: 2px solid #dc3545;">
                  </div>
                </div>
              </div>
            </div>
          `);
        }

        // Add animation effect
        container.find('.card').hide().fadeIn(600);
      }
    }

    // Handle date input changes for any dynamic date input
    $(document).on('change', '.date-input', async function(e) {
      // Collect all date ranges
      const dateRanges = collectDateRanges();
      
      if (dateRanges.length > 0) {
        $('#loading-overlay').removeClass('d-none');
        await getStudentWithMultipleRanges(dateRanges);
      }
    });

    // Collect all date ranges from the dynamic inputs
    function collectDateRanges() {
      const ranges = [];
      const tableCount = parseInt($('#table_count').val());
      
      for (let i = 1; i <= tableCount; i++) {
        const fromValue = $(`#from_${i}`).val();
        const toValue = $(`#to_${i}`).val();
        
        if (fromValue && toValue) {
          ranges.push({
            table: i,
            from: fromValue,
            to: toValue
          });
        }
      }
      
      return ranges;
    }

    // Updated function to handle multiple date ranges
    function getStudentWithMultipleRanges(dateRanges) {
      return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: "{{ url('/pendaftar/student/reportRA/getStudentReportRA') }}",
        method: 'GET',
        data: {
          date_ranges: JSON.stringify(dateRanges),
          multiple_tables: true
        },
        beforeSend: function() {
          $('#loading-overlay').removeClass('d-none');
        },
        error: function(err) {
          $('#loading-overlay').addClass('d-none');
          alert("Error");
          console.log(err);
        },
        success: function(data) {
          $('#loading-overlay').addClass('d-none');
          if (data.error) {
            alert(data.error);
          } else {
            $('#form-student').html(data);

            $('#myTable').DataTable({
              dom: 'lfrtip',
              ordering: false
            });

            $('#myTable2').DataTable({
              dom: 'lfrtip'
            });
          }
        }
      });
    }

  //   // Keep original functions for backward compatibility
  //   $(document).on('change', '#from', async function(e){
  //     from = $(e.target).val();
  //     $('#loading-overlay').removeClass('d-none');
  //     await getStudent(from,to);
  //   });

  //   $(document).on('change', '#to', async function(e){
  //     to = $(e.target).val();
  //     $('#loading-overlay').removeClass('d-none');
  //     await getStudent(from,to);
  //   });

  // function getStudent(from,to)
  // {
  //   //alert(from);
  //   return $.ajax({
  //           headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
  //           url      : "{{ url('/pendaftar/student/reportRA/getStudentReportRA') }}",
  //           method   : 'GET',
  //           data 	 : {from: from, to: to},
  //           beforeSend: function() {
  //               $('#loading-overlay').removeClass('d-none');
  //           },
  //           error:function(err){
  //               $('#loading-overlay').addClass('d-none');
  //               alert("Error");
  //               console.log(err);
  //           },
  //           success  : function(data){
  //           $('#loading-overlay').addClass('d-none');
  //           if(data.error)
  //           {
  //             alert(data.error);
  //           }else{
  //               $('#form-student').html(data);

  //               $('#myTable').DataTable({
  //                 dom: 'lfrtip', // Removed B for buttons
  //                 ordering: false // Disable ordering
  //               });

  //               $('#myTable2').DataTable({
  //                 dom: 'lfrtip' // Removed B for buttons
  //               });
                
  //             }
  //           }
  //       });

  // }

  $(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      $('#loading-overlay').removeClass('d-none');
      printReport();
    });
  });

  function printReport() {
    // Check if using multiple tables or single range
    const tableCount = parseInt($('#table_count').val());
    
    if (tableCount > 0) {
      // Use multiple date ranges for printing
      const dateRanges = collectDateRanges();
      
      return $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "{{ url('pendaftar/student/reportRA/getStudentReportRA?print=true') }}",
        method: 'GET',
        data: { 
          date_ranges: JSON.stringify(dateRanges),
          multiple_tables: true
        },
        beforeSend: function() {
          $('#loading-overlay').removeClass('d-none');
        },
        error: function(err) {
          $('#loading-overlay').addClass('d-none');
          alert("Error");
          console.log(err);
        },
        success: function(data) {
          $('#loading-overlay').addClass('d-none');
          var newWindow = window.open();
          newWindow.document.write(data);
          newWindow.document.close();
        }
      });
    } else {
      // Use original single range method
      var from = $('#from').val();
      var to = $('#to').val();

      return $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "{{ url('pendaftar/student/reportRA/getStudentReportRA?print=true') }}",
        method: 'GET',
        data: { from: from, to: to },
        beforeSend: function() {
            $('#loading-overlay').removeClass('d-none');
        },
        error: function(err) {
          $('#loading-overlay').addClass('d-none');
          alert("Error");
          console.log(err);
        },
        success: function(data) {
          $('#loading-overlay').addClass('d-none');
          var newWindow = window.open();
          newWindow.document.write(data);
          newWindow.document.close();
        }
      });
    }
  }
  </script>
@endsection
