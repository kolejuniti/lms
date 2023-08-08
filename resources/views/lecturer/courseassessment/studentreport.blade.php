
@extends((Auth::user()->usrtype == "LCT" && isset(request()->id)) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "LCT" ? 'layouts.lecturer' : (Auth::user()->usrtype == "PL" && isset(request()->id) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "AO" && isset(request()->id) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "ADM" ? 'layouts.admin' : (Auth::user()->usrtype == "DN" ? 'layouts.dekan' : ''))))))))


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
            @if(count($groups) > 0)
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
                              <thead>
                                <tr>
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
                                  @if (count($quiz[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'quiz']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall QUIZ ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall QUIZ (%)
                                    @endif
                                  </th>
                                  @endif
                                  
                                  <!--<th>
                                    TEST
                                  </th>-->
                                  @foreach ($test[$ky] as $key=>$qz)
                                  <th>
                                    TEST {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($test[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'test']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall TEST ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall TEST (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    ASSIGNMENT
                                  </th>-->
                                  @foreach ($assign[$ky] as $key=>$qz)
                                  <th>
                                    ASSIGNMENT {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($assign[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'assignment']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall ASSIGNMENT ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall ASSIGNMENT (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    EXTRA
                                  </th>-->
                                  @foreach ($extra[$ky] as $key=>$ex)
                                  <th>
                                    Extra {{ $key+1 }} : {{ $ex->title }} ({{ $ex->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($extra[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'extra']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall EXTRA ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall EXTRA (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    OTHER
                                  </th>-->
                                  @foreach ($other[$ky] as $key=>$ex)
                                  <th>
                                    Other {{ $key+1 }} : {{ $ex->title }} ({{ $ex->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($other[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'lain-lain']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall OTHER ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall OTHER (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    MIDTERM
                                  </th>-->
                                  @foreach ($midterm[$ky] as $key=>$ex)
                                  <th>
                                    Midterm {{ $key+1 }} : {{ $ex->title }} ({{ $ex->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($midterm[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'midterm']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall MIDTERM ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall MIDTERM (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    FINAL
                                  </th>-->
                                  @foreach ($final[$ky] as $key=>$ex)
                                  <th>
                                    Final {{ $key+1 }} : {{ $ex->title }} ({{ $ex->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($final[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $id],
                                    ['assessment', 'final']
                                    ])->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall FINAL ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall FINAL (%)
                                    @endif
                                  </th>
                                  @endif
                                  <th>
                                    OVERALL PERCENTAGE
                                  </th>
                                  <th>
                                    GRADE
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($students[$ky] as $key => $std)
                                <tr>
                                  <td>
                                    <a class="btn btn-success btn-sm mr-2" href="/lecturer/report/{{ $id }}/{{ $std->ic }}">{{ $std->name }}</a>
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
                                  
                                  @if (count($quiz[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassquiz')->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                                    ->where([
                                      ['tblclassquiz.classid', $id],
                                      ['tblclassquiz.sessionid', Session::get('SessionID')],
                                      ['tblclassquiz_group.groupname', $grp->group_name],
                                      ['tblclassquiz.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'quiz']
                                      ])->first() != null)
                                        @foreach ((array) $overallquiz[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
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
                                  
                                  @if (count($test[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest')->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                                    ->where([
                                      ['tblclasstest.classid', $id],
                                      ['tblclasstest.sessionid', Session::get('SessionID')],
                                      ['tblclasstest_group.groupname', $grp->group_name],
                                      ['tblclasstest.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'test']
                                      ])->first() != null)
                                        @foreach ((array) $overalltest[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
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
                                  
                                  @if (count($assign[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassassign')->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                                    ->where([
                                      ['tblclassassign.classid', $id],
                                      ['tblclassassign.sessionid', Session::get('SessionID')],
                                      ['tblclassassign_group.groupname', $grp->group_name],
                                      ['tblclassassign.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'assignment']
                                      ])->first() != null)
                                        @foreach ((array) $overallassign[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <!-- EXTRA -->

                                  @if (isset($extraanswer[$ky][$key]))
                                    @foreach ($extraanswer[$ky][$key] as $keys => $tsanswer)
                                      @if ($tsanswer != null)
                                      <td>
                                        <span >{{ $tsanswer->total_mark }}</span>
                                      </td>
                                      @elseif($tsanswer == null) 
                                      <td>
                                        <span >-</span>
                                      </td>
                                      @endif
                                    @endforeach
                                  @else
                                    @foreach ($extra[$ky] as $ts)
                                    <td>
                                      <span >-</span>
                                    </td> 
                                    @endforeach
                                  @endif
                                  
                                  @if (count($extra[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassextra')->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                                    ->where([
                                      ['tblclassextra.classid', $id],
                                      ['tblclassextra.sessionid', Session::get('SessionID')],
                                      ['tblclassextra_group.groupname', $grp->group_name],
                                      ['tblclassextra.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'extra']
                                      ])->first() != null)
                                        @foreach ((array) $overallextra[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <!-- OTHER -->

                                  @if (isset($otheranswer[$ky][$key]))
                                    @foreach ($otheranswer[$ky][$key] as $keys => $tsanswer)
                                      @if ($tsanswer != null)
                                      <td>
                                        <span >{{ $tsanswer->total_mark }}</span>
                                      </td>
                                      @elseif($tsanswer == null) 
                                      <td>
                                        <span >-</span>
                                      </td>
                                      @endif
                                    @endforeach
                                  @else
                                    @foreach ($other[$ky] as $ts)
                                    <td>
                                      <span >-</span>
                                    </td> 
                                    @endforeach
                                  @endif
                                  
                                  @if (count($other[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassother')->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                                    ->where([
                                      ['tblclassother.classid', $id],
                                      ['tblclassother.sessionid', Session::get('SessionID')],
                                      ['tblclassother_group.groupname', $grp->group_name],
                                      ['tblclassother.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'lain-lain']
                                      ])->first() != null)
                                        @foreach ((array) $overallother[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <!-- MIDTERM -->

                                  @if (isset($midtermanswer[$ky][$key]))
                                    @foreach ($midtermanswer[$ky][$key] as $keys => $tsanswer)
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
                                    @foreach ($midterm[$ky] as $ts)
                                    <td>
                                      <span >-</span>
                                    </td> 
                                    @endforeach
                                  @endif
                                  
                                  @if (count($midterm[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassmidterm')->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                                    ->where([
                                      ['tblclassmidterm.classid', $id],
                                      ['tblclassmidterm.sessionid', Session::get('SessionID')],
                                      ['tblclassmidterm_group.groupname', $grp->group_name],
                                      ['tblclassmidterm.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'midterm']
                                      ])->first() != null)
                                        @foreach ((array) $overallmidterm[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <!-- FINAL -->

                                  @if (isset($finalanswer[$ky][$key]))
                                    @foreach ($finalanswer[$ky][$key] as $keys => $tsanswer)
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
                                    @foreach ($final[$ky] as $ts)
                                    <td>
                                      <span >-</span>
                                    </td> 
                                    @endforeach
                                  @endif
                                  
                                  @if (count($final[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassfinal')->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                                    ->where([
                                      ['tblclassfinal.classid', $id],
                                      ['tblclassfinal.sessionid', Session::get('SessionID')],
                                      ['tblclassfinal_group.groupname', $grp->group_name],
                                      ['tblclassfinal.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'final']
                                      ])->first() != null)
                                        @foreach ((array) $overallfinal[$ky][$key] as $ag)
                                        <td style="background-color: #677ee2">
                                          <span >{{ $ag }}</span>
                                        </td> 
                                        @endforeach
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif
                                  
                                  <td >
                                    <span >{{ $overallall[$ky][$key] }}%</span>
                                  </td> 
                                  <td>
                                    <span >{{ $valGrade[$ky][$key] }}</span>
                                  </td>
                                </tr> 
                                @endforeach
                              </tbody>
                              <tbody>
                                <tr>
                                  <td>

                                  </td>
                                  <td>

                                  </td>
                                  <td  style="text-align-last: right">
                                    Average Mark :
                                  </td>
                                  @foreach ($quiz[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $quizavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($quiz[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassquiz')->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                                    ->where([
                                      ['tblclassquiz.classid', $id],
                                      ['tblclassquiz.sessionid', Session::get('SessionID')],
                                      ['tblclassquiz_group.groupname', $grp->group_name],
                                      ['tblclassquiz.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'quiz']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $quizavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($test[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $testavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest')->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                                    ->where([
                                      ['tblclasstest.classid', $id],
                                      ['tblclasstest.sessionid', Session::get('SessionID')],
                                      ['tblclasstest_group.groupname', $grp->group_name],
                                      ['tblclasstest.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'test']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $testavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($assign[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $assignavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($assign[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassassign')->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                                    ->where([
                                      ['tblclassassign.classid', $id],
                                      ['tblclassassign.sessionid', Session::get('SessionID')],
                                      ['tblclassassign_group.groupname', $grp->group_name],
                                      ['tblclassassign.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'assignment']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $assignavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($extra[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $extraavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($extra[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassextra')->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                                    ->where([
                                      ['tblclassextra.classid', $id],
                                      ['tblclassextra.sessionid', Session::get('SessionID')],
                                      ['tblclassextra_group.groupname', $grp->group_name],
                                      ['tblclassextra.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'extra']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $extraavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($other[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $otheravg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($other[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassother')->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                                    ->where([
                                      ['tblclassother.classid', $id],
                                      ['tblclassother.sessionid', Session::get('SessionID')],
                                      ['tblclassother_group.groupname', $grp->group_name],
                                      ['tblclassother.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'other']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $otheravgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($midterm[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $midtermavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($midterm[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassmidterm')->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                                    ->where([
                                      ['tblclassmidterm.classid', $id],
                                      ['tblclassmidterm.sessionid', Session::get('SessionID')],
                                      ['tblclassmidterm_group.groupname', $grp->group_name],
                                      ['tblclassmidterm.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'midterm']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $midtermavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($final[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $finalavg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($final[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassfinal')->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                                    ->where([
                                      ['tblclassfinal.classid', $id],
                                      ['tblclassfinal.sessionid', Session::get('SessionID')],
                                      ['tblclassfinal_group.groupname', $grp->group_name],
                                      ['tblclassfinal.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'final']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $finalavgoverall }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <td>
                                    {{ $avgoverall }}%
                                  </td>
                                  <td>
                                    
                                  </td>
                                </tr>
                                <tr>
                                  <td>

                                  </td>
                                  <td>

                                  </td>
                                  <td style="text-align-last: right">
                                    Maximum Mark :
                                  </td>
                                  @foreach ($quiz[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $quizmax[$ky][$keyss] }}
                                  </td>
                                  @endforeach
                                  
                                  @if (count($quiz[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassquiz')->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                                    ->where([
                                      ['tblclassquiz.classid', $id],
                                      ['tblclassquiz.sessionid', Session::get('SessionID')],
                                      ['tblclassquiz_group.groupname', $grp->group_name],
                                      ['tblclassquiz.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'quiz']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $quizcollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($test[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $testmax[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest')->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                                    ->where([
                                      ['tblclasstest.classid', $id],
                                      ['tblclasstest.sessionid', Session::get('SessionID')],
                                      ['tblclasstest_group.groupname', $grp->group_name],
                                      ['tblclasstest.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'test']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $testcollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($assign[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $assignmax[$ky][$keyss] }}
                                  </td>
                                  @endforeach
                                  
                                  @if (count($assign[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassassign')->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                                    ->where([
                                      ['tblclassassign.classid', $id],
                                      ['tblclassassign.sessionid', Session::get('SessionID')],
                                      ['tblclassassign_group.groupname', $grp->group_name],
                                      ['tblclassassign.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'assignment']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $assigncollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($extra[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $extramax[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($extra[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassextra')->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                                    ->where([
                                      ['tblclassextra.classid', $id],
                                      ['tblclassextra.sessionid', Session::get('SessionID')],
                                      ['tblclassextra_group.groupname', $grp->group_name],
                                      ['tblclassextra.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'extra']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $extracollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($other[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $othermax[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($other[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassother')->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                                    ->where([
                                      ['tblclassother.classid', $id],
                                      ['tblclassother.sessionid', Session::get('SessionID')],
                                      ['tblclassother_group.groupname', $grp->group_name],
                                      ['tblclassother.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'other']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $othercollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($midterm[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $midtermmax[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($midterm[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassmidterm')->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                                    ->where([
                                      ['tblclassmidterm.classid', $id],
                                      ['tblclassmidterm.sessionid', Session::get('SessionID')],
                                      ['tblclassmidterm_group.groupname', $grp->group_name],
                                      ['tblclassmidterm.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'midterm']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $midtermcollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($final[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $finalmax[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($final[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassfinal')->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                                    ->where([
                                      ['tblclassfinal.classid', $id],
                                      ['tblclassfinal.sessionid', Session::get('SessionID')],
                                      ['tblclassfinal_group.groupname', $grp->group_name],
                                      ['tblclassfinal.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'final']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $finalcollection->max() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <td>
                                    {{ max($overallall[$ky]) }}%
                                  </td>
                                  <td>
                                    
                                  </td>
                                </tr>
                                <tr>
                                  <td>

                                  </td>
                                  <td>

                                  </td>
                                  <td  style="text-align-last: right">
                                    Minimum Mark :
                                  </td>
                                  @foreach ($quiz[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $quizmin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($quiz[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassquiz')->join('tblclassquiz_group', 'tblclassquiz.id', 'tblclassquiz_group.quizid')
                                    ->where([
                                      ['tblclassquiz.classid', $id],
                                      ['tblclassquiz.sessionid', Session::get('SessionID')],
                                      ['tblclassquiz_group.groupname', $grp->group_name],
                                      ['tblclassquiz.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'quiz']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $quizcollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($test[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $testmin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest')->join('tblclasstest_group', 'tblclasstest.id', 'tblclasstest_group.testid')
                                    ->where([
                                      ['tblclasstest.classid', $id],
                                      ['tblclasstest.sessionid', Session::get('SessionID')],
                                      ['tblclasstest_group.groupname', $grp->group_name],
                                      ['tblclasstest.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'test']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $testcollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($assign[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $assignmin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($assign[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassassign')->join('tblclassassign_group', 'tblclassassign.id', 'tblclassassign_group.assignid')
                                    ->where([
                                      ['tblclassassign.classid', $id],
                                      ['tblclassassign.sessionid', Session::get('SessionID')],
                                      ['tblclassassign_group.groupname', $grp->group_name],
                                      ['tblclassassign.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'assignment']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $assigncollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($extra[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $extramin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($extra[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassextra')->join('tblclassextra_group', 'tblclassextra.id', 'tblclassextra_group.extraid')
                                    ->where([
                                      ['tblclassextra.classid', $id],
                                      ['tblclassextra.sessionid', Session::get('SessionID')],
                                      ['tblclassextra_group.groupname', $grp->group_name],
                                      ['tblclassextra.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'extra']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $extracollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($other[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $othermin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($other[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassother')->join('tblclassother_group', 'tblclassother.id', 'tblclassother_group.otherid')
                                    ->where([
                                      ['tblclassother.classid', $id],
                                      ['tblclassother.sessionid', Session::get('SessionID')],
                                      ['tblclassother_group.groupname', $grp->group_name],
                                      ['tblclassother.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'other']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $othercollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($midterm[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $midtermmin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($midterm[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassmidterm')->join('tblclassmidterm_group', 'tblclassmidterm.id', 'tblclassmidterm_group.midtermid')
                                    ->where([
                                      ['tblclassmidterm.classid', $id],
                                      ['tblclassmidterm.sessionid', Session::get('SessionID')],
                                      ['tblclassmidterm_group.groupname', $grp->group_name],
                                      ['tblclassmidterm.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'midterm']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $midtermcollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  @foreach ($final[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $finalmin[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($final[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclassfinal')->join('tblclassfinal_group', 'tblclassfinal.id', 'tblclassfinal_group.finalid')
                                    ->where([
                                      ['tblclassfinal.classid', $id],
                                      ['tblclassfinal.sessionid', Session::get('SessionID')],
                                      ['tblclassfinal_group.groupname', $grp->group_name],
                                      ['tblclassfinal.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $id],
                                      ['assessment', 'final']
                                      ])->first() != null)
                                        <td style="background-color: #677ee2">{{ $finalcollection->min() }}</td>
                                      @else
                                      <td style="background-color: #677ee2">
                                        <span >0</span>
                                      </td> 
                                      @endif
                                    @else
                                    <td style="background-color: #677ee2">
                                      <span >0</span>
                                    </td> 
                                    @endif
                                  @endif

                                  <td>
                                    {{ min($overallall[$ky]) }}%
                                  </td>
                                  <td>

                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <script>
                $(document).ready( function () {
                    $('#myTable{{$grp->group_name}}').DataTable({
                      dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                      
                      buttons: [
                          {
                            text: 'Excel',
                            action: function () {
                              getExcel('{{$grp->group_name}}');
                            }
                          }
                      ]

                    });
                } );
              </script>
              <script>
                function getExcel(group) {
                  // get the HTML table to export
                  const table = document.getElementById("myTable" + group);
                  
                  // create a new Workbook object
                  const wb = XLSX.utils.book_new();
                  
                  // add a new worksheet to the Workbook object
                  const ws = XLSX.utils.table_to_sheet(table);
                  XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                  
                  // trigger the download of the Excel file
                  XLSX.writeFile(wb, "exported-data.xlsx");
                };
              </script>
              @endforeach
            @else
              <div class="box bg-danger">
                <div class="box-body d-flex p-0">
                  <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                    <div class="row">
                      <div class="col-12 col-xl-12">
                        <h1 class="mb-0 fw-600">No Group available.</h1>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
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
                    $('#myTable').DataTable( {
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                footer: true, // Enable footer callback function
                            }
                        ]
                    } );
                    //$('#group').selectpicker('refresh');
            }
        });

    }


</script>
@stop