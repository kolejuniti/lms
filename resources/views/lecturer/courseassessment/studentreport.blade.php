
@extends('layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Report</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="card-header mb-4">
                <h3 class="card-title">Student List</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div class="col-md-2 mb-4">
                        <div class="form-group">
                          <label class="form-label" for="group">Group</label>
                          <select class="form-select" id="group" name="group" required>
                              <option value="" selected disabled>-</option>
                       
                          </select>
                          <span class="text-danger">@error('folder')
                            {{ $message }}
                          @enderror</span>
                        </div>
                      </div>
                      <div id = "status">
                        <div class="col-sm-12">
                          <table id="myTable" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                            <thead>
                              <tr>
                                <th >
                                  No.
                                </th>
                                <th >
                                  Name
                                </th>
                                <th >
                                  Matric No.
                                </th>
                                <th >
                                  Group Name
                                </th>
                                <!--<th>
                                  Action
                                </th>-->
                                @foreach ($quiz as $key=>$qz)
                                <th>
                                  QUIZ {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall Quiz (%)
                                </th>
                                @foreach ($test as $key=>$ts)
                                <th>
                                  TEST {{ $key+1 }} : {{ $ts->title }} ({{ $ts->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall TEST (%)
                                </th>
                                @foreach ($assign as $key=>$as)
                                <th>
                                  Assignment {{ $key+1 }} : {{ $as->title }} ({{ $as->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall Assignment (%)
                                </th>
                                @foreach ($midterm as $key=>$qz)
                                <th>
                                  MIDTERM {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall Midterm (%)
                                </th>
                                @foreach ($final as $key=>$qz)
                                <th>
                                  FINAL {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall Final (%)
                                </th>
                                @foreach ($paperwork as $key=>$pw)
                                <th>
                                  PAPERWORK {{ $key+1 }} : {{ $pw->title }} ({{ $pw->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall PAPERWORK (%)
                                </th>
                                @foreach ($practical as $key=>$qz)
                                <th>
                                  PRACTICAL {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall PRACTICAL (%)
                                </th>
                                @foreach ($other as $key=>$qz)
                                <th>
                                  OTHER {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  Overall OTHER (%)
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($students as $key => $std)
                              <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                  <a class="btn btn-success btn-sm mr-2" href="/lecturer/report/{{ request()->id }}/{{ $std->ic }}">{{ $std->name }}</a>
                                </td>
                                <td>
                                  <span >{{ $std->no_matric }}</span>
                                </td>
                                <td>
                                  <span >{{ $std->group_name }}</span>
                                </td>
                                <!--<td>
                                  <a class="btn btn-success btn-sm mr-2" href="/lecturer/report/{{ request()->id }}/{{ $std->ic }}">
                                      <i class="ti-user">
                                      </i>
                                      Report
                                  </a>
                                </td>--> 
                                @if (count($quizanswer[$key]) > 0)
                                  @foreach ($quizanswer[$key] as $keys => $qzanswer)
                                  <td>
                                    <span >{{ $qzanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($quizanswer[$key]) < count($quiz))
                                    @for ($i=count($quizanswer[$key]); $i < count($quiz); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($quiz as $qz)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @foreach ((array) $overallquiz[$key] as $qz)
                                <td>
                                  <span >{{ round($qz) }}</span>
                                </td> 
                                @endforeach

                                @if (count($testanswer[$key]) > 0)
                                @foreach ($testanswer[$key] as $keys => $tsanswer)
                                <td>
                                  <span >{{ $tsanswer->final_mark }}</span>
                                </td> 
                                @endforeach
                                @if (count($testanswer[$key]) < count($test))
                                  @for ($i=count($testanswer[$key]); $i < count($test); $i++)
                                    <td>
                                      <span >-</span>
                                    </td>
                                  @endfor
                                @endif
                                @else
                                  @foreach ($test as $ts)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                              
                                @foreach ((array) $overalltest[$key] as $ts)
                                <td>
                                  <span >{{ round($ts) }}</span>
                                </td> 
                                @endforeach

                                @if (count($assignanswer[$key]) > 0)
                                  @foreach ($assignanswer[$key] as $keys => $asanswer)
                                  <td>
                                    <span >{{ $asanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($assignanswer[$key]) < count($assign))
                                    @for ($i=count($assignanswer[$key]); $i < count($assign); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($assign as $as)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @foreach ((array) $overallassign[$key] as $as)
                                <td>
                                  <span >{{ round($as) }}</span>
                                </td> 
                                @endforeach
                                
                                @if (count($midtermanswer[$key]) > 0)
                                  @foreach ($midtermanswer[$key] as $keys => $qzanswer)
                                  <td>
                                    <span >{{ $qzanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($midtermanswer[$key]) < count($midterm))
                                    @for ($i=count($midtermanswer[$key]); $i < count($midterm); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($midterm as $qz)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif

                                @foreach ((array) $overallmidterm[$key] as $qz)
                                <td>
                                  <span >{{ round($qz) }}</span>
                                </td> 
                                @endforeach

                                @if (count($finalanswer[$key]) > 0)
                                  @foreach ($finalanswer[$key] as $keys => $qzanswer)
                                  <td>
                                    <span >{{ $qzanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($finalanswer[$key]) < count($final))
                                    @for ($i=count($finalanswer[$key]); $i < count($final); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($final as $qz)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif

                                @foreach ((array) $overallfinal[$key] as $qz)
                                <td>
                                  <span >{{ round($qz) }}</span>
                                </td> 
                                @endforeach


                                @if (count($paperworkanswer[$key]) > 0)
                                  @foreach ($paperworkanswer[$key] as $keys => $pwanswer)
                                  <td>
                                    <span >{{ $pwanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($paperworkanswer[$key]) < count($paperwork))
                                    @for ($i=count($paperworkanswer[$key]); $i < count($paperwork); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($paperwork as $pw)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif

                                @foreach ((array) $overallpaperwork[$key] as $pw)
                                <td>
                                  <span >{{ round($pw) }}</span>
                                </td> 
                                @endforeach


                                @if (count($practicalanswer[$key]) > 0)
                                  @foreach ($practicalanswer[$key] as $keys => $pranswer)
                                  <td>
                                    <span >{{ $pranswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($practicalanswer[$key]) < count($practical))
                                    @for ($i=count($practicalanswer[$key]); $i < count($practical); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($practical as $pr)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif

                                @foreach ((array) $overallpractical[$key] as $pr)
                                <td>
                                  <span >{{ round($pr) }}</span>
                                </td> 
                                @endforeach


                                @if (count($otheranswer[$key]) > 0)
                                  @foreach ($otheranswer[$key] as $keys => $otanswer)
                                  <td>
                                    <span >{{ $otanswer->final_mark }}</span>
                                  </td> 
                                  @endforeach
                                  @if (count($otheranswer[$key]) < count($other))
                                    @for ($i=count($otheranswer[$key]); $i < count($other); $i++)
                                      <td>
                                        <span >-</span>
                                      </td>
                                    @endfor
                                  @endif
                                @else
                                  @foreach ($other as $ot)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif

                                @foreach ((array) $overallother[$key] as $ot)
                                <td>
                                  <span >{{ round($ot) }}</span>
                                </td> 
                                @endforeach

                                
                              </tr> 
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
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
<!-- /.content-wrapper -->
<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">
    var selected_group = "";
    var selected_quiz = "{{ request()->quiz }}";

    $(document).ready( function () {
        $('#myTable').DataTable();

        
    } );

    $(document).on('change', '#group', function(e) {
        selected_group = $(e.target).val();

        getGroup(selected_group,selected_quiz);
    });

    function getGroup(group,quiz)
    {

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/quiz/getStatus') }}",
            method   : 'POST',
            data 	 : {group: group,quiz: quiz },
            error:function(err){
                alert("Error");
                console.log(err);
            },
            success  : function(data){
                
                //$('#lecturer-selection-div').removeAttr('hidden');
                //$('#lecturer').selectpicker('refresh');
      
                //$('#chapter').removeAttr('hidden');
                    $('#status').html(data);
                    $('#myTable').DataTable();
                    //$('#group').selectpicker('refresh');
            }
        });

    }

</script>
@stop