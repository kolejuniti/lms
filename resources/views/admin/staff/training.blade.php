@extends('../layouts.admin')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Course And Training</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Course And Training</li>
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
                <h3 class="card-title">Course And Training</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Staff</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label class="form-label" for="name">Name / No. IC / No. Staff</label>
                            <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="user">Staff</label>
                              <select class="form-select" id="user" name="user">
                                <option value="-" selected disabled>-</option>
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div id="form-user">
                
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>

    <div id="uploadModal" class="modal" class="modal fade" role="dialog">
      <div class="modal-dialog">
          <!-- modal content-->
          <div class="modal-content" id="getModal">
              <form action="/admin/training/updateTrainingData" method="post" role="form" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
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
                          <label class="form-label" for="name">Course Name</label>
                          <input type="text" class="form-control" id="name2" name="name2" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                          <label class="form-label" for="organizer">organizer</label>
                          <input type="text" class="form-control" id="organizer2" name="organizer2" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="type2">Training Mode</label>
                            <select class="form-select" id="type2" name="type2">
                            <option value="-" selected disabled>-</option>
                            <option value="Indoor Program">Indoor Program</option>
                            <option value="Outdoor Program">Outdoor Program</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="start_date2">Start Date</label>
                          <input type="date" class="form-control" id="start_date2" name="start_date2" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="end_date2">End Date</label>
                          <input type="date" class="form-control" id="end_date2" name="end_date2" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="start_time2">Start Time</label>
                          <input type="time" class="form-control" id="start_time2" name="start_time2" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="end_time2">End Time</label>
                          <input type="time" class="form-control" id="end_time2" name="end_time2" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="year2">Year</label>
                          <input type="text" class="form-control" id="year2" name="year2" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label class="form-label" for="cpd2">CPD Point</label>
                          <input type="text" class="form-control" id="cpd2" name="cpd2" required>
                        </div>
                    </div>
                    <div class="col-md-6" hidden>
                      <div class="form-group">
                        <input type="text" class="form-control" id="id" name="id" required>
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

    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getUser(searchTerm);
    }
});

$('#user').on('change', function(){
    var selectUser = $(this).val();
    getUserInfo(selectUser);
});


function getUser(search)
{

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('admin/training/getUserList') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#user').html(data);
                $('#user').selectpicker('refresh');

            }
        });
    
}

function getUserInfo(user)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('admin/training/getUserInfo') }}",
            method   : 'POST',
            data 	 : {user: user},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
              var d = new Date();

              var month = d.getMonth()+1;
              var day = d.getDate();

              var output = d.getFullYear() + '/' +
                  (month<10 ? '0' : '') + month + '/' +
                  (day<10 ? '0' : '') + day;


                $('#form-user').html(data);
            
                $('#complex_header').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                      {
                          extend: 'pdfHtml5',
                          title: 'Training' + ' - ' + users,
                          text: 'Report',
                          exportOptions: {
                              columns: ':not(:last-child)' // Exclude the last column (Edit/Delete)
                          },
                          customize: function (doc) {
                              // Center the title
                              doc.content[1].text = doc.content[1].text;
                              doc.content[1].alignment = 'center';

                              // Format the current date as dd/mm/yyyy
                              var currentDate = new Intl.DateTimeFormat('en-GB').format(new Date());

                              // Add the formatted current date to the right side of the header
                              doc.content.unshift({
                                  text: currentDate,
                                  alignment: 'right',
                                  margin: [0, 10, 10, 0], // Top, right margin
                                  style: 'header'
                              });
                          }
                      }
                  ],



                });
                //$('#student').selectpicker('refresh');

                "use strict";
                ClassicEditor
                .create( document.querySelector( '#commenttxt' ),{ height: '25em' } )
                .then(newEditor =>{editor = newEditor;})
                .catch( error => { console.log( error );});
            }
        });
}

function submitForm(ic)
{
    var forminput = [];
    var formData = new FormData();

    forminput = {
        ic: ic,
        name: $('#name').val(),
        organizer: $('#organizer').val(),
        type: $('#type').val(),
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        start_time: $('#start_time').val(),
        end_time: $('#end_time').val(),
        year: $('#year').val(),
        cpd: $('#cpd').val(),
        // comment: editor.getData(),
      };

    if(forminput.name == '' || forminput.type == '' || forminput.start_date == '' || forminput.end_date == '' || forminput.start_time == '' || forminput.end_time == '' || forminput.year == '')
    {

      alert('Please fill in the form to submit!')

    }else{

      formData.append('userData', JSON.stringify(forminput));

      $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url: '{{ url('/admin/training/storeUserTraining') }}',
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
                  if(res.message == "Success"){
                      alert("Success! User Training has been added!")
                      $('#complex_header').html(res.data);
                      
                  }else{
                      $('.error-field').html('');
                      if(res.message == "Field Error"){
                          for (f in res.error) {
                              $('#'+f+'_error').html(res.error[f]);
                          }
                      }
                      else if(res.message == "Group code already existed inside the system"){
                          $('#classcode_error').html(res.message);
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

}

function deletedtl(id, ic)
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
                  url      : "{{ url('admin/training/deleteUserTraining') }}",
                  method   : 'POST',
                  data 	 : {id: id, ic: ic},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      alert("success");
                      $('#complex_header').html(data);
                  }
              });
          }
      });

}

function updateTraining(id)
{

  return $.ajax({
          headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
          url      : "{{ url('admin/training/getTrainingData') }}",
          method   : 'GET',
          data 	 : {id: id},
          error:function(err){
              alert("Error");
              console.log(err);
          },
          success  : function(data){
              $('#name2').val(data.training_name);
              $('#organizer2').val(data.organizer);
              $('#type2').val(data.training_type).change();
              $('#start_date2').val(data.start_date);
              $('#end_date2').val(data.end_date);
              $('#start_time2').val(data.start_time);
              $('#end_time2').val(data.end_time);
              $('#year2').val(data.year);
              $('#cpd2').val(data.cpd_point);
              $('#id').val(data.id);
              $('#uploadModal').modal('show');
          }
      });

}
</script>
@endsection
