
@extends('layouts.lecturer.lecturer')

@section('main')

<style>
    .cke_chrome{
        border:1px solid #eee;
        box-shadow: 0 0 0 #eee;
    }

    div.dt-buttons {
    float: right;
    margin-left:10px;
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
            @foreach ($groups as $ky => $grp)
            <div class="box">
              <div class="card-header mb-4">
                <h3 class="card-title">Student List : Group {{ $grp->group_name }}</h3>
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                      <div id = "status">
                        <div class="col-sm-12">
                          <table id="myTable{{$grp->group_name}}" class="table table-striped projects display dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="complex_header_info">
                            <script>
                              $(document).ready( function () {
                                  $('#myTable{{$grp->group_name}}').DataTable({
                                    dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                                    
                                    buttons: [
                                        'copy', 'csv', 'excel'
                                    ],
                                  });
                              } );
                            </script>
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
                                  QUIZ
                                </th>-->
                                @foreach ($quiz[$ky] as $key=>$qz)
                                <th>
                                  QUIZ {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  @php
                                  $markpercen = DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'quiz']
                                  ])->first();
                                  @endphp
                                  @if ($markpercen != null)
                                  Overall QUIZ ({{ $markpercen->mark_percentage }}%)
                                  @else
                                  Overall QUIZ (%)
                                  @endif
                                </th>
                                <!--<th>
                                  TEST
                                </th>-->
                                @foreach ($test[$ky] as $key=>$qz)
                                <th>
                                  TEST {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  @php
                                  $markpercen = DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'test']
                                  ])->first();
                                  @endphp
                                  @if ($markpercen != null)
                                  Overall Test ({{ $markpercen->mark_percentage }}%)
                                  @else
                                  Overall Test (%)
                                  @endif
                                </th>
                                <!--<th>
                                  ASSIGNMENT
                                </th>-->
                                @foreach ($assign[$ky] as $key=>$qz)
                                <th>
                                  ASSIGNMENT {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  @php
                                  $markpercen = DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'assignment']
                                  ])->first();
                                  @endphp
                                  @if ($markpercen != null)
                                  Overall ASSIGNMENT ({{ $markpercen->mark_percentage }}%)
                                  @else
                                  Overall ASSIGNMENT (%)
                                  @endif
                                </th>
                                <!--<th>
                                  EXTRA
                                </th>-->
                                @foreach ($extra[$ky] as $key=>$qz)
                                <th>
                                  EXTRA {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                </th>
                                @endforeach
                                <th >
                                  @php
                                  $markpercen = DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'extra']
                                  ])->first();
                                  @endphp
                                  @if ($markpercen != null)
                                  Overall EXTRA ({{ $markpercen->mark_percentage }}%)
                                  @else
                                  Overall EXTRA (%)
                                  @endif
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($students[$ky] as $key => $std)
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
                            
                                <!-- QUIZ -->

                                @if (isset($quizanswer[$ky][$key]))
                                  @foreach ($quizanswer[$ky][$key] as $keys => $qzanswer)
                                    @if ($qzanswer != null)
                                    <td>
                                      <span >{{ $qzanswer->final_mark }}</span>
                                    </td>
                                    @elseif($qzanswer == null) 
                                    <td>
                                      <span >-</span>
                                    </td>
                                    @endif
                                  @endforeach
                                @else
                                  @foreach ($quiz[$ky] as $qz)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @if ($groupcheck = DB::table('tblclassquiz')->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                                ->where([
                                  ['tblclassquiz.classid', request()->id],
                                  ['tblclassquiz.sessionid', Session::get('SessionID')],
                                  ['tblclassquiz_group.groupname', $grp->group_name]
                                ])->exists())
                                  @if(DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'quiz']
                                  ])->first() != null)
                                    @foreach ((array) $overallquiz[$ky][$key] as $ag)
                                    <td>
                                      <span >{{ $ag }}</span>
                                    </td> 
                                    @endforeach
                                  @else
                                  <td>
                                    <span >0</span>
                                  </td> 
                                  @endif
                                @else
                                <td>
                                  <span >0</span>
                                </td> 
                                @endif

                                <!-- TEST -->

                                @if (isset($testanswer[$ky][$key]))
                                  @foreach ($testanswer[$ky][$key] as $keys => $tsanswer)
                                    @if ($tsanswer != null)
                                    <td>
                                      <span >{{ $tsanswer->final_mark }}</span>
                                    </td>
                                    @elseif($tsanswer == null) 
                                    <td>
                                      <span >-</span>
                                    </td>
                                    @endif
                                  @endforeach
                                @else
                                  @foreach ($test[$ky] as $ts)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @if ($groupcheck = DB::table('tblclasstest')->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                                ->where([
                                  ['tblclasstest.classid', request()->id],
                                  ['tblclasstest.sessionid', Session::get('SessionID')],
                                  ['tblclasstest_group.groupname', $grp->group_name]
                                ])->exists())
                                  @if(DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'test']
                                  ])->first() != null)
                                    @foreach ((array) $overalltest[$ky][$key] as $ag)
                                    <td>
                                      <span >{{ $ag }}</span>
                                    </td> 
                                    @endforeach
                                  @else
                                  <td>
                                    <span >0</span>
                                  </td> 
                                  @endif
                                @else
                                <td>
                                  <span >0</span>
                                </td> 
                                @endif

                                <!-- ASSIGNMENT -->

                                @if (isset($assignanswer[$ky][$key]))
                                  @foreach ($assignanswer[$ky][$key] as $keys => $aganswer)
                                    @if ($aganswer != null)
                                    <td>
                                      <span >{{ $aganswer->final_mark }}</span>
                                    </td>
                                    @elseif($aganswer == null) 
                                    <td>
                                      <span >-</span>
                                    </td>
                                    @endif
                                  @endforeach
                                @else
                                  @foreach ($assign[$ky] as $ag)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @if ($groupcheck = DB::table('tblclassassign')->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                                ->where([
                                  ['tblclassassign.classid', request()->id],
                                  ['tblclassassign.sessionid', Session::get('SessionID')],
                                  ['tblclassassign_group.groupname', $grp->group_name]
                                ])->exists())
                                  @if(DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'assignment']
                                  ])->first() != null)
                                    @foreach ((array) $overallassign[$ky][$key] as $ag)
                                    <td>
                                      <span >{{ $ag }}</span>
                                    </td> 
                                    @endforeach
                                  @else
                                  <td>
                                    <span >0</span>
                                  </td> 
                                  @endif
                                @else
                                <td>
                                  <span >0</span>
                                </td> 
                                @endif

                                <!-- EXTRA -->

                                @if (isset($extraanswer[$ky][$key]))
                                  @foreach ($extraanswer[$ky][$key] as $keys => $qzanswer)
                                    @if ($qzanswer != null)
                                    <td>
                                      <span >{{ $qzanswer->final_mark }}</span>
                                    </td>
                                    @elseif($qzanswer == null) 
                                    <td>
                                      <span >-</span>
                                    </td>
                                    @endif
                                  @endforeach
                                @else
                                  @foreach ($extra[$ky] as $qz)
                                  <td>
                                    <span >-</span>
                                  </td> 
                                  @endforeach
                                @endif
                                
                                @if ($groupcheck = DB::table('tblclassextra')->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                                ->where([
                                  ['tblclassextra.classid', request()->id],
                                  ['tblclassextra.sessionid', Session::get('SessionID')],
                                  ['tblclassextra_group.groupname', $grp->group_name]
                                ])->exists())
                                  @if(DB::table('tblclassmarks')->where([
                                  ['course_id', request()->id],
                                  ['assessment', 'extra']
                                  ])->first() != null)
                                    @foreach ((array) $overallextra[$ky][$key] as $ag)
                                    <td>
                                      <span >{{ $ag }}</span>
                                    </td> 
                                    @endforeach
                                  @else
                                  <td>
                                    <span >0</span>
                                  </td> 
                                  @endif
                                @else
                                <td>
                                  <span >0</span>
                                </td> 
                                @endif
                              
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
            @endforeach         
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