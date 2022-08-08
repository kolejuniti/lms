
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
                <h4 class="page-title">Other</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">Other</li>
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
                <h3 class="card-title">Other List</h3>
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
                              @foreach ($group as $grp)
                                <option value="{{ $grp->groupid }}">Group {{ $grp->groupname }}</option>
                              @endforeach
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
                                <th style="width: 1%">
                                  No.
                                </th>
                                <th style="width: 15%">
                                  Name
                                </th>
                                <th style="width: 5%">
                                  Matric No.
                                </th>
                                <th style="width: 20%">
                                  Submission Date
                                </th>
                                <th style="width: 15%">
                                  Attachment
                                </th>
                                <th style="width: 10%">
                                  Status Submission
                                </th>
                                <th style="width: 5%">
                                  Marks
                                </th>
                                <th style="width: 20%">
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($other as $key => $qz)

                              @if (count($status[$key]) > 0)
                                @php
                                  $alert = "badge bg-success";
                                @endphp
                              @else
                                @php
                                  $alert = "badge bg-danger";
                                @endphp
                              @endif
                              
                              <tr>
                                <td style="width: 1%">
                                    {{ $key+1 }}
                                </td>
                                <td style="width: 15%">
                                  <span class="{{ $alert }}">{{ $qz->name }}</span>
                                </td>
                                <td style="width: 5%">
                                  <span class="">{{ $qz->no_matric }}</span>
                                </td>
                                @if (count($status[$key]) > 0)
                                  @foreach ($status[$key] as $keys => $sts)
                                  <td style="width: 20%">
                                        {{ empty($sts) ? '-' : $sts->subdate}}
                                  </td>
                                  <td style="width: 5%">
                                    @if (empty($sts))
                                      -
                                    @else
                                      <a href="{{ Storage::disk('linode')->url($sts->content) }}"><i class="fa fa-file-pdf-o fa-3x"></i></a>
                                    @endif
                                </td>
                                  <td>
                                    @if (empty($sts))
                                    -
                                    @else
                                      @if ($sts->status_submission == 2)
                                        <span class="badge bg-danger">Late</span>
                                      @else
                                        <span class="badge bg-success">Submit</span>
                                      @endif
                                    @endif
                                  </td>
                                  <td>
                                        {{ empty($sts) ? '-' : $sts->final_mark }} / {{ $qz->total_mark }}
                                  </td>
                                  <td class="project-actions text-center" >
                                    <a class="btn btn-success btn-sm mr-2" href="/lecturer/other/{{ request()->other }}/{{ $sts->userid }}/result">
                                        <i class="ti-user">
                                        </i>
                                        Students
                                    </a>
                                  </td>                                               
                                  @endforeach
                                @else
                                  <td style="width: 20%">
                                    -
                                  </td>
                                  <td>
                                    -
                                  </td>
                                  <td>
                                  -
                                  </td> 
                                  <td>

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
    var selected_other = "{{ request()->other }}";

    $(document).ready( function () {
        $('#myTable').DataTable();

        
    } );

    $(document).on('change', '#group', function(e) {
        selected_group = $(e.target).val();

        getGroup(selected_group,selected_other);
    });

    function getGroup(group,other)
    {

      return $.ajax({
            headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
            url      : "{{ url('lecturer/other/getStatus') }}",
            method   : 'POST',
            data 	 : {group: group,other: other },
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