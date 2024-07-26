@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : 'layouts.ketua_program')))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Meeting Hour</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item" aria-current="page">Dashboard</li>
              <li class="breadcrumb-item active" aria-current="page">Meeting Hour</li>
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
                <h3 class="card-title">Subject Meeting Hour</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Program</b>
                </div>
                <div class="card-body">
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
                    </div>
                </div>
              </div>
              <div id="form-student">
                
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>
<script type="text/javascript">

$('#program').on('change', function(){
    var selected_program = $(this).val();
    getSubject(selected_program);
});

function getSubject(id)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('KP/assign/getMeetingHour') }}",
            method   : 'POST',
            data 	 : {id: id},
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


                $('#form-student').html(data);
            
                $('#complex_header').DataTable({
                  dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                  
                  buttons: [
                    {
                        extend: 'excelHtml5',
                        messageTop: output,
                        title: 'Excel' + '-' + output,
                        text:'Export to excel'
                        //Columns to export
                        //exportOptions: {
                       //     columns: [0, 1, 2, 3,4,5,6]
                       // }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'PDF' + '-' + output,
                        text: 'Export to PDF'
                        //Columns to export
                        //exportOptions: {
                       //     columns: [0, 1, 2, 3, 4, 5, 6]
                      //  }
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

function submitForm()
{

  $.ajax({
      headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
      url: "{{ url('KP/assign/submitMeetingHour') }}", // Update with your route
      method: 'POST',
      data: $('#subjectForm').serialize(),
      success: function(response) {
          alert('Data updated successfully');
      },
      error: function(xhr, status, error) {
          console.log(xhr.responseText);
      }
  });
};
  

// function submitForm2()
// {
//     var forminput = [];
//     var formData = new FormData();

//     forminput = {
//         ic: ic,
//         intake: $('#intake').val(),
//         batch: $('#batch').val(),
//         session: $('#session').val(),
//         semester: $('#semester').val(),
//         status: $('#status').val(),
//         kuliah: $('#kuliah').val(),
//         comment: editor.getData(),
//       };

//     if(forminput.status == '' || forminput.comment == '')
//     {

//       alert('Please fill in Student Status & Comment before submit!')

//     }else{

//       formData.append('studentData', JSON.stringify(forminput));

//       $.ajax({
//           headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
//           url: '{{ url('/pendaftar/student/status/storeStudent') }}',
//           type: 'POST',
//           data: formData,
//           cache : false,
//           processData: false,
//           contentType: false,
//           error:function(err){
//               console.log(err);
//           },
//           success:function(res){
//               try{
//                   if(res.message == "Success"){
//                       alert("Success! Status & Student info has been updated!")
//                       $('#complex_header').html(res.data);
                      
//                   }else{
//                       $('.error-field').html('');
//                       if(res.message == "Field Error"){
//                           for (f in res.error) {
//                               $('#'+f+'_error').html(res.error[f]);
//                           }
//                       }
//                       else if(res.message == "Group code already existed inside the system"){
//                           $('#classcode_error').html(res.message);
//                       }
//                       else{
//                           alert(res.message);
//                       }
//                       $("html, body").animate({ scrollTop: 0 }, "fast");
//                   }
//               }catch(err){
//                   alert("Ops sorry, there is an error");
//               }
//           }
//       });

//     }

// }
</script>
@endsection
