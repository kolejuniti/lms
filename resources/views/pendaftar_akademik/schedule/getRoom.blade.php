<form action="/AR/schedule/room/create?idS={{ $data['room']->id }}" method="post" role="form" enctype="multipart/form-data">
  @csrf
  @method('POST')
  <div class="modal-header">
  </div>
  <div class="modal-body">
    <div class="row col-md-12">
      <div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data['room']->name) }}">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Start Time</label>
          <input type="time" name="start" id="start" class="form-control" value="{{ old('start', $data['room']->start) }}">
          @error('start')
              <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>End Time</label>
          <input type="time" name="end" id="end" class="form-control" value="{{ old('end', $data['room']->end) }}">
          @error('end')
              <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Capacity</label>
          <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity', $data['room']->capacity) }}">
          @error('capacity')
              <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Total Hour Per Day</label>
          <input type="number" name="t_hour" id="t_hour" class="form-control" value="{{ old('t_hour', $data['room']->total_hour) }}">
          @error('t_hour')
              <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Projector</label>
          <input type="number" name="projector" id="projector" class="form-control" value="{{ old('projector', $data['room']->projector) }}">
          @error('projector')
              <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>
      </div>
      <div>
        <div class="form-group">
            <label class="form-label" for="weekend">Weekend</label>
            <select class="form-select" id="weekend" name="weekend">
            <option value="" selected disabled>-</option>
              <option value="0" {{ $data['room']->weekend == 0 ? 'selected' : '' }}>No</option> 
              <option value="1" {{ $data['room']->weekend == 1 ? 'selected' : '' }}>Yes</option> 
            </select>
        </div>
      </div>
      <div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="desc" id="desc" class="form-control" rows="4" cols="50">{{ old('desc', $data['room']->description) }}</textarea>
          @error('desc')
              <span class="text-danger">{{ $message }}</span>
          @enderror
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