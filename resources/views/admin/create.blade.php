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
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lecturer Registration</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="nostaf">No Staf</label>
                        <input type="text" class="form-control" id="nostaf" name="nostaf" placeholder="Enter Staff No" max="12" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="ic">IC</label>
                        <input type="text" class="form-control" id="ic" name="ic" placeholder="Enter ic" max="12" required>
                      </div>
                    </div>
                  
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="usrtype">User Type</label>
                        <select class="form-select" id="usrtype" name="usrtype">
                          <option value="-" selected disabled>-</option>
                          <option value="PL">Program Lead</option>
                          <option value="AO">Administrative Officer</option>
                          <option value="RGS">HEA</option>
                          <option value="LCT">Lecturer</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="faculty">Faculty</label>
                        <select class="form-select" id="faculty" name="faculty">
                          <option value="-" selected disabled>-</option>
                          @foreach ($faculty as $fcl)
                          <option value="{{ $fcl->id }}">{{ $fcl->facultyname }}
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="from">Starting Date</label>
                        <input type="date" class="form-control" id="from" placeholder="" name="from" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="to">Ending Date (Empty if not applicable)</label>
                        <input type="date" class="form-control" id="to" placeholder="Leave empty if not applied" name="to">
                      </div>
                    </div>
                    <!--<div class="col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="academic">Academic Qualification</label>
                        <select class="form-select" id="academic" name="academic" required>
                          <option value="-" selected disabled>-</option>
                          <option value="DP">DIPLOMA</option>
                          <option value="DG">DEGREE</option>
                          <option value="MS">MASTER</option>
                          <option value="PHD">PHD</option>
                        </select>
                      </div>
                    </div>-->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-label" for="academic">Academic Qualification</label>
                        <div class="container mt-1" id="academic">
                          <table class="table">
                            <tr>
                              <td><input type="checkbox" id="diploma" name="academic[]" value="DP"><label for="diploma" class="form-label">DIPLOMA</label></td>
                              <td id="tb_DP" hidden><input type="text" class="form-control" id="prg_DP" placeholder="Enter program name" name="prg[]"></td>
                              <td id="tb2_DP" hidden><input type="text" class="form-control" id="uni_DP" placeholder="Enter university name" name="uni[]"></td>
                            </tr>
                            <tr>
                              <td><input type="checkbox" id="degree" name="academic[]" value="DG"><label for="degree" class="form-label">DEGREE</label></td>
                              <td id="tb_DG" hidden><input type="text" class="form-control" id="prg_DG" placeholder="Enter program name" name="prg[]"></td>
                              <td id="tb2_DG" hidden><input type="text" class="form-control" id="uni_DG" placeholder="Enter university name" name="uni[]"></td>
                            </tr>
                            <tr>
                              <td><input type="checkbox" id="master" name="academic[]" value="MS"><label for="master" class="form-label">MASTER</label></td>
                              <td id="tb_MS" hidden><input type="text" class="form-control" id="prg_MS" placeholder="Enter program name" name="prg[]"></td>
                              <td id="tb2_MS" hidden><input type="text" class="form-control" id="uni_MS" placeholder="Enter university name" name="uni[]"></td>
                            </tr>
                            <tr>
                              <td><input type="checkbox" id="phd" name="academic[]" value="PHD"><label for="phd" class="form-label">PHD</label></td>
                              <td id="tb_PHD" hidden><input type="text" class="form-control" id="prg_PHD" placeholder="Enter program name" name="prg[]"></td>
                              <td id="tb2_PHD" hidden><input type="text" class="form-control" id="uni_PHD" placeholder="Enter university name" name="uni[]"></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" id="program-card" hidden>
                      <div class="form-group" id="program">
                        <label class="form-label" for="program">Program</label>
                        <div class="container mt-1" id="program">
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
