<div class="row">
  @foreach ($data as $ky => $key)
  <div class="col-md-4 course-item">
      <div class="course-card">
          <div class="course-image">
              <img src="" 
              onerror="this.onerror=null;this.src='{{ asset('assets/images/uniti.jpg') }}';" 
              alt="{{ $key->course_name }}">                              
              <div class="fx-overlay">
                  <ul class="fx-info">
                      <li>
                          <a href="/student/{{ $key->id }}?session={{ $key->SessionID }}" class="course-view-btn">
                              <i class="fa fa-paper-plane"></i> View Course
                          </a>
                      </li>
                  </ul>
              </div>
          </div>
          <div class="course-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                  <h5 class="course-title">{{ ucwords($key->course_name) }}</h5>
                  <span class="badge-active">ACTIVE</span>
              </div>
              <div class="course-detail">
                  <strong>Code:</strong> {{ ucwords($key->course_code) }}
              </div>
              <div class="course-detail">
                  <strong>Lecturer:</strong> {{ (isset($lecturer[$ky]->name)) ? ucwords($lecturer[$ky]->name) : 'NOT ASSIGNED' }}
              </div>
              <div class="course-detail">
                  <strong>Session:</strong> {{ $key->SessionName }}
              </div>
              <!--<div class="course-detail">
                  <strong>Program:</strong> {{ ucwords($key->progname) }}
              </div>-->
          </div>
      </div>
  </div>
  @endforeach
</div>