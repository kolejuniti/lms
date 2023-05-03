@extends('layouts.student.student')

@section('main')
	
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		  <!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h4 class="page-title">Student Reports</h4>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Assessment</li>
								<li class="breadcrumb-item active" aria-current="page">Report</li>
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
										<h1 class="mb-0 fw-600">{{ $student->name }}</h1>
										<p class="my-10 fs-16"><strong>IC :</strong> {{ $student->ic }}</p>
										<p class="my-10 fs-16"><strong>No. Matric :</strong> {{ $student->no_matric }}</p>
										<p class="my-10 fs-16"><strong>Email :</strong> {{ $student->status }}</p>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- QUIZ -->
					@if ($percentagequiz != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">QUIZ ({{ $percentagequiz }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_quiz" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Duration
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												Final Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($quiz as $keys=>$qz)
												<tr>
													<td>
													{{ $keys+1 }}
													</td>
													<td >
													{{ $qz->title }}
													</td>
													<td>
													{{ $qz->duration }}
													</td>
													<td>
													{{ $qz->total_mark }}
													</td>
													<td>
													{{ $quizlist[$keys]->final_mark ?? '-' }}
													</td>
												</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markquiz }} \over Total Mark :{{ $totalquiz }} \\ \times Percentage : {{ $percentagequiz }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allquiz }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Quiz percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- TEST -->
					@if ($percentagetest != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Test ({{ $percentagetest }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_test" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Duration
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												Final Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($test as $keys=>$ts)
											<tr>
												<td>
												{{ $keys+1 }}
												</td>
												<td >
												{{ $ts->title }}
												</td>
												<td>
												{{ $ts->duration }}
												</td>
												<td>
												{{ $ts->total_mark }}
												</td>
												<td>
												{{ $testlist[$keys]->final_mark ?? '-' }}
												</td>
											</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $marktest }} \over Total Mark :{{ $totaltest }} \\ \times Percentage : {{ $percentagetest }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_alltest }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Test percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- ASSIGNMENT -->

					@if ($percentageassign != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Assignment ({{ $percentageassign }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_assign" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Deadline
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												Final Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($assign as $keys=>$qz)
											<tr>
												<td>
												{{ $keys+1 }}
												</td>
												<td >
												{{ $qz->title }}
												</td>
												<td>
												{{ $qz->deadline }}
												</td>
												<td>
												{{ $qz->total_mark }}
												</td>
												<td>
												{{ $assignlist[$keys]->final_mark ?? '-' }}
												</td>
											</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markassign }} \over Total Mark :{{ $totalassign }} \\ \times Percentage : {{ $percentageassign }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allassign }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Assignment percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- midterm -->

					@if ($percentagemidterm != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Midterm ({{ $percentagemidterm }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_midterm" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												Final Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($midterm as $keys=>$qz)
											<tr>
												<td>
												{{ $keys+1 }}
												</td>
												<td >
												{{ $qz->title }}
												</td>
												<td>
												{{ $qz->total_mark }}
												</td>
												<td>
												{{ $midtermlist[$keys]->final_mark ?? '-' }}
												</td>
											</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markmidterm }} \over Total Mark :{{ $totalmidterm }} \\ \times Percentage : {{ $percentagemidterm }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allmidterm }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Midterm percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- final -->

					@if ($percentagefinal != "")
					<!--
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">final ({{ $percentagefinal }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_final" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												Final Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($final as $keys=>$qz)
												<tr>
													<td>
													{{ $keys+1 }}
													</td>
													<td >
													{{ $qz->title }}
													</td>
													<td>
													{{ $qz->total_mark }}
													</td>
													<td>
													{{ $finallist[$keys]->final_mark ?? '-' }}
													</td>
												</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markfinal }} \over Total Mark :{{ $totalfinal }} \\ \times Percentage : {{ $percentagefinal }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allfinal }}%</strong>
													</td>
												</tr>
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
					@else
					<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Final percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif

					-->
					
					<!-- paperwork -->

					{{-- @if ($percentagepaperwork != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">paperwork ({{ $percentagepaperwork }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_paperwork" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Deadline
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												paperwork Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($paperworklist as $keys=>$paperwork)
												<tr>
													<td>
													{{ $keys+1 }}
													</td>
													<td >
													{{ $paperwork->title }}
													</td>
													<td>
													{{ $paperwork->deadline }}
													</td>
													<td>
													{{ $paperwork->total_mark }}
													</td>
													<td>
													{{ $paperwork->final_mark }}
													</td>
												</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markpaperwork }} \over Total Mark :{{ $totalpaperwork }} \\ \times Percentage : {{ $percentagepaperwork }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allpaperwork }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Paperwork percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- practical -->

					@if ($percentagepractical != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Practical ({{ $percentagepractical }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_practical" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Deadline
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												practical Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($practicallist as $keys=>$practical)
												<tr>
													<td>
													{{ $keys+1 }}
													</td>
													<td >
													{{ $practical->title }}
													</td>
													<td>
													{{ $practical->deadline }}
													</td>
													<td>
													{{ $practical->total_mark }}
													</td>
													<td>
													{{ $practical->final_mark }}
													</td>
												</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markpractical }} \over Total Mark :{{ $totalpractical }} \\ \times Percentage : {{ $percentagepractical }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allpractical }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Practical percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif --}}

					<!-- other -->

					@if ($percentageother != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Other ({{ $percentageother }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_other" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												other Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($other as $keys=>$qz)
											<tr>
												<td>
												{{ $keys+1 }}
												</td>
												<td >
												{{ $qz->title }}
												</td>
												<td>
												{{ $qz->total_mark }}
												</td>
												<td>
												{{ $otherlist[$keys]->final_mark ?? '-' }}
												</td>
											</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markother }} \over Total Mark :{{ $totalother }} \\ \times Percentage : {{ $percentageother }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allother }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Other percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif

					<!-- extra -->

					@if ($percentageextra != "")
					<div class="col-12">
						<div class="box">
							<div class="card-header">
							<h3 class="card-title d-flex">Extra ({{ $percentageextra }}%)</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<div id="complex_header_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
									<div class="row">
										<div class="col-sm-12">
										<table id="table_projectprogress_extra" class="table table-striped projects display dataTable no-footer " style="width: 100%;">
											<thead class="thead-themed">
											<tr>
												<th style="width: 1%">
												No.
												</th>
												<th style="width: 20%">
												Title
												</th>
												<th style="width: 20%">
												Total Mark
												</th>
												<td style="width: 20%">
												extra Mark
												</td>
											</tr>
											</thead>
											<tbody>
											@foreach ($extra as $keys=>$qz)
											<tr>
												<td>
												{{ $keys+1 }}
												</td>
												<td >
												{{ $qz->title }}
												</td>
												<td>
												{{ $qz->total_mark }}
												</td>
												<td>
												{{ $extralist[$keys]->final_mark ?? '-' }}
												</td>
											</tr>
											@endforeach
												<tr>
													<td style="width: 1%">
														
													</td>
													<td >
														
													</td>
													<td>
														Total Marks by Percentage
													</td>
													<td>
														$${ Overall Mark : {{ $markextra }} \over Total Mark :{{ $totalextra }} \\ \times Percentage : {{ $percentageextra }} }$$
													</td>
													<td>
														<strong>Overall Percentage : {{ $total_allextra }}%</strong>
													</td>
												</tr>
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
					@else
					<!--<div class="box bg-danger">
						<div class="box-body d-flex p-0">
							<div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
								<div class="row">
									<div class="col-12 col-xl-12">
										<h1 class="mb-0 fw-600">Extra percentage is not set, please consult the person in charge.</h1>
									</div>
								</div>
							</div>
						</div>
					</div>-->
					@endif
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
		CreateTable('table_projectprogress_quiz');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_assign');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_midterm');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_final');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_paperwork');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_practical');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_other');  
	});

	$(document).ready(function() {
		CreateTable('table_projectprogress_extra');  
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