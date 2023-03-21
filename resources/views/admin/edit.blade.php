@extends('../layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Registration</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Extra</li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container col-md-12">
        <form action="/admin/{{ $id->id }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="row">
            <div class="col-xl-4">
              <!-- Profile picture card-->
              <div class="card mb-4 mb-xl-0">
                <div class="custom-file" hidden >
                  <div class="controls">
                    <input type="file" class="form-control" name="image" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" aria-invalid="false"> 
                    <div class="help-block"></div>
                  </div>
                </div>
                <div class="pos-relative d-inline-block text-center" >
                  <label for="inputGroupFile01" class="mt-2 mb-2 text-muted">User Image</label>
                  <br><br>
                  <label for="inputGroupFile01"  style="cursor:pointer">
                    <img src="{{ ($id->image != null) ? Storage::disk('linode')->url($id->image) : asset('assets/images/1.jpg')}}" 
                    
                    height="300em" witdh="auto" 
                    id="userimage" class="bg-light w-200 h-200 rounded-circle avatar-lg img-thumbnail" alt="">
                  </label>
                  <br>
                  <label id="userimage_error" class="text-danger small error-field"></label>
                </div>
              </div>
            </div>
            <div class="col-xl-8">
              <!-- Account details card-->
              <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                  <!-- Form Group (username)-->
                  <div class="mb-3">
                    <label class="form-label" class="small mb-1" for="name">Full Name</label>
                    <input class="form-control" id="name" name="name" type="text" placeholder="Enter your username" value="{{ $id->name }}" required>
                  </div>
                  <div class="row gx-3 mb-3">
                    <!-- Form Group (first name)-->
                    <div class="col-md-3">
                      <label class="form-label" class="small mb-1" for="ic">IC</label>
                      <input class="form-control" id="ic" name="ic" type="text" placeholder="Enter your first name" value="{{ $id->ic }}" required>
                    </div>
                    <div class="col-md-3">
                      <label class="form-label" class="small mb-1" for="nostaf">Staff No</label>
                      <input class="form-control" id="nostaf" name="nostaf" type="text" placeholder="Enter your Staff No" value="{{ $id->no_staf }}" required>
                    </div>
                    <!-- Form Group (last name)-->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="usrtype">User Type</label>
                        <select class="form-select" id="usrtype" name="usrtype" selected="{{ $id->usrtype }}" required>
                          <option value="" disabled selected>Please choose type</option>
                          <option value="ADM" {{ ($id->usrtype == "ADM") ? 'selected' : '' }}>Admin</option>
                          <option value="PL" {{ ($id->usrtype == "PL") ? 'selected' : '' }}>Program Lead</option>
                          <option value="DN" {{ ($id->usrtype == "DN") ? 'selected' : '' }}>Deen</option>
                          <option value="AO" {{ ($id->usrtype == "AO") ? 'selected' : '' }}>Administrative Officer</option>
                          <option value="RGS" {{ ($id->usrtype == "RGS") ? 'selected' : '' }}>HEA</option>
                          <option value="AR" {{ ($id->usrtype == "AR") ? 'selected' : '' }}>Academic Registrar</option>
                          <option value="LCT" {{ ($id->usrtype == "LCT") ? 'selected' : '' }}>Lecturer</option>
                          <option value="FN" {{ ($id->usrtype == "FN") ? 'selected' : '' }}>Finance</option>
                          <option value="TS" {{ ($id->usrtype == "TS") ? 'selected' : '' }}>Treasurer</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <!-- Form Row-->
                  <div class="row gx-3 mb-3">
                    <!-- Form Group (first name)-->
                    <div class="col-md-6">
                      <label class="form-label" for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ $id->email }}" required>
                    </div>
                    <!-- Form Group (last name)-->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="faculty">Faculty</label>
                        <select class="form-select" id="faculty" name="faculty">
                          <option value="-" selected disabled>-</option>
                          @foreach ($faculty as $fcl)
                          <option value="{{ $fcl->id }}" {{ ($fcl->id == $id->faculty) ? 'selected' : '' }}>{{ $fcl->facultyname }}
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="from">Starting Date</label>
                        <input type="date" class="form-control" id="from" placeholder="" name="from" value="{{ $id->start  }}" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="to">Ending Date</label>
                        <input type="date" class="form-control" id="to" placeholder="Leave empty if not applied" value="{{ $id->end }}" name="to">
                      </div>
                    </div>
                    <div class="col-md-12" id="program-card" hidden>
                      <div class="form-group">
                        <label class="form-label" for="program">Program</label>
                        <div class="container mt-1" id="program">
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-label" for="academic">Academic Qualification</label>
                        <div class="container mt-1" id="academic">
                          <table class="table">
                            @foreach ($academics as $key => $ac)
                            @php
                              $ace = explode('|', $ac)
                            @endphp
                            <tr>
                              <td><input type="checkbox" id="{{ $ace[0] }}" name="academic[]" value="{{ $ace[0] }}" {{ ($academic[$key] != null) ? ($academic[$key]->academic_id == $ace[0]) ? 'checked' : '' : '' }}><label for="{{ $ace[0] }}" class="form-label">{{ $ace[1] }}</label></td>
                              <td id="tb_{{ $ace[0] }}" {{ ($academic[$key] != null) ? '' : 'hidden'}}><input type="text" class="form-control" id="prg_{{ $ace[0] }}" placeholder="Enter program name" name="prg[]" value="{{ ($academic[$key] != null) ? $academic[$key]->academic_name : ''}}"></td>
                              <td id="tb2_{{ $ace[0] }}" {{ ($academic[$key] != null) ? '' : 'hidden'}}><input type="text" class="form-control" id="uni_{{ $ace[0] }}" placeholder="Enter program name" name="uni[]" value="{{ ($academic[$key] != null) ? $academic[$key]->university_name : ''}}"></td>
                            </tr>
                            @endforeach
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Form Group (Qualification)-->
                  <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                          <option value="-" selected disabled>-</option>
                          <option value="ACTIVE" {{ ($id->status == "ACTIVE") ? 'selected' : '' }}>ACTIVE</option>
                          <option value="NOTACTIVE" {{ ($id->status == "NOTACTIVE") ? 'selected' : '' }}>NOTACTIVE</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 mt-3" id="comment-card">
                      <div class="form-group">
                          <label class="form-label">Description</label>
                          <textarea id="comments" name="comments" class="mt-2" rows="10" cols="80">
                            {{ $id->comment }}
                          </textarea>
                          <span class="text-danger">@error('classdescription')
                            {{ $message }}
                          @enderror</span>
                      </div>   
                    </div>
                  </div>
                  <!-- Save changes button-->
                  <button class="btn btn-primary" type="submit">Save changes</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <br>
    </section>
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

<script type="text/javascript">
  var usertype = "";
  var selected_faculty = "";
  var check = "";

  var program_id = "{{ $id->programid }}";

  var user_ic = "{{ $id->ic }}";

  $(document).ready( function () {
    usertype = $("#usrtype").val();
    status = $("#status").val();
  
    "use strict";
        ClassicEditor
        .create( document.querySelector( '#comments' ),{ height: '25em' } )
        .then(newEditor =>{editor = newEditor;})
        .catch( error => { console.log( error );});
    
    if(usertype == 'PL' || usertype == 'AO')
      {
        document.getElementById('program-card').hidden = false;
        document.getElementById('program').required = true;

        selected_faculty = $("#faculty").val();
  
        getProgramOption2(selected_faculty, user_ic);

      }else{
        document.getElementById('program-card').hidden = true;
        document.getElementById('program').required = false;
      }

      if(status == 'NOTACTIVE')
      {
        document.getElementById('comment-card').hidden = false;
        document.getElementById('comments').required = true;

      }else{
        document.getElementById('comment-card').hidden = true;
        document.getElementById('comments').required = false;
      }


  } );

  inputGroupFile01.onchange = evt => {
    const [file] = inputGroupFile01.files
    if (file) {
      userimage.src = URL.createObjectURL(file)
    }
  }
 
  $(document).on('change', '#usrtype', async function(e){
      usertype = $(e.target).val();
  
      if(usertype == 'PL' || usertype == 'AO')
      {
        document.getElementById('program-card').hidden = false;
        document.getElementById('program').required = true;
      }else{
        document.getElementById('program-card').hidden = true;
        document.getElementById('program').required = false;
      }
  })

  $(document).on('change', '#status', function(e){
      status = $(e.target).val();
  
      if(status == 'NOTACTIVE')
      {
        document.getElementById('comment-card').hidden = false;
        document.getElementById('comments').required = true;

      }else{
        document.getElementById('comment-card').hidden = true;
        document.getElementById('comments').required = false;
      }
  })
  
  $(document).on('change', '#faculty', async function(e){
      selected_faculty = $(e.target).val();
  
      await getProgramOption2(selected_faculty, user_ic);
  })
  
  function getProgramOption2(faculty, ic)
    {
      return $.ajax({
              headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
              url      : "{{ url('admin/getProgramoptions2') }}",
              method   : 'POST',
              data 	 : {faculty: faculty, ic: ic},
              error:function(err){
                  alert("Error");
                  console.log(err);
              },
              success  : function(data){
                  $('#program').html(data);
                  //$('#program').selectpicker('refresh');
  
              }
          });
    }

    $("input[type='checkbox']").change(function(){
    check = this.value;

    if(this.checked){
      document.getElementById('tb_' + check).hidden = false;
      document.getElementById('tb2_' + check).hidden = false;
      document.getElementById('prg_' + check).required = true;
      document.getElementById('uni_' + check).required = true;
    }else{
      document.getElementById('tb_' + check).hidden = true;
      document.getElementById('tb2_' + check).hidden = true;
      document.getElementById('prg_' + check).required = false;
      document.getElementById('uni_' + check).required = false;
    }

  });
  
  </script>
@endsection
