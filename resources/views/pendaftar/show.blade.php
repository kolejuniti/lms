@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">ADMIN Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Projects</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Projects</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

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
                          <th style="width: 10%">Faculty</th>
                          <th style="width: 10%">Intake</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data as $key=> $students)
                        @if ($students->status == 'ACTIVE')
                        <tr>
                          <td>
                            <div>
                              <input class="mr-3" type="checkbox" name="students[]" value="{{ $students->id }}">
                              <label for="students" >{{ $students->student_ic }}</label>
                            </div>
                          </td>
                          <td>{{ $students->course_id }}</td>
                          <td>{{ $students->session_id }}</td>
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
                          <th style="width: 10%">Faculty</th>
                          <th style="width: 10%">Intake</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data as $key=> $students)
                        @if ($students->status == 'NOTACTIVE')
                        <tr>
                          <td>
                            <div>
                              <input class="mr-3" type="checkbox" name="students[]" value="{{ $students->id }}">
                              <label for="students" >{{ $students->student_ic }}</label>
                            </div>
                          </td>
                          <td>{{ $students->course_id }}</td>
                          <td>{{ $students->session_id }}</td>
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
                <div class="card-footer float:right">
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
@endsection
