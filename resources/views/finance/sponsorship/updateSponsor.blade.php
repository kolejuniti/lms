<form action="/finance/sponsorship/library/create?idS={{ $sponsor->id }}" method="post" role="form" enctype="multipart/form-data">
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
            <label>Sponsor Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $sponsor->code }}">
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Sponsor Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $sponsor->name }}">
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