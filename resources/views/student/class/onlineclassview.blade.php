@extends('layouts.student.student')

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
				<h4 class="page-title">Profile</h4>
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
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xl-12 col-12">
					<div class="box ">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">GROUP {{ $class->groupname }}</h1>
										<p class="my-10 fs-16"><strong>Lecturer Name :</strong> {{ $class->name }}</p>
										<p class="my-10 fs-16"><strong>Description :</p>
										{!! $class->classdescription !!}
										
										<div class="col-12 mt-45 d-md-flex align-items-center">
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-danger b-1 border-white rounded-circle">
														<i class="fa fa-calendar"></i>
													</div>
													<div>
														<h5 class="mb-0">Date</h5>
														<p class="mb-0">{{ $class->classdate }}</p>
													</div>
												</div>
											</div>
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-success b-1 border-white rounded-circle">
														<i class="fa fa-clock-o"></i>
													</div>
													<div>
														<h5 class="mb-0">Time</h5>
														<p class="mb-0">{{ $class->classstarttime }} - {{ $class->classendtime }}</p>
													</div>
												</div>
											</div>
											<div class="col-mx-4 me-30 mb-30 mb-md-0">
												<div class="d-flex align-items-center">
													<div class="me-15 text-center fs-24 w-50 h-50 l-h-50 bg-warning b-1 border-white rounded-circle">
														<i class="fa fa-link"></i>
													</div>
													<div>
														<h5 class="mb-0">Link</h5>
														<p class="mb-0">{{ $class->classlink }}</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Chapters &nbsp<p class="text-fade d-flex"></p></h3>
							</div>
							<div class="box-body">
							<div class="table-responsive">
								<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
								<div class="row">
									<div class="col-sm-12">
									<table id="table_projectprogress" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
										<thead class="thead-themed">
										<tr>
											<th style="width: 1%">
											No.
											</th>
											<th style="width: 5%">
											Chapter No
											</th>
											<th style="width: 15%">
											Name
											</th>
										</tr>
										</thead>
										<tbody>
										@foreach ($chapters as $keys=>$chp)
										<tr>
											<td style="width: 1%">
												{{ $keys+1 }}
											</td>
											<td style="width: 5%">
												{{ $chp->SubChapterNo }}
											</td>
											<td style="width: 15%">
												{{ $chp->DrName }}
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
				</div>
			</div>
		</section>
		<!-- /.content -->
		</div>
	</div>
</div>
  <!-- /.content-wrapper -->
	
  <script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>
  <script>
	$(document).ready(function() {
		CreateTable('table_projectprogress');  
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
  @stop