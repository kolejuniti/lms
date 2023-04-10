<form action="/finance/claim/create?idS={{ $claims->id }}" method="post" role="form" enctype="multipart/form-data">
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
            <label>Claim Code</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $claims->code }}">
          </div>
        </div>
        <div>
          <div class="form-group">
            <label>Claim Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $claims->name }}">
          </div>
        </div>
        <div>
          <div class="form-group">
              <label class="form-label" for="group">Group</label>
              <select class="form-select" id="group" name="group">
                <option value="-" selected disabled>-</option>
                  <option value="1" {{ ($claims->groupid == '1') ? 'selected' : '' }}>YURAN</option> 
                  <option value="4" {{ ($claims->groupid == '4') ? 'selected' : '' }}>DENDE / SAMAN</option> 
                  <option value="5" {{ ($claims->groupid == '5') ? 'selected' : '' }}>LAIN - LAIN</option>
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

<script>
  function closeModal()
  {
    
    $('#uploadModal2').modal('hide');

  }
</script>