@extends('layouts.student')

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

  /* Responsive styles for mobile devices */
  @media (max-width: 768px) {
    .student-info-item {
      display: flex;
      flex-direction: column;
      margin-bottom: 8px;
    }
    
    .info-label {
      font-weight: bold;
      margin-bottom: 2px;
      color: #333;
    }
    
    .info-value {
      color: #666;
      word-break: break-word;
    }
    
    .table-responsive {
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    
    .card {
      margin-bottom: 1rem !important;
    }
    
    .card-body {
      padding: 0.75rem;
    }
    
    .content-wrapper {
      padding: 0.5rem;
    }
    
    .breadcrumb {
      font-size: 0.875rem;
    }
    
    /* Make button more mobile friendly */
    #printButton {
      margin-top: 10px;
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .student-info-item {
      font-size: 0.9rem;
    }
    
    .card-header h3 {
      font-size: 1.1rem;
    }
    
    .page-title {
      font-size: 1.5rem;
    }
    
    .table th, .table td {
      font-size: 0.8rem;
      padding: 0.5rem 0.25rem;
    }
    
    /* Improve table readability on very small screens */
    .table-responsive table {
      min-width: 600px;
    }
  }

  /* General improvements for all screen sizes */
  .student-info-item {
    margin-bottom: 0.5rem;
  }
  
  .info-label {
    display: inline-block;
    min-width: 120px;
    font-weight: 600;
  }
  
  .info-value {
    display: inline-block;
  }
  
  @media (min-width: 769px) {
    .student-info-item {
      display: block;
    }
  }
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
              <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-0">Student Statement</h3>
                <button id="printButton" class="waves-effect waves-light btn btn-primary btn-sm">
                  <i class="ti-printer"></i>&nbsp Print
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card mb-3">
                <div class="card-body">
                  <div id="form-student">
                  @include('student.affair.statement.statementGetStudent')
                  </div>
                  <div class="row" id="confirm-card" hidden>
                    <div class="col-md-12 mt-3 text-center">
                        <div class="form-group mt-3">
                          <button type="submit" class="btn btn-primary mb-3" onclick="confirm()">Confirm</button>
                        </div>
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
            url      : "{{ url('finance/report/statement/getStudent') }}",
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

$(document).ready(function() {
    $('#printButton').on('click', function(e) {
      e.preventDefault();
      printReport();
    });
  });

  function printReport() {
    var student = "{{ $data['student']->ic }}";

    return $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      url: "{{ url('finance/report/statement/getStudent?print=true') }}",
      method: 'POST',
      data: { student: student},
      error: function(err) {
        alert("Error");
        console.log(err);
      },
      success: function(data) {
        var newWindow = window.open();
        newWindow.document.write(data);
        newWindow.document.close();
      }
    });
  }

</script>
@endsection
