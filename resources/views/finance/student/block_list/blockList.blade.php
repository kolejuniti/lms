@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : (Auth::user()->usrtype == "COOP" ? 'layouts.coop' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "HEA" ? 'layouts.hea' : (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : ''))))))))))))

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Blocked list</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Blocked list</li>
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
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Student's Blocked List</h3>
        </div>
        {{-- <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="pull-right">
                  <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                      <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Claims
                  </a>
              </div>
            </div>
        </div>
        </div> --}}
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th>
                        Name
                    </th>
                    <th>
                        Ic
                    </th>
                    <th>
                        No. Matric
                    </th>
                    <th>
                        Program
                    </th>
                    <th>
                        Session
                    </th>
                    <th>
                        Semester
                    </th>
                    <th>
                        Campus
                    </th>
                    {{-- <th>
                    </th> --}}
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['block'] as $key=> $std)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <th>
                  {{ $std->name }}
                </td>
                <td>
                  {{ $std->ic }}
                </td>
                <td>
                  {{ $std->no_matric }}
                </td>
                <td>
                  {{ $std->progcode }}
                </td>
                <td>
                  {{ $std->SessionName }}
                </td>
                <td>
                  {{ $std->semester }}
                </td>
                <td>
                  @if($std->student_status == 1)
                    {{ $student_status[$key] = 'Holding' }}
                  @elseif($std->student_status == 2)
                    {{ $student_status[$key] = 'Kuliah' }}
                  @elseif($std->student_status == 4)
                    {{ $student_status[$key] = 'Latihan Industri' }}
                  @endif
                </td>
                {{-- <td class="project-actions text-right" style="text-align: center;">
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="updateClaim('{{ $clm->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteClaim('{{ $clm->id }}')">
                      <i class="ti-trash">
                      </i>
                      Delete
                  </a>
                </td> --}}
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        {{-- <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal">
                  <form action="/finance/claim/create" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <div class="">
                            <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                      <div class="row col-md-12">
                        <div>
                          <div class="form-group">
                            <label>Claim Code</label>
                            <input type="text" name="code" id="code" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Claim Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="group">Group</label>
                              <select class="form-select" id="group" name="group">
                              <option value="-" selected disabled>-</option>
                                <option value="1">YURAN</option> 
                                <option value="4">DENDE / SAMAN</option> 
                                <option value="5">LAIN - LAIN</option>
                              </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group pull-right">
                            <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                        </div>
                    </div>
                  </form>
              </div>
          </div>
        </div>
        <div id="uploadModal2" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal2">
              </div>
          </div>
        </div> --}}
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

  function deleteClaim(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('finance/claim/delete') }}",
                  method   : 'POST',
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
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script type="text/javascript">
    var selected_program = "";

    var url = window.location.href;


    $(document).on('change', '#program', async function(e){
      selected_program = $(e.target).val();

    await getClaim(selected_program);
    })

  function getClaim(program)
  {
    $('#myTable').DataTable().destroy();

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('AR/getClaim') }}",
            method   : 'POST',
            data 	 : {program: program},
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
        });
  }

  function updateClaim(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/claim/update') }}",
            method   : 'POST',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal2').html(data);
                $('#uploadModal2').modal('show');
            }
        });

  }
  

  </script>
@endsection
