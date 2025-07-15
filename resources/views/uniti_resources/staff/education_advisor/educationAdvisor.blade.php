@extends('layouts.ur')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Education Advisor</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Education Advisor</li>
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
          <h3 class="card-title">Assigned Education Advisor</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="name">Name</label>
                  <input type="text" class="form-control" id="name" name="name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="ic">IC</label>
                  <input type="text" class="form-control" id="ic" name="ic">
                </div>
              </div>
                <div class="pull-right">
                    <a type="button" class="waves-effect waves-light btn btn-info btn-sm" onclick="submit()" id="submitBtn">
                        <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp <span id="submitText">Submit</span>
                    </a>
                    <a type="button" class="waves-effect waves-light btn btn-secondary btn-sm" onclick="cancelEdit()" id="cancelBtn" style="display:none;">
                        <i class="fa fa-times"></i> &nbsp Cancel
                    </a>
                </div>
                <!-- Hidden field to store the ID during edit mode -->
                <input type="hidden" id="editId" value="">
            </div>
        </div>
        </div>
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th>
                        No.
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Ic
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="table">
              @foreach($data['ea'] as $i => $ea)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>
                  {{ $ea->name }}
                </td>
                <td>
                  {{ $ea->ic }}
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                  <a class="btn btn-warning btn-sm" href="#" onclick="editMaterial('{{ $ea->id }}', '{{ $ea->name }}', '{{ $ea->ic }}')">
                    <i class="ti-pencil-alt"></i> Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $ea->id }}')">
                    <i class="ti-trash"></i> Delete
                  </a>
                </td>
              </tr>
              @endforeach
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

  function submit()
  {
    name = $('#name').val();
    ic = $('#ic').val();
    editId = $('#editId').val();

    // Validation
    if (!name || !ic) {
        alert("Please fill in all fields");
        return;
    }

    var url, method, data;
    
    if (editId) {
        // Update mode
        url = "{{ url('ur/educationAdvisor/update') }}";
        method = 'PUT';
        data = {id: editId, name: name, ic: ic};
    } else {
        // Create mode
        url = "{{ url('ur/educationAdvisor/post') }}";
        method = 'POST';
        data = {name: name, ic: ic};
    }

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : url,
            method   : method,
            data 	 : data,
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
              resetForm();
              window.location.reload();
            }
        });
  }

  function editMaterial(id, name, ic)
  {
    // Populate form fields
    $('#name').val(name);
    $('#ic').val(ic);
    $('#editId').val(id);
    
    // Update UI for edit mode
    $('#submitText').text('Update');
    $('#submitBtn').removeClass('btn-info').addClass('btn-success');
    $('#submitBtn i:first').removeClass('fa-plus').addClass('fa-edit');
    $('#cancelBtn').show();
    
    // Add visual indicator that we're in edit mode
    $('.card-header h3').html('Edit Education Advisor <small class="text-muted">(Editing: ' + name + ')</small>');
    
    // Scroll to form
    $('html, body').animate({
        scrollTop: $('.card').offset().top - 20
    }, 500);
  }

  function cancelEdit()
  {
    resetForm();
  }

  function resetForm()
  {
    // Clear form fields
    $('#name').val('');
    $('#ic').val('');
    $('#editId').val('');
    
    // Reset UI to create mode
    $('#submitText').text('Submit');
    $('#submitBtn').removeClass('btn-success').addClass('btn-info');
    $('#submitBtn i:first').removeClass('fa-edit').addClass('fa-plus');
    $('#cancelBtn').hide();
    
    // Reset header
    $('.card-header h3').text('Assigned Education Advisor');
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
                  url      : "{{ url('/ur/educationAdvisor/delete') }}",
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
@endsection
