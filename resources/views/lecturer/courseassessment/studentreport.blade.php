
@extends((Auth::user()->usrtype == "LCT" && isset(request()->id)) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "LCT" ? 'layouts.lecturer' : (Auth::user()->usrtype == "PL" && isset(request()->id) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "PL" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "AO" && isset(request()->id) ? 'layouts.lecturer.lecturer' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "ADM" ? 'layouts.admin' : (Auth::user()->usrtype == "DN" ? 'layouts.dekan' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : '')))))))))


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

    /* File icon colors */
    .text-purple {
        color: #6f42c1 !important;
    }
    
    /* File icon styling */
    .fa-file-pdf-o { color: #dc3545 !important; }
    .fa-file-word-o { color: #007bff !important; }
    .fa-file-excel-o { color: #28a745 !important; }
    .fa-file-powerpoint-o { color: #ffc107 !important; }
    .fa-file-text-o { color: #6c757d !important; }
    .fa-file-archive-o { color: #17a2b8 !important; }
    .fa-file-image-o { color: #6f42c1 !important; }
    .fa-file-o { color: #6c757d !important; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="page-header">
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

    @php

    $sub_id = DB::table('subjek')->where('id', $id)->value('sub_id');

    @endphp

    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="box bg-success">
              <div class="box-body d-flex p-0">
                  <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                      <div class="row">
                          <div class="col-12 col-xl-12">
                              <h1 class="mb-0 fw-600">{{ Auth::user()->name }}</h1>
                              <p class="my-10 fs-16"><strong>Subject : {{ $data['lectInfo']->course_name }}</strong> </p>
                              <p class="my-10 fs-16"><strong>Code : {{ $data['lectInfo']->course_code }}</strong> </p>
                              <p class="my-10 fs-16"><strong>Session : {{ $data['lectInfo']->session }}</strong> </p>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'quiz']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'test']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall TEST ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall TEST (%)
                                    @endif
                                  </th>
                                  @endif

                                  <!--<th>
                                    TEST2
                                  </th>-->
                                  @foreach ($test2[$ky] as $key=>$qz)
                                  <th>
                                    TEST 2 {{ $key+1 }} : {{ $qz->title }} ({{ $qz->total_mark }})
                                  </th>
                                  @endforeach
                                  @if (count($test2[$ky]) > 0)
                                  <th >
                                    @php
                                    $markpercen = DB::table('tblclassmarks')->where([
                                    ['course_id', $sub_id],
                                    ['assessment', 'test2']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
                                    @endphp
                                    @if ($markpercen != null)
                                    Overall TEST 2 ({{ $markpercen->mark_percentage }}%)
                                    @else
                                    Overall TEST 2 (%)
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'assignment']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'extra']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'lain-lain']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'midterm']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                    ['course_id', $sub_id],
                                    ['assessment', 'final']
                                    ])
                                    ->orderBy('tblclassmarks.id', 'desc')
                                    ->first();
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'quiz']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'test']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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

                                  <!-- TEST2 -->

                                  @if (isset($test2answer[$ky][$key]))
                                    @foreach ($test2answer[$ky][$key] as $keys => $tsanswer)
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
                                    @foreach ($test2[$ky] as $ts)
                                    <td>
                                      <span >-</span>
                                    </td> 
                                    @endforeach
                                  @endif
                                  
                                  @if (count($test2[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest2')->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                                    ->where([
                                      ['tblclasstest2.classid', $id],
                                      ['tblclasstest2.sessionid', Session::get('SessionID')],
                                      ['tblclasstest2_group.groupname', $grp->group_name],
                                      ['tblclasstest2.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $sub_id],
                                      ['assessment', 'test2']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
                                        @foreach ((array) $overalltest2[$ky][$key] as $ag)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'assignment']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'extra']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'lain-lain']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'midterm']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'final']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                    <span >{{ $overallall2[$ky][$key] }}%</span>
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'quiz']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'test']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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

                                  @foreach ($test2[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $test2avg[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test2[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest2')->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                                    ->where([
                                      ['tblclasstest2.classid', $id],
                                      ['tblclasstest2.sessionid', Session::get('SessionID')],
                                      ['tblclasstest2_group.groupname', $grp->group_name],
                                      ['tblclasstest2.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $sub_id],
                                      ['assessment', 'test2']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
                                        <td style="background-color: #677ee2">{{ $test2avgoverall }}</td>
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'assignment']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'extra']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'lain-lain']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'midterm']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'final']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'quiz']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'test']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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

                                  @foreach ($test2[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $test2max[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test2[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest2')->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                                    ->where([
                                      ['tblclasstest2.classid', $id],
                                      ['tblclasstest2.sessionid', Session::get('SessionID')],
                                      ['tblclasstest2_group.groupname', $grp->group_name],
                                      ['tblclasstest2.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $sub_id],
                                      ['assessment', 'test2']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
                                        <td style="background-color: #677ee2">{{ $test2collection->max() }}</td>
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'assignment']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'extra']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'lain-lain']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'midterm']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'final']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'quiz']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'test']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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

                                  @foreach ($test2[$ky] as $keyss => $qz)
                                  <td>
                                    {{ $test2min[$ky][$keyss] }}
                                  </td>
                                  @endforeach

                                  @if (count($test2[$ky]) > 0)
                                    @if ($groupcheck = DB::table('tblclasstest2')->join('tblclasstest2_group', 'tblclasstest2.id', 'tblclasstest2_group.testid')
                                    ->where([
                                      ['tblclasstest2.classid', $id],
                                      ['tblclasstest2.sessionid', Session::get('SessionID')],
                                      ['tblclasstest2_group.groupname', $grp->group_name],
                                      ['tblclasstest2.status', '!=', 3]
                                    ])->exists())
                                      @if(DB::table('tblclassmarks')->where([
                                      ['course_id', $sub_id],
                                      ['assessment', 'test2']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
                                        <td style="background-color: #677ee2">{{ $test2collection->min() }}</td>
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'assignment']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'extra']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'lain-lain']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'midterm']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                                      ['course_id', $sub_id],
                                      ['assessment', 'final']
                                      ])
                                      ->orderBy('tblclassmarks.id', 'desc')
                                      ->first() != null)
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
                    // $('#myTable{{$grp->group_name}}').DataTable({
                    //   dom: 'lBfrtip', // if you remove this line you will see the show entries dropdown
                      
                    //   buttons: [
                    //       {
                    //         text: 'Excel',
                    //         action: function () {
                    //           getExcel('{{$grp->group_name}}');
                    //         }
                    //       }
                    //   ]

                    // });

                    $('#myTable{{ $grp->group_name }}').DataTable({
                        dom: 'lBfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: function () {
                                    return `{{ Auth::user()->name }} - {{ $data['lectInfo']->course_name }} - {{ $data['lectInfo']->course_code }} - {{ $data['lectInfo']->session }}`;
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

        <!-- Lecturer Documents Section -->
        <div class="row">
          <div class="col-12">
            <div class="box">
              <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-folder-open"></i> Lecturer Documents (Rubrik/Rowscore/Others)</h4>
              </div>
              <div class="box-body">
                <div class="row mb-20">
                  <div class="col-md-12">
                    <!-- Upload Form -->
                    <form id="materialUploadForm" enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                        <div class="col-md-4">
                          <label>Category</label>
                          <select class="form-control" name="category" id="materialCategory" required>
                            <option value="">Select Category</option>
                            <option value="Rubrik">Rubrik</option>
                            <option value="Rowscore">Rowscore</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label>Description (Optional)</label>
                          <input type="text" class="form-control" name="description" id="materialDescription" placeholder="Brief description of the materials">
                        </div>
                        <div class="col-md-2">
                          <label>&nbsp;</label>
                          <button type="button" class="btn btn-primary btn-block" id="uploadBtn">
                            <i class="fa fa-upload"></i> Upload Files
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Dropzone Area -->
                <div class="row mb-20">
                  <div class="col-md-12">
                    <div id="materialDropzone" class="dropzone" style="border: 2px dashed #007bff; border-radius: 10px; text-align: center; padding: 40px; background-color: #f8f9fa; cursor: pointer;">
                      <div class="dz-message">
                        <i class="fa fa-cloud-upload fa-3x text-muted mb-10"></i>
                        <h4 class="text-muted">Drop files here or click to upload</h4>
                        <p class="text-muted">Support for PDF, Word, Excel, PowerPoint, Images, Text & Archive files (Max 10MB per file)</p>
                      </div>
                    </div>
                    <input type="file" id="fileInput" multiple style="display: none;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z,.jpg,.jpeg,.png,.gif,.bmp">
                  </div>
                </div>

                <!-- Progress Bar -->
                <div class="row mb-20" id="uploadProgress" style="display: none;">
                  <div class="col-md-12">
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%"></div>
                    </div>
                  </div>
                </div>

                <!-- Materials List -->
                <div class="row">
                  <div class="col-md-12">
                    <h5><i class="fa fa-list"></i> Uploaded Materials</h5>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover" id="materialsTable">
                        <thead class="bg-light">
                          <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Upload Date</th>
                            <th>Description</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody id="materialsTableBody">
                          <!-- Materials will be loaded here -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Lecturer Documents Section -->

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

    // Lecturer Materials Management
    $(document).ready(function() {
        loadMaterials();

        // Dropzone click handler
        $('#materialDropzone, #uploadBtn').click(function() {
            $('#fileInput').click();
        });

        // Drag and drop handlers
        $('#materialDropzone').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('bg-light');
        }).on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('bg-light');
        }).on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('bg-light');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $('#fileInput')[0].files = files;
                uploadFiles();
            }
        });

        // File input change handler
        $('#fileInput').change(function() {
            if (this.files.length > 0) {
                uploadFiles();
            }
        });

        function uploadFiles() {
            const category = $('#materialCategory').val();
            const description = $('#materialDescription').val();
            const files = $('#fileInput')[0].files;

            if (!category) {
                alert('Please select a category first');
                return;
            }

            if (files.length === 0) {
                alert('Please select files to upload');
                return;
            }

            const formData = new FormData();
            formData.append('category', category);
            formData.append('description', description);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Show progress bar
            $('#uploadProgress').show();
            $('#progressBar').css('width', '0%');

            $.ajax({
                url: '{{ route("lecturer.materials.upload") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = evt.loaded / evt.total * 100;
                            $('#progressBar').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    $('#uploadProgress').hide();
                    $('#progressBar').css('width', '0%');
                    
                    if (response.success) {
                        alert(response.message);
                        $('#materialUploadForm')[0].reset();
                        $('#fileInput').val('');
                        loadMaterials();
                    } else {
                        alert('Upload failed: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#uploadProgress').hide();
                    $('#progressBar').css('width', '0%');
                    alert('Upload failed: ' + error);
                }
            });
        }

        function loadMaterials() {
            $.ajax({
                url: '{{ route("lecturer.materials.get") }}',
                type: 'GET',
                success: function(response) {
                    const tbody = $('#materialsTableBody');
                    tbody.empty();

                    if (response.materials && response.materials.length > 0) {
                        response.materials.forEach(function(material, index) {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${getFileIcon(material.file_type)} ${material.file_name}</td>
                                    <td><span class="badge badge-info">${material.category}</span></td>
                                    <td>${formatFileSize(material.file_size)}</td>
                                    <td>${formatDate(material.created_at)}</td>
                                    <td>${material.description || '-'}</td>
                                    <td>
                                        <a href="{{ url('lecturer/materials/download') }}/${material.id}" class="btn btn-sm btn-success" title="Download" target="_blank">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteMaterial(${material.id})" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-center text-muted">No materials uploaded yet</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to load materials:', error);
                }
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        function getFileIcon(fileType) {
            const extension = fileType.toLowerCase();
            let iconClass = '';
            let iconColor = '';

            switch (extension) {
                case 'pdf':
                    iconClass = 'fa fa-file-pdf-o';
                    iconColor = 'text-danger'; // Red for PDF
                    break;
                case 'doc':
                case 'docx':
                    iconClass = 'fa fa-file-word-o';
                    iconColor = 'text-primary'; // Blue for Word
                    break;
                case 'xls':
                case 'xlsx':
                    iconClass = 'fa fa-file-excel-o';
                    iconColor = 'text-success'; // Green for Excel
                    break;
                case 'ppt':
                case 'pptx':
                    iconClass = 'fa fa-file-powerpoint-o';
                    iconColor = 'text-warning'; // Orange for PowerPoint
                    break;
                case 'txt':
                    iconClass = 'fa fa-file-text-o';
                    iconColor = 'text-muted'; // Gray for text files
                    break;
                case 'zip':
                case 'rar':
                case '7z':
                    iconClass = 'fa fa-file-archive-o';
                    iconColor = 'text-info'; // Light blue for archives
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'bmp':
                    iconClass = 'fa fa-file-image-o';
                    iconColor = 'text-purple'; // Purple for images
                    break;
                default:
                    iconClass = 'fa fa-file-o';
                    iconColor = 'text-muted'; // Default gray
                    break;
            }

            return `<i class="${iconClass} ${iconColor}"></i>`;
        }

        // Global function for delete button
        window.deleteMaterial = function(materialId) {
            if (confirm('Are you sure you want to delete this material?')) {
                $.ajax({
                    url: '{{ route("lecturer.materials.delete") }}',
                    type: 'DELETE',
                    data: {
                        material_id: materialId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            loadMaterials();
                        } else {
                            alert('Delete failed: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Delete failed: ' + error);
                    }
                });
            }
        };
    });


</script>
@stop