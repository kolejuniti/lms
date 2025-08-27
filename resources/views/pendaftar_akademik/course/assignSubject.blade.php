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
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="program">Program</label>
                    <select class="form-select" id="program" name="program">
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['program'] as $prg)
                      <option value="{{ $prg->id }}">{{ $prg->progname }} ({{ $prg->progcode }})</option> 
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="course">Course</label>
                    <select class="form-select" id="course" name="course" style="height: 400px" multiple>
                    <option value="-" selected disabled>-</option>
                      {{-- @foreach ($data['course'] as $crs)
                      <option value="{{ $crs->id }}">{{ $crs->course_name }} ({{ $crs->course_code }})</option> 
                      @endforeach --}}
                    </select>
                </div>
              </div>
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="structure">Structure</label>
                    <select class="form-select" id="structure" name="structure">
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['structure'] as $stc)
                      <option value="{{ $stc->id }}">{{ $stc->structure_name}}</option> 
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="intake">Intake</label>
                    <select class="form-select" id="intake" name="intake" style="height: 400px" multiple>
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['intake'] as $crs)
                      <option value="{{ $crs->SessionID }}">{{ $crs->SessionName }}</option> 
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-4 ml-3">
                <div class="form-group">
                    <label class="form-label" for="semester">Semester</label>
                    <select class="form-select" id="semester" name="semester">
                    <option value="-" selected disabled>-</option>
                      @foreach ($data['semester'] as $crs)
                      <option value="{{ $crs->id }}">{{ $crs->id }}</option> 
                      @endforeach
                    </select>
                </div>
              </div>
                <div class="pull-right">
                    <a type="button" class="waves-effect waves-light btn btn-info btn-sm" onclick="submit()">
                        <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Course
                    </a>
                    <a type="button" class="waves-effect waves-light btn btn-success btn-sm ml-2" onclick="showCopyModal()">
                        <i class="fa fa-copy"></i> &nbsp Copy Structure
                    </a>
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
        <h5 class="modal-title" id="copyStructureModalLabel">Copy Structure to Different Intake</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
                <option value="{{ $stc->id }}">{{ $stc->structure_name}}</option> 
                @endforeach
              </select>
            </div>
          </div>
        </div>
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
              <label class="form-label" for="copyTargetIntakes">Target Intake(s)</label>
              <select class="form-select" id="copyTargetIntakes" name="copyTargetIntakes" multiple style="height: 120px">
                @foreach ($data['intake'] as $crs)
                <option value="{{ $crs->SessionID }}">{{ $crs->SessionName }}</option> 
                @endforeach
              </select>
              <small class="text-muted">Hold Ctrl/Cmd to select multiple intakes</small>
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

  function deleteCourse(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/AR/course/create') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

</script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          pageLength: 100,
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script type="text/javascript">
    var selected_program = "";
    var selected_course = "";
    var selected_structure = "";
    var selected_intake = "";

    var url = window.location.href;

    $(document).on('change', '#program', function(e){
      selected_program = $(e.target).val();

       getCourse0(selected_program);

    })

    function getCourse0(program)
    {

        return $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('AR/assignCourse/getCourse0') }}",
                method   : 'POST',
                data 	 : {program: program},
                error:function(err){
                    alert("Error");
                    console.log(err);
                },
                success  : function(data){
                    $('#course').html(data);
                }
            });
        
    }

    $(document).on('change', '#course', async function(e){
      selected_course = $(e.target).val();

      await getCourse(selected_course,selected_structure,selected_intake);

    })

    $(document).on('change', '#structure', async function(e){
      selected_structure = $(e.target).val();

      await getCourse(selected_course,selected_structure,selected_intake);

    })

    $(document).on('change', '#intake', async function(e){
      selected_intake = $(e.target).val();

      await getCourse(selected_course,selected_structure,selected_intake);

    })

  function getCourse(course,structure,intake)
  {
      $('#myTable').DataTable().destroy();

      program = $('#program').val();

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/assignCourse/getCourse2') }}",
            method   : 'POST',
            data 	 : {course: course, structure: structure, intake: intake, program: program},
            beforeSend:function(xhr){
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
                background:"rgba(255,255,255, 0.3)",
                imageResizeFactor : 1,    
                imageAnimation : "2000ms pulse" , 
                imageColor: "#019ff8",
                text : "Please wait...",
                textResizeFactor: 0.15,
                textColor: "#019ff8",
                textColor: "#019ff8"
              });
              $("#myTable").LoadingOverlay("hide");
            },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                if(data.error)
                {

                  alert(data.error);

                }else{
                  $('#myTable').removeAttr('hidden');
                  $('#myTable').html(data);
                  
                  $('#myTable').DataTable({
                    dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                    
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                  });
                  //window.location.reload();
                }
              }
        });
  }

  function updateCourse(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/course/update') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal').html(data);
                $('#uploadModal').modal('show');
            }
        });

  }

  function submit()
  {

    var formData = new FormData();

    getInput = {
      course : $('#course').val(),
      structure : $('#structure').val(),
      intake : $('#intake').val(),
      semester : $('#semester').val(),
      program : $('#program').val()
    };

    formData.append('addCourse', JSON.stringify(getInput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: "/AR/assignCourse/addCourse",
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.message == "Success"){
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

                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Group code already existed inside the system"){
                        $('#classcode_error').html(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });
    
  }

  function deleteMaterial(id)
  {

    Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/AR/assignCourse/deleteCourse2') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
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
    $('#copyTargetIntakes').val([]);
    $('#copyPreviewSection').hide();
    $('#executeCopyBtn').prop('disabled', true);
  }

  function loadCopyPreview() {
    var program = $('#copySourceProgram').val();
    var structure = $('#copySourceStructure').val();
    var intake = $('#copySourceIntake').val();
    var targetIntakes = $('#copyTargetIntakes').val();

    if (!program || program === '-' || !structure || structure === '-' || 
        !intake || intake === '-' || !targetIntakes || targetIntakes.length === 0) {
      alert('Please select all required fields: Program, Source Structure, Source Intake, and Target Intake(s)');
      return;
    }

    // Check if source intake is in target intakes
    if (targetIntakes.includes(intake)) {
      alert('Source intake cannot be the same as target intake(s)');
      return;
    }

    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
          
          $('#copyPreviewSummary').html(
            '<div class="alert alert-info">' +
            '<strong>Summary:</strong> ' + data.count + ' courses will be copied to ' + 
            targetIntakes.length + ' target intake(s): <em>' + targetIntakeNames.join(', ') + '</em>' +
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
    var targetIntakes = $('#copyTargetIntakes').val();

    if (!program || !sourceStructure || !sourceIntake || !targetIntakes || targetIntakes.length === 0) {
      alert('Please ensure all fields are selected');
      return;
    }

    var copyData = {
      program: program,
      sourceStructure: sourceStructure,
      sourceIntake: sourceIntake,
      targetIntakes: targetIntakes
    };

    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('/AR/assignCourse/copyStructure') }}",
      method: 'POST',
      data: {copyData: JSON.stringify(copyData)},
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
          if (selected_course && selected_structure && selected_intake) {
            getCourse(selected_course, selected_structure, selected_intake);
          }
        } else {
          alert(data.message || 'Copy operation failed');
        }
        
        $('#executeCopyBtn').prop('disabled', false).html('<i class="fa fa-copy"></i> Copy Structure');
      }
    });
  }
  

  </script>
@endsection
