@extends((Auth::user()->usrtype == "RGS") ? 'layouts.pendaftar' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : '')))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Student Result</h4>
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
                <h3 class="card-title">Student Result</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Student view result period</b>
                </div>
                {{-- <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label" for="from">FROM</label>
                        <input type="date" class="form-control" id="from" name="from" value="{{ ($data['range']->Start) ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label" for="name">TO</label>
                        <input type="date" class="form-control" id="to" name="to" value="{{ ($data['range']->End) ?? '' }}">
                        </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary pull-right mb-3" onclick="submit()">Submit</button>
                </div> --}}
              </div>
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                            <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label class="form-label" for="student">Student</label>
                              <select class="form-select" id="student" name="student">
                                <option value="-" selected disabled>-</option>
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

$('#search').keyup(function(event){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
        var searchTerm = $(this).val();
        getStudent(searchTerm);
    }
});

$('#student').on('change', function(){
    var selectedStudent = $(this).val();
    getStudInfo(selectedStudent);
});


function getStudent(search)
{

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/student/status/listStudent') }}",
            method   : 'POST',
            data 	 : {search: search},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                $('#student').html(data);
                $('#student').selectpicker('refresh');

            }
        });
    
}

function getStudInfo(student)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/student/result/getStudentResult') }}",
            method   : 'POST',
            data 	 : {student: student},
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

function submit()
{
   
  var from = $('#from').val();
  var to = $('#to').val();

  if(from != '' && to != '')
  {

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/student/result/storeResultPeriod') }}",
            method   : 'POST',
            data 	 : {from: from, to: to},
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                if(data.success)
                {
                  alert(data.success)
                }
            }
        });

  }else{

    alert('Please fill in the range period!');

  }

}
</script>
@endsection
