<!-- Course Cards Section -->
<style>
  .course-card {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      height: 30em;
      border: none;
      transform: translateY(20px);
      opacity: 0;
      background: white;
      margin-bottom: 25px;
  }
  
  .course-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
  }
  
  .course-image {
      height: 200px;
      overflow: hidden;
      position: relative;
  }
  
  .course-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: all 0.5s ease;
  }
  
  .course-card:hover .course-image img {
      transform: scale(1.1);
  }
  
  .fx-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(67, 97, 238, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: all 0.3s ease;
  }
  
  .course-card:hover .fx-overlay {
      opacity: 1;
  }
  
  .fx-info {
      list-style: none;
      padding: 0;
      margin: 0;
  }
  
  .course-view-btn {
      background: white;
      color: #4361ee;
      border: none;
      border-radius: 50px;
      padding: 10px 25px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-decoration: none;
  }
  
  .course-view-btn:hover {
      background: #4361ee;
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.25);
      text-decoration: none;
  }
  
  .course-body {
      padding: 25px;
      position: relative;
  }
  
  .course-title {
      font-weight: 700;
      color: #333;
      margin-bottom: 15px;
      font-size: 18px;
      transition: all 0.3s ease;
  }
  
  .course-card:hover .course-title {
      color: #4361ee;
  }
  
  .course-detail {
      display: flex;
      margin-bottom: 10px;
      color: #64748b;
  }
  
  .course-detail strong {
      color: #333;
      min-width: 70px;
      margin-right: 8px;
  }
  
  .badge-active {
      background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
      color: white;
      font-weight: 600;
      padding: 5px 12px;
      border-radius: 8px;
      font-size: 12px;
      box-shadow: 0 4px 10px rgba(255, 193, 7, 0.2);
      position: absolute;
      top: 20px;
      right: 20px;
  }
  
  @keyframes fadeIn {
      from {
          opacity: 0;
          transform: translateY(20px);
      }
      to {
          opacity: 1;
          transform: translateY(0);
      }
  }
</style>

<div class="row">
  @foreach ($data as $key)
  <div class="col-md-4 course-item">
      <div class="course-card">
          <div class="course-image">
              <img src="{{ asset('assets/images/uniti.jpg') }}" 
                  onerror="this.onerror=null;this.src='{{ asset('assets/images/uniti.jpg') }}';" 
                  alt="{{ $key->course_name }}">
              <div class="fx-overlay">
                  <ul class="fx-info">
                      <li>
                          <a href="/lecturer/{{ $key->id }}?session={{ $key->SessionID }}" class="course-view-btn">
                              <i class="fa fa-paper-plane"></i> View Course
                          </a>
                      </li>
                  </ul>
              </div>
          </div>
          <div class="course-body">
              <span class="badge-active">ACTIVE</span>
              <h5 class="course-title">{{ ucwords($key->course_name) }}</h5>
              <div class="course-detail">
                  <strong>Code:</strong> {{ ucwords($key->course_code) }}
              </div>
              <div class="course-detail">
                  <strong>Session:</strong> {{ ucwords($key->SessionName) }}
              </div>
          </div>
      </div>
  </div>
  @endforeach
</div>

<script>
  // Animate cards on load
  document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.course-card');
      cards.forEach((card, index) => {
          setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
          }, index * 150); // Staggered animation
      });
  });
</script>