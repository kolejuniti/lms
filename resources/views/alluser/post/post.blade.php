@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "LCT" || Auth::user()->usrtype == "PL" || Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : ''))))))))

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
          <h4 class="page-title">Postings</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Postings</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      @if(Session::has('success'))
          <div class="form-group">
              <div class="alert alert-success">
                  <span>{{ Session::get('success') }}</span>
              </div>
          </div>
      @elseif(Session::has('deleted'))
          <div class="form-group">
              <div class="alert alert-danger">
                  <span>{{ Session::get('deleted') }}</span>
              </div>
          </div>
      @endif

    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Lecturer's Post</h3>
        </div>
        <div class="card mb-3" id="stud_info">
          <div class="card-header">
          <b>Staff Info</b>
          </div>
          <div class="card-body">
              <div class="row mb-5">
                  <div class="col-md-6">
                      <div class="form-group">
                          <p>Staff Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['user']->name }}</p>
                      </div>
                      <div class="form-group">
                          <p>Position &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['user']->type }}</p>
                      </div>
                      <div class="form-group">
                          <p>Unit &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; </p>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <p>Faculty &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['user']->faculty }}</p>
                      </div>
                      <div class="form-group">
                          <p>No. Staf &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['user']->no_staf }}</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
              <div class="pull-right">
                  <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                      <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Post
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
                    <th>
                        Date Key-in
                    </th>
                    <th>
                        Date Posting
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
                        Total View
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
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['post'] as $key=> $pst)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <th>
                  {{ $pst->date }}
                </td>
                <th>
                  {{ $pst->post_date }}
                </td>
                <th>
                  {{ $pst->channel }}
                </td>
                <th>
                  {{ $pst->title }}
                </td>
                <th class="compact-cell">
                  <a href="{{ $pst->link }}" target="_blank" class="short-link">{{ $pst->link }}</a>
                </th>
                <th>
                  {{ $pst->channel_type }}
                </td>
                <th>
                  {{ $pst->status }}
                </td>
                <th>
                  {{ $pst->total_view }}
                </td>
                <th>
                  {{ $pst->total_comment }}
                </td>
                <th>
                  {{ $pst->total_like }}
                </td>
                <th>
                  {{ $pst->total_share }}
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                  {{-- <button class="btn btn-info btn-sm btn-sm mr-2" data-toggle="modal" data-target="#fbModal">
                      <i class="ti-layout-media-center">
                      </i>
                      View
                  </button>
                  --}}
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="updatePost('{{ $pst->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-danger btn-sm mt-2" href="#" onclick="deletePost('{{ $pst->id }}')">
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
        <!-- This is the modal -->
        <div class="modal fade" id="fbModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com/100015771307087/videos/2427899657380806/" width="500" height="157" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>              </div>
            </div>
          </div>
        </div>

        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal">
                  <form action="/posting/staff/create" method="post" role="form" enctype="multipart/form-data">
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
                            <label>Date Posting</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="channel">Channel</label>
                              <select class="form-select" id="channel" name="channel" required>
                              <option value="-" selected disabled>-</option>
                                <option value="facebook">Facebook</option> 
                                <option value="instagram">Instagram</option> 
                                <option value="twitter">Twitter</option>
                                <option value="tiktok">Tiktok</option>
                                <option value="youtube">Youtube</option>
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Link</label>
                            <input type="url" name="link" id="link" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="type">Type</label>
                              <select class="form-select" id="type" name="type" required>
                              <option value="-" selected disabled>-</option>
                                <option value="private">Private</option> 
                                <option value="faculty">Faculty</option> 
                                <option value="collage">Collage</option>
                                <option value="unit">Unit</option>
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="status">Status</label>
                              <select class="form-select" id="status" name="status" required>
                              <option value="-" selected disabled>-</option>
                                <option value="individual">Individual</option> 
                                <option value="group">Group</option>
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total View</label>
                            <input type="number" name="view" id="view" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Comment</label>
                            <input type="number" name="comment" id="comment" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Like</label>
                            <input type="number" name="like" id="like" class="form-control" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Share</label>
                            <input type="number" name="share" id="share" class="form-control" required>
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

  function deletePost(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('posting/staff/delete') }}",
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

  function updatePost(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('posting/staff/update') }}",
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
