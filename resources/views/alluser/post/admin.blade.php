@extends('layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Posting</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Posting</li>
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
          <h3 class="card-title">Staff Posting</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="staff">Student</label>
                  <select class="form-select" id="staff" name="staff">
                    <option value="-" selected disabled>-</option>
                  </select>
                </div>
            </div>
          </div>
          <div class="row mt-3 ">
            <div class="col-md-6 mr-3" id="faculty-card">
              <div class="form-group">
                <label class="form-label" for="faculty">Faculty</label>
                <select class="form-select" id="faculty" name="faculty">
                  <option value="-" selected disabled>-</option>
                  @foreach ($data['faculty'] as $fcl)
                  <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}</option> 
                  @endforeach
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
                    <th style="width: 10%">
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
            {{-- @foreach ($student as $key=> $stud)
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
                <td style="width: 10%">
                  {{ $stud->no_matric }}
                </td>
                <td>
                  {{ $stud->program }}
                </td>
                <td class="project-actions text-right" >
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="/pendaftar/edit/{{ $stud->ic }}">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="getProgram('{{ $stud->ic }}')">
                    <i class="ti-pencil-alt">
                    </i>
                    Program History
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteMaterial('{{ $stud->ic }}')">
                      <i class="ti-trash">
                      </i>
                      Delete
                  </a>
                </td>
              </tr>
            @endforeach --}}
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
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
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

{{-- @if(session('newStud'))
    <script>
      alert('Success! Student has been registered!')
      window.open('/pendaftar/surat_tawaran?ic={{ session("newStud") }}')
    </script>
@endif --}}

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
    var selected_session = "";
    var selected_year = "";
    var selected_semester = "";
    var selected_status = "";

    var url = window.location.href;

    $('#search').keyup(function(event){
        if (event.keyCode === 13) { // 13 is the code for the "Enter" key
            var searchTerm = $(this).val();
            getStaff(searchTerm);
        }
    });

    $('#staff').on('change', function(){
        var selectedStaf = $(this).val();
        getStafInfo(selectedStaf);
    });

    function getStaff(search)
    {

        return $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('/posting/admin/listStaff') }}",
                method   : 'POST',
                data 	 : {search: search},
                error:function(err){
                    alert("Error");
                    console.log(err);
                },
                success  : function(data){
                    $('#staff').html(data);
                    $('#staff').selectpicker('refresh');

                }
            });
        
    }

    function getStafInfo(staff)
    {
        return $.ajax({
                headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                url      : "{{ url('finance/report/statement/getstaff') }}",
                method   : 'POST',
                data 	 : {staff: staff},
                error:function(err){
                    alert("Error");
                    console.log(err);
                },
                success  : function(data){
                  var d = new Date();

                  var month = d.getMonth()+1;
                  var day = d.getDate();

                  var output = d.getFullYear() + '/' +
                      (month<10 ? '0' : '') + month + '/' +
                      (day<10 ? '0' : '') + day;


                    $('#form-student').html(data);
                
                    $('#complex_header').DataTable({
                      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                      
                      buttons: [
                        {
                            extend: 'excelHtml5',
                            messageTop: output,
                            title: 'Excel' + '-' + output,
                            text:'Export to excel'
                            //Columns to export
                            //exportOptions: {
                          //     columns: [0, 1, 2, 3,4,5,6]
                          // }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'PDF' + '-' + output,
                            text: 'Export to PDF'
                            //Columns to export
                            //exportOptions: {
                          //     columns: [0, 1, 2, 3, 4, 5, 6]
                          //  }
                        }
                      ],
                    });
                    //$('#student').selectpicker('refresh');

                    "use strict";
                    ClassicEditor
                    .create( document.querySelector( '#commenttxt' ),{ height: '25em' } )
                    .then(newEditor =>{editor = newEditor;})
                    .catch( error => { console.log( error );});
                }
            });
    }

    //var session = document.getElementById('session-card');

    $(document).on('change', '#year', function(e){
    selected_year = $(e.target).val();

      getStudent(selected_program,selected_session,selected_year,selected_semester,selected_status);

    });

    $(document).on('change', '#program', function(e){
      selected_program = $(e.target).val();
      // session.hidden = false;
      // document.getElementById('semester-card').hidden = false;
      
      getStudent(selected_program,selected_session,selected_year,selected_semester,selected_status);

    })

    $(document).on('change', '#session', function(e){
    selected_session = $(e.target).val();

      getStudent(selected_program,selected_session,selected_year,selected_semester,selected_status);

    });

    $(document).on('change', '#semester', function(e){
    selected_semester = $(e.target).val();

      getStudent(selected_program,selected_session,selected_year,selected_semester,selected_status);

    });

    $(document).on('change', '#status', async function(e){
    selected_status = $(e.target).val();

      await getStudent(selected_program,selected_session,selected_year,selected_semester,selected_status);

    });

  function getStudent(program,session,year,semester,status)
  {

    $('#complex_header').DataTable().destroy();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/group/getStudentTableIndex') }}",
            method   : 'POST',
            data 	 : {program: program,session: session,year: year,semester: semester,status: status},
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


  function getProgram(ic)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/getProgram') }}",
            method   : 'POST',
            data 	 : {ic: ic},
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
  </script>
@endsection