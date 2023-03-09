<form action="/finance/claim/create?idS={{ $claims->id }}" method="post" role="form" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="modal-header">
       
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
                <option value="1" {{ ($claims->groupid == '1') ? 'selected' : '' }}>Group 1</option> 
                <option value="2" {{ ($claims->groupid == '2') ? 'selected' : '' }}>Group 2</option> 
                <option value="3" {{ ($claims->groupid == '3') ? 'selected' : '' }}>Group 3</option> 
                <option value="4" {{ ($claims->groupid == '4') ? 'selected' : '' }}>Group 4</option> 
                <option value="5" {{ ($claims->groupid == '5') ? 'selected' : '' }}>Group 5</option> 
                <option value="6" {{ ($claims->groupid == '6') ? 'selected' : '' }}>Group 6</option> 
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