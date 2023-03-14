@extends('../layouts.pendaftar')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Edit</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Student</li>
              <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student Edit</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="/pendaftar/edit/update?id={{ $student->ic }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Maklumat Peribadi</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" value="{{ $student->name }}" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="ic">IC</label>
                            <input type="text" class="form-control" id="ic" name="ic" placeholder="Enter ic" value="{{ (strlen($student->ic) == 12) ? $student->ic : '' }}" readonly>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="passport">No. Passport</label>
                            <input type="text" class="form-control" id="passport" name="passport" placeholder="Enter passport" value="{{ (strlen($student->ic) != 12) ? $student->ic : '' }}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="birth_place">Place Of Birth</label>
                            <select class="form-select" id="birth_place" name="birth_place">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['state'] as $state)
                                <option value="{{ $state->id }}" {{ ($student->state_id == $state->id) ? 'selected' : '' }}>{{ $state->state_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="birth_date">Date Of Birth</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="gender">Gender</label>
                            <select class="form-select" id="gender" name="gender">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['gender'] as $gender)
                                <option value="{{ $gender->id }}" {{ ($student->sex_id == $gender->id) ? 'selected' : '' }}>{{ $gender->sex_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="race">Race</label>
                            <select class="form-select" id="race" name="race">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['race'] as $ny)
                                <option value="{{ $ny->id }}" {{ ($student->nationality_id == $ny->id) ? 'selected' : '' }}>{{ $ny->nationality_name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="religion">Religion</label>
                            <select class="form-select" id="religion" name="religion">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['religion'] as $religion)
                                <option value="{{ $religion->id }}" {{ ($student->religion_id == $religion->id) ? 'selected' : '' }}>{{ $religion->religion_name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="CL">Citizenship Level</label>
                            <select class="form-select" id="CL" name="CL">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['CL'] as $CL)
                                <option value="{{ $CL->id }}" {{ ($student->statelevel_id == $CL->id) ? 'selected' : '' }}>{{ $CL->citizenshiplevel_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="citizen">Citizen</label>
                            <select class="form-select" id="citizen" name="citizen">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['citizen'] as $ct)
                                <option value="{{ $ct->id }}" {{ ($student->citizenship_id == $ct->id) ? 'selected' : '' }}>{{ $ct->citizenship_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="mstatus">Status</label>
                            <select class="form-select" id="mstatus" name="mstatus">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['mstatus'] as $mstatus)
                                <option value="{{ $mstatus->id }}" {{ ($student->marriage_id == $mstatus->id) ? 'selected' : '' }}>{{ $mstatus->marriage_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np1">No. Phone 1</label>
                            <input type="text" class="form-control" id="np1" placeholder="Enter Phone Number 1" name="np1" value="{{ $student->no_tel }}" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np2">No. Phone 2</label>
                            <input type="text" class="form-control" id="np2" placeholder="Enter Phone Number 2" name="np2" value="{{ $student->no_tel2 }}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np3">Home Phone Np.</label>
                            <input type="text" class="form-control" id="np3" placeholder="Enter Home Phone Number" name="np3" value="{{ $student->no_telhome }}">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="matric">No. Matric</label>
                            <input type="text" class="form-control" id="matric" name="matric" placeholder="Enter matric" required value="{{ $student->no_matric }}">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required value="{{ $student->email }}">
                          </div>
                        </div> 
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="EA">Education Advisor</label>
                            <select class="form-select" id="EA" name="EA">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['EA'] as $ea)
                                <option value="{{ $ea->id }}" {{ ($student->advisor_id == $ea->id) ? 'selected' : '' }}>{{ $ea->name}}</option> 
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="bank_name">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" placeholder="Enter Bank Name" name="bank_name">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="bank_number">Bank Account No.</label>
                            <input type="text" class="form-control" id="bank_number" placeholder="Enter Bank Number" name="bank_number">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="PN">PTPTN Pin No.</label>
                            <input type="text" class="form-control" id="PN" placeholder="Enter PTPTN Pin No." name="PN">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="dt">Date/Time</label>
                            <input type="datetime-local" class="form-control" id="dt" placeholder="Enter Bank Name" name="dt">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Visa / Student Pass Information</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="pt">Pass Type</label>
                            <select class="form-select" id="pt" name="pt">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['pass'] as $pss)
                                <option value="{{ $pss->id }}">{{$pss->name }}</option> 
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="spn">Student Pass No.</label>
                            <input type="text" class="form-control" id="spn" placeholder="Enter Student Pass No." name="spn">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="di">Date Issued</label>
                            <input type="date" class="form-control" id="di" name="di">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="de">Date Expired</label>
                            <input type="date" class="form-control" id="de" name="de">
                          </div>
                        </div> 
                      </div>
                    </div>
                  </div>

                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Permanent Address</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="address1">Address 1</label>
                            <input type="text" class="form-control" id="address1" placeholder="Enter Address 1" name="address1" value="{{ $student->address1 }}" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="address2">Address 2</label>
                            <input type="text" class="form-control" id="address2" placeholder="Enter Address 2" name="address2" value="{{ $student->address2 }}" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="address3">Address 3</label>
                            <input type="text" class="form-control" id="address3" placeholder="Enter Address 3" name="address3" value="{{ $student->address3 }}" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="postcode">Postcode</label>
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter Postcode" value="{{ $student->postcode }}" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="{{ $student->city }}" required>
                          </div>
                        </div> 
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="state">State</label>
                            <select class="form-select" id="state" name="state">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['state'] as $state)
                              <option value="{{ $state->id }}" {{ ($student->state_id == $state->id) ? 'selected' : '' }}>{{ $state->state_name}}</option> 
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="country">Country</label>
                            <select class="form-select" id="country" name="country">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Heir (Waris)</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_name">Name</label>
                            <input type="text" class="form-control" id="w_name" placeholder="Enter Name" name="w_name" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_ic">IC</label>
                            <input type="text" class="form-control" id="w_ic" placeholder="Enter IC" name="w_ic" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_notel_home">Home No. Tel</label>
                            <input type="text" class="form-control" id="w_notel_home" placeholder="Enter No. Tel Home" name="w_notel_home" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_notel">Phone No. Tel</label>
                            <input type="text" class="form-control" id="w_notel" placeholder="Enter No. Tel" name="w_notel" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="occupation">Occupation</label>
                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="dependent">No. Dependent</label>
                            <input type="text" class="form-control" id="dependent" name="dependent" placeholder="Enter Dependent" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="relationship">Relationship</label>
                            <select class="form-select" id="relationship" name="relationship" required>
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_race">Race</label>
                            <select class="form-select" id="w_race" name="w_race" required>
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_status">Status</label>
                            <select class="form-select" id="w_status" name="w_status" required>
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Heir 2 (Waris 2)</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_name">Name</label>
                            <input type="text" class="form-control" id="w_name" placeholder="Enter Name" name="w_name">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_ic">IC</label>
                            <input type="text" class="form-control" id="w_ic" placeholder="Enter IC" name="w_ic">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_notel_home">Home No. Tel</label>
                            <input type="text" class="form-control" id="w_notel_home" placeholder="Enter No. Tel Home" name="w_notel_home">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_notel">Phone No. Tel</label>
                            <input type="text" class="form-control" id="w_notel" placeholder="Enter No. Tel" name="w_notel">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="occupation">Occupation</label>
                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="dependent">No. Dependent</label>
                            <input type="text" class="form-control" id="dependent" name="dependent" placeholder="Enter Dependent">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="relationship">Relationship</label>
                            <select class="form-select" id="relationship" name="relationship">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_race">Race</label>
                            <select class="form-select" id="w_race" name="w_race">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="w_status">Status</label>
                            <select class="form-select" id="w_status" name="w_status">
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['country'] as $cry)
                                <option value="{{ $cry->id }}">{{$cry->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card mb-3">
                    <div class="card-header">
                      <b>Program Registration</b>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label class="form-label" for="program">Program</label>
                            <select class="form-select" id="program" name="program">
                              <option value="-" selected disabled>-</option>
                                @foreach ($program as $prg)
                                <option value="{{ $prg->id }}" {{ ($prg->id == $student->program) ? 'selected' : ''}}>{{$prg->progname }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-12 mt-3" id="commentID" hidden>
                          <div class="form-group">
                              <label class="form-label">Comment</label>
                              <textarea id="commenttxt" name="comment" class="mt-2" rows="10" cols="80">
                              </textarea>
                              <span class="text-danger">@error('comment')
                                {{ $message }}
                              @enderror</span>
                          </div>   
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="session">Intake</label>
                            <select class="form-select" id="session" name="session">
                              <option value="-" selected disabled>-</option>
                                @foreach ($session as $ses)
                                <option value="{{ $ses->SessionID }}" {{ ($student->intake == $ses->SessionID) ? 'selected' : '' }}>{{ $ses->SessionName}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="batch">Batch</label>
                            <select class="form-select" id="batch" name="batch">
                              <option value="-" selected disabled>-</option>
                                @foreach ($session as $ses)
                                <option value="{{ $ses->SessionID }}" {{ ($ses->SessionID == $student->batch) ? 'selected' : ''}}>{{ $ses->SessionName}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <div class="row pull-right">
                    <div class="d-flex ">
                      <div class="form-group" style="margin-right: 5px">
                        <a class="btn btn-info mb-3" type="button" href="/pendaftar/surat_tawaran?ic={{ $student->ic }}" target="_blank">Edit</a>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>
<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script src="{{ asset('assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js') }}"></script>

<script type="text/javascript">

"use strict";
ClassicEditor
.create( document.querySelector( '#commenttxt' ),{ height: '25em' } )
.then(newEditor =>{editor = newEditor;})
.catch( error => { console.log( error );});

$(document).on('change', "#CF",function(){
  if(this.checked)
  {
    document.getElementById('missingform').hidden = true;
  }else{
    document.getElementById('missingform').hidden = false;
  }
    //
})

$(document).on('change', '#program', function(){

  document.getElementById('commentID').hidden = false;

  document.getElementById('commenttxt').required = true;

})
</script>
@endsection
