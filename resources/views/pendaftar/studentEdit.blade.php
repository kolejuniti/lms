@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : (Auth::user()->usrtype == "COOP" ? 'layouts.coop' : (Auth::user()->usrtype == "PL" || Auth::user()->usrtype == "AO" || Auth::user()->usrtype == "LCT" ? 'layouts.ketua_program' : '')))))))))

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
          <h3 class="card-title">Edit Student</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row mt-3 ">
            <div class="row col-md-12">
              <div class="col-md-3">
                <div class="form-group">
                  <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                  <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                </div>
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

  <script type="text/javascript">
  $('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
  });

  function getStudent(search)
  {

    $('#complex_header').DataTable().destroy();

    var edit = true;

    return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('pendaftar/group/getStudentTableIndex2') }}",
        method   : 'POST',
        data 	 : {search: search},
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
