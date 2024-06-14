@extends('../layouts.pendaftar')

@section('main')

<style>
.form-control,
.form-select {
    border: 1px solid black;
    padding: 5px;
}
</style>
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
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student Registration</h3>
                <div class="row mt-3 d-flex">
                  <div class="col-md-12 mb-3">
                    <div class="pull-right">
                        <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                          <i class="fa fa-user-o"></i> &nbsp Check Student
                        </a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('pendaftar.store') }}" method="POST">
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
                            <label class="form-label" for="name">Full Name <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="ic">IC <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="ic" name="ic" placeholder="Enter ic" minlength="12" maxlength="12" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="passport">No. Passport <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="passport" name="passport" placeholder="Enter passport" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="birth_place">Place Of Birth <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="birth_place" name="birth_place" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['state'] as $state)
                                <option value="{{ $state->id }}">{{ $state->state_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="birth_date">Date Of Birth <p style="color:red; display:inline-block;">*</p></label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="gender">Gender <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="gender" name="gender" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['gender'] as $gender)
                                <option value="{{ $gender->id }}">{{ $gender->sex_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <h4 style="color:red; display:inline-block;"><a target="_blank" href="https://mysprsemak.spr.gov.my/semakan">Untuk semak student di MYSPR, boleh click di sini.</a></h4>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="race">Race <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="race" name="race" required>
                              <option value="-" selected disabled>-</option>
                              @foreach ($data['race'] as $rc)
                                <option value="{{ $rc->id }}">{{ $rc->nationality_name }}</option> 
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="religion">Religion <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="religion" name="religion" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['religion'] as $religion)
                                  <option value="{{ $religion->id }}">{{ $religion->religion_name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="CL">Citizenship Level <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="CL" name="CL" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['CL'] as $CL)
                                <option value="{{ $CL->id }}">{{ $CL->citizenshiplevel_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="citizen">Citizen <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="citizen" name="citizen" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['citizen'] as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->citizenship_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="mstatus">Status <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="mstatus" name="mstatus" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['mstatus'] as $mstatus)
                                <option value="{{ $mstatus->id }}">{{ $mstatus->marriage_name}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np1">No. Phone 1 <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="np1" placeholder="Enter Phone Number 1" name="np1" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np2">No. Phone 2</label>
                            <input type="text" class="form-control" id="np2" placeholder="Enter Phone Number 2" name="np2">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="np3">Home Phone Np.</label>
                            <input type="text" class="form-control" id="np3" placeholder="Enter Home Phone Number" name="np3">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="dun">DUN <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="dun" name="dun" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['dun'] as $dun)
                                <option value="{{ $dun->id }}">{{ $dun->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="parlimen">Parlimen <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="parlimen" name="parlimen" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['parlimen'] as $parlimen)
                                <option value="{{ $parlimen->id }}">{{ $parlimen->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="qualification">Qualification <p style="color:red; display:inline-block;">*</p></label>
                            <select class="form-select" id="qualification" name="qualification" required>
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['qualification'] as $quality)
                                <option value="{{ $quality->id }}">{{ $quality->name }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                          </div>
                        </div> 
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="EA">Education Advisor</label>
                            <input class="form-select" type="text" id="EA" name="EA" list="EA-list">
                            <datalist id="EA-list">
                              @foreach ($data['EA'] as $ea)
                                <option value="{{ $ea->id }}">{{ $ea->name}}</option> 
                              @endforeach
                            </datalist>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mt-4">
                          <div class="form-group">
                              <input type="checkbox" id="oku" class="filled-in" name="oku" value="1">
                              <label for="oku">OKU</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-2">
                          <div class="form-group">
                            <label class="form-label" for="jkm">No. KAD</label>
                            <input type="text" class="form-control" id="jkm" name="jkm">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="bank_name" class="form-label">Choose a bank:</label>
                            <input class="form-control" list="banks" id="bank_name" name="bank_name" placeholder="Type to search...">
                            <datalist id="banks">
                                <option value="AFFIN BANK">
                                <option value="ALLIANCE BANK">
                                <option value="AMBANK">
                                <option value="BANK ISLAM MALAYSIA">
                                <option value="BANK MUAMALAT MALAYSIA">
                                <option value="BANK RAKYAT">
                                <option value="BANK SIMPANAN NASIONAL">
                                <option value="CIMB BANK">
                                <option value="CITIBANK">
                                <option value="HONG LEONG BANK">
                                <option value="HSBC BANK MALAYSIA">
                                <option value="MAYBANK">
                                <option value="OCBC BANK">
                                <option value="PUBLIC BANK">
                                <option value="RHB BANK">
                                <option value="STANDARD CHARTERED BANK MALAYSIA">
                                <option value="UOB BANK">
                            </datalist>
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
                        <div class="col-md-6 mb-2">
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
                            <label class="form-label" for="address1">Address 1 <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="address1" placeholder="Enter Address 1" name="address1" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="address2">Address 2</label>
                            <input type="text" class="form-control" id="address2" placeholder="Enter Address 2" name="address2">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="address3">Address 3</label>
                            <input type="text" class="form-control" id="address3" placeholder="Enter Address 3" name="address3">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="postcode">Postcode <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter Postcode" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="city">City <p style="color:red; display:inline-block;">*</p></label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" required>
                          </div>
                        </div> 
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="state">State</label>
                            <select class="form-select" id="state" name="state">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['state'] as $stt)
                                <option value="{{ $stt->id }}">{{ $stt->state_name }}</option> 
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

                  {{-- <div id="forms-container">
                    <div class="card mb-3" id="card-1">
                      <div class="card-header">
                        <b>Heir (Waris)</b>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_name">Name</label>
                              <input type="text" class="form-control" id="w_name" placeholder="Enter Name" name="w_name[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_ic">IC</label>
                              <input type="text" class="form-control" id="w_ic" placeholder="Enter IC" name="w_ic[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_notel_home">Home No. Tel</label>
                              <input type="text" class="form-control" id="w_notel_home" placeholder="Enter No. Tel Home" name="w_notel_home[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_notel">Phone No. Tel</label>
                              <input type="text" class="form-control" id="w_notel" placeholder="Enter No. Tel" name="w_notel[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="occupation">Occupation</label>
                              <select class="form-select" id="occupation" name="occupation[]">
                                <option value="-" selected disabled>-</option>
                                <option value="SEKTOR KERAJAAN">SEKTOR KERAJAAN</option>
                                <option value="SEKTOR SWASTA">SEKTOR SWASTA</option>
                                <option value="BEKERJA SENDIRI">BEKERJA SENDIRI</option>
                                <option value="PESARA">PESARA</option>
                                <option value="TIDAK BEKERJA">TIDAK BEKERJA</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="dependent">No. Dependent</label>
                              <input type="text" class="form-control" id="dependent" name="dependent[]" placeholder="Enter Dependent">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="relationship">Relationship</label>
                              <select class="form-select" id="relationship" name="relationship[]">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['relationship'] as $rlp)
                                  <option value="{{ $rlp->id }}">{{$rlp->name }}</option> 
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_race">Race</label>
                              <select class="form-select" id="w_race" name="w_race[]">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['race'] as $rc)
                                  <option value="{{ $rc->id }}">{{ $rc->nationality_name }}</option> 
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_kasar">Salary (Kasar)</label>
                              <input type="text" class="form-control" id="w_kasar" name="w_kasar[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_bersih">Salary (Bersih)</label>
                              <input type="text" class="form-control" id="w_bersih" name="w_bersih[]">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="w_status">Status</label>
                              <select class="form-select" id="w_status" name="w_status[]">
                                <option value="-" selected disabled>-</option>
                                @foreach ($data['wstatus'] as $sts)
                                  <option value="{{ $sts->id }}">{{$sts->name }}</option> 
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="form-group">
                      <button id="add-form" class="btn btn-primary pull-right" type="button">Add Waris</button>
                    </div>
                  </div> --}}

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
                                <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{$prg->progname }}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="session">Intake</label>
                            <select class="form-select" id="session" name="session">
                              <option value="-" selected disabled>-</option>
                                @foreach ($session as $ses)
                                <option value="{{ $ses->SessionID }}">{{ $ses->SessionName}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="batch">Batch</label>
                            <select class="form-select" id="batch" name="batch">
                              <option value="-" selected disabled>-</option>
                                @foreach ($data['batch'] as $bch)
                                <option value="{{ $bch->BatchID }}">{{ $bch->BatchName}}</option> 
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label" for="dol">Date of Offer Letter</label>
                            <input type="date" class="form-control" id="dol" name="dol">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="ml-2">
                              <input type="checkbox" id="main" class="filled-in" name="main" value="1">
                              <label for="main">Main</label>
                            </div>
                            <div class="ml-2">
                              <input type="checkbox" id="PR" class="filled-in" name="PR" value="1">
                              <label for="PR">Pre-Registration</label>
                            </div>
                            {{-- <div class="ml-2">
                              <input type="checkbox" id="c19" class="filled-in" name="c19" value="1">
                              <label for="c19">C19</label>
                            </div> --}}
                            <div class="ml-2">
                              <input type="checkbox" id="CF" class="filled-in" name="CF" value="1">
                              <label for="CF">Complete Form</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" id="missingform">
                        <div class="card mb-3">
                          <div class="card-header">
                            <b>Please check the missing/incomplete form below.</b>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <div class="ml-2">
                                    <input type="checkbox" id="copyic" class="filled-in" name="copyic" value="1">
                                    <label for="copyic">Copy of student's identification card</label>
                                  </div>
                                  <div class="ml-2">
                                    <input type="checkbox" id="copybc" class="filled-in" name="copybc" value="1">
                                    <label for="copybc">Copy of student's birth certificate.</label>
                                  </div>
                                  <div class="ml-2">
                                    <input type="checkbox" id="copyspm" class="filled-in" name="copyspm" value="1">
                                    <label for="copyspm">Copy of SPM certificate.</label>
                                  </div>
                                  <div class="ml-2">
                                    <input type="checkbox" id="coppysc" class="filled-in" name="coppysc" value="1">
                                    <label for="coppysc">Copy of school certificate.</label>
                                  </div>
                                  <div class="ml-2">
                                    <input type="checkbox" id="copypic" class="filled-in" name="copypic" value="1">
                                    <label for="copypic">Copy of parent's identification card.</label>
                                  </div>
                                  <div class="ml-2">
                                    <input type="checkbox" id="copypp" class="filled-in" name="copypp" value="1">
                                    <label for="copypp">Copy of parant's payslip/income confirmation.</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                    
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                </div>
              </form>

              <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- modal content-->
                    <div class="modal-content" id="getModal">
                        <div class="modal-header">
                            <div class="">
                                <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                          <div class="row col-md-12">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                                <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                              </div>
                            </div>
                          </div>
                          <div id="form-student">
                            <div class="card mb-3" id="stud_info">
                              <div class="card-header">
                              <b>Student</b>
                              </div>
                              <div class="card-body">
                                  <div class="row">
                                      <div class="col-md-12 mt-3">
                                          <div class="form-group mt-3">
                                              <table id="get_student" class="w-100 table table-bordered display margin-top-10 w-p100">
                                                  <thead>
                                                      <tr>
                                                          <th >
                                                              Name
                                                          </th>
                                                          <th >
                                                              IC
                                                          </th>
                                                      </tr>
                                                  </thead>
                                                  <tbody id="table">
                                                      <tr>
                                                          <td>
                                                          
                                                          </td>
                                                          <td>
                                                          
                                                          </td>
                                                      </tr>
                                                  </tbody>
                                              </table>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

@if(session('newStud'))
    <script>
      alert('Success! Student has been registered!')
      window.open('/pendaftar/surat_tawaran?ic={{ session("newStud") }}')
    </script>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#add-form').click(function() {
      // Clone the card element with ID #card-1
      var newForm = $('#card-1').clone();
      // Clear the input values in the cloned form
      newForm.find('input, select').val('');
      // Add a delete button to the new form
      newForm.append('<div class="form-group" style="margin-left: 10px"><button class="btn btn-danger delete-form" type="button">Delete Form</button></div>');
      // Append the new form to the forms container
      $('#forms-container').append(newForm);
    });

    // Attach a click event listener to the delete buttons
    $('#forms-container').on('click', '.delete-form', function() {
      // Remove the parent card element
      $(this).closest('.card').remove();
    });
  });
</script>

<script type="text/javascript">

$(document).on('keyup', '#ic', function(){
    if($(this).val() === '')
    {

      $('#passport').attr('disabled', false);
      $('#passport').attr('required', true);

    }else{

      $('#passport').attr('disabled', true);
      $('#passport').attr('required', false);

    } 
});

$(document).on('keyup', '#passport', function(){
    if($(this).val() === '')
    {

      $('#ic').attr('disabled', false);
      $('#ic').attr('required', true);

    }else{

      $('#ic').attr('disabled', true);
      $('#ic').attr('required', false);
       
    } 
});

$(document).on('change', "#CF",function(){
  if(this.checked)
  {
    document.getElementById('missingform').hidden = true;
      $('#copyic').prop('checked', false);
      $('#copybc').prop('checked', false);
      $('#copyspm').prop('checked', false);
      $('#coppysc').prop('checked', false);
      $('#copypic').prop('checked', false);
      $('#copypp').prop('checked', false);
  }else{
    document.getElementById('missingform').hidden = false;
  }
    //
})

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
});

function getStudent(search)
{

          return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/create/search') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#get_student').html(data); 
            }
        });
    
}

</script>
@endsection
