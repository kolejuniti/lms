@extends('../layouts.pendaftar')

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
                <h3 class="card-title">Status Report</h3>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="form-label" for="name">Date From</label>
                            <input type="date" class="form-control" id="from" name="from">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label class="form-label" for="name">Date To</label>
                            <input type="date" class="form-control" id="to" name="to">
                          </div>
                      </div>
                    </div>
                </div>
              </div>
              <div id="tbl_student">
                
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
var from = "";
var to = "";

$('#from').on('change', function(){

from = $(this).val();

getReport(from,to);

});

$('#to').on('change', function(){

to = $(this).val();

getReport(from,to);

});


function getReport(from,to)
{
    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('pendaftar/student/status/getReportStd') }}",
            method   : 'POST',
            data 	 : {from: from, to: to},
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


                $('#tbl_student').html(data);
            
                $('#table_dismissed').DataTable({
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

                $('#table_active').DataTable({
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


            }
        });
}

function submitForm(ic)
{
    var forminput = [];
    var formData = new FormData();

    forminput = {
        ic: ic,
        intake: $('#intake').val(),
        batch: $('#batch').val(),
        session: $('#session').val(),
        semester: $('#semester').val(),
        status: $('#status').val(),
        comment: editor.getData(),
      };

    formData.append('studentData', JSON.stringify(forminput));

    $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url: '{{ url('/pendaftar/student/status/storeStudent') }}',
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
                    alert("Success! Status & Student info has been updated!")
                    $('#complex_header').html(res.data);

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
</script>
@endsection
