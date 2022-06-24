@extends('../layouts.ketua_program')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Asessment</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Asessment</li>
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
              <form action="/KP/{{ $data['course'] }}/update/marks" method="POST">
              @csrf
              @method('PATCH')
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">List of Assessments</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th style="width: 10%">Assessments</th>
                          <th style="width: 1%">Mark Percentage (%)</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data['classmark'] as $key=>$datas)
                        <tr>
                          <td>{{ $datas->assessment }}</td>
                          <td>
                            <div>
                              <input type="number" class="form-control" id="mark_{{ $datas->id }}" name="marks[]" value="{{ $datas->mark_percentage }}" min="0" max="100">
                            </div>
                          </td>
                        </tr>
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
