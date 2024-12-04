@extends((Auth::user()->usrtype == "ADM") ? 'layouts.admin' : (Auth::user()->usrtype == "RGS" ? 'layouts.pendaftar' : (Auth::user()->usrtype == "AR" ? 'layouts.pendaftar_akademik' : (Auth::user()->usrtype == "FN" ? 'layouts.finance' : (Auth::user()->usrtype == "TS" ? 'layouts.treasurer' : (Auth::user()->usrtype == "DN" ? 'layouts.deen' : (Auth::user()->usrtype == "OTR" ? 'layouts.other_user' : (Auth::user()->usrtype == "COOP" ? 'layouts.coop' : (Auth::user()->usrtype == "UR" ? 'layouts.ur' : (Auth::user()->usrtype == "AO" ? 'layouts.ketua_program' : (Auth::user()->usrtype == "HEA" ? 'layouts.hea' : '')))))))))))

@section('main')
<!-- Content Header (Page header) -->
<div class="content-wrapper" style="min-height: 695.8px;">
  <div class="container-full">
    <!-- Content Header (Page header) -->	  
    <div class="content-header">
      <div class="d-flex align-items-center">
        <div class="me-auto">
          <h4 class="page-title">Dashboard</h4>
          <div class="d-inline-block align-items-center">
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="row">
				<div class="col-xl-12 col-12">
          <div class="box bg-success">
            <div class="box-body d-flex p-0">
              <div class="flex-grow-1 p-30 flex-grow-1 bg-img bg-none-md align-content-center" style="background-position: right bottom; background-size: auto 100%; background-image: url(images/svg-icon/color-svg/custom-30.svg)">
                <div class="row">
                  <div class="col-12 col-xl-12">
                    <h1 class="mb-0 fw-600" style="text-align:center">Welcome to UCMS!</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if(Auth::user()->usrtype == "RGS")
      <div class="row">
        <div class="col-md-12">
          {{-- <div class="col-md-6"> --}}
            <div class="container">
              <canvas id="studentYearChart"></canvas>
            </div>
          {{-- </div> --}}
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="box">
            <div class="box-body">
              <h4 class="box-title">Filter by Status and Date</h4>
              <form id="statusDateForm">
                <div class="form-group">
                  <label for="statusCheckboxes">Select Status:</label>
                  <div id="statusCheckboxes">
                    @foreach($data['status'] as $status)
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $status->id }}" id="status_{{ $status->id }}">
                        <label class="form-check-label" for="status_{{ $status->id }}">
                          {{ $status->name }}
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
                <div class="form-group mt-3">
                  <label for="selectedDate">Select Date:</label>
                  <input type="date" id="selectedDate" class="form-control">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box-body">
              <h4 class="box-title">Status Count Chart</h4>
              <canvas id="statusCountChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('studentYearChart').getContext('2d');
            const studentYearChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($data['year']->pluck('year')), // Extract the years from $data['year']
                    datasets: [{
                        label: 'Number of Students',
                        data: @json($data['student']), // Student counts per year
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 8000,
                            ticks: {
                                stepSize: 500,
                                callback: function(value) { return value.toLocaleString(); }
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: (value) => value.toLocaleString(),
                            color: '#000'
                        }
                    }
                }
            });

            const statusCountCtx = document.getElementById('statusCountChart').getContext('2d');
            const statusCountChart = new Chart(statusCountCtx, {
                type: 'doughnut',
                data: {
                    labels: [], // Initially empty
                    datasets: [{
                        label: 'Status Count',
                        data: [], // Initially empty
                        backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 159, 64, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 205, 86, 0.5)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 205, 86, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        datalabels: {
                            formatter: (value) => value.toLocaleString(),
                            color: '#000'
                        }
                    }
                }
            });

            document.getElementById('statusDateForm').addEventListener('change', function () {
                // Fetch checked status values and selected date
                const selectedStatuses = Array.from(document.querySelectorAll('#statusCheckboxes input:checked')).map(checkbox => checkbox.value);
                const selectedDate = document.getElementById('selectedDate').value;

                if (selectedStatuses.length > 0 && selectedDate) {
                    // Call an AJAX function to fetch data based on selected statuses and date
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ url('/pendaftar_dashboard/getCircleData') }}",
                        method: 'POST',
                        data: { statuses: selectedStatuses, date: selectedDate },
                        success: function (response) {
                            // Update the chart with new data
                            statusCountChart.data.labels = response.labels;
                            statusCountChart.data.datasets[0].data = response.data;
                            statusCountChart.update();
                        },
                        error: function (error) {
                            console.error('Error fetching status data:', error);
                        }
                    });
                }
            });
        });
      </script>
      @endif
    </section>
    <!-- /.content -->
  </div>
</div>

<script src="{{ asset('assets/src/js/pages/data-table.js') }}"></script>

<script type="text/javascript">

  function deleteMaterial(ic){     
      Swal.fire({
    title: "Are you sure?",
    text: "This will be permanent",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!"
  }).then(function(res){
    
    if (res.isConfirmed){
              $.ajax({
                  headers: {'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')},
                  url      : "{{ url('/admin/delete') }}",
                  method   : 'DELETE',
                  data 	 : {ic:ic},
                  error:function(err){
                      alert("Error");
                      console.log(err);
                  },
                  success  : function(data){
                      window.location.reload();
                      alert("success");
                  }
              });
          }
      });
  }

</script>
@endsection
