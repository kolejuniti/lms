@extends('layouts.pendaftar_akademik')

@section('main')

<!-- Include DataTables and other CSS files -->
<link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Result Filter</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Result Filter</li>
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
      <!-- /.card-header -->
      <div class="card card-primary">
        <div class="card-header">
          <b>Select Input</b>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="from">FROM</label>
                <input type="date" class="form-control" id="from" name="from">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">TO</label>
                <input type="date" class="form-control" id="to" name="to">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                @php
                    // No need for pre-selected values since we're not editing in the form
                    $selectedSessionIds = [];
                @endphp

                <select class="form-select" id="session" name="session" multiple style="height: 250px;">
                    @foreach ($data['session'] as $ses)
                        <option value="{{ $ses->SessionID }}"
                            @if (in_array($ses->SessionID, $selectedSessionIds)) selected @endif>
                            {{ $ses->SessionName }}
                        </option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="lecturer">Lecturer</label>
                @php
                    // No need for pre-selected values since we're not editing in the form
                    $selectLecturerIC = [];
                @endphp

                <select class="form-select" id="lecturer" name="lecturer" multiple style="height: 250px;">
                  <option value="">Select Lecturer</option>
                    @foreach ($data['lecturer'] as $lct)
                        <option value="{{ $lct->ic }}"
                            @if (in_array($lct->ic, $selectLecturerIC)) selected @endif>
                            {{ $lct->name }}
                        </option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>
                    <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Save</button>
          <button type="button" class="btn btn-secondary pull-right mb-3 me-2" onclick="clearForm()">Clear</button>
        </div>
      </div>

      <!-- Data Table Section -->
      <div class="card card-primary mt-4">
        <div class="card-header">
          <b>Assessment Filter Records</b>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="assessmentTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Sessions</th>
                  <th>Lecturers</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($data['periods'] as $index => $period)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $period->Start }}</td>
                  <td>{{ $period->End }}</td>
                  <td>
                    @php
                      $sessions = json_decode($period->session, true);
                      $sessionNames = [];
                      foreach($sessions as $sessionId) {
                        $sessionName = $data['session']->where('SessionID', $sessionId)->first();
                        if($sessionName) {
                          $sessionNames[] = $sessionName->SessionName;
                        }
                      }
                    @endphp
                    {{ implode(', ', $sessionNames) }}
                  </td>
                  <td>
                    @php
                      $lecturers = json_decode($period->user_ic, true);
                      $lecturerNames = [];
                      foreach($lecturers as $lecturerIc) {
                        $lecturer = $data['lecturer']->where('ic', $lecturerIc)->first();
                        if($lecturer) {
                          $lecturerNames[] = $lecturer->name;
                        }
                      }
                    @endphp
                    {{ implode(', ', $lecturerNames) }}
                  </td>
                  <td>{{ $period->created_at ? date('Y-m-d H:i', strtotime($period->created_at)) : '-' }}</td>
                  <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editRecord({{ $period->id }})">
                      <i class="fa fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $period->id }})">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Assessment Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" id="editId" name="editId">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="editFrom">FROM</label>
                <input type="date" class="form-control" id="editFrom" name="editFrom">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="editTo">TO</label>
                <input type="date" class="form-control" id="editTo" name="editTo">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="editSession">Session</label>
                <select class="form-select" id="editSession" name="editSession" multiple style="height: 200px;">
                  @foreach ($data['session'] as $ses)
                    <option value="{{ $ses->SessionID }}">{{ $ses->SessionName }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label" for="editLecturer">Lecturer</label>
                <select class="form-select" id="editLecturer" name="editLecturer" multiple style="height: 200px;">
                  @foreach ($data['lecturer'] as $lct)
                    <option value="{{ $lct->ic }}">{{ $lct->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateRecord()">Update</button>
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

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script type="text/javascript">
    var currentEditId = null;

    function submit()
    {
        var formData = new FormData();

        getInput = {
            from : $('#from').val(),
            to : $('#to').val(),
            session : $('#session').val(),
            lecturer : $('#lecturer').val()
        };

        // Simple form validation
        if (!getInput.from || !getInput.to || !getInput.session.length || !getInput.lecturer.length) {
            alert("Please fill in all fields before submitting.");
            return;
        }

        formData.append('submitData', JSON.stringify(getInput))

        return $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('AR/student/assessmentFilter/submit') }}",
                  method   : 'POST',
                  cache : false,
                  processData: false,
                  contentType: false,
                  data 	 : formData,
                  error: function(xhr, status, error) {
                      let errorMessage = xhr.status + ': ' + xhr.statusText;
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      }
                      alert('Error - ' + errorMessage);
                  },
                  success  : function(data){
                      if(data.error) {
                          alert(data.error);
                      } else {
                          alert(data.success);
                          clearForm();
                          location.reload(); // Reload page to show new data
                      }
                  }
              });
    }

    function clearForm() {
        $('#from').val('');
        $('#to').val('');
        $('#session').val([]).trigger('change');
        $('#lecturer').val([]).trigger('change');
    }

    function editRecord(id) {
        currentEditId = id;
        
        // Get record data
        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('AR/student/assessmentFilter/get') }}/" + id,
            method: 'GET',
            success: function(data) {
                if (data) {
                    $('#editId').val(data.id);
                    $('#editFrom').val(data.Start);
                    $('#editTo').val(data.End);
                    
                    // Set selected sessions
                    $('#editSession').val(data.session);
                    
                    // Set selected lecturers
                    $('#editLecturer').val(data.user_ic);
                    
                    $('#editModal').modal('show');
                } else {
                    alert('Record not found');
                }
            },
            error: function() {
                alert('Error loading record data');
            }
        });
    }

    function updateRecord() {
        var formData = new FormData();
        
        var getInput = {
            from : $('#editFrom').val(),
            to : $('#editTo').val(),
            session : $('#editSession').val(),
            lecturer : $('#editLecturer').val()
        };

        // Simple form validation
        if (!getInput.from || !getInput.to || !getInput.session.length || !getInput.lecturer.length) {
            alert("Please fill in all fields before updating.");
            return;
        }

        formData.append('submitData', JSON.stringify(getInput));

        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('AR/student/assessmentFilter/edit') }}/" + currentEditId,
            method: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function(data) {
                if(data.error) {
                    alert(data.error);
                } else {
                    alert(data.success);
                    $('#editModal').modal('hide');
                    location.reload(); // Reload page to show updated data
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert('Error - ' + errorMessage);
            }
        });
    }

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('AR/student/assessmentFilter/delete') }}/" + id,
                method: 'DELETE',
                success: function(data) {
                    if(data.error) {
                        alert(data.error);
                    } else {
                        alert(data.success);
                        location.reload(); // Reload page to show updated data
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert('Error - ' + errorMessage);
                }
            });
        }
    }

    // Initialize DataTable for the assessment records
    $(document).ready(function() {
        $('#assessmentTable').DataTable({
            dom: 'lBfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            order: [[5, 'desc']] // Sort by created date descending
        });
    });
  </script>
@endsection
