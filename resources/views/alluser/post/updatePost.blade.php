<form action="/posting/staff/create?idS={{ $data['post']->id }}" method="post" role="form" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="modal-header">
      <div class="">
        <button type="button" onclick="closeModal()" class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
            &times;
        </button>
      </div>
    </div>
    <div class="modal-body">
      <div class="row col-md-12">
        <div>
          <div class="form-group">
            <label>Date Posting</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $data['post']->post_date }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="channel">Channel</label>
              <select class="form-select" id="channel" name="channel" required>
              <option value="-" selected disabled>-</option>
                <option value="facebook" {{ ($data['post']->channel == 'facebook') ? 'selected' : '' }}>Facebook</option> 
                <option value="instagram" {{ ($data['post']->channel == 'instagram') ? 'selected' : '' }}>Instagram</option> 
                <option value="twitter" {{ ($data['post']->channel == 'twitter') ? 'selected' : '' }}>Twitter</option>
                <option value="tiktok" {{ ($data['post']->channel == 'tiktok') ? 'selected' : '' }}>Tiktok</option>
                <option value="youtube" {{ ($data['post']->channel == 'youtube') ? 'selected' : '' }}>Youtube</option>
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $data['post']->title }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Link</label>
            <input type="url" name="link" id="link" class="form-control" value="{{ $data['post']->link }}" required>
            <button type="button" id="fetchMetrics" class="btn btn-sm btn-info mt-2">Fetch Metrics</button>
            <span id="fetchStatus" class="ml-2"></span>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="type">Type</label>
              <select class="form-select" id="type" name="type" required>
              <option value="-" selected disabled>-</option>
                <option value="private" {{ ($data['post']->channel_type == 'private') ? 'selected' : '' }}>Private</option> 
                <option value="faculty" {{ ($data['post']->channel_type == 'faculty') ? 'selected' : '' }}>Faculty</option> 
                <option value="collage" {{ ($data['post']->channel_type == 'collage') ? 'selected' : '' }}>Collage</option>
                <option value="unit" {{ ($data['post']->channel_type == 'unit') ? 'selected' : '' }}>Unit</option>
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="status">Status</label>
              <select class="form-select" id="status" name="status" required>
              <option value="-" selected disabled>-</option>
                <option value="individual" {{ ($data['post']->status == 'individual') ? 'selected' : '' }}>Individual</option> 
                <option value="group" {{ ($data['post']->status == 'group') ? 'selected' : '' }}>Group</option>
              </select>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Total View</label>
            <input type="number" name="view" id="view" class="form-control" value="{{ $data['post']->total_view }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Total Comment</label>
            <input type="number" name="comment" id="comment" class="form-control" value="{{ $data['post']->total_comment }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Total Like</label>
            <input type="number" name="like" id="like" class="form-control" value="{{ $data['post']->total_like }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Total Share</label>
            <input type="number" name="share" id="share" class="form-control"  value="{{ $data['post']->total_share }}" required>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Total Save</label>
            <input type="number" name="save" id="save" class="form-control"  value="{{ $data['post']->total_save }}" required>
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

<script>
  function closeModal()
  {
    
    $('#uploadModal2').modal('hide');

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

        // Add event listener for the fetch metrics button
        $('#fetchMetrics').on('click', function() {
            var url = $('#link').val();
            var channel = $('#channel').val();
            
            if (!url || !channel || channel === '-') {
                $('#fetchStatus').html('<span class="text-danger">Please enter a valid URL and select a channel</span>');
                return;
            }

            $('#fetchStatus').html('<span class="text-info">Fetching data...</span>');
            
            // Make AJAX request to fetch metrics
            $.ajax({
                url: '/api/fetch-social-metrics',
                method: 'POST',
                data: {
                    url: url,
                    channel: channel,
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        // Update form fields with fetched data
                        $('#view').val(response.data.views || 0);
                        $('#comment').val(response.data.comments || 0);
                        $('#like').val(response.data.likes || 0);
                        $('#share').val(response.data.shares || 0);
                        $('#save').val(response.data.saves || 0);
                        
                        $('#fetchStatus').html('<span class="text-success">Data fetched successfully</span>');
                    } else {
                        $('#fetchStatus').html('<span class="text-danger">' + response.message + '</span>');
                    }
                },
                error: function(xhr) {
                    $('#fetchStatus').html('<span class="text-danger">Error fetching data: ' + (xhr.responseJSON?.message || 'Unknown error') + '</span>');
                }
            });
        });
    });
</script>