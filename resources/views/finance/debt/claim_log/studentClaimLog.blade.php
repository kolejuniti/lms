@extends('../layouts.finance')

@section('main')
<style>
    a[data-toggle="modal"][data-target="#uploadModal"]:hover {
    color: blue;
}

</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Student Claim Log</h4>
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

  <div id="printableArea">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Student Claim Log</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-body">
                    <div class="card mb-3" id="stud_info">
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
                                        <p>No. IC / No. Passport &nbsp; &nbsp;: &nbsp;&nbsp; {{ $data['student']->ic }}</p>
                                    </div>
                                    <div class="form-group">
                                        <p>Program &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->program }}</p>
                                    </div>
                                    <div class="form-group">
                                        <p>No. Matric &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->no_matric }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Address</th>
                                                    <th>Phone No.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $data['student']->address }}</td>
                                                    <td>{{ $data['student']->no_tel }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p>Email &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['student']->email }}</p>
                                    </div>
                                    <div class="form-group">
                                        <p>Sponsorship &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $data['sponsorship']->package_name }}</p>
                                        <p> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['sponsorship']->payment_type_name }}</p>
                                        <p> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $data['sponsorship']->amount }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3" id="stud_info">
                        <div class="card-header">
                        <b>Waris/Guardian Info</b>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Waris/Guardian Name</th>
                                                    <th colspan="2" style="text-align: center">Phone No.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['waris'] as $key => $wrs)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $wrs->name }}</td>
                                                    <td style="text-align: center">{{ $wrs->home_tel }}</td>
                                                    <td style="text-align: center">{{ $wrs->phone_tel }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3" id="stud_info">
                        <div class="card-header">
                        <b>Payment Info</b>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    {{-- <div class="form-group">
                                        <p>TUNGGAKAN SEMESTER (RM) &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['total_balance']) ? number_format($data['total_balance'], 2) : 0.00 }}</p>
                                    </div> --}}
                                    <div class="form-group">
                                        <p>TUNGGAKAN SEMESTER (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['current_balance']) ? number_format($data['current_balance'], 2) : 0.00 }}</p>
                                    </div>
                                    <div class="form-group">
                                        <p>TUNGGAKAN PEMBIAYAAN KHAS (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['pk_balance']) ? number_format($data['pk_balance'], 2) : 0.00 }}</p>
                                    </div>
                                    <div class="form-group">
                                        <p>TUNGGAKAN KESULURUHAN (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ isset($data['total_all']) ? number_format($data['total_all'], 2) : 0.00 }}</p>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            @foreach($data['payment'] as $pym)
                                            <!-- Ensure the date and amount are in separate divs but in the same row -->
                                            <div class="col-md-6">
                                                <p>Last Payment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $pym->add_date }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Amount (RM) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; {{ $pym->amount }}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(!empty($data['remark']))
                                        <div class="card border-danger mb-3">
                                            <div class="card-header bg-danger text-white">
                                                Remark Details
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text mb-3"><strong>Category :</strong> {{ $data['remark']->name }}</p>
                                                <div class="row">
                                                    <div class="col-md-6">  
                                                        <div class="p-3 mb-2 bg-danger text-white rounded">
                                                            <p class="card-text"><strong>Correct Amount :</strong> {{ $data['remark']->correction_amount }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">  
                                                        <div class="p-3 mb-2 bg-danger text-white rounded">
                                                            <p class="card-text"><strong>Latest Balance :</strong> {{ $data['remark']->latest_balance }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">  
                                                        <div class="p-3 mb-2 bg-danger text-white rounded">
                                                            <p class="card-text"><strong>Notes :</strong> {{ $data['remark']->notes }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 d-flex">
                        <div class="col-md-12 mb-3">
                          <div class="pull-right">
                              <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                                  <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Log Claim
                              </a>
                          </div>
                        </div>
                    </div>

                    <table id="myTable" class="table table-striped projects display dataTable">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 10%">
                                    Date Contacted
                                </th>
                                <th style="width: 10%">
                                    Date Payment
                                </th>
                                <th style="width: 10%">
                                    Amount
                                </th>
                                <th style="width: 20%">
                                    Note
                                </th>
                                <th style="width: 5%">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="table">
                        @foreach ($data['log'] as $key=> $log)
                          <tr>
                            <td>
                                {{ $key+1 }}
                            </td>
                            <th>
                                {{ $log->date_of_call }}
                            </td>
                            <td>
                                {{ $log->date_of_payment }}
                            </td>
                            <td>
                                {{ $log->amount }}
                            </td>
                            <td>
                                {{ $log->note }}
                            </td>
                            <td class="project-actions text-right" style="text-align: center;">
                              <a class="btn btn-danger btn-sm" href="#" onclick="deleteLog('{{ $log->id }}')">
                                  <i class="ti-trash">
                                  </i>
                                  Delete
                              </a>
                            </td>
                          </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="uploadModal" class="modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- modal content-->
                        <div class="modal-content" id="getModal">
                            <form action="/finance/debt/claimLog/storeStudentLog/{{ request()->ic }}" method="post" role="form" enctype="multipart/form-data">
                              @csrf
                              @method('POST')
                              <div class="modal-header">
                                  <div class="">
                                      <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                          &times;
                                      </button>
                                  </div>
                              </div>
                              <div class="modal-body">
                                <div class="row col-md-12">
                                    <div>
                                        <div class="form-group">
                                            <label>Date Contacted</label>
                                            <input type="date" name="date1" id="date1" class="form-control">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label>Date Payment</label>
                                            <input type="date" name="date2" id="date2" class="form-control">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label>Amount Payment</label>
                                            <input type="number" name="payment" id="payment" step="0.01" class="form-control">
                                        </div>
                                    </div>
                                    <div id="note_add">
                                        <div class="form-group">
                                            <label class="form-label" for="note">Note</label>
                                            <select class="form-select" id="note" name="note[]" multiple>
                                              <option value="-" selected disabled>-</option>
                                              @foreach ($data['note'] as $nt)
                                              <option value="{{ $nt->name }}">{{ $nt->name }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                    </div>
                                </div>
                                <div class="row mb-3 d-flex">
                                    <div class="col-md-12 mb-3">
                                        <div class="pull-right">
                                            <a data-toggle="modal" data-target="#uploadModal2">
                                                Add Note
                                            </a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                  <div class="form-group pull-right">
                                      <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                                  </div>
                              </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="uploadModal2" class="modal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- modal content-->
                        <div class="modal-content" id="getModal">
                            <form action="/finance/debt/claimLog/storeNote" method="post" role="form" enctype="multipart/form-data">
                              @csrf
                              @method('POST')
                              <div class="modal-header">
                                  <div class="">
                                      <button class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                                          &times;
                                      </button>
                                  </div>
                              </div>
                              <div class="modal-body">
                                <div class="row col-md-12">
                                  <div>
                                    <div class="form-group">
                                      <label>Note</label>
                                      <input type="text" name="note" id="note" class="form-control">
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="form-label" style="text-align: center"><b>Notes</b></label>
                                        <table class="w-100 table table-bordered display margin-top-10 w-p100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Note Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['note'] as $key => $nt)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $nt->name }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                  <div class="form-group pull-right">
                                      <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                                  </div>
                              </div>
                            </form>
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
  </div>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

function deleteLog(id)
{

  Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('finance/debt/claimLog/deleteStudentLog') }}",
                  method   : 'POST',
                  data 	 : {id: id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert(data.message);
                      location.reload()
                  }
              });
          }
      });

}

</script>
@endsection
