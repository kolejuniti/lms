<label class="form-label">Group Weekly Schedule *</label>
@php 
    $classat = array("monday", 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
@endphp
<div class="container mt-1">
@foreach($classat as $key => $day)
    <div class="row mb-2">
        <div class="col-md-3 align-self-center">
            <div class="align-middle">
                <input type="checkbox" id="basic_checkbox_{{$day}}"
                    class="filled-in" name="classday" value="{{$day}}" {{ (isset($schedule)) ? ($schedule[$key] ->classstatusid != 0) ? 'checked' : '' : 'checked'}}
                >
                <label for="basic_checkbox_{{$day}}">{{ ucwords($day) }}</label>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                    <div>
                        <div class="input-group">
                            <input id="{{ $day }}-start-classtime" value="{{  (isset($schedule)) ? ($schedule[$key] ->classstatusid != 0) ? $schedule[$key]->classstarttime : '00:00 AM' : '00:00'}}" class="form-control" type="time">
                        </div>
                    </div>
                </div>
                <div class="col-md-1 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                <div class="col-md-4">
                    <div>
                        <div class="input-group">
                            <input id="{{ $day }}-end-classtime" value="{{ (isset($schedule)) ? ($schedule[$key] ->classstatusid != 0) ? $schedule[$key]->classendtime : '00:00 AM' : '00:00' }}" class="form-control" type="time">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 align-self-center">
                    <div class=" align-middle">
                        <label id="{{ $day }}-duration" class="badge badge-success"></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>


<script src="{{ asset('assets/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_plugins/iCheck/icheck.min.js') }}"></script>
