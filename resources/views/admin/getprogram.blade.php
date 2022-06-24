<option value='-' disabled selected>-</option>
@foreach ($program as $prg)
<option value="{{ $prg->id }}" {{ ($id == $prg->id) ? 'selected' : ''}}>
    {{ $prg->progname }}</option>
@endforeach

