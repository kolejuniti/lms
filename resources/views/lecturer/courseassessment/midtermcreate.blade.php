@extends('layouts.lecturer.lecturer')

@section('main')



<style>


#fb-rendered-form {
    clear:both;
    display:none;
    button{
        float:right;
    }
}

.btn.btn-default.get-data{
    display:none;
}

.cb-wrap {

}
.form-wrap.form-builder .frmb-control li{
    font-family: Arial, Helvetica, sans-serif !important;
    font-weight: Bold !important;
}

div.form-actions.btn-group > button{
    font-size:1.2em !important;
    border-radius:0.5em !important;
    padding:0.5em !important;
    min-width:100px;
    margin: 0.5em;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">
                        {{ empty($data['midterm']->title) ? "Create Midterm" : $data['midterm']->title }}
                    </h4>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Academics</li>
                                <li class="breadcrumb-item" aria-current="page">Groups</li>
                                <li class="breadcrumb-item" aria-current="page">Group List</li>
                                <li class="breadcrumb-item" aria-current="page">Group Content</li>
                                
                                    @if(empty($data['midterm']->title))
                                        <li class="breadcrumb-item active" aria-current="page">Create Midterm</li>
                                    @else
                                        <li class="breadcrumb-item active" aria-current="page">Midterm</li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $data['midterm']->title }}</li>
                                    @endif
                                </li>
                              
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-xl-12 col-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="header-setting row">
                                <div class="row col-md-12">
                                    <div class="col-md-2 mb-4">
                                        <label for="midterm-title" class="form-label "><strong>Midterm Title</strong></label>
                                        <input type="text" oninput="this.value = this.value.toUpperCase()"  id="midterm-title" class="form-control"
                                            value="{{ empty($data['midterm']->title) ? "" : $data['midterm']->title }}">
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <label for="from" class="form-label "><strong>Midterm Duration (From)</strong></label>
                                        <input type="datetime-local" oninput="this.value = this.value.toUpperCase()"  id="from" class="form-control"
                                            value={{ empty($data['midterm']->duration) ? 30 : $data['midterm']->duration }}>
                                    </div>
                                    <div class="col-md-2 mb-4" id="time-to" hidden>
                                        <label for="to" class="form-label "><strong>Midterm Duration (To)</strong></label>
                                        <input type="datetime-local" oninput="this.value = this.value.toUpperCase()"  id="to" class="form-control"
                                            value={{ empty($data['midterm']->duration) ? 30 : $data['midterm']->duration }}>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <label for="midterm-duration" class="form-label "><strong>Midterm Duration (minutes)</strong></label>
                                        <input readonly type="number" oninput="this.value = this.value.toUpperCase()"  id="midterm-duration" class="form-control"
                                            value="">
                                    </div>
                                    <div class="col-md-2 mb-4" hidden>
                                        <label for="question-index" class="form-label "><strong>Question Index</strong></label>
                                        <input id="question-index" type="number" class="form-control"
                                            value={{ empty($data['midterm']->questionindex) ? 1 : $data['midterm']->questionindex }}>
                                    </div>
                                    <!--<div class="col-md-2 mb-4">
                                        <label for="date" class="form-label "><strong>Date</strong></label>
                                        <input type="date" id="date" class="form-control"
                                            value="">
                                    </div>-->
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group">
                                          <label class="form-label" for="folder">Lecturer Folder</label>
                                          <select class="form-select" id="folder" name="folder" required>
                                              <option value="" disabled selected>-</option>
                                              @foreach ($folder as $fold)
                                              <option value="{{ $fold->DrID }}">{{ $fold->DrName }}</option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger">@error('folder')
                                            {{ $message }}
                                          @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <label for="total-marks" class="form-label "><strong>Total Marks</strong></label>
                                        <input type="number" id="total-marks" class="form-control"
                                            value="">
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group" >
                                            <label class="form-label">Group List</label>
                                            <div class="table-responsive" style="width:99.7%">
                                                <table id="table_registerstudent" class="w-100 table text-fade table-bordered table-hover display nowrap margin-top-10 w-p100">
                                                    <thead class="thead-themed">
                                                    <th>Name</th>
                                                    <th>Course</th>
                                                    <th></th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($group as $grp)
                                                        <tr>
                                                            <td >
                                                                <label>{{$grp->group_name}}</label>
                                                            </td>
                                                            <td >
                                                                <label>{{$grp->course_name}}</label>
                                                            </td>
                                                            <td >
                                                                <div class="pull-right" >
                                                                    <input type="checkbox" id="chapter_checkbox_{{$grp->group_name}}"
                                                                        class="filled-in" name="group[]" value="{{$grp->id}}|{{$grp->group_name}}" >
                                                                    <label for="chapter_checkbox_{{$grp->group_name}}"> </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group" >
                                            <label class="form-label">Chapter List</label>
                                            <div class="container mt-1" id="chapterlist">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="form-div" class="hide-published-element col-md-12 mb-3">
                                    <div class="addFieldWrap pull-left">
                                        <a id="appendfield3" class="appendfield3 waves-effect waves-light btn btn-app btn-danger-light " data-label="Question" type="button">
                                            <i class="fa fa-plus"></i> <b>Header</b></a>
                                        <a id="appendfield1" class="appendfield1 waves-effect waves-light btn btn-app btn-info-light " data-label="Question" type="button">
                                            <i class="fa fa-plus"></i> <b>Radio Question</b></a>
                                        <a id="appendfield2" class="appendfield2 waves-effect waves-light btn btn-app btn-warning-light " data-label="Question" type="button">
                                            <i class="fa fa-plus"></i> <b>Checkbox Question</b></a>
                                        <a id="appendfield2" class="appendfield4 waves-effect waves-light btn btn-app btn-success-light " data-label="Question" type="button">
                                            <i class="fa fa-plus"></i> <b>Subjective Question</b></a>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div id="fb-editor"></div>
                                    <div id="build-wrap"></div>
                                </div>
                                
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-12">
                    <div class=" d-flex justify-content-center">
                        <div id="fb-rendered-form" class="card" style="width:800px">
                            <div class="card-body">
                                <form action="#" id="fb-render" class="mb-3"></form>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button onclick="history.back();" class="btn btn-secondary pull-left m-1"><i class="mdi mdi-keyboard-return"></i> Back</button>
                                    </div>
                                    <div class="col-md-9 hide-published-element">
                                        <button id="publish-midterm"  class="btn btn-info pull-right m-1"><i class="mdi mdi-publish"></i> Save & Publish</button>
                                        <button id="save-midterm"  class="btn btn-primary pull-right m-1"><i class="mdi mdi-content-save"></i> Save</button>
                                        <button class="btn btn-primary-light edit-form pull-right m-1"><i class="mdi mdi-edit"></i> Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
              
            </div>
        </section>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-builder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.4.2/form-render.min.js"></script>

<script>
var selected_from = '';
var selected_to = '';

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

    $('#midterm-duration').val(minutes);

}

</script>

<script>
var selected_folder = "";

$(document).on('change', '#folder', async function(e){
    selected_folder = $(e.target).val();

    await getChapters(selected_folder);
});

function getChapters(folder)
{

  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/midterm/getChapters') }}",
        method   : 'POST',
        data 	 : {folder: folder},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
            
            //$('#lecturer-selection-div').removeAttr('hidden');
            //$('#lecturer').selectpicker('refresh');
  
            //$('#chapter').removeAttr('hidden');
                $('#chapterlist').html(data);
                //$('#chapter').selectpicker('refresh');
        }
    });

}
</script>

<script>

var questionnum     = $('#question-index').val();
var selected_class  = "{{ Session::get('CourseID') }}";
var selected_midterm  = "{{ empty($data['midtermid']) ? '' : $data['midtermid'] }}";
var midterm            = {!! empty($data['midterm']) ? "''" : json_encode($data['midterm']) !!};
var midterm_status  = {!! empty($data['midtermstatus']) ? "''" : $data['midtermstatus'] !!};
var midtermFormData    = [];

jQuery(function($) {

    var $fbEditor = $(document.getElementById('fb-editor'));
    $formContainer = $(document.getElementById('fb-rendered-form'));

    if(Object.keys(midterm).length > 0){
        midtermFormData = midterm.content;
        midtermFormData = JSON.parse(midtermFormData).formData;
    }

    if(midterm_status == 2){
        Swal.fire({
			title: "midterm is already started!",
			text: "You are not allowed to edit anymore once published",
			confirmButtonText: "Ok"
		}).then(function(res){
            renderForm(midtermFormData);
            $('#fb-rendered-form').show();
            $('.header-setting *').attr('disabled', 'disabled');
            $('.hide-published-element').hide();
		});
    }else{
        
        fbOptions = {
            formData: midtermFormData,
            dataType: 'xml',
            onCloseFieldEdit: function(editPanel) { },
            onOpenFieldEdit: function(editPanel) {},
            onSave: function() {
                $fbEditor.toggle();
                $formContainer.toggle();
                $('#form-div').hide();
                $('.addFieldWrap').hide();
                $('#fb-render').formRender({formData: formBuilder.formData});
            }
        },

        formBuilder = $fbEditor.formBuilder(fbOptions);
    }

    var buttons = document.getElementsByClassName('appendfield1');
    for (var i = 0; i < buttons.length; i++) {
        buttons[i].onclick = function() {
            var field = {
                type: 'header',
                className: 'mt-4',
                label: this.dataset.label + ' '+ questionnum++
            };
            var index = this.dataset.index ? Number(this.dataset.index) : undefined;

            //alert(i);

            formBuilder.actions.addField(field, index);

            field = {
                type: 'paragraph',
                className: '',
                label: 'Sample of the question paragraph...'
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'radio-group',
                className: 'with-gap radio-col-primary',
                label: '<label class="mt-2 text-primary"><strong>Your Answer</strong></label>',
                name: 'radio-question'+ i++,
                values: [
                    {
                        "label": "a) ",
                        "value": "a",
                        "selected": true,
                    },
                    {
                        "label": "b)",
                        "value": "b",
                        "selected": false
                    },
                    {
                        "label": "c)",
                        "value": "c",
                        "selected": false
                    },
                    {
                        "label": "d)",
                        "value": "d",
                        "selected": false
                    },
                ]
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'paragraph',
                className: 'correct-answer',
                label: 'a'
            };

            formBuilder.actions.addField(field, index);

            field = {
                type: 'checkbox-group',
                className: 'collected-marks pull-right chk-col-danger',
                label: '',  
                values: [
                    {
                        "label": "1 mark",
                        "value": "1",
                        "selected": false
                    }
                ]
            };
            
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'text',
                className: 'feedback-text form-control',
                placeholder: 'Comment',
                label: '',
            };
            
            formBuilder.actions.addField(field, index);
            $('#question-index').val(questionnum);
        };
    }

    var buttons2 = document.getElementsByClassName('appendfield2');
    for (var i = 0; i < buttons2.length; i++) {
        buttons2[i].onclick = function() {
            var field = {
                type: 'header',
                className: 'mt-4',
                label: this.dataset.label + ' '+ questionnum++
            };
            var index = this.dataset.index ? Number(this.dataset.index) : undefined;

            //alert(i);

            formBuilder.actions.addField(field, index);

            field = {
                type: 'paragraph',
                className: '',
                label: 'Sample of the question paragraph...'
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'checkbox-group',
                className: 'filled-in chk-col-warning',
                label: '<label class="mt-2 text-primary"><strong>Your Answer</strong></label>',
                name: 'checkbox-question'+ i++,
                values: [
                    {
                        "label": "a)",
                        "value": "a",
                        "selected": true
                    },
                    {
                        "label": "b)",
                        "value": "b",
                        "selected": false
                    },
                    {
                        "label": "c)",
                        "value": "c",
                        "selected": false
                    },
                    {
                        "label": "d)",
                        "value": "d",
                        "selected": false
                    },
                ]
            };

            formBuilder.actions.addField(field, index);

            field = {
                type: 'paragraph',
                className: 'correct-answer',
                label: 'a'
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'checkbox-group',
                className: 'collected-marks pull-right chk-col-danger',
                label: '',  
                values: [
                    {
                        "label": "1 mark",
                        "value": "1",
                        "selected": false
                    }
                ]
            };
            
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'text',
                className: 'feedback-text form-control',
                placeholder: 'Comment',
                label: '',
            };
            
            formBuilder.actions.addField(field, index);
            $('#question-index').val(questionnum);
        };
    }

    var buttons3 = document.getElementsByClassName('appendfield3');
    for (var i = 0; i < buttons3.length; i++) {
        buttons3[i].onclick = function() {
            var field = {
                type: 'header',
                className: 'd-flex justify-content-center bg-primary p-2',
                label: 'Header'
            };
            var index = this.dataset.index ? Number(this.dataset.index) : undefined;

            formBuilder.actions.addField(field, index);
            $('#question-index').val(questionnum);
        };
    }

    var buttons4 = document.getElementsByClassName('appendfield4');
    for (var i = 0; i < buttons4.length; i++) {
        buttons4[i].onclick = function() {
            var field = {
                type: 'header',
                className: 'mt-4',
                label: this.dataset.label + ' '+ questionnum++
            };
            var index = this.dataset.index ? Number(this.dataset.index) : undefined;

            //alert(i)

            formBuilder.actions.addField(field, index);

            field = {
                type: 'paragraph',
                className: '',
                label: 'Sample of the question paragraph...'
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'text',
                className: 'form-control',
                placeholder: 'Your Answer',
                label: '',
                name: 'subjective-text'+ i++,
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'checkbox-group',
                className: 'collected-marks pull-right chk-col-danger',
                label: '',  
                values: [
                    {
                        "label": "1 mark",
                        "value": "1",
                        "selected": false
                    }
                ]
            };
            
            formBuilder.actions.addField(field, index);

            field = {
                type: 'text',
                className: 'feedback-text form-control',
                placeholder: 'Comment',
                label: '',
            };
            
            formBuilder.actions.addField(field, index);
            $('#question-index').val(questionnum);  
        };
    }

    /* On Clicks */
    $('.edit-form', $formContainer).click(function() {
        $fbEditor.toggle();
        $formContainer.toggle();
    });

    document.getElementById('publish-midterm').addEventListener('click', function() {
    
        Swal.fire({
			title: "Are you sure?",
			text: "Student will be able to start the midterm and you are not allow to edit anymore",
			showCancelButton: true,
			confirmButtonText: "Confirm"
		}).then(function(res){
			if (res.isConfirmed){
                saveForm(2);
			}
		});
  
    }, false);

    document.getElementById('save-midterm').addEventListener('click', function() {
        saveForm();
    }, false);
    
    $(document).on('click', '.edit-form', function(e){
        $('#form-div').show();
        $('.addFieldWrap').show();
    });

    /* On Keyups */
    $(document).on('keyup', '#question-index', function(e){
        questionnum  = $('#question-index').val();
    });

    function saveForm(status = 1){
        $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url: "{{ url('lecturer/midterm/insert') }}",
            type: 'POST',
            data:  { 
                class: selected_class, 
                midterm: selected_midterm,
                title: $("#midterm-title").val(),
                duration: $("#midterm-duration").val(),
                questionindex: $("#question-index").val(),
                from: $("#from").val(),
                to: $("#to").val(),
                marks: $("#total-marks").val(),
                group: $('input[name="group[]"]:checked').map(function(){ 
                    return this.value; 
                }).get(),
                chapter: $('input[name="chapter[]"]:checked').map(function(){ 
                    return this.value; 
                }).get(),
                status: status,
                data:window.JSON.stringify({formData: $fbEditor.formRender("userData") })
            },
            error:function(err){
                console.log(err);
            },
            success:function(res){
                location.href= "/lecturer/midterm/{{ Session::get('CourseIDS') }}";
            }
        });
    }
});

$('.btn.btn-primary.save-template.save-template').html("Done");




function renderForm(formdata){
    jQuery(function($) {
        const fbRender = document.getElementById("fb-render");
        const originalFormData = formdata;

        var formRenderOptions = {
            datatype: 'json',
            formData: JSON.stringify(originalFormData)
        };

        $(fbRender).formRender(formRenderOptions );
    });
}
</script>




@stop
