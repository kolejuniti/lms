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
          <div class="table-responsive">
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
                      {{-- <th>
                      </th> --}}
                      <th>
                      </th>
                  </tr>
              </thead>
              <tbody id="table">
              @foreach ($data['post'] as $key => $pst)
                <tr>
                  <td style="width: 1%">
                    {{ $key+1 }}
                  </td>
                  <td>
                    {{ $pst->date }}
                  </td>
                  <td>
                    {{ $pst->post_date }}
                  </td>
                  <td>
                    {{ $pst->channel }}
                  </td>
                  <td>
                    {{ $pst->title }}
                  </td>
                  <td class="compact-cell">
                    <a href="{{ $pst->link }}" target="_blank" class="short-link">{{ $pst->link }}</a>
                  </td>
                  <td>
                    {{ $pst->channel_type }}
                  </td>
                  <td>
                    {{ $pst->status }}
                  </td>
                  <td>
                    <span id="view-{{ $pst->id }}">{{ $pst->total_view }}</span>
                  </td>
                  <td>
                    <span id="comment-{{ $pst->id }}">{{ $pst->total_comment }}</span>
                  </td>
                  <td>
                    <span id="like-{{ $pst->id }}">{{ $pst->total_like }}</span>
                  </td>
                  <td>
                    <span id="share-{{ $pst->id }}">{{ $pst->total_share }}</span>
                  </td>
                  <td>
                    <span id="save-{{ $pst->id }}">{{ $pst->total_save }}</span>
                  </td>
                  {{-- <td>
                    <button type="button" class="btn btn-sm btn-info refresh-metrics" 
                      data-id="{{ $pst->id }}" 
                      data-url="{{ $pst->link }}"
                      data-channel="{{ $pst->channel }}">
                      <i class="fa fa-refresh"></i> Live Metrics
                    </button>
                    <span id="status-{{ $pst->id }}"></span>
                  </td> --}}
                  <td class="project-actions text-right" style="text-align: center;">
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
                            <label>Total View / Reach</label>
                            <input type="number" name="view" id="view" class="form-control" value="0" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Comment</label>
                            <input type="number" name="comment" id="comment" class="form-control" value="0" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Like</label>
                            <input type="number" name="like" id="like" class="form-control" value="0" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Share</label>
                            <input type="number" name="share" id="share" class="form-control" value="0" required>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Total Save</label>
                            <input type="number" name="save" id="save" class="form-control" value="0" required>
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
    // Function to detect channel from URL
    $(document).ready(function() {
        $('#link').on('input', function() {
            var url = $(this).val();
            if(url) {
                var channel = '';
                
                // Extract domain from URL
                try {
                    var urlObj = new URL(url);
                    var domain = urlObj.hostname.toLowerCase();
                    
                    // Check which platform the URL belongs to
                    if(domain.includes('facebook.com') || domain.includes('fb.com')) {
                        channel = 'facebook';
                    } else if(domain.includes('instagram.com') || domain.includes('ig.com')) {
                        channel = 'instagram';
                    } else if(domain.includes('twitter.com') || domain.includes('x.com')) {
                        channel = 'twitter';
                    } else if(domain.includes('tiktok.com') || domain.includes('vm.tiktok.com')) {
                        channel = 'tiktok';
                    } else if(domain.includes('youtube.com') || domain.includes('youtu.be')) {
                        channel = 'youtube';
                    }
                    
                    // Set the channel dropdown value if detected
                    if(channel) {
                        $('#channel').val(channel);
                    }
                } catch(e) {
                    // Invalid URL, do nothing
                    console.log("Invalid URL format");
                }
            }
        });
    });
</script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
                    // Set the DOM structure: 'l' for length changing input, 'B' for buttons, 'f' for filtering input, 'r' for processing display, 't' for the table, 'i' for table info, 'p' for pagination control
                    dom: 'lBfrtip',
                    // Set 'paging' to false to disable pagination
                    paging: false,
                    scrollX: true,
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
                          
                      }
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

  <script>
$(document).ready(function() {
    // Listen for click events on refresh metrics buttons
    $('.refresh-metrics').on('click', function() {
        const button = $(this);
        const postId = button.data('id');
        const url = button.data('url');
        const channel = button.data('channel');
        
        if (!url || !channel) {
            $(`#status-${postId}`).html('<small class="text-danger">Missing URL or channel</small>');
            return;
        }
        
        button.attr('disabled', true);
        $(`#status-${postId}`).html('<small class="text-info">Fetching...</small>');
        
        // Make AJAX request to fetch the metrics
        $.ajax({
            url: '/api/fetch-social-metrics',
            method: 'POST',
            data: {
                url: url,
                channel: channel,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update the metrics in the table
                    $(`#view-${postId}`).text(response.data.views || 0);
                    $(`#comment-${postId}`).text(response.data.comments || 0);
                    $(`#like-${postId}`).text(response.data.likes || 0);
                    $(`#share-${postId}`).text(response.data.shares || 0);
                    $(`#save-${postId}`).text(response.data.saves || 0);
                    
                    // Add a visual indication that the data was updated
                    $(`#view-${postId}, #comment-${postId}, #like-${postId}, #share-${postId}, #save-${postId}`).addClass('text-success font-weight-bold');
                    setTimeout(function() {
                        $(`#view-${postId}, #comment-${postId}, #like-${postId}, #share-${postId}, #save-${postId}`).removeClass('text-success font-weight-bold');
                    }, 3000);
                    
                    $(`#status-${postId}`).html('<small class="text-success">Updated!</small>');
                    
                    // Also update the database with new metrics
                    updateDatabaseMetrics(postId, response.data);
                } else {
                    $(`#status-${postId}`).html(`<small class="text-danger">${response.message}</small>`);
                }
            },
            error: function(xhr) {
                $(`#status-${postId}`).html(`<small class="text-danger">Error: ${xhr.responseJSON?.message || 'Unknown error'}</small>`);
            },
            complete: function() {
                button.attr('disabled', false);
                // Clear status message after 5 seconds
                setTimeout(function() {
                    $(`#status-${postId}`).html('');
                }, 5000);
            }
        });
    });
    
    // Function to update the database with new metrics
    function updateDatabaseMetrics(postId, metricsData) {
        $.ajax({
            url: `/api/update-post-metrics/${postId}`,
            method: 'POST',
            data: {
                views: metricsData.views || 0,
                comments: metricsData.comments || 0,
                likes: metricsData.likes || 0,
                shares: metricsData.shares || 0,
                saves: metricsData.saves || 0,
                _token: '{{ csrf_token() }}'
            },
            error: function(xhr) {
                console.error('Failed to update database:', xhr.responseText);
            }
        });
    }
});
</script>
@endsection
