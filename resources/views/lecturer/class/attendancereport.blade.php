@extends('layouts.lecturer.lecturer')

@section('main')
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		  <!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Attendance Reports</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Academics</li>
								<li class="breadcrumb-item" aria-current="page">Courses</li>
								<li class="breadcrumb-item active" aria-current="page">Subjects</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xl-12 col-12">
					<div class="box bg-success">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">GROUP {{ $group->group_name }}</h1>
										<p class="my-10 fs-16"><strong>Course :</strong> {{ $group->course_name }}</p>
										<p class="my-10 fs-16"><strong>Code :</strong> {{ $group->course_code }}</p>
										<p class="my-10 fs-16"><strong>Session :</strong> {{ $group->session_id }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">ATTENDANCE ( {{ $date }} )</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="attendance_report" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Name
												</th>
												<th style="width: 20%">
												IC
												</th>
												<th style="width: 20%">
												No Matric
												</th>
												<td style="width: 20%">
												Status
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach($students as $keys=>$std)
												<tr>
													<td>
													{{ $keys+1 }}
													</td>
													<td >
													{{ $std->name }}
													</td>
													<td>
													{{ $std->ic }}
													</td>
													<td>
													{{ $std->no_matric }}
													</td>
													@if (count($lists[$keys]) > 0)
													<td>
														<span class="badge bg-success">Checked</span>
													</td>
													@else
													<td>
														<span class="badge bg-danger">Absent</span>
													</td>
													@endif
												</tr>
											@endforeach
											</tbody>
											<tfoot class="tfoot-themed">
												<tr>
													
												</tr>
											</tfoot>
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

  <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
  <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
  <script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
  <script>
	$(document).ready(function() {
		CreateTable('attendance_report');  
	});

  </script>
  <script>
	  function CreateTable(divid) {
		  
		  var w_table 		= [];
		  var export_table 	= [ 0,1,2,3,4 ];
		  
		  if ( $.fn.dataTable.isDataTable( '#' + divid ) ) {
			  return false;
		  }
  
		  $('#' + divid).dataTable({
			  responsive: true,
			  columnDefs: [{ width:"5%", targets: [0,2] }, { width:"20%", targets: [3] }],
			  dom:
				  "<'row mb-3'<'col-sm-6 col-md-3 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-3 d-flex align-items-center justify-content-start'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
				  "<'row'<'col-sm-12'tr>>" +
				  "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
			  buttons: [
				  {
					  extend: 'excelHtml5',
					  text: 'Excel',
					  titleAttr: 'Generate Excel',
					  className: 'waves-effect waves-light btn btn-outline btn-sm  btn-primary-light  mr-1',
					  exportOptions: {
						  columns: export_table
					  }
				  }
			  ],
			  order: [[ 0, "asc" ]],
			  pageLength: 100,
			  drawCallback: function() {
			  }
		  });
  
		  $('#' + divid).DataTable();
		  $('#' + divid).on( 'page.dt', function () {
			  var table = $('#' + divid).DataTable();
			  table.responsive.recalc();
		  });
		  
		  $('[data-toggle="tooltip"]').tooltip();
	  }
  
  </script>

<script>
	$(document).ready(function(){
		$("#collapsee").hide();
		$("#myButton").click(function(){
			$("#collapsee").slideToggle(500);
		});
	});
	</script>
  @stop