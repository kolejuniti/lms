
@extends('layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Quiz</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Quiz</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="card-header mb-4">
                <h3 class="card-title">Quiz List</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-md-2 mb-4">
                        <div class="form-group">
                          <label class="form-label" for="group">Group</label>
                          <select class="form-select" id="group" name="group" required>
                              <option value="" selected disabled>-</option>
                              @foreach ($group as $grp)
                                <option value="{{ $grp->groupid }}|{{ $grp->groupname }}">Group {{ $grp->groupname }}</option>
                              @endforeach
                          </select>
                          <span class="text-danger">@error('folder')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                      <div id = "status">
                        <div class="col-sm-12">
                          <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                            <thead>
                              <tr>
                                <th style="width: 1%">
                                  No.
                                </th>
                                <th style="width: 15%">
                                  Name
                                </th>
                                <th style="width: 5%">
                                  Matric No.
                                </th>
                                <th style="width: 5%">
                                  Program
                                </th>
                                <th style="width: 20%">
                                  Submission Date
                                </th>
                                <th style="width: 10%">
                                  Status
                                </th>
                                <th style="width: 5%">
                                  Marks
                                </th>
                                <th style="width: 20%">
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($quiz as $key => $qz)

                              @if (count($status[$key]) > 0)
                                @php
                                  $alert = "badge bg-success";
                                @endphp
                              @else
                                @php
                                  $alert = "badge bg-danger";
                                @endphp
                              @endif
                              
                              <tr>
                                <td style="width: 1%">
                                    {{ $key+1 }}
                                </td>
                                <td style="width: 15%">
                                  <span class="{{ $alert }}">{{ $qz->name }}</span>
                                </td>
                                <td style="width: 5%">
                                  <span class="">{{ $qz->no_matric }}</span>
                                </td>
                                <td style="width: 5%">
                                  <span class="">{{ $qz->progcode }}</span>
                                </td>
                                @if (count($status[$key]) > 0)
                                  @foreach ($status[$key] as $keys => $sts)
                                    <td style="width: 20%">
                                          {{ empty($sts) ? '-' : $sts->submittime }}
                                    </td>
                                    <td>
                                          {{ empty($sts) ? '-' : $sts->status }}
                                    </td>
                                    <td>
                                          {{ empty($sts) ? '-' : $sts->final_mark }} / {{ $qz->total_mark }}
                                    </td>
                                    @php
                                      // Get active assessment period for current user and session
                                      $currentDate = now()->format('Y-m-d');
                                      $currentUserIc = auth()->user()->ic;
                                      $currentSessionId = Session::get('SessionID');
                                      
                                      $period = null;
                                      if ($currentSessionId) {
                                          $period = DB::table('tblassessment_period')
                                              ->where('Start', '<=', $currentDate)
                                              ->where('End', '>=', $currentDate)
                                              ->get()
                                              ->filter(function ($p) use ($currentUserIc, $currentSessionId) {
                                                  $userIcs = json_decode($p->user_ic, true) ?: [];
                                                  $sessions = json_decode($p->session, true) ?: [];
                                                  
                                                  return in_array($currentUserIc, $userIcs) && 
                                                         in_array($currentSessionId, $sessions);
                                              })
                                              ->first();
                                      }
                                      
                                      // Determine if buttons should be visible
                                      $showButtons = false;
                                      if (!empty($period)) {
                                          if ($period->subject == 'ALL') {
                                              $showButtons = true;
                                          } else {
                                              $course = DB::table('subjek')->where('id', Session::get('CourseIDS'))->first();

                                              $courseName = $course->course_name ?? '';
                                              $showButtons = in_array($courseName, ['LATIHAN INDUSTRI', 'LATIHAN PRAKTIKAL', 'LATIHAN PRAKTIKUM', 'LATIHAN AMALI (PRAKTIKAL)', 'INDUSTRIAL TRAINING', 'PRACTICAL TRAINING', 'PRAKTIKUM']);
                                          }
                                      }
                                    @endphp
                                    <td class="project-actions text-center" >
                                      <a class="btn btn-success btn-sm mr-2" href="/lecturer/quiz/{{ request()->quiz }}/{{ $sts->userid }}/result" {{ $showButtons ? '' : 'hidden' }}>
                                          <i class="ti-pencil-alt">
                                          </i>
                                          Answer
                                      </a>
                                      @if(date('Y-m-d H:i:s') >= $qz->date_from && date('Y-m-d H:i:s') <= $qz->date_to)
                                      <a class="btn btn-danger btn-sm mr-2" onclick="deleteStdQuiz('{{ $sts->id }}')" {{ $showButtons ? '' : 'hidden' }}>
                                          <i class="ti-trash">
                                          </i>
                                          Delete
                                      </a>
                                      @endif
                                      {{-- @if(date('Y-m-d H:i:s') > $qz->date_to && (empty($sts->final_mark) || $sts->final_mark == '' || $sts->final_mark == '0' || $sts->final_mark == 0 || $sts->final_mark == '-'))
                                      <a class="btn btn-warning btn-sm mr-2" onclick="openManualMarkModal('{{ $sts->userid }}', '{{ $qz->name }}', '{{ $qz->total_mark }}', '{{ request()->quiz }}')" {{ $showButtons ? '' : 'hidden' }}>
                                          <i class="ti-marker-alt">
                                          </i>
                                          Manual Mark
                                      </a>
                                      @endif --}}
                                    </td>
                                  @endforeach
                                @else
                                  <td style="width: 20%">
                                    -
                                  </td>
                                  <td>
                                    -
                                  </td>
                                  <td>
                                  -
                                  </td> 
                                  <td >
                                    @if(date('Y-m-d H:i:s') > $qz->date_to)
                                    @php
                                      // Get active assessment period for current user and session
                                      $currentDate = now()->format('Y-m-d');
                                      $currentUserIc = auth()->user()->ic;
                                      $currentSessionId = Session::get('SessionID');
                                      
                                      $period = null;
                                      if ($currentSessionId) {
                                          $period = DB::table('tblassessment_period')
                                              ->where('Start', '<=', $currentDate)
                                              ->where('End', '>=', $currentDate)
                                              ->get()
                                              ->filter(function ($p) use ($currentUserIc, $currentSessionId) {
                                                  $userIcs = json_decode($p->user_ic, true) ?: [];
                                                  $sessions = json_decode($p->session, true) ?: [];
                                                  
                                                  return in_array($currentUserIc, $userIcs) && 
                                                         in_array($currentSessionId, $sessions);
                                              })
                                              ->first();
                                      }
                                      
                                      // Determine if buttons should be visible
                                      $showButtons = false;
                                      if (!empty($period)) {
                                          if ($period->subject == 'ALL') {
                                              $showButtons = true;
                                          } else {
                                              $course = DB::table('subjek')->where('id', Session::get('CourseIDS'))->first();
                                              $courseName = $course->course_name ?? '';
                                              $showButtons = in_array($courseName, ['LATIHAN INDUSTRI', 'LATIHAN PRAKTIKAL', 'LATIHAN PRAKTIKUM', 'LATIHAN AMALI (PRAKTIKAL)', 'INDUSTRIAL TRAINING', 'PRACTICAL TRAINING', 'PRAKTIKUM']);
                                          }
                                      }
                                    @endphp
                                    <a class="btn btn-warning btn-sm mr-2" onclick="openManualMarkModal('{{ $qz->student_ic }}', '{{ $qz->name }}', '{{ $qz->total_mark }}', '{{ request()->quiz }}')" {{ $showButtons ? '' : 'hidden' }}>
                                        <i class="ti-marker-alt">
                                        </i>
                                        Manual Mark
                                    </a>
                                    @endif
                                  </td>
                                @endif
                              </tr> 
                            
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
        <!-- /.content -->
    
    </div>
</div>
<!-- /.content-wrapper -->

<!-- Manual Mark Modal -->
<div id="manualMarkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Manual Mark Entry</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="manualMarkForm">
          <input type="hidden" id="manual_userid" name="userid">
          <input type="hidden" id="manual_quizid" name="quizid">
          
          <div class="form-group mb-3">
            <label for="student_name">Student Name</label>
            <input type="text" class="form-control" id="student_name" readonly>
          </div>
          
          <div class="form-group mb-3">
            <label for="manual_mark">Final Mark <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="manual_mark" name="mark" min="0" step="0.01" required>
            <small class="form-text text-muted">Total Mark: <span id="total_mark_display"></span></small>
          </div>
          
          <div class="form-group mb-3">
            <label for="manual_comments">Comments</label>
            <textarea class="form-control" id="manual_comments" name="comments" rows="3" placeholder="Enter comments (optional)"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitManualMark()">Save Mark</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
    var selected_group = "";
    var selected_quiz = "{{ request()->quiz }}";

    $(document).ready( function () {
        $('#myTable').DataTable();

        
    } );

    $(document).on('change', '#group', function(e) {
        selected_group = $(e.target).val();

        getGroup(selected_group);
    });

    function getGroup(group)
    {

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/quiz/' . request()->id . '/' . request()->quiz . '/getGroup') }}",
            method   : 'POST',
            data 	 : {group: group},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
              if(data.message == 'success')
              {
              $('#myTable').DataTable().destroy();
              $('#myTable').html(data.content);
              $('#myTable').DataTable({
                dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
              });
              }

            }
        });

    }

    function deleteStdQuiz(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
    }).then(function(res){
      
      if (res.isConfirmed){
                $.ajax({
                    headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                    url      : "{{ url('lecturer/quiz/status/delete') }}",
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

    function openManualMarkModal(userid, studentName, totalMark, quizid) {
        // Set modal values
        $('#manual_userid').val(userid);
        $('#manual_quizid').val(quizid);
        $('#student_name').val(studentName);
        $('#total_mark_display').text(totalMark);
        $('#manual_mark').attr('max', totalMark);
        $('#manual_mark').val('');
        $('#manual_comments').val('');
        
        // Show modal
        $('#manualMarkModal').modal('show');
    }

    function submitManualMark() {
        var userid = $('#manual_userid').val();
        var quizid = $('#manual_quizid').val();
        var mark = $('#manual_mark').val();
        var comments = $('#manual_comments').val();
        var totalMark = $('#total_mark_display').text();

        // Validate mark
        if (!mark || mark === '') {
            Swal.fire({
                title: "Error!",
                text: "Please enter a mark",
                icon: "error"
            });
            return;
        }

        if (parseFloat(mark) > parseFloat(totalMark)) {
            Swal.fire({
                title: "Error!",
                text: "Mark cannot exceed total mark (" + totalMark + ")",
                icon: "error"
            });
            return;
        }

        if (parseFloat(mark) < 0) {
            Swal.fire({
                title: "Error!",
                text: "Mark cannot be negative",
                icon: "error"
            });
            return;
        }

        // Confirm submission
        Swal.fire({
            title: "Confirm Manual Mark Entry?",
            text: "This will create a manual submission entry for the student with mark: " + mark + " / " + totalMark,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, save it!"
        }).then(function(result) {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('lecturer/quiz/manual-mark') }}",
                    method: 'POST',
                    data: {
                        userid: userid,
                        quizid: quizid,
                        mark: mark,
                        comments: comments || 'Manual entry by lecturer'
                    },
                    error: function(err) {
                        console.log(err);
                        let errorMessage = "An error occurred while saving the mark.";
                        if (err.responseJSON && err.responseJSON.message) {
                            errorMessage = err.responseJSON.message;
                        }
                        Swal.fire({
                            title: "Error!",
                            text: errorMessage,
                            icon: "error"
                        });
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: "Success!",
                                text: data.message,
                                icon: "success"
                            }).then(() => {
                                $('#manualMarkModal').modal('hide');
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
    }

</script>
@stop