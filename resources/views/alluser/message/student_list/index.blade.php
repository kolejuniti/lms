@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : (Auth::user()->usrtype == "COOP" ? 'layouts.coop' : (Auth::user()->usrtype == "HEA" ? 'layouts.hea' : '')))))))))

@section('main')
<style>
  /* Table responsive fixes */
  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  
  .table-responsive .dataTables_wrapper {
    overflow: visible;
  }
  
  .dataTables_scrollBody {
    overflow-x: auto !important;
  }
  
  /* Ensure DataTables fits within container */
  #complex_header_wrapper,
  #complex_header2_wrapper,
  #complex_header3_wrapper {
    width: 100% !important;
  }
  
  /* Tab content padding */
  .tab-pane .table-responsive {
    padding: 0 15px 15px 15px;
  }
  
  /* Fix DataTables in hidden tabs */
  .tab-pane table.dataTable {
    width: 100% !important;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Student Massage</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Student Massage</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Tabbed Card Structure -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Student Messages</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <!-- Tab Navigation -->
          <ul class="nav nav-tabs" id="messageTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="find-student-tab" data-bs-toggle="tab" data-target="#find-student" type="button" role="tab" aria-controls="find-student" aria-selected="true">
                <i class="fa fa-search me-2"></i>Find Student
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="new-message-tab" data-bs-toggle="tab" data-target="#new-message" type="button" role="tab" aria-controls="new-message" aria-selected="false">
                <i class="fa fa-envelope me-2"></i>New Message
                <span class="badge bg-danger ms-2" id="new-msg-count" style="display: none;">0</span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="message-history-tab" data-bs-toggle="tab" data-target="#message-history" type="button" role="tab" aria-controls="message-history" aria-selected="false">
                <i class="fa fa-history me-2"></i>Message History
              </button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content" id="messageTabsContent">
            <!-- Tab 1: Find Student -->
            <div class="tab-pane fade show active" id="find-student" role="tabpanel" aria-labelledby="find-student-tab">
              <div class="p-3">
                <div class="row mt-3">
                  <div class="row col-md-12">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="search">Name / No. IC / No. Matric</label>
                        <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-3" id="group-card" hidden>
                  <div class="col-md-6 ml-3">
                    <div class="form-group">
                      <label class="form-label" for="group">Group</label>
                      <select class="form-select" id="group" name="group">
                      </select>
                    </div>
                  </div>        
                </div>
              </div>
              <div class="table-responsive">
                <table id="complex_header" class="table table-striped projects display dataTable" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Name</th>
                      <th>No. IC</th>
                      <th>No. Matric</th>
                      <th>Program</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="table-find-student">
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Tab 2: New Message -->
            <div class="tab-pane fade" id="new-message" role="tabpanel" aria-labelledby="new-message-tab">
              <div class="table-responsive">
                <table id="complex_header2" class="table table-striped projects display dataTable" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Name</th>
                      <th>No. IC</th>
                      <th>No. Matric</th>
                      <th>Program</th>
                      <th>Intake</th>
                      <th>Current Session</th>
                      <th>Semester</th>
                      <th>Last Message</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="table-new-message">
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Tab 3: Message History -->
            <div class="tab-pane fade" id="message-history" role="tabpanel" aria-labelledby="message-history-tab">
              <div class="table-responsive">
                <table id="complex_header3" class="table table-striped projects display dataTable" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Name</th>
                      <th>No. IC</th>
                      <th>No. Matric</th>
                      <th>Program</th>
                      <th>Intake</th>
                      <th>Current Session</th>
                      <th>Semester</th>
                      <th>Last Message</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="table-message-history">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- Single Modal (shared across tabs) -->
      <div id="uploadModal" class="modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <div class="">
                <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                  &times;
                </button>
              </div>
            </div>
            <div class="modal-body" id="getModal">
            </div>
          </div>
        </div>
      </div>

      <div id="app">
        <example-component></example-component>
      </div>
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

<script>
    window.Laravel = {
        sessionUserId: '{{ Auth::user()->usrtype }}'
    };
</script>

<script src="{{ mix('js/app.js') }}"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
  // Tab switching functionality
  document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('#messageTabs button[data-bs-toggle="tab"]');
    
    tabButtons.forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active from all tabs and panes
        tabButtons.forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => {
          pane.classList.remove('show', 'active');
        });
        
        // Add active to clicked tab
        this.classList.add('active');
        const targetId = this.getAttribute('data-target');
        const targetPane = document.querySelector(targetId);
        if (targetPane) {
          targetPane.classList.add('show', 'active');
        }
        
        // Recalculate DataTables column widths when tab becomes visible
        setTimeout(function() {
          $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
        }, 50);
      });
    });
  });

  function deleteMaterial(id) {     
    Swal.fire({
      title: "Are you sure?",
      text: "This will be permanent",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!"
    }).then(function(res) {
      if (res.isConfirmed) {
        $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: "{{ url('/pendaftar/delete') }}",
          method: 'DELETE',
          data: {id: id},
          error: function(err) {
            alert("Error");
            console.log(err);
          },
          success: function(data) {
            window.location.reload();
            alert("success");
          }
        });
      }
    });
  }
</script>

<script type="text/javascript">
  // Search functionality for Find Student tab
  $('#search').keyup(function(event) {
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
      var searchTerm = $(this).val();
      getStudent(searchTerm);
    }
  });

  function getStudent(search) {
    $('#complex_header').DataTable().destroy();

    var edit = true;

    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentMassage') }}",
      method: 'POST',
      data: {search: search},
      beforeSend: function(xhr) {
        $("#complex_header").LoadingOverlay("show", {
          image: `<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="30px" viewBox="0 0 24 30" style="enable-background:new 0 0 50 50;" xml:space="preserve">
            <rect x="0" y="10" width="4" height="10" fill="#333" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0s" dur="0.6s" repeatCount="indefinite"></animate>
            </rect>
            <rect x="8" y="10" width="4" height="10" fill="#333" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.15s" dur="0.6s" repeatCount="indefinite"></animate>
            </rect>
            <rect x="16" y="10" width="4" height="10" fill="#333" opacity="0.2">
            <animate attributeName="opacity" attributeType="XML" values="0.2; 1; .2" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="height" attributeType="XML" values="10; 20; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
            <animate attributeName="y" attributeType="XML" values="10; 5; 10" begin="0.3s" dur="0.6s" repeatCount="indefinite"></animate>
            </rect>
          </svg>`,
          background: "rgba(255,255,255, 0.3)",
          imageResizeFactor: 1,    
          imageAnimation: "2000ms pulse", 
          imageColor: "#019ff8",
          text: "Please wait...",
          textResizeFactor: 0.15,
          textColor: "#019ff8"
        });
        $("#complex_header").LoadingOverlay("hide");
      },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#complex_header').removeAttr('hidden');
        $('#complex_header').html(data);
        $('#complex_header').DataTable({
          scrollX: true,
          autoWidth: false
        });
      }
    });
  }

  // Initialize on document ready
  $(document).ready(function() {
    getNewStudent();
    getOldStudent();
  });

  // Get new (unread) messages
  function getNewStudent() {
    $('#complex_header2').DataTable().destroy();

    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentNewMassage') }}",
      method: 'GET',
      beforeSend: function(xhr) {
        $("#complex_header2").LoadingOverlay("show", {
          // Loading overlay settings
        });
      },
      complete: function() {
        $("#complex_header2").LoadingOverlay("hide");
      },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#complex_header2').removeAttr('hidden');
        $('#complex_header2').html(data);
        $('#complex_header2').DataTable({
          scrollX: true,
          autoWidth: false,
          order: [[8, 'desc']] // Sort by Last Message column (index 8) descending
        });
        
        // Update badge count
        var rowCount = $('#complex_header2 tbody tr').length;
        if (rowCount > 0 && !$('#complex_header2 tbody tr td').hasClass('dataTables_empty')) {
          $('#new-msg-count').text(rowCount).show();
        } else {
          $('#new-msg-count').hide();
        }
      }
    });
  }

  // Get message history (old messages)
  function getOldStudent() {
    $('#complex_header3').DataTable().destroy();

    return $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/all/massage/user/getStudentOldMassage') }}",
      method: 'GET',
      beforeSend: function(xhr) {
        $("#complex_header3").LoadingOverlay("show", {
          // Loading overlay settings
        });
      },
      complete: function() {
        $("#complex_header3").LoadingOverlay("hide");
      },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#complex_header3').removeAttr('hidden');
        $('#complex_header3').html(data);
        $('#complex_header3').DataTable({
          scrollX: true,
          autoWidth: false,
          order: [[8, 'desc']] // Sort by Last Message column (index 8) descending
        });
      }
    });
  }
</script>
@endsection
