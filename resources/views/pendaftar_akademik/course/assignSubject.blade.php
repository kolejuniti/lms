@extends('layouts.pendaftar_akademik')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Subject</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Subject</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Assigned Subject</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-12 mb-3">
              <div class="row align-items-end">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="program">Program</label>
                    <select class="form-select" id="program" name="program">
                      <option value="-" selected disabled>-- Select Program --</option>
                      @foreach ($data['program'] as $prg)
                      <option value="{{ $prg->id }}">{{ $prg->progname }} ({{ $prg->progcode }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="form-label" for="structure">Structure</label>
                    <select class="form-select" id="structure" name="structure">
                      <option value="-" selected disabled>-- Select Structure --</option>
                      @foreach ($data['structure'] as $stc)
                      <option value="{{ $stc->id }}">{{ $stc->structure_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4 d-flex align-items-end justify-content-end">
                  <a type="button" class="waves-effect waves-light btn btn-info btn-sm" onclick="showAddCourseModal()">
                    <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Course
                  </a>
                  <a type="button" class="waves-effect waves-light btn btn-success btn-sm ml-2" onclick="showCopyModal()">
                    <i class="fa fa-copy"></i> &nbsp Copy Structure
                  </a>
                  <a type="button" class="waves-effect waves-light btn btn-warning btn-sm ml-2" onclick="showStudentSubjectModal()">
                    <i class="fa fa-users"></i> &nbsp Add Subject to Students
                  </a>
                </div>
              </div>

              {{-- Hidden fields kept for backward-compatibility with submit() / getCourse() calls --}}
              <input type="hidden" id="course" name="course" value="">
              <input type="hidden" id="intake" name="intake" value="">
              <select class="d-none" id="semester" name="semester">
                <option value="-" selected disabled>-</option>
                @foreach ($data['semester'] as $crs)
                <option value="{{ $crs->id }}">{{ $crs->id }}</option>
                @endforeach
              </select>

              {{-- Intake caption --}}
              <div id="intakeCaptionWrapper" class="mt-3" style="display:none;">
                <div class="alert alert-info mb-0 py-2 px-3" id="intakeCaption">
                  <i class="fa fa-calendar"></i> <strong>Linked Intake(s):</strong> <span id="intakeCaptionText">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
              <tr>
                <th style="width: 1%">
                  No.
                </th>
                <th style="width: 20%">
                  Course Name
                </th>
                <th style="width: 5%">
                  Course Code
                </th>
                <th style="width: 5%">
                  Structure
                </th>
                <th style="width: 10%">
                  Program
                </th>
                <th style="width: 5%">
                  Semester
                </th>
                <th style="width: 20%">
                </th>
              </tr>
            </thead>
            <tbody id="table">
              {{-- @foreach($data['assigned'] as $i => $course)
              <tr>
                <td>{{ $i+1 }}</td>
              <td class="project-actions text-right" style="text-align: center;">
                <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $course->id }}')">
                  <i class="ti-trash"></i> Delete</a>
              </td>
              </tr>
              @endforeach --}}
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- Copy Structure Modal -->
<div class="modal fade" id="copyStructureModal" tabindex="-1" role="dialog" aria-labelledby="copyStructureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="copyStructureModalLabel">Copy Structure to Different Intake &amp; Structure</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        {{-- Row 1: Program + Source Structure --}}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="copySourceProgram">Program</label>
              <select class="form-select" id="copySourceProgram" name="copySourceProgram">
                <option value="-" selected disabled>Select Program</option>
                @foreach ($data['program'] as $prg)
                <option value="{{ $prg->id }}">{{ $prg->progname }} ({{ $prg->progcode }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="copySourceStructure">Source Structure</label>
              <select class="form-select" id="copySourceStructure" name="copySourceStructure">
                <option value="-" selected disabled>Select Structure</option>
                @foreach ($data['structure'] as $stc)
                <option value="{{ $stc->id }}">{{ $stc->structure_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        {{-- Row 2: Source Intake + Target Structure --}}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="copySourceIntake">Source Intake</label>
              <select class="form-select" id="copySourceIntake" name="copySourceIntake">
                <option value="-" selected disabled>Select Source Intake</option>
                @foreach ($data['intake'] as $crs)
                <option value="{{ $crs->SessionID }}">{{ $crs->SessionName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label" for="copyTargetStructure">
                Target Structure <span class="text-danger">*</span>
              </label>
              <select class="form-select" id="copyTargetStructure" name="copyTargetStructure">
                <option value="-" selected disabled>Select Target Structure</option>
                @foreach ($data['structure'] as $stc)
                <option value="{{ $stc->id }}">{{ $stc->structure_name }}</option>
                @endforeach
              </select>
              <small class="text-muted">Can be the same or different from source structure.</small>
            </div>
          </div>
        </div>

        {{-- Row 3: Target Intake(s) --}}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-label" for="copyTargetIntakes">
                Target Intake(s) <span class="text-danger">*</span>
              </label>
              <select class="form-select" id="copyTargetIntakes" name="copyTargetIntakes" multiple style="height: 120px">
                @foreach ($data['intake'] as $crs)
                <option value="{{ $crs->SessionID }}">{{ $crs->SessionName }}</option>
                @endforeach
              </select>
              <small class="text-muted">Hold Ctrl/Cmd to select multiple intakes.</small>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div id="copyPreviewSection" style="display: none;">
          <hr>
          <h6>Preview of courses to be copied:</h6>
          <div id="copyPreviewContent" class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <!-- Preview content will be loaded here -->
          </div>
          <div id="copyPreviewSummary" class="mt-2">
            <!-- Summary will be shown here -->
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info" onclick="loadCopyPreview()">
          <i class="fa fa-eye"></i> Preview
        </button>
        <button type="button" class="btn btn-success" onclick="executeCopy()" id="executeCopyBtn" disabled>
          <i class="fa fa-copy"></i> Copy Structure
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Add Subject to Students Modal -->
<div class="modal fade" id="studentSubjectModal" tabindex="-1" role="dialog" aria-labelledby="studentSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentSubjectModalLabel">Add Subjects to Students</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label" for="studentProgram">Program</label>
              <select class="form-select" id="studentProgram" name="studentProgram">
                <option value="-" selected disabled>Select Program</option>
                @foreach ($data['program'] as $prg)
                <option value="{{ $prg->id }}">{{ $prg->progname }} ({{ $prg->progcode }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label" for="studentIntake">Intake</label>
              <select class="form-select" id="studentIntake" name="studentIntake">
                <option value="-" selected disabled>Select Intake</option>
                @foreach ($data['intake'] as $crs)
                <option value="{{ $crs->SessionID }}">{{ $crs->SessionName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label" for="studentSemester">Semester</label>
              <select class="form-select" id="studentSemester" name="studentSemester">
                <option value="-" selected disabled>Select Semester</option>
                @foreach ($data['semester'] as $crs)
                <option value="{{ $crs->id }}">{{ $crs->id }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div id="studentPreviewSection" style="display: none;">
          <hr>
          <h6>Students and Subjects Preview:</h6>

          <!-- Students Section -->
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-info">Students to Process:</h6>
              <div id="studentsList" class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                <!-- Students list will be loaded here -->
              </div>
            </div>
            <div class="col-md-6">
              <h6 class="text-success">Subjects to Assign:</h6>
              <div id="subjectsList" class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                <!-- Subjects list will be loaded here -->
              </div>
            </div>
          </div>

          <div id="studentPreviewSummary" class="mt-3">
            <!-- Summary will be shown here -->
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info" onclick="loadStudentPreview()">
          <i class="fa fa-eye"></i> Preview Students & Subjects
        </button>
        <button type="button" class="btn btn-warning" onclick="executeStudentSubjectAssignment()" id="executeStudentBtn" disabled>
          <i class="fa fa-users"></i> Add Subjects to Students
        </button>
      </div>
    </div>
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
  function deleteCourse(id) {
    Swal.fire({
      title: "Are you sure?",
      text: "This will be permanent",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!"
    }).then(function(res) {

      if (res.isConfirmed) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ url('/AR/course/create') }}",
          method: 'DELETE',
          data: {
            id: id
          },
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

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
      pageLength: 100,

      buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ],
    });
  });
</script>

{{-- ===== Add Course Modal ===== --}}
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white" id="addCourseModalLabel">
          <i class="fa fa-plus-circle"></i> Add Course to Structure
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        {{-- Program & Structure info (read-only) --}}
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label text-muted mb-1"><strong>Program</strong></label>
            <div class="form-control bg-light" id="modalProgramLabel" style="min-height:38px;">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label text-muted mb-1"><strong>Structure</strong></label>
            <div class="form-control bg-light" id="modalStructureLabel" style="min-height:38px;">-</div>
          </div>
        </div>

        <hr class="my-3">

        {{-- Subject dropdown (Select2 multi-select) --}}
        <div class="form-group">
          <label class="form-label" for="modalSubjek"><strong>Subject(s)</strong> <span class="text-danger">*</span></label>
          <select class="select2-subjek" id="modalSubjek" name="modalSubjek[]" multiple="multiple" style="width:100%">
            @foreach ($data['subjek'] as $sbj)
            <option value="{{ $sbj->id }}">{{ $sbj->course_code }} - {{ $sbj->course_name }}</option>
            @endforeach
          </select>
          <small class="text-muted">You can search and select multiple subjects at once.</small>
        </div>

        {{-- Intake dropdown (10 latest) --}}
        <div class="form-group">
          <label class="form-label" for="modalIntake"><strong>Intake</strong> <span class="text-danger">*</span></label>
          <select class="form-select" id="modalIntake" name="modalIntake">
            <option value="">-- Select Intake --</option>
            @foreach ($data['intake_latest'] as $itk)
            <option value="{{ $itk->SessionID }}">{{ $itk->SessionName }}</option>
            @endforeach
          </select>
        </div>

        {{-- Semester dropdown (1–8) --}}
        <div class="form-group">
          <label class="form-label" for="modalSemester"><strong>Semester</strong> <span class="text-danger">*</span></label>
          <select class="form-select" id="modalSemester" name="modalSemester">
            <option value="">-- Select Semester --</option>
            @foreach(range(1, 8) as $sem)
            <option value="{{ $sem }}">Semester {{ $sem }}</option>
            @endforeach
          </select>
        </div>

        {{-- Alert area --}}
        <div id="addCourseAlert" class="alert" style="display:none;"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info" id="submitAddCourseBtn" onclick="submitAddCourse()">
          <i class="fa fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>
{{-- ===== /Add Course Modal ===== --}}

{{-- ===== Delete by Intake Modal ===== --}}
<div class="modal fade" id="deleteIntakeModal" tabindex="-1" role="dialog" aria-labelledby="deleteIntakeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="deleteIntakeModalLabel">
          <i class="fa fa-trash"></i> Remove Subject from Intake(s)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="mb-1">Select which intake(s) to remove</p>
        <p>
          <strong id="deleteCourseLabel" class="text-danger"></strong>
          &nbsp;&mdash;&nbsp;Semester <strong id="deleteSemesterLabel"></strong>
        </p>
        <hr class="my-2">
        <div id="deleteIntakeList">
          <div class="text-center text-muted py-3">
            <i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading intakes...
          </div>
        </div>
        <div id="deleteIntakeAlert" class="alert mt-2" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDeleteByIntakes()">
          <i class="fa fa-trash"></i> Delete Selected
        </button>
      </div>
    </div>
  </div>
</div>
{{-- ===== /Delete by Intake Modal ===== --}}

<script type="text/javascript">
  var selected_program = "";
  var selected_structure = "";

  // When program changes, check if we can load subjects
  $(document).on('change', '#program', function(e) {
    selected_program = $(e.target).val();
    tryLoadSubjects();
  });

  // When structure changes, check if we can load subjects
  $(document).on('change', '#structure', function(e) {
    selected_structure = $(e.target).val();
    tryLoadSubjects();
  });

  // Load subjects when both program and structure are selected
  function tryLoadSubjects() {
    var program = $('#program').val();
    var structure = $('#structure').val();

    if (!program || program === '-' || !structure || structure === '-') {
      // Hide intake caption if either dropdown is unselected
      $('#intakeCaptionWrapper').hide();
      return;
    }

    loadSubjectsByProgramStructure(program, structure);
  }

  function loadSubjectsByProgramStructure(program, structure) {
    // Destroy existing DataTable before reloading
    try {
      $('#myTable').DataTable().destroy();
    } catch (e) {}

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/assignCourse/getSubjectsByFilter') }}",
      method: 'POST',
      data: {
        program: program,
        structure: structure
      },
      beforeSend: function() {
        $('#intakeCaptionWrapper').hide();
        $("#myTable").LoadingOverlay("show", {
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
        $("#myTable").LoadingOverlay("hide");
      },
      error: function(err) {
        alert("Error loading subjects.");
        console.log(err);
      },
      success: function(data) {
        if (data.error) {
          alert(data.error);
          return;
        }

        // Update table
        $('#myTable').removeAttr('hidden');
        $('#myTable').html(data.tableHtml);
        $('#myTable').DataTable({
          dom: 'lBfrtip',
          buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });

        // Update intake caption
        if (data.intakes && data.intakes.length > 0) {
          var intakeNames = data.intakes.map(function(i) {
            return i.SessionName;
          }).join(', ');
          $('#intakeCaptionText').text(intakeNames);
          $('#intakeCaptionWrapper').show();
        } else {
          $('#intakeCaptionText').text('No intake linked to this program & structure.');
          $('#intakeCaptionWrapper').show();
        }
      }
    });
  }

  // Legacy getCourse function (still used by submit() after Add Course)
  function getCourse(course, structure, intake) {
    var program = $('#program').val();
    var sel_structure = $('#structure').val();
    if (program && program !== '-' && sel_structure && sel_structure !== '-') {
      loadSubjectsByProgramStructure(program, sel_structure);
    }
  }

  function updateCourse(id) {
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/course/update') }}",
      method: 'POST',
      data: {
        id: id
      },
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        $('#getModal').html(data);
        $('#uploadModal').modal('show');
      }
    });

  }

  function submit() {

    var formData = new FormData();

    getInput = {
      course: $('#course').val(),
      structure: $('#structure').val(),
      intake: $('#intake').val(),
      semester: $('#semester').val(),
      program: $('#program').val()
    };

    formData.append('addCourse', JSON.stringify(getInput));

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/AR/assignCourse/addCourse",
      type: 'POST',
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      error: function(err) {
        console.log(err);
      },
      success: function(res) {
        try {
          if (res.message == "Success") {
            alert("Success! Claim has been updated!");

            // Start with an empty table structure
            var newTable = '<thead><tr><th style="width: 1%">No.</th><th style="width: 20%">Course Name</th><th style="width: 5%">Course Code</th><th style="width: 5%">Credit</th><th style="width: 5%">Program</th><th style="width: 5%">Session</th><th style="width: 5%">Semester</th><th style="width: 20%"></th></tr></thead><tbody>';

            // Add new rows
            $.each(res.data, function(i, item) {
              newTable += '<tr>';
              newTable += '<td style="width: 1%">' + (i + 1) + '</td>';
              newTable += '<td style="width: 20%">' + item.course_name + '</td>';
              newTable += '<td style="width: 5%">' + item.course_code + '</td>';
              newTable += '<td style="width: 5%">' + item.structure_name + '</td>';
              newTable += '<td style="width: 5%">' + item.progname + '</td>';
              newTable += '<td style="width: 5%">' + item.SessionName + '</td>';
              newTable += '<td style="width: 5%">' + item.semester_id + '</td>';
              newTable += '<td class="project-actions text-right" style="text-align: center;">';
              newTable += '<a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial(\'' + item.id + '\')">';
              newTable += '<i class="ti-trash"></i> Delete</a>';
              newTable += '</td>';
              newTable += '</tr>';
            });

            // Close table structure
            newTable += "</tbody>";

            // Replace the div contents with the new table
            $('#myTable').html(newTable);

          } else {
            $('.error-field').html('');
            if (res.message == "Field Error") {
              for (f in res.error) {
                $('#' + f + '_error').html(res.error[f]);
              }
            } else if (res.message == "Group code already existed inside the system") {
              $('#classcode_error').html(res.message);
            } else {
              alert(res.message);
            }
            $("html, body").animate({
              scrollTop: 0
            }, "fast");
          }
        } catch (err) {
          alert("Ops sorry, there is an error");
        }
      }
    });

  }

  function deleteMaterial(id) {

    Swal.fire({
      title: "Are you sure?",
      text: "This will be permanent",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!"
    }).then(function(res) {

      if (res.isConfirmed) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ url('/AR/assignCourse/deleteCourse2') }}",
          method: 'DELETE',
          data: {
            id: id
          },
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

  // Copy Structure Functions
  function showCopyModal() {
    $('#copyStructureModal').modal('show');
    resetCopyModal();
  }

  function resetCopyModal() {
    $('#copySourceProgram').val('-');
    $('#copySourceStructure').val('-');
    $('#copySourceIntake').val('-');
    $('#copyTargetStructure').val('-');
    $('#copyTargetIntakes').val([]);
    $('#copyPreviewSection').hide();
    $('#executeCopyBtn').prop('disabled', true);
  }

  function loadCopyPreview() {
    var program = $('#copySourceProgram').val();
    var structure = $('#copySourceStructure').val();
    var intake = $('#copySourceIntake').val();
    var targetStructure = $('#copyTargetStructure').val();
    var targetIntakes = $('#copyTargetIntakes').val();

    if (!program || program === '-' || !structure || structure === '-' ||
      !intake || intake === '-' || !targetStructure || targetStructure === '-' ||
      !targetIntakes || targetIntakes.length === 0) {
      alert('Please select all required fields: Program, Source Structure, Source Intake, Target Structure, and Target Intake(s)');
      return;
    }

    // Warn if same structure AND same intake overlap exists
    if (targetStructure === structure && targetIntakes.includes(intake)) {
      alert('Source intake cannot be the same as a target intake when copying to the same structure.');
      return;
    }

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('/AR/assignCourse/getStructurePreview') }}",
      method: 'POST',
      data: {
        program: program,
        structure: structure,
        intake: intake
      },
      beforeSend: function() {
        $('#copyPreviewContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading preview...</div>');
      },
      error: function(err) {
        alert("Error loading preview");
        console.log(err);
      },
      success: function(data) {
        if (data.courses && data.courses.length > 0) {
          var previewHtml = '<table class="table table-sm table-bordered">';
          previewHtml += '<thead><tr><th>Course Code</th><th>Course Name</th><th>Semester</th></tr></thead><tbody>';

          data.courses.forEach(function(course) {
            previewHtml += '<tr>';
            previewHtml += '<td>' + course.course_code + '</td>';
            previewHtml += '<td>' + course.course_name + '</td>';
            previewHtml += '<td>' + course.semester_id + '</td>';
            previewHtml += '</tr>';
          });

          previewHtml += '</tbody></table>';
          $('#copyPreviewContent').html(previewHtml);

          var targetIntakeNames = [];
          $('#copyTargetIntakes option:selected').each(function() {
            targetIntakeNames.push($(this).text());
          });
          var targetStructureName = $('#copyTargetStructure option:selected').text();

          $('#copyPreviewSummary').html(
            '<div class="alert alert-info">' +
            '<strong>Summary:</strong> ' + data.count + ' course(s) will be copied to ' +
            'structure <em>' + targetStructureName + '</em> &mdash; ' +
            targetIntakes.length + ' intake(s): <em>' + targetIntakeNames.join(', ') + '</em>' +
            '</div>'
          );

          $('#copyPreviewSection').show();
          $('#executeCopyBtn').prop('disabled', false);
        } else {
          $('#copyPreviewContent').html('<div class="alert alert-warning">No courses found in the selected structure and intake.</div>');
          $('#copyPreviewSummary').html('');
          $('#copyPreviewSection').show();
          $('#executeCopyBtn').prop('disabled', true);
        }
      }
    });
  }

  function executeCopy() {
    var program = $('#copySourceProgram').val();
    var sourceStructure = $('#copySourceStructure').val();
    var sourceIntake = $('#copySourceIntake').val();
    var targetStructure = $('#copyTargetStructure').val();
    var targetIntakes = $('#copyTargetIntakes').val();

    if (!program || !sourceStructure || !sourceIntake || !targetStructure || !targetIntakes || targetIntakes.length === 0) {
      alert('Please ensure all fields are selected');
      return;
    }

    var copyData = {
      program: program,
      sourceStructure: sourceStructure,
      sourceIntake: sourceIntake,
      targetStructure: targetStructure,
      targetIntakes: targetIntakes
    };

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('/AR/assignCourse/copyStructure') }}",
      method: 'POST',
      data: {
        copyData: JSON.stringify(copyData)
      },
      beforeSend: function() {
        $('#executeCopyBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Copying...');
      },
      error: function(err) {
        alert("Error during copy operation");
        console.log(err);
        $('#executeCopyBtn').prop('disabled', false).html('<i class="fa fa-copy"></i> Copy Structure');
      },
      success: function(data) {
        $('#copyStructureModal').modal('hide');

        if (data.message === 'Copy operation completed successfully') {
          var message = 'Copy completed successfully!\n';
          message += 'Copied: ' + data.copiedCount + ' records\n';
          if (data.skippedCount > 0) {
            message += 'Skipped: ' + data.skippedCount + ' records (already exist)';
          }
          alert(message);

          // Refresh the current view if filters are applied
          var program = $('#program').val();
          var structure = $('#structure').val();
          if (program && program !== '-' && structure && structure !== '-') {
            loadSubjectsByProgramStructure(program, structure);
          }
        } else {
          alert(data.message || 'Copy operation failed');
        }

        $('#executeCopyBtn').prop('disabled', false).html('<i class="fa fa-copy"></i> Copy Structure');
      }
    });
  }

  // Student Subject Assignment Functions
  function showStudentSubjectModal() {
    $('#studentSubjectModal').modal('show');
    resetStudentModal();
  }

  function resetStudentModal() {
    $('#studentProgram').val('-');
    $('#studentIntake').val('-');
    $('#studentSemester').val('-');
    $('#studentPreviewSection').hide();
    $('#executeStudentBtn').prop('disabled', true);
  }

  function loadStudentPreview() {
    var program = $('#studentProgram').val();
    var intake = $('#studentIntake').val();
    var semester = $('#studentSemester').val();

    if (!program || program === '-' || !intake || intake === '-' || !semester || semester === '-') {
      alert('Please select all required fields: Program, Intake, and Semester');
      return;
    }

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('/AR/assignCourse/getStudentPreview') }}",
      method: 'POST',
      data: {
        program: program,
        intake: intake,
        semester: semester
      },
      beforeSend: function() {
        $('#studentsList').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading students...</div>');
        $('#subjectsList').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading subjects...</div>');
      },
      error: function(err) {
        alert("Error loading preview");
        console.log(err);
      },
      success: function(data) {
        // Display students to process
        if (data.studentsToProcess && data.studentsToProcess.length > 0) {
          var studentsHtml = '<table class="table table-sm table-bordered">';
          studentsHtml += '<thead><tr><th>IC</th><th>Name</th><th>Matric No</th></tr></thead><tbody>';

          data.studentsToProcess.forEach(function(student) {
            studentsHtml += '<tr>';
            studentsHtml += '<td>' + student.ic + '</td>';
            studentsHtml += '<td>' + student.name + '</td>';
            studentsHtml += '<td>' + student.no_matric + '</td>';
            studentsHtml += '</tr>';
          });

          studentsHtml += '</tbody></table>';
          $('#studentsList').html(studentsHtml);
        } else {
          $('#studentsList').html('<div class="alert alert-info">No students without subjects found.</div>');
        }

        // Display subjects to assign
        if (data.subjectsToAssign && data.subjectsToAssign.length > 0) {
          var subjectsHtml = '<table class="table table-sm table-bordered">';
          subjectsHtml += '<thead><tr><th>Code</th><th>Name</th><th>Credit</th></tr></thead><tbody>';

          data.subjectsToAssign.forEach(function(subject) {
            subjectsHtml += '<tr>';
            subjectsHtml += '<td>' + subject.course_code + '</td>';
            subjectsHtml += '<td>' + subject.course_name + '</td>';
            subjectsHtml += '<td>' + subject.course_credit + '</td>';
            subjectsHtml += '</tr>';
          });

          subjectsHtml += '</tbody></table>';
          $('#subjectsList').html(subjectsHtml);
        } else {
          $('#subjectsList').html('<div class="alert alert-warning">No subjects found for this program/intake/semester.</div>');
        }

        // Display summary
        var summaryHtml = '<div class="alert alert-info">';
        summaryHtml += '<strong>Summary:</strong><br>';
        summaryHtml += 'Total Students: ' + data.totalStudents + '<br>';
        summaryHtml += 'Students with existing subjects: ' + data.studentsWithSubjects + '<br>';
        summaryHtml += 'Students to process: ' + data.studentsWithoutSubjects + '<br>';
        summaryHtml += 'Subjects to assign: ' + data.subjectCount;
        summaryHtml += '</div>';

        $('#studentPreviewSummary').html(summaryHtml);

        $('#studentPreviewSection').show();

        // Enable execute button only if there are students to process and subjects to assign
        if (data.studentsWithoutSubjects > 0 && data.subjectCount > 0) {
          $('#executeStudentBtn').prop('disabled', false);
        } else {
          $('#executeStudentBtn').prop('disabled', true);
        }
      }
    });
  }

  function executeStudentSubjectAssignment() {
    var program = $('#studentProgram').val();
    var intake = $('#studentIntake').val();
    var semester = $('#studentSemester').val();

    if (!program || !intake || !semester) {
      alert('Please ensure all fields are selected');
      return;
    }

    Swal.fire({
      title: "Are you sure?",
      text: "This will assign subjects to all eligible students based on the program structure",
      showCancelButton: true,
      confirmButtonText: "Yes, proceed!"
    }).then(function(res) {
      if (res.isConfirmed) {
        var studentData = {
          program: program,
          intake: intake,
          semester: semester
        };

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ url('/AR/assignCourse/addSubjectToStudents') }}",
          method: 'POST',
          data: {
            studentData: JSON.stringify(studentData)
          },
          beforeSend: function() {
            $('#executeStudentBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
          },
          error: function(err) {
            alert("Error during student subject assignment");
            console.log(err);
            $('#executeStudentBtn').prop('disabled', false).html('<i class="fa fa-users"></i> Add Subjects to Students');
          },
          success: function(data) {
            $('#studentSubjectModal').modal('hide');

            if (data.message === 'Subject assignment completed successfully') {
              var message = 'Subject assignment completed!\n\n';
              message += 'Total Students Processed: ' + data.totalStudents + '\n';
              message += 'Students with Subjects Added: ' + data.studentsWithSubjectsAdded + '\n';
              message += 'Students Already Had Subjects: ' + data.studentsAlreadyHaveSubjects + '\n';
              if (data.studentsSkipped > 0) {
                message += 'Students Skipped: ' + data.studentsSkipped;
              }

              alert(message);
            } else {
              alert(data.message || 'Subject assignment failed');
            }

            $('#executeStudentBtn').prop('disabled', false).html('<i class="fa fa-users"></i> Add Subjects to Students');
          }
        });
      }
    });
  }
</script>

<script type="text/javascript">
  // ===== Add Course Modal =====
  // Initialise Select2 on the subject multi-select once (inside modal so it works with the modal)
  $('#addCourseModal').on('shown.bs.modal', function() {
    if (!$('#modalSubjek').hasClass('select2-hidden-accessible')) {
      $('#modalSubjek').select2({
        dropdownParent: $('#addCourseModal'),
        placeholder: 'Search and select subject(s)...',
        allowClear: true,
        width: '100%'
      });
    }
  });

  function showAddCourseModal() {
    var program = $('#program').val();
    var structure = $('#structure').val();

    if (!program || program === '-') {
      alert('Please select a Program first.');
      return;
    }
    if (!structure || structure === '-') {
      alert('Please select a Structure first.');
      return;
    }

    // Fill read-only labels in the modal
    var programText = $('#program option:selected').text();
    var structureText = $('#structure option:selected').text();
    $('#modalProgramLabel').text(programText);
    $('#modalStructureLabel').text(structureText);

    // Reset form fields and alert
    if ($('#modalSubjek').hasClass('select2-hidden-accessible')) {
      $('#modalSubjek').val(null).trigger('change'); // proper Select2 reset
    } else {
      $('#modalSubjek').val('');
    }
    $('#modalIntake').val('');
    $('#modalSemester').val('');
    $('#addCourseAlert').hide().removeClass('alert-success alert-danger').text('');
    $('#submitAddCourseBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');

    $('#addCourseModal').modal('show');
  }

  function submitAddCourse() {
    var program = $('#program').val();
    var structure = $('#structure').val();
    var subjekIds = $('#modalSubjek').val(); // array (multiple select)
    var intakeId = $('#modalIntake').val();
    var semester = $('#modalSemester').val();

    // Client-side validation
    if (!subjekIds || subjekIds.length === 0) {
      showAddCourseAlert('danger', 'Please select at least one Subject.');
      return;
    }
    if (!intakeId) {
      showAddCourseAlert('danger', 'Please select an Intake.');
      return;
    }
    if (!semester) {
      showAddCourseAlert('danger', 'Please select a Semester.');
      return;
    }

    $('#submitAddCourseBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    $('#addCourseAlert').hide();

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/assignCourse/storeCourseToStructure') }}",
      method: 'POST',
      data: {
        program: program,
        structure: structure,
        subjek_ids: subjekIds, // send as array
        intake_id: intakeId,
        semester: semester
      },
      error: function(err) {
        showAddCourseAlert('danger', 'Server error. Please try again.');
        $('#submitAddCourseBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
        console.log(err);
      },
      success: function(data) {
        if (data.error) {
          showAddCourseAlert('danger', data.error);
          $('#submitAddCourseBtn').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
          return;
        }
        // Show summary: inserted + skipped counts
        var msg = data.message || 'Done.';
        if (typeof data.inserted !== 'undefined') {
          msg += ' (' + data.inserted + ' added';
          if (data.skipped > 0) msg += ', ' + data.skipped + ' already existed';
          msg += ')';
        }
        showAddCourseAlert('success', msg);
        setTimeout(function() {
          $('#addCourseModal').modal('hide');
          loadSubjectsByProgramStructure(program, structure);
        }, 1200);
      }
    });
  }

  function showAddCourseAlert(type, msg) {
    $('#addCourseAlert')
      .removeClass('alert-success alert-danger')
      .addClass('alert-' + type)
      .text(msg)
      .show();
  }
  // ===== /Add Course Modal =====
</script>

<script type="text/javascript">
  // ===== Delete by Intake Modal =====

  function showDeleteIntakeModal(courseId, structureId, programId, semester, courseName, courseCode) {
    // Store context for use in confirmDeleteByIntakes()
    window._deleteCtx = {
      courseId: courseId,
      structureId: structureId,
      programId: programId,
      semester: semester
    };

    // Set labels
    $('#deleteCourseLabel').text(courseCode + ' - ' + courseName);
    $('#deleteSemesterLabel').text(semester);

    // Reset modal state
    $('#deleteIntakeList').html(
      '<div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin fa-2x"></i><br>Loading intakes...</div>'
    );
    $('#deleteIntakeAlert').hide().removeClass('alert-success alert-danger alert-warning').text('');
    $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected');

    $('#deleteIntakeModal').modal('show');

    // Load intakes for this course/structure/program/semester via AJAX
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/assignCourse/getIntakesForCourse') }}",
      method: 'POST',
      data: {
        courseId: courseId,
        structureId: structureId,
        programId: programId,
        semester: semester
      },
      error: function() {
        $('#deleteIntakeList').html('<div class="alert alert-danger">Error loading intakes. Please try again.</div>');
      },
      success: function(data) {
        if (data.error) {
          $('#deleteIntakeList').html('<div class="alert alert-danger">' + data.error + '</div>');
          return;
        }
        if (!data.intakes || data.intakes.length === 0) {
          $('#deleteIntakeList').html('<div class="alert alert-warning">No intake records found for this subject.</div>');
          return;
        }

        var html = '<div class="mb-2">';
        html += '<label class="font-weight-bold">';
        html += '<input type="checkbox" id="selectAllDeleteIntakes" class="mr-1"> Select All';
        html += '</label>';
        html += '</div><hr class="my-2">';

        data.intakes.forEach(function(intake) {
          html += '<div class="form-check mb-1">';
          html += '<input class="form-check-input delete-intake-check" type="checkbox"';
          html += ' value="' + intake.id + '" id="del_intake_' + intake.id + '">';
          html += '<label class="form-check-label" for="del_intake_' + intake.id + '">';
          html += intake.SessionName || ('Intake ID: ' + intake.SessionID);
          html += '</label>';
          html += '</div>';
        });

        $('#deleteIntakeList').html(html);

        // Select-all toggle
        $('#selectAllDeleteIntakes').on('change', function() {
          $('.delete-intake-check').prop('checked', $(this).is(':checked'));
        });

        // Keep select-all in sync when individual boxes change
        $(document).on('change', '.delete-intake-check', function() {
          var allChecked = $('.delete-intake-check:not(:checked)').length === 0;
          $('#selectAllDeleteIntakes').prop('checked', allChecked);
        });
      }
    });
  }

  function confirmDeleteByIntakes() {
    var selectedIds = $('.delete-intake-check:checked').map(function() {
      return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
      showDeleteIntakeAlert('danger', 'Please select at least one intake to remove.');
      return;
    }

    $('#confirmDeleteBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
    $('#deleteIntakeAlert').hide();

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{ url('AR/assignCourse/deleteByIntakes') }}",
      method: 'POST',
      data: {
        record_ids: selectedIds
      },
      error: function() {
        showDeleteIntakeAlert('danger', 'Server error. Please try again.');
        $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected');
      },
      success: function(data) {
        if (data.error) {
          showDeleteIntakeAlert('danger', data.error);
          $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Selected');
          return;
        }
        showDeleteIntakeAlert('success', data.message || 'Deleted successfully.');
        setTimeout(function() {
          $('#deleteIntakeModal').modal('hide');
          // Refresh the table
          var program = $('#program').val();
          var structure = $('#structure').val();
          loadSubjectsByProgramStructure(program, structure);
        }, 1000);
      }
    });
  }

  function showDeleteIntakeAlert(type, msg) {
    $('#deleteIntakeAlert')
      .removeClass('alert-success alert-danger alert-warning')
      .addClass('alert-' + type)
      .text(msg)
      .show();
  }

  // ===== /Delete by Intake Modal =====
</script>
@endsection