@extends('layouts.lecturer.lecturer')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
  <!-- Content Header (Page header) -->	  
  <div class="content-header">
    <div class="d-flex align-items-center">
      <div class="me-auto">
        <h4 class="page-title">Library</h4>
        <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Library</li>
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
                <h3 class="card-title">Library</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body d-flex">
                  <div class="box-body col-4" style="border: 1px solid blue;margin-right: 20px;">
                    <table class="table" id="myTable">
                      <tbody class="table-body">
                          @foreach ($lecturer as $key => $lct)
                          <tr class="cell-1" data-toggle="collapse" data-target="#demo-{{ $lct->ic }}">
                              <td>
                                  <i class="ti-book" style="margin-right: 5px;"></i> 
                                  {{ $lct->name }}
                              </td>
                          </tr>
                          <tr id="demo-{{ $lct->ic }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-"  onclick="getContent('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 40px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Course Content
                                </div>
                            </td>
                          </tr>
                          <tr id="demo-{{ $lct->ic }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-assessment-{{ $key }}">
                              <td>
                                  <div style="margin-left: 40px;">
                                      <i class="ti-folder" style="margin-right: 5px;"></i>
                                      Asessment
                                  </div>
                              </td>
                          </tr>
                          <tr id="demo-assessment-{{ $key }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-" onclick="getQuiz('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 60px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Quiz
                                </div>
                            </td>
                          </tr>
                          <tr id="demo-assessment-{{ $key }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-" onclick="getTest('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 60px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Test
                                </div>
                            </td>
                          </tr>
                          <tr id="demo-assessment-{{ $key }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-" onclick="getAssignment('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 60px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Assignment
                                </div>
                            </td>
                          </tr>
                          <tr id="demo-assessment-{{ $key }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-" onclick="getMidterm('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 60px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Midterm
                                </div>
                            </td>
                          </tr>
                          <tr id="demo-assessment-{{ $key }}" class="collapse cell-1 row-child" data-toggle="collapse" data-target="#demo-" onclick="getFinal('{{ $lct->ic }}')">
                            <td>
                                <div style="margin-left: 60px;">
                                    <i class="ti-folder" style="margin-right: 5px;"></i>
                                    Final
                                </div>
                            </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="box-body col-md-8 mt-2">
                    <div id="showMaterial">

                    </div>
                  </div>
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

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
$(document).ready( function () {
        $('#myTable').DataTable({
          dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
          
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
        });
    } );

function getContent(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getFolder') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

  
function tryerr(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

  
  //alert(id);
}

function tryerr2(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder/getSubfolder2') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function tryerr3(id)
{
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getSubfolder/getSubfolder2/getMaterial') }}",
        method   : 'POST',
        data 	 : {id: id},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });
}

function getQuiz(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getQuiz') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getTest(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getTest') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getAssignment(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getAssignment') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getMidterm(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getMidterm') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

function getFinal(ic)
{
  //alert(id);
  return $.ajax({
        headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
        url      : "{{ url('lecturer/library/getFinal') }}",
        method   : 'POST',
        data 	 : {ic: ic},
        error:function(err){
            alert("Error");
            console.log(err);
        },
        success  : function(data){
       
          $('#showMaterial').html(data);
          $('html, body').animate({ scrollTop: 0 });
          //$('#chapter').selectpicker('refresh');
        }
    });

}

</script>
@endsection
