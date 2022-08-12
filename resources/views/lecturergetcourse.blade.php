<div class="row">
@foreach ($data as $key)
<div class="col-md-4">
  <div class="card" style="padding:0 !important; height:30em">
    <div class="row g-0 fx-element-overlay g-0 align-items-center">
      <div class="col-md-12">
        <div class="fx-card-item">
          <div class="fx-card-avatar fx-overlay-1" style="cursor:pointer">
            <img src="" style="height:auto !important; max-height:250px !important" onerror="this.onerror=null;this.src='{{ asset('assets/images/uniti.jpg') }}';" 
            class="bber-0 bbsr-0" alt="...">
            <div class="fx-overlay ">
              <ul class="fx-info">
                <li>
                  <a href="/lecturer/{{ $key->id }}" class="btn btn-primary-outline mr-1" data-toggle="tooltip" data-placement="auto" ><i class="fa fa-paper-plane"></i> View</a>
                </li> 
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <span class="badge badge-warning pull-right" >ACTIVE</span>
        <div class="card-body">
          <div class="p-2">
            <div class="row">
                <div class="col-md-12">
                  <h5 class="card-title fw-600">{{ ucwords($key->course_name) }}</h5>
                  <p class="card-text text-gray-600 pt-5">
                    <strong>Code</strong> {{ ucwords($key->course_code) }}
                  </p>
                  <p class="card-text text-gray-600">
                    <strong>Faculty</strong> {{ ucwords($key->course_code) }}
                  </p>
                  <p class="card-text text-gray-600">
                    <strong>Session</strong> {{ ucwords($key->SessionName) }}
                  </p>
                </div>
              </div>
          </div>
        </div> <!-- end card-body-->
      </div> <!-- end col -->
    </div> <!-- end row-->
  </div>
</div>
@endforeach
</div>