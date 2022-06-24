@extends('../layouts.ketua_program')

@section('main')
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		  <!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Lecturer Reports</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Dashboard</li>
								<li class="breadcrumb-item" aria-current="page">Lecturer List</li>
								<li class="breadcrumb-item active" aria-current="page">Lecturer Report</li>
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
										<h1 class="mb-0 fw-600">{{ $lecturer->name }}</h1>
										<p class="my-10 fs-16"><strong>IC :</strong> {{ $lecturer->ic }}</p>
										<p class="my-10 fs-16"><strong>Staff No. :</strong> {{ $lecturer->no_staf }}</p>
										<p class="my-10 fs-16"><strong>Email :</strong> {{ $lecturer->email }}</p>
										<div id="collapsee">
											<h4 class="mb-0 fw-600">Total Hours by Active Session</h4>
											@foreach ($sumbyses as $key => $session)
												@if ($sums[$key] != 0)
													<p class="my-10 fs-16"><strong>{{ $session->SessionName }} :</strong> {{ $sums[$key] }} Hours ({{ $session->Start }} To {{ $session->End }})</p>
												@endif
											@endforeach
										</div>
										<button type="button" id="myButton" class="btn btn-info">More Info</button>
										<div class="col-12 mt-45 d-md-flex align-items-center">
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-danger b-1 border-white rounded-circle">
														<i class="fa fa-users"></i>
													</div>
													<div>
														<h5 class="mb-0">Total Group</h5>
														<p class="mb-0 text-white-70">{{ ($group != null) ? count($group) : "0"}}</p>
													</div>
												</div>
											</div>
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-primary b-1 border-white rounded-circle">
														<i class="fa fa-clock-o"></i>
													</div>
													<div>
														<h5 class="mb-0">Total Hours</h5>
														<p class="mb-0 text-white-70">{{ (isset($sum)) ? $sum : "0"}} Hours</p>
													</div>
												</div>
											</div>
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
														<i class="fa fa-user"></i>
													</div>
													<div>
														<h5 class="mb-0">Total Students</h5>
														<p class="mb-0 text-white-70">{{ (isset($numStud)) ? array_sum($numStud) : "0"}}</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@if ($group != null)
					@foreach ($group as $key => $grp)
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">{{ $grp->progname }} : Group {{ $grp->group_name }} &nbsp<p class="text-fade d-flex"> ( {{ $grp->course_name }})</p></h3>
							</div>
							<div class="box-body">
							<div class="col-xl-12 col-12">
								<div class="card">
									<div class="card-body">
										<p class="fs-16 ml-2"><strong>Course Code :</strong> {{ $grp->course_code }}</p>
										<p class="fs-16"><strong>Course Credit :</strong> {{ $grp->course_credit }}</p>
										<p class="fs-16"><strong>Semester :</strong> {{ $grp->semesterid }}</p>
									</div>
								</div>
							</div>
							<div class="table-responsive">
								<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-sm-12">
									<table id="table_projectprogress{{ $key+1 }}" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
										<script>
											$(document).ready(function() {
												CreateTable('table_projectprogress{{ $key+1 }}');  
											});
										</script>
										<thead class="thead-themed">
										<tr>
											<th style="width: 1%">
											No.
											</th>
											<th style="width: 30%">
											Name
											</th>
											<th style="width: 15%">
											No Matric
											</th>
											<th style="width: 20%">
											IC
											</th>
											<th style="width: 20%">
											Status
											</th>
										</tr>
										</thead>
										<tbody>
										@foreach ($student[$key] as $keys=>$students)
										<tr>
											<td style="width: 1%">
											{{ $keys+1 }}
											</td>
											<td style="width: 30%">
												{{ $students->name }}
											</td>
											<td style="width: 15%">
											{{ $students->no_matric }}
											</td>
											<td style="width: 20%">
											{{ $students->ic }}
											</td>
											<td>
											{{ $students->status }}
											</td>
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
					@endif									
				</div>
			</div>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
	
  <script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
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