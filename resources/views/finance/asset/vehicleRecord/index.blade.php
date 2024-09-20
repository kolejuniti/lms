@extends('layouts.finance')

@section('main')
<style>
  .modal-custom-width .modal-dialog {
    max-width: 80%; /* Adjust percentage or fixed width as needed */
}
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Vehicle & Service Records</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                <li class="breadcrumb-item active" aria-current="page">Vehicle & Service Records</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Vehicle & Service Records</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3 d-flex">
            <div class="col-md-12 mb-3">
                <div class="pull-right">
                    <a type="button" class="waves-effect waves-light btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fa fa-plus"></i> <i class="fa fa-object-group"></i> &nbsp Add Vehicle
                    </a>
                </div>
            </div>
        </div>
        </div>
        <div class="card-body p-0">
          <table id="myTable" class="table table-striped projects display dataTable">
            <thead>
                <tr>
                    <th style="width: 1%">
                        No.
                    </th>
                    <th style="width: 20%">
                        Brand
                    </th>
                    <th style="width: 10%">
                        Model
                    </th>
                    <th style="width: 10%">
                        Year
                    </th>
                    <th style="width: 10%">
                        Registration No.
                    </th>
                    <th style="width: 10%">
                        Roadtax Due Date
                    </th>
                    <th style="width: 20%">
                    </th>
                </tr>
            </thead>
            <tbody id="table">
            @foreach ($data['vehicles'] as $key=> $vec)
              <tr>
                <td style="width: 1%">
                  {{ $key+1 }}
                </td>
                <td style="width: 20%">
                  {{ $vec->brand }}
                </td>
                <td style="width: 10%">
                  {{ $vec->model }}
                </td>
                <td style="width: 10%">
                  {{ $vec->year }}
                </td>
                <td style="width: 10%">
                  {{ $vec->registration_number }}
                </td>
                <td>
                  {{ $vec->date_of_roadtax }}
                </td>
                <td class="project-actions text-right" style="text-align: center;">
                  <a class="btn btn-info btn-sm btn-sm mr-2" href="#" onclick="updateVehicle('{{ $vec->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Edit
                  </a>
                  <a class="btn btn-primary btn-sm btn-sm mr-2" href="#" onclick="serviceRecord('{{ $vec->id }}')">
                      <i class="ti-pencil-alt">
                      </i>
                      Record
                  </a>
                  <a class="btn btn-danger btn-sm" href="#" onclick="deleteVehicle('{{ $vec->id }}')">
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
        <!-- /.card-body -->
        <div id="uploadModal" class="modal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal">
                  <form action="/finance/asset/vehicleRecord/storeVehicle" method="post" role="form" enctype="multipart/form-data">
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
                              <label class="form-label" for="type">Car Type</label>
                              <select class="form-select" id="type" name="type">
                              <option value="-" selected disabled>-</option>
                              <option value="Sedan">Sedan</option>
                              <option value="SUV">SUV</option>
                              <option value="Hatchback">Hatchback</option>
                              <option value="Coupe">Coupe</option>
                              <option value="Convertible">Convertible</option>
                              <option value="Wagon">Wagon</option>
                              <option value="Pickup Truck">Pickup Truck</option>
                              <option value="Crossover">Crossover</option>
                              <option value="Luxury Car">Luxury Car</option>
                              <option value="Sports Car">Sports Car</option>
                              <option value="Diesel Car">Diesel Car</option>
                              <option value="Electric Car">Electric Car</option>
                              <option value="Hybrid Car">Hybrid Car</option>
                              <option value="Off-Road Vehicle">Off-Road Vehicle</option>
                              <option value="Microcar">Microcar</option>
                              <option value="Roadster">Roadster</option>
                              <option value="Limousine">Limousine</option>
                              <option value="Muscle Car">Muscle Car</option>
                              <option value="Compact Car">Compact Car</option>
                              <option value="Subcompact Car">Subcompact Car</option>
                              <option value="MPV">MPV</option>
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="brand">Car Brand</label>
                              <select class="form-select" id="brand" name="brand">
                              <option value="-" selected disabled>-</option>
                              <option value="Proton">Proton</option>
                              <option value="Perodua">Perodua</option>
                              <option value="Honda">Honda</option>
                              <option value="Toyota">Toyota</option>
                              <option value="Nissan">Nissan</option>
                              <option value="Mazda">Mazda</option>
                              <option value="Mitsubishi">Mitsubishi</option>
                              <option value="Ford">Ford</option>
                              <option value="Hyundai">Hyundai</option>
                              <option value="Kia">Kia</option>
                              <option value="BMW">BMW</option>
                              <option value="Mercedes-Benz">Mercedes-Benz</option>
                              <option value="Audi">Audi</option>
                              <option value="Volkswagen">Volkswagen</option>
                              <option value="Volvo">Volvo</option>
                              <option value="Peugeot">Peugeot</option>
                              <option value="Subaru">Subaru</option>
                              <option value="Suzuki">Suzuki</option>
                              <option value="Chevrolet">Chevrolet</option>
                              <option value="Lexus">Lexus</option>
                              <option value="Jaguar">Jaguar</option>
                              <option value="Land Rover">Land Rover</option>
                              <option value="Jeep">Jeep</option>
                              <option value="Porsche">Porsche</option>
                              <option value="Mini">Mini</option>
                              <option value="Ferrari">Ferrari</option>
                              <option value="Bentley">Bentley</option>
                              <option value="Lamborghini">Lamborghini</option>
                              <option value="Rolls-Royce">Rolls-Royce</option>
                              <option value="Maserati">Maserati</option>
                              <option value="Bugatti">Bugatti</option>
                              <option value="McLaren">McLaren</option>
                              <option value="Aston Martin">Aston Martin</option>
                              <option value="Alfa Romeo">Alfa Romeo</option>
                              <option value="Lotus">Lotus</option>
                              <option value="Fiat">Fiat</option>
                              <option value="Citroen">Citroen</option>
                              <option value="Renault">Renault</option>
                              <option value="Daihatsu">Daihatsu</option>
                              <option value="Isuzu">Isuzu</option>
                              <option value="Ssangyong">Ssangyong</option>
                              <option value="Chery">Chery</option>
                              <option value="Geely">Geely</option>
                              <option value="Great Wall">Great Wall</option>
                              <option value="Changan">Changan</option>
                              <option value="BYD">BYD</option>
                              <option value="Haval">Haval</option>
                              <option value="Datsun">Datsun</option>
                              <option value="Infiniti">Infiniti</option>
                              <option value="Lancia">Lancia</option>
                              <option value="Smart">Smart</option>
                              <option value="Saab">Saab</option>
                              <option value="Hummer">Hummer</option>
                              <option value="Saturn">Saturn</option>
                              <option value="Pontiac">Pontiac</option>
                              <option value="Oldsmobile">Oldsmobile</option>
                              <option value="Mercury">Mercury</option>
                              <option value="Lincoln">Lincoln</option>
                              <option value="GMC">GMC</option>
                              <option value="Cadillac">Cadillac</option>
                              <option value="Buick">Buick</option>
                              <option value="Acura">Acura</option>
                              <option value="Scion">Scion</option>
                              <option value="Plymouth">Plymouth</option>
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                              <label class="form-label" for="year">Year</label>
                              <select class="form-select" id="year" name="year">
                              <option value="-" selected disabled>-</option>
                              @foreach($data['year'] as $yr)
                              <option value="{{ $yr }}">{{ $yr }}</option>
                              @endforeach
                              </select>
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" id="model" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Colour</label>
                            <input type="text" name="colour" id="colour" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Registration No.</label>
                            <input type="text" name="reNo" id="reNo" class="form-control">
                          </div>
                        </div>
                        <div>
                          <div class="form-group">
                            <label>Roadtax Due Date</label>
                            <input type="date" name="roadtax" id="roadtax" class="form-control">
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

        <div id="uploadModal2" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <!-- modal content-->
              <div class="modal-content" id="getModal2">
              </div>
          </div>
        </div>    
        
        <div id="uploadModal3" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg"> <!-- Add modal-lg or your custom class -->
              <!-- modal content-->
              <div class="modal-content" id="getModal3">
              </div>
          </div>
        </div> 
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Page specific script -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteVehicle(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/finance/asset/vehicleRecord/deleteVehicle') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      Swal.fire('Success', data.success, 'success');
                      window.location.reload();
                  }
              });
          }
      });
  }

  function deleteRecord(id){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/finance/asset/vehicleRecord/deleteRecord') }}",
                  method   : 'DELETE',
                  data 	 : {id:id},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      Swal.fire('Success', data.success, 'success');
                      getService(data.id);
                  }
              });
          }
      });
  }

</script>

<script>
     $(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );
  </script>

  <script type="text/javascript">

  function updateVehicle(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/asset/vehicleRecord/updateVehicle') }}",
            method   : 'GET',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal2').html(data);
                $('#uploadModal2').modal('show');
            }
        });
  }
  
  function serviceRecord(id)
  {
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/asset/vehicleRecord/serviceRecord') }}",
            method   : 'GET',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#getModal3').html(data);
                $('#uploadModal3').modal('show');
                getService(id);
            }
        });
  }

  function storeService(id)
  {
    var idS = id;

    var forminput = [];
    var formData = new FormData();

    forminput = {
      id: id,
      date: $('#date').val(),
      meter: $('#meter').val(),
      address: $('#address').val(),
      amount: $('#amount').val(),
      note: $('#note').val(),
      checkboxes: [] 
    };

    // Collect all checked checkboxes with the class 'filled-in'
    $("input:checkbox.filled-in:checked").each(function(){
        var checkboxId = this.id; // Get the checkbox id
        var textareaId = `#${checkboxId}d`; // Construct the corresponding textarea id
        var textareaValue = $(textareaId).val(); // Get the value of the textarea

        // Push both checkbox value and corresponding textarea value to the array
        forminput.checkboxes.push({
            checkboxValue: $(this).val(),
            textareaValue: textareaValue
        });
    });


    // Example: adding formData if needed
    formData.append('formData', JSON.stringify(forminput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/finance/asset/vehicleRecord/storeService') }}',
        type: 'POST',
        data: formData,
        cache : false,
        processData: false,
        contentType: false,
        error:function(err){
            console.log(err);
        },
        success:function(res){
            try{
                if(res.status == "success"){
                    alert("Success! Service details has been added!");

                    getService(idS);

                    // $('#voucher_table').html(res.data);

                    // if (res.exists && res.exists.length > 0) {
                    //     var existingVouchers = res.exists.join(', ');
                    //     alert('These vouchers already exist: ' + existingVouchers);
                    // }
                    
                    // $('#voucher_table').DataTable();
                    
                }else{
                    $('.error-field').html('');
                    if(res.message == "Field Error"){
                        for (f in res.error) {
                            $('#'+f+'_error').html(res.error[f]);
                        }
                    }
                    else if(res.message == "Please fill all required field!"){
                        alert(res.message);
                    }
                    else{
                        alert(res.message);
                    }
                    $("html, body").animate({ scrollTop: 0 }, "fast");
                }
            }catch(err){
                alert("Ops sorry, there is an error");
            }
        }
    });

  }

  function getService(id)
  {

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/asset/vehicleRecord/getServiceList') }}",
            method   : 'GET',
            data 	 : {id: id},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#service_list').html(data);
            }
        });

  }

  </script>
@endsection
