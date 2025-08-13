@extends('../layouts.pendaftar')

@section('main')

<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    @if(session()->has('error'))
    <div class="form-group">
        <div class="alert alert-danger">
        <span>{{ session()->get('error') }} </span>
        </div>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="form-group">
        <div class="alert alert-success">
        <span>{{ session()->get('success') }} </span>
        </div>
    </div>
    @endif
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">SPM</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Student</li>
              <li class="breadcrumb-item active" aria-current="page">SPM</li>
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
              <!-- /.card-header -->
              @php 
              $required = ['1','2','3','4','5','6','7','8','9','10','11'];
              @endphp
              <!-- form start -->
              <form action="/pendaftar/spm/{{ request()->ic }}/store" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">SPM</h3>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <b>Student Info</b>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>Student Name &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->name }}</p>
                                </div>
                                <div class="form-group">
                                    <p>Status &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->status }}</p>
                                </div>
                                <div class="form-group">
                                    <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                                </div>
                                <div class="form-group">
                                    <p>Intake &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->intake_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                                </div>
                                <div class="form-group">
                                    <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                                </div>
                                <div class="form-group">
                                    <p>Semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->semester }}</p>
                                </div>
                                <div class="form-group">
                                    <p>Session &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->session_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="card-body">
                        <div class="row">
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="year">Year</label>
                                    <select class="form-select" id="year" name="year" required>
                                    <option value="" selected disabled>-</option>
                                    @for ($year = date('Y'); $year >= 1900; $year--)
                                        <option value="{{ $year }}" {{ isset($data['info']->year) ? (($data['info']->year == $year) ? 'selected' : '') : '' }}>{{ $year }}</option>
                                    @endfor
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="year">Year</label>
                                    <input type="text" class="form-control" id="year" name="year" value="{{ isset($data['info']->year) ? $data['info']->year : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="turn">Turn No.</label>
                                    <input type="text" class="form-control" id="turn" name="turn" value="{{ isset($data['info']->number_turn) ? $data['info']->number_turn : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="col-md-12">
                        @if (count($data['spm']) == 0)
                            {{-- <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject" name="subject[]" required>
                                            <option value="" selected disabled>-</option>
                                            @foreach ($data['subject'] as $sub)
                                                <option value="{{ $sub->id }}" {{ ($sub->id == 1) ? 'selected' : 'disabled' }}>{{$sub->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade" name="grade[]" required>
                                            <option value="" selected>-</option>
                                            @foreach ($data['grade'] as $grd)
                                                <option value="{{ $grd->id }}" {{ old('grade.0') == $grd->id ? "selected": "" }}>{{$grd->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject" name="subject[]" required>
                                            <option value="" selected disabled>-</option>
                                            @foreach ($data['subject'] as $sub)
                                                <option value="{{ $sub->id }}" {{ ($sub->id == 2) ? 'selected' : 'disabled' }}>{{$sub->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade" name="grade[]" required>
                                            <option value="" selected>-</option>
                                            @foreach ($data['grade'] as $grd)
                                                <option value="{{ $grd->id }}" {{ old('grade.1') == $grd->id ? "selected": "" }}>{{$grd->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject" name="subject[]" required>
                                            <option value="" selected disabled>-</option>
                                            @foreach ($data['subject'] as $sub)
                                                <option value="{{ $sub->id }}" {{ ($sub->id == 5) ? 'selected' : 'disabled' }}>{{$sub->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade" name="grade[]" required>
                                            <option value="" selected>-</option>
                                            @foreach ($data['grade'] as $grd)
                                                <option value="{{ $grd->id }}" {{ old('grade.2') == $grd->id ? "selected": "" }}>{{$grd->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject" name="subject[]" required>
                                            <option value="" selected disabled>-</option>
                                            @foreach ($data['subject'] as $sub)
                                                <option value="{{ $sub->id }}" {{ ($sub->id == 6) ? 'selected' : 'disabled' }}>{{$sub->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade" name="grade[]" required>
                                            <option value="" selected>-</option>
                                            @foreach ($data['grade'] as $grd)
                                                <option value="{{ $grd->id }}" {{ old('grade.3') == $grd->id ? "selected": "" }}>{{$grd->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            @php
                                $count = 0;
                            @endphp
                            @foreach ($required as $key=> $req)
                            @php
                                $count++;
                            @endphp
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject_{{ $key }}" name="subject[]" onchange="reqGrade('{{ $key }}')" >
                                            <option value="" selected >-</option>
                                            @foreach ($data['subject'] as $sub)
                                                <option value="{{ $sub->id }}" {{ old('subject.'.$count) == $sub->id ? "selected": "" }}>{{$sub->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center "> <div class="d-flex justify-content-center align-middle">-</div></div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade_{{ $key }}" name="grade[]">
                                            <option value="" selected >-</option>
                                            @foreach ($data['grade'] as $grd)
                                                <option value="{{ $grd->id }}" {{ old('grade.'.$count) == $grd->id ? "selected": "" }}>{{$grd->name }}</option> 
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        @php
                            $totalRows = 11; // Define the total number of rows you want
                            $existingRows = count($data['spm']); // Get the count of existing rows
                            $remainingRows = $totalRows - $existingRows; // Calculate how many rows are left to add
                        @endphp
                        
                        @foreach ($data['spm'] as $key => $req)
                            <div class="col-md-12">
                                <div class="row">
                                    {{-- <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject_{{ $key }}" name="subject[]" onchange="reqGrade('{{ $key }}')">
                                                <option value="" selected>-</option>
                                                @foreach ($data['subject'] as $sub)
                                                    <option value="{{ $sub->id }}" {{ $req->subject_spm_id == $sub->id ? "selected" : "" }} 
                                                        {{ ((0 <= $key) && ($key <= 3)) ? ($sub->id == $req->subject_spm_id ? '' : 'disabled') : '' }}>
                                                        {{ $sub->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject_{{ $key }}" name="subject[]" onchange="reqGrade('{{ $key }}')">
                                                <option value="" selected>-</option>
                                                @foreach ($data['subject'] as $sub)
                                                    <option value="{{ $sub->id }}" {{ $req->subject_spm_id == $sub->id ? "selected" : "" }} 
                                                        >
                                                        {{ $sub->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center">
                                        <div class="d-flex justify-content-center align-middle">-</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade_{{ $key }}" name="grade[]">
                                                <option value="" selected>-</option>
                                                @foreach ($data['grade'] as $grd)
                                                    <option value="{{ $grd->id }}" {{ $req->grade_spm_id == $grd->id ? "selected" : "" }}>{{ $grd->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Add the remaining rows if the total count is less than 11 --}}
                        @for ($i = 0; $i < $remainingRows; $i++)
                            @php $key = $existingRows + $i; @endphp
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="subject">Subject</label>
                                            <select class="form-select" id="subject_{{ $key }}" name="subject[]" onchange="reqGrade('{{ $key }}')">
                                                <option value="" selected>-</option>
                                                @foreach ($data['subject'] as $sub)
                                                    <option value="{{ $sub->id }}" {{ old('subject.' . $key) == $sub->id ? "selected" : "" }}>{{ $sub->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 align-self-center">
                                        <div class="d-flex justify-content-center align-middle">-</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="grade">Grade</label>
                                            <select class="form-select" id="grade_{{ $key }}" name="grade[]">
                                                <option value="" selected>-</option>
                                                @foreach ($data['grade'] as $grd)
                                                    <option value="{{ $grd->id }}" {{ old('grade.' . $key) == $grd->id ? "selected" : "" }}>{{ $grd->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    
                        @endif
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                </div>
              </form>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">SPMV / SVM Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="/pendaftar/spm/{{ request()->ic }}/SPMVstore" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="row mb-4">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="year">Year</label>
                            <input type="text" class="form-control" id="year" name="year" value="{{ old('year', isset($data['spmv']->year) ? $data['spmv']->year : '') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="turn">Turn No.</label>
                            <input type="text" class="form-control" id="turn" name="turn" value="{{ old('turn', isset($data['spmv']->number_turn) ? $data['spmv']->number_turn : '') }}">
                        </div>
                      </div>
                      <div class="col-md-9 mt-3" id="payment-card">
                        <div class="form-group">
                            <label class="form-label" for="class">Certificate Type</label>
                            <fieldset>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="class" id="class1" value="spmv" {{ old('class', isset($data['spmv']) && $data['spmv']->cert_type == 'spmv' ? 'spmv' : '') == 'spmv' ? 'checked' : '' }}>
                                    <label for="class1">
                                        Sijil Pelajaran Malaysia Vokasional (SPMV)
                                    </label>
                                    <input class="form-check-input" type="radio" name="class" id="class2" value="svm" {{ old('class', isset($data['spmv']) && $data['spmv']->cert_type == 'svm' ? 'svm' : '') == 'svm' ? 'checked' : '' }}>
                                    <label for="class2">
                                        Sijil Vokasional Malaysia (SVM)
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                      <hr>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="pngka">Purata Nilai Gred Kumulatif Akademik (PNGKA)</label>
                                <input type="number" class="form-control" id="pngka" name="pngka" step="0.01" value="{{ old('pngka', isset($data['spmv']->pngka) ? $data['spmv']->pngka : '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="pngkv">Purata Nilai Gred Kumulatif Vokasional (PNGKV)</label>
                                <input type="number" class="form-control" id="pngkv" name="pngkv" step="0.01" value="{{ old('pngkv', isset($data['spmv']->pngkv) ? $data['spmv']->pngkv : '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="bmkv">Bahasa Melayu Kolej Vokasional 1104</label>
                                <input type="text" class="form-control" id="bmkv" name="bmkv" value="{{ old('bmkv', isset($data['spmv']->bmkv) ? $data['spmv']->bmkv : '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="sejarahspm">Sejarah SPM (Jika Perlu)</label>
                                <input type="text" class="form-control" id="sejarahspm" name="sejarahspm" value="{{ old('sejarahspm', isset($data['spmv']->sejarahspm) ? $data['spmv']->sejarahspm : '') }}">
                            </div>
                        </div>
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="card-footer">
                      <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                  </div>
                </form>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">SKM Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="/pendaftar/spm/{{ request()->ic }}/SKMstore" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="row mb-4">
                      <div class="row">
                        <div class="col-md-6 mt-4">
                          <div class="form-group">
                            <input type="checkbox" id="level" class="filled-in" name="level" value="1" {{ old('level', isset($data['skm']->tahap3) && $data['skm']->tahap3 ? '1' : '') == '1' ? 'checked' : '' }}>
                              <label for="level">Level 3</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-9 mt-3" id="payment-card">
                        <div class="form-group">
                            <label class="form-label" for="class">Field Type</label>
                            <fieldset>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="class" id="class3" value="0" {{ old('class', isset($data['skm']) && $data['skm']->in_field == '0' ? '0' : '') == '0' ? 'checked' : '' }}>
                                    <label for="class3">
                                        In field
                                    </label>
                                    <input class="form-check-input" type="radio" name="class" id="class4" value="1" {{ old('class', isset($data['skm']) && $data['skm']->in_field == '1' ? '1' : '') == '1' ? 'checked' : '' }}>
                                    <label for="class4">
                                        Public
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="program">Program</label>
                            <input type="text" class="form-control" id="program" name="program" value="{{ old('program', isset($data['skm']->program) ? $data['skm']->program : '') }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="card-footer">
                      <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
                  </div>
                </form>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">SKK Details</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="/pendaftar/spm/{{ request()->ic }}/SKKstore" method="POST">
                  @csrf
                  <div class="card-body">
                    <div class="row mb-4">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="registration_no">Registration No.</label>
                            <input type="text" class="form-control" id="registration_no" name="registration_no" value="{{ old('registration_no', isset($data['skk']->registration_no) ? $data['skk']->registration_no : '') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="hpnm">HPNM</label>
                            <input type="text" class="form-control" id="hpnm" name="hpnm" value="{{ old('hpnm', isset($data['skk']->hpnm) ? $data['skk']->hpnm : '') }}">
                        </div>
                      </div>
                      <div class="col-md-9 mt-3" id="payment-card">
                        <div class="form-group">
                            <label class="form-label" for="class">Field Type</label>
                            <fieldset>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="class3" id="class5" value="0" {{ old('class3', isset($data['skk']) && $data['skk']->in_field == '0' ? '0' : '') == '0' ? 'checked' : '' }}>
                                    <label for="class5">
                                        In field
                                    </label>
                                    <input class="form-check-input" type="radio" name="class3" id="class6" value="1" {{ old('class3', isset($data['skk']) && $data['skk']->in_field == '1' ? '1' : '') == '1' ? 'checked' : '' }}>
                                    <label for="class6">
                                        Public
                                    </label>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="program">Program</label>
                            <input type="text" class="form-control" id="program2" name="program2" value="{{ old('program2', isset($data['skk']->program) ? $data['skk']->program : '') }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-body -->
                  <div class="card-footer">
                      <button type="submit" class="btn btn-primary pull-right mb-3">Submit</button>
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

<script type="text/javascript">
var usertype = "";
var selected_faculty = "";
var check = "";


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

$(document).on('change', '#faculty', async function(e){
    selected_faculty = $(e.target).val();

    await getProgramOption(selected_faculty);
})

function getProgramOption(faculty)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('admin/getProgramoptions') }}",
            method   : 'POST',
            data 	 : {faculty: faculty},
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


function reqGrade(key)
{
    var subject = document.getElementById('subject_' + key).value;

    if(subject != '')
    {
        document.getElementById('grade_' + key).required = true;
    }else{
        document.getElementById('grade_' + key).required = false;
    }

}

</script>
@endsection
