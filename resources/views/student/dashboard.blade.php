@extends('layouts.student')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="row">
				<div class="col-xl-12 col-12">
          <div class="box bg-success">
            <div class="box-body d-flex p-0">
              <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md align-content-center" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                <div class="row">
                  <div class="col-12 col-xl-12">
                    <h1 class="mb-0 fw-600" style="text-align:center">Welcome to UCMS!</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteMaterial(ic){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/admin/delete') }}",
                  method   : 'DELETE',
                  data 	 : {ic:ic},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

</script>
@endsection
