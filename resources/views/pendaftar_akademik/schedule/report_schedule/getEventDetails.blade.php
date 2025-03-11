<!-- Hyper-Modern Interactive Event Details Modal -->
<div class="event-modal-wrapper">
  <!-- Header with glass morphism effect -->
  <div class="event-modal-header">
    <div class="pulse-badge">
      <i class="fa fa-calendar-day"></i>
    </div>
    <h4 class="event-title">Event Details</h4>
    <button type="button" class="close-btn" onclick="$('#uploadModal').modal('hide')">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>
  
  <!-- Content with animated cards -->
  <div class="event-modal-content">
    <!-- Subject Card -->
    <div class="info-card" id="subject-card" data-aos="fade-up" data-aos-delay="100">
      <div class="card-header">
        <div class="icon-wrapper">
          <i class="fa fa-book-open"></i>
        </div>
        <h5>Subject</h5>
      </div>
      <div class="card-content">
        <div class="content-chip">{{ $data['event']->course_code }}</div>
        <p class="content-value">{{ $data['event']->course_name }}</p>
      </div>
    </div>
    
    <!-- Session Card -->
    <div class="info-card" id="session-card" data-aos="fade-up" data-aos-delay="200">
      <div class="card-header">
        <div class="icon-wrapper">
          <i class="fa fa-clock"></i>
        </div>
        <h5>Session</h5>
      </div>
      <div class="card-content">
        <p class="content-value">{{ $data['event']->SessionName }}</p>
      </div>
    </div>
    
    <!-- Lecturer Card -->
    <div class="info-card" id="lecturer-card" data-aos="fade-up" data-aos-delay="300">
      <div class="card-header">
        <div class="icon-wrapper">
          <i class="fa fa-user-tie"></i>
        </div>
        <h5>Lecturer</h5>
      </div>
      <div class="card-content">
        <p class="content-value">{{ $data['event']->lecturer }}</p>
        <div class="interaction-buttons">
          <button class="action-btn" title="Email lecturer">
            <i class="fa fa-envelope"></i>
          </button>
          <button class="action-btn" title="View profile">
            <i class="fa fa-user"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Footer with interactive buttons -->
  <div class="event-modal-footer">
    <button type="button" class="btn-close-modal" onclick="$('#uploadModal').modal('hide')">
      <span>Close</span>
      <i class="fa fa-times"></i>
    </button>
  </div>
</div>

<style>
  /* Base styles with modern variables */
  :root {
    --primary-gradient: linear-gradient(135deg, #3a7bd5, #00d2ff);
    --secondary-gradient: linear-gradient(135deg, #FF6B6B, #FFE66D);
    --tertiary-gradient: linear-gradient(135deg, #1DE9B6, #2979FF);
    --surface-color: #ffffff;
    --background-color: #f8f9fa;
    --glass-effect: rgba(255, 255, 255, 0.25);
    --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.04);
    --shadow-md: 0 8px 15px rgba(50, 50, 93, 0.1), 0 5px 10px rgba(0, 0, 0, 0.05);
    --shadow-lg: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
    --radius-sm: 8px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-full: 9999px;
    --transition-fat: 0.2s ease;
    --transition-normal: 0.3s ease;
    --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }
  
  /* Modal Container */
  .event-modal-wrapper {
    font-family: var(--font-family);
    color: #333;
    max-width: 100%;
    background: var(--surface-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    position: relative;
  }
  
  /* Modal Header */
  .event-modal-header {
    position: relative;
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: var(--primary-gradient);
    color: white;
    border-radius: var(--radius-md) var(--radius-md) 0 0;
  }
  
  .pulse-badge {
    position: relative;
    background: var(--glass-effect);
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-full);
    margin-right: 1rem;
    backdrop-filter: blur(8px);
    box-shadow: var(--shadow-sm);
  }
  
  .pulse-badge::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    animation: pulse 2s infinite;
    z-index: -1;
  }
  
  .event-title {
    margin: 0;
    font-weight: 600;
    flex-grow: 1;
  }
  
  .close-btn {
    background: transparent;
    border: none;
    color: white;
    cursor: pointer;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-full);
    transition: var(--transition-fat);
  }
  
  .close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
  }
  
  /* Modal Content */
  .event-modal-content {
    padding: 1.5rem;
    display: grid;
    gap: 1rem;
  }
  
  /* Info Cards */
  .info-card {
    background: var(--background-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-normal);
    transform-origin: center;
    cursor: pointer;
  }
  
  .info-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-4px) scale(1.01);
  }
  
  .info-card:active {
    transform: translateY(2px) scale(0.99);
  }
  
  /* Card Header */
  .card-header {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .icon-wrapper {
    width: 38px;
    height: 38px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
    color: white;
  }
  
  #subject-card .icon-wrapper {
    background: var(--primary-gradient);
  }
  
  #session-card .icon-wrapper {
    background: var(--secondary-gradient);
  }
  
  #lecturer-card .icon-wrapper {
    background: var(--tertiary-gradient);
  }
  
  .card-header h5 {
    margin: 0;
    font-weight: 500;
    color: #6c757d;
    font-size: 0.875rem;
  }
  
  /* Card Content */
  .card-content {
    padding: 1rem;
    position: relative;
  }
  
  .content-chip {
    display: inline-block;
    background: rgba(58, 123, 213, 0.1);
    color: #3a7bd5;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
  }
  
  .content-value {
    margin: 0;
    font-weight: 600;
    font-size: 1.125rem;
    color: #333;
    word-wrap: break-word;
  }
  
  /* Interactive elements */
  .interaction-buttons {
    display: flex;
    margin-top: 1rem;
    opacity: 0;
    transform: translateY(10px);
    transition: var(--transition-normal);
  }
  
  #lecturer-card:hover .interaction-buttons {
    opacity: 1;
    transform: translateY(0);
  }
  
  .action-btn {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    margin-right: 0.5rem;
    color: #6c757d;
    cursor: pointer;
    transition: var(--transition-fat);
  }
  
  .action-btn:hover {
    background: #f8f9fa;
    color: #333;
    box-shadow: var(--shadow-sm);
  }
  
  /* Modal Footer */
  .event-modal-footer {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .btn-close-modal {
    background: #f8f9fa;
    border: none;
    border-radius: var(--radius-sm);
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition-fat);
  }
  
  .btn-close-modal:hover {
    background: #e9ecef;
  }
  
  .btn-close-modal i {
    font-size: 0.875rem;
  }
  
  /* Animations */
  @keyframes pulse {
    0% {
      transform: scale(1);
      opacity: 1;
    }
    100% {
      transform: scale(1.5);
      opacity: 0;
    }
  }
  
  /* Card entrance animations */
  [data-aos] {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  
  [data-aos].aos-animate {
    opacity: 1;
    transform: translateY(0);
  }
  
  /* Responsive adjustments */
  @media (max-width: 576px) {
    .event-modal-header {
      padding: 1rem;
    }
    
    .event-modal-content {
      padding: 1rem;
    }
    
    .content-value {
      font-size: 1rem;
    }
  }
</style>

<script>
  // Initialize animations and interactive elements
  $(document).ready(function() {
    // Simulate AOS library functionality
    setTimeout(function() {
      $('[data-aos]').each(function(index) {
        var $this = $(this);
        setTimeout(function() {
          $this.addClass('aos-animate');
        }, $this.attr('data-aos-delay') || 0);
      });
    }, 100);
    
    // Ensure modal closes properly
    $('.close-btn, .btn-close-modal').on('click', function() {
      $('#uploadModal').modal('hide');
    });
    
    // Add ripple effect on card click
    $('.info-card').on('click', function(e) {
      var $card = $(this);
      
      // Create ripple element
      var $ripple = $('<span class="ripple"></span>');
      $card.append($ripple);
      
      // Set ripple position
      var offsetX = e.pageX - $card.offset().left;
      var offsetY = e.pageY - $card.offset().top;
      
      $ripple.css({
        left: offsetX + 'px',
        top: offsetY + 'px'
      });
      
      // Remove ripple after animation
      setTimeout(function() {
        $ripple.remove();
      }, 600);
    });
    
    // Interactive buttons preview
    $('.action-btn').on('click', function(e) {
      e.stopPropagation();
      var action = $(this).attr('title');
      // Just for demonstration - would connect to actual functionality in production
      alert('Action triggered: ' + action);
    });
  });
</script>