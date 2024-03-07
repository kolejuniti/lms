@extends((Auth::user()->usrtype == "AR") ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : '')))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
    <div class="container-full">
        <!-- Content Header (Page header) -->	  
        <div class="content-header">
            <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Student Report SPM</h4>
                <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                    <li class="breadcrumb-item active" aria-current="page">Student Report SPM</li>
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
                            <h3 class="card-title">Student Report</h3>
                          </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="card-body" style="width: 100%; overflow-x: auto;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label class="form-label" for="program">Program</label>
                                          <select class="form-select" id="program" name="program">
                                            <option value="-" selected disabled>-</option>
                                            @foreach($data['program'] as $prg)
                                            <option value="{{ $prg->id }}">{{ $prg->progcode }} - {{ $prg->progname }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label class="form-label" for="year">Year</label>
                                          <select class="form-select" id="year" name="year">
                                            <option value="-" selected disabled>-</option>
                                            @foreach($data['year'] as $prg)
                                            <option value="{{ $prg->year }}">{{ $prg->year }}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>

<script>

var selected_program = "";
var selected_year = "";

$(document).on('change', '#program', function(e){
    selected_program = $(e.target).val();

    getStudent(selected_program,selected_year);

});


$(document).on('change', '#year', function(e){
    selected_year = $(e.target).val();

    getStudent(selected_program,selected_year);

});

function getStudent(program,year)
{

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('all/student/spm/report/getStudentSPM') }}",
            method   : 'POST',
            data 	 : {program: program, year: year},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#table').html(data);
            }
        });

}

</script>

  @endsection