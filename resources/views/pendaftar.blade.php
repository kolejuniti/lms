@extends('layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Profile</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Extra</li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
          <h3 class="card-title">Assigned Student</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row mt-3 ">
            <div class="col-md-12 ml-3">
              <div class="form-group">
                  <label class="form-label" for="program">Program</label>
                  <select class="form-select" id="program" name="program">
                  <option value="-" selected disabled>-</option>
                    @foreach ($program as $prg)
                    <option value="{{ $prg->id }}">{{ $prg->progname}}</option> 
                    @endforeach
                  </select>
              </div>
            </div>        
          </div>
          <div class="row mt-3 " id="subses-card" hidden>
            <div class="col-md-6 ml-3">
              <div class="form-group">
                  <label class="form-label" for="subject">Subject</label>
                  <select class="form-select" id="subject" name="subject">
                  <option value="-" selected disabled>-</option>
                  </select>
              </div>
            </div>        
            <div class="col-md-6 mr-3">
              <div class="form-group">
                <label class="form-label" for="session">Session</label>
                <select class="form-select" id="session" name="session">
                  <option value="-" selected disabled>-</option>
                  @foreach ($session as $ses)
                  <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3 " id="group-card" hidden>
            <div class="col-md-6 ml-3">
              <div class="form-group">
                  <label class="form-label" for="group">Group</label>
                  <select class="form-select" id="group" name="group">
                  </select>
              </div>
            </div>        
          </div>
        </div>
        <div class="card-body p-0">
          <table id="complex_header" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 15%">
                        Name
                    </th>
                    <th style="width: 15%">
                        No. IC
                    </th>
                    <th style="width: 30%">
                        No. Matric
                    </th>
                    <th style="width: 10%">
                        Program
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($student as $key=> $stud)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <td style="width: 15%">
                  {{ $stud->name }}
                </td>
                <td style="width: 15%">
                  {{ $stud->ic }}
                </td>
                <td style="width: 30%">
                  {{ $stud->no_matric }}
                </td>
                <td>
                  {{ $stud->program }}
                </td>
                <td class="project-actions text-right" >
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $stud->ic }}')">
                      <i class="ti-trash">
                      </i>
                      Delete
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

<script type="text/javascript">

  function deleteMaterial(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/pendaftar/delete') }}",
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
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>

  <script type="text/javascript">
    var selected_program = "";
    var selected_subject = "";
    var selected_session = "";
    var selected_group = "";

    var url = window.location.href;

    var group = document.getElementById('group-card');

    $(document).on('change', '#program', async function(e){
      selected_program = $(e.target).val();

    await getStudent(selected_program);
    })

    $(document).on('change', '#subject', async function(e){
    selected_subject = $(e.target).val();

    
    await getStudent(selected_subject);
    await getGroupOptions(selected_subject);
    });

    $(document).on('change', '#session', async function(e){
    selected_session = $(e.target).val();

    group.hidden = false;

    
    await getStudent(selected_subject,selected_session);
    await getGroupOptions(selected_subject,selected_session);
    });

    $(document).on('change', '#group', async function(e){
    selected_group = $(e.target).val();
    
    await getStudent(selected_subject,selected_session,selected_group);
    });

  function getSubject(program)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/group/getSubject') }}",
            method   : 'POST',
            data 	 : {program: program},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                document.getElementById('subses-card').hidden = false;
                $('#subject').html(data);
                $('#subject').selectpicker('refresh');
            }
        });
  }

  function getGroupOptions(subject,session)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/group/getGroupOption') }}",
            method   : 'POST',
            data 	 : {subject: subject,session: session},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#group').html(data);
                $('#group').selectpicker('refresh');
            }
        });
  }

  function getStudent(program)
  {
    $('#complex_header').DataTable().destroy();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/group/getStudentTableIndex') }}",
            method   : 'POST',
            data 	 : {program: program},
            beforeSend:function(xhr){
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
                background:"rgba(255,255,255, 0.3)",
                imageResizeFactor : 1,    
                imageAnimation : "2000ms pulse" , 
                imageColor: "#019ff8",
                text : "Please wait...",
                textResizeFactor: 0.15,
                textColor: "#019ff8",
                textColor: "#019ff8"
              });
              $("#complex_header").LoadingOverlay("hide");
            },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#complex_header').removeAttr('hidden');
                $('#complex_header').html(data);
                
                $('#complex_header').DataTable();
                //window.location.reload();
            }
        });
  }
  </script>
@endsection
