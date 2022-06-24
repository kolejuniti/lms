@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Students</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Students</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <form action="/KP/{{ request()->group }}" method="POST">
              @csrf
              @method('PATCH')
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of student who are currently active</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 10%">Name</th>
                          <th style="width: 10%">Ic</th>
                          <th style="width: 10%">No. Matric</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data as $key=>$students)
                        @if ($students->status == 'ACTIVE')
                        <tr>
                          <td>
                            <div>
                              <input class="mr-3" type="checkbox" class="filled-in" id="check_{{ $students->id }}" name="students[]" value="{{ $students->id }}">
                              <label for="check_{{ $students->id }}" >{{ $students->name }}</label>
                            </div>
                          </td>
                          <td>{{ $students->student_ic }}</td>
                          <td>{{ $students->no_matric }}</td>
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                      <tfoot>
                        
                      </tfoot>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of student who are not active</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 10%">Name</th>
                          <th style="width: 10%">Ic</th>
                          <th style="width: 10%">No. Matric</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data as $key=> $students)
                        @if ($students->status == 'NOTACTIVE')
                        <tr>
                          <td>
                            <div>
                              <input class="mr-3" type="checkbox" class="filled-in" id="check_{{ $students->id }}" name="students[]" value="{{ $students->id }}">
                              <label for="check_{{ $students->id }}" >{{ $students->name }}</label>
                            </div>
                          </td>
                          <td>{{ $students->student_ic }}</td>
                          <td>{{ $students->no_matric }}</td>
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                      <tfoot>
                        
                      </tfoot>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="form-group pull-right">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.col -->
          </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>
@endsection
