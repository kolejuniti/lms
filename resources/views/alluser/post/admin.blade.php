@extends('layouts.admin')

@section('main')

<style>
  .short-link {
  display: inline-block;
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.compact-cell {
  max-width: 200px;
}

</style>

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
                  <label class="form-label" for="staff">Staff</label>
                  <select class="form-select" id="staff" name="staff">
                    <option value="-" selected disabled>-</option>
                  </select>
                </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6 mr-3" id="faculty-card">
              <div class="form-group">
                <label class="form-label" for="faculty">Faculty</label>
                <select class="form-select" id="faculty" name="faculty">
                  <option value="-" selected disabled>-</option>
                  <option value="all">ALL FACULTY</option>
                  @foreach ($data['faculty'] as $fcl)
                  <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}</option> 
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="from">FROM POSTING</label>
                <input type="date" class="form-control" id="from" name="from">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">TO POSTING</label>
                <input type="date" class="form-control" id="to" name="to">
                </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="from">FROM KEY-IN</label>
                <input type="date" class="form-control" id="from2" name="from2">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label class="form-label" for="name">TO KEY-IN</label>
                <input type="date" class="form-control" id="to2" name="to2">
                </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table id="complex_header" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                  <th style="width: 1%">
                    No.
                  </th>
                  <th>
                      Date
                  </th>
                  <th>
                      Channel
                  </th>
                  <th>
                      Title
                  </th>
                  <th style="width: 10%">
                      Link
                  </th>
                  <th>
                      Type
                  </th>
                  <th>
                      Status
                  </th>
                  <th>
                      Total View / Reach
                  </th>
                  <th>
                      Total Comment
                  </th>
                  <th>
                      Total Like
                  </th>
                  <th>
                      Total Share
                  </th>
                  <th>
                      Total Save / Bookmark
                  </th>
                  <th>
                      Latest Update
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
   var selected_staff = "";
    var selected_faculty = "";
    var selected_from = "";
    var selected_to = "";
    var selected_from2 = "";
    var selected_to2 = "";

    var url = window.location.href;

    $('#search').keyup(function(event){
        if (event.keyCode === 13) { // 13 is the code for the "Enter" key
            var searchTerm = $(this).val();
            getStaff(searchTerm);
        }
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

    $('#staff').on('change', function(){
        selected_staff = $(this).val();
        getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);
    });

    $(document).on('change', '#faculty', function(e){
      selected_faculty = $(e.target).val();

      getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);

    });

    $(document).on('change', '#from', function(e){
      selected_from = $(e.target).val();
      // session.hidden = false;
      // document.getElementById('semester-card').hidden = false;
      
      getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);

    })

    $(document).on('change', '#to', function(e){
      selected_to = $(e.target).val();

      getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);

    });

    $(document).on('change', '#from2', function(e){
      selected_from = $(e.target).val();
      // session.hidden = false;
      // document.getElementById('semester-card').hidden = false;
      
      getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);

    })

    $(document).on('change', '#to2', function(e){
      selected_to = $(e.target).val();

      getStaffPost(selected_staff,selected_faculty,selected_from,selected_to,selected_from2,selected_to2);

    });


  function getStaffPost(staff,faculty,from,to,from2,to2)
  {

    $('#complex_header').DataTable().destroy();

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('posting/admin/getStaffPost') }}",
            method   : 'POST',
            data 	 : {staff: staff,faculty: faculty,from: from,to: to,from2: from2,to2: to2},
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
      
                $(document).ready(function() {
                // Initialize DataTable for the table
                $('#complex_header').DataTable({
                    // Set the DOM structure: 'l' for length changing input, 'B' for buttons, 'f' for filtering input, 'r' for processing display, 't' for the table, 'i' for table info, 'p' for pagination control
                    dom: 'lBfrtip',
                    // Set 'paging' to false to disable pagination
                    paging: false,
                    // Define buttons to add to the table
                    buttons: [
                        // Copy button with footer enabled
                        { extend: 'copyHtml5', footer: true },
                        // Excel export button with footer enabled
                        { extend: 'excelHtml5', footer: true },
                        // CSV export button with footer enabled
                        { extend: 'csvHtml5', footer: true },
                        // PDF export button with custom settings
                        {
                          extend: 'pdfHtml5',
                          // Set page orientation to landscape
                          orientation: 'landscape',
                          // Set page size to A2
                          pageSize: 'A2',
                          customize: function(doc) {
                            // Get all the link elements in the table
                            var links = $(doc.content).find('table td a');
                            
                            // Shorten the link text
                            links.each(function() {
                              var link = $(this);
                              var href = link.attr('href');
                              var shortenedLink = shortenLink(href); // Replace with your own function to shorten the link
                              link.text(shortenedLink);
                            });
                          }
                          
                      }
                    ],
                });
            });
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
