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
            <label>Date</label>
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