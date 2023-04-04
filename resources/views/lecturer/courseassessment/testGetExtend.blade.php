<form action="/lecturer/test/updateExtend?id={{ $data['test']->id }}" method="post" role="form" enctype="multipart/form-data">
    @csrf
    @method('POST')
    {{-- <div class="modal-header">
        <div class="">
            <a class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                &times;
            </a>
        </div> --}}
    </div>
    <div class="modal-body">
      <div class="row col-md-12">
        <div>
          <div class="form-group">
            <label for="from" class="form-label "><strong>Test Duration (From)</strong></label>
            <input type="datetime-local" id="from" name="from" class="form-control" value={{ date('Y-m-d\TH:i:s', strtotime($data['test']->date_from)) }}>
          </div>
        </div>
        <div>
          <div class="form-group">
              <label for="to" class="form-label "><strong>Test Duration (To)</strong></label>
              <input type="datetime-local"  id="to" name="to" class="form-control" value={{ date('Y-m-d\TH:i:s', strtotime($data['test']->date_to)) }}>
          </div>
        </div>
        <div>
          <div class="form-group">
            <label for="test-duration" class="form-label "><strong>Test Duration (minutes)</strong></label>
            <input readonly type="number" oninput="this.value = this.value.toUpperCase()"  id="test-duration" name="duration" class="form-control" value="">
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
    var selected_from = '';
    var selected_to = '';

    $(document).ready(function(){
            //alert('true');

            $('#time-to').removeAttr('hidden');
        
            selected_from = $('#from').val();

            selected_to = $('#to').val();

            getDuration(selected_from,selected_to);

    });

    $(document).on('change', '#from', async function(e){

        $('#time-to').removeAttr('hidden');
        
        selected_from = $(e.target).val();

        if(selected_to != '')
        {
            await getDuration(selected_from,selected_to);
        }

    });

    $(document).on('change', '#to', async function(e){
        selected_to = $(e.target).val();

        await getDuration(selected_from,selected_to);
    });

    function getDuration(from,to)
    {
        var x = new Date(from);
        var y = new Date(to);
        var z =  y - x;

        var minutes = Math.floor(z / 60000);
        //alert(z)

        $('#test-duration').val(minutes);

    }
  </script>