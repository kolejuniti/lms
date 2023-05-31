@extends('../layouts.finance')

@section('main')
<style>
  @media print {

  @page {size: A4 landscape;max-height:100%; max-width:100%}

  /* use width if in portrait (use the smaller size to try 
    and prevent image from overflowing page... */
  img { height: 90%; margin: 0; padding: 0; }

  body{width:100%;
  height:100%;
  -webkit-transform: rotate(-90deg) scale(.68,.68); 
  -moz-transform:rotate(-90deg) scale(.58,.58) }    }
</style>
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Student Statement</h4>
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
              {{-- <div class="card-header">
                <h3 class="card-title">Student Statement</h3>
                <a type="button" class="waves-effect waves-light btn btn-primary btn-sm" onclick="printDiv('printableArea')">
                  <i class="ti-printer"></i>&nbsp Print
                </a>
              </div> --}}
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-header">
                  <b>Search Student</b>
                </div>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-3">
                          <div class="form-group">
                          <label class="form-label" for="refno">No. Rujukan</label>
                          <input type="text" class="form-control" id="refno" name="refno">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                          <label class="form-label" for="name">Name / No. IC / No. Matric</label>
                          <input type="text" class="form-control" id="search" placeholder="Search..." name="search">
                          </div>
                      </div>
                  </div>
                  <div id="form-student">
              
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card -->
            <div id="uploadModal2" class="modal" class="modal fade" role="dialog">
              <div class="modal-dialog">
                  <!-- modal content-->
                  <div class="modal-content" id="getModal2">
                    <form action="/finance/payment/cancel/confirm" method="post" role="form" enctype="multipart/form-data">
                      @csrf
                      @method('POST')
                      <div class="modal-header">
                        <div class="">
                          <button type="button" onclick="closeModal()" class="close waves-effect waves-light btn btn-danger btn-sm pull-right" data-dismiss="modal">
                              &times;
                          </button>
                        </div>
                      </div>
                      <div class="modal-body">
                        <div class="row col-md-12">
                          <div>
                            <div class="form-group">
                              <label for="exampleTextarea">Reason</label>
                              <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                            </div>
                          </div>
                          <div hidden>
                            <div class="form-group">
                              <input class="form-control" id="receiptID" name="receiptID" required>
                              <input class="form-control" id="typeID" name="typeID" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <div>
                          <div class="form-group pull-right">
                            <input type="submit" name="addtopic" class="form-controlwaves-effect waves-light btn btn-primary btn-sm pull-right" value="submit">
                          </div>
                        </div>
                      </div>
                    </form>
                  
                    <script>
                      function closeModal()
                      {
                        
                        $('#uploadModal2').modal('hide');
                    
                      }
                    </script>
                  </div>
              </div>
            </div>
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

var refno = '';
var search = '';
var cancel = 1;

  $(document).on('keyup', '#refno', async function(e){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
      refno = $(e.target).val();
      await getStudent(refno,search);
    } 
  });

  $(document).on('keyup', '#search', async function(e){
    if (event.keyCode === 13) { // 13 is the code for the "Enter" key
      search = $(e.target).val();
      await getStudent(refno,search);
    }
  });


function getStudent(refno,search)
{

    return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('finance/report/receiptlist/getReceiptList') }}",
            method   : 'POST',
            data 	 : {refno: refno,search: search,cancel: cancel},
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

function printDiv(divName) 
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}

function cancelTrans(id,type) 
{

  $('#receiptID').val(id);
  $('#typeID').val(type);
  $('#uploadModal2').modal('show');

}

</script>
@endsection
