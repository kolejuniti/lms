<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="EDUHUB - Modern Education Platform">
  <meta name="author" content="">
  <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">
  <title>EDUHUB - Log in</title>
  
  <!-- External CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
  
  <style>
    :root {
      --primary: #3a57e8;
      --primary-light: #5a77ff;
      --secondary: #304dc9;
      --success: #4cc9f0;
      --light: #f8f9fa;
      --dark: #212529;
      --transition: all 0.3s ease;
      --border-radius: 12px;
      --shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-image: url('{{ asset("assets/images/auth-bg/bg-16.jpg") }}');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 2rem 0;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(3px);
      z-index: 0;
    }
    
    .login-container {
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: var(--border-radius);
      overflow: hidden;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      position: relative;
      z-index: 1;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transform: translateY(30px);
      opacity: 0;
      transition: all 0.5s ease;
    }
    
    .login-header {
      padding: 2rem 2rem 1rem;
      text-align: center;
    }
    
  .login-container h4, .login-header h4 {
      color: #536dfe;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    
    .logo-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      margin-bottom: 1.5rem;
    }
    
    .logo-container img {
      max-height: 60px;
      width: auto;
      transition: var(--transition);
    }
    
    /* Tab styling to match the screenshot */
    .tab-switcher {
      display: flex;
      margin-bottom: 1.5rem;
      border-radius: 50px;
      background: rgba(240, 243, 255, 0.6);
      padding: 6px;
      position: relative;
      z-index: 1;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
      max-width: 350px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .tab-switcher:hover {
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .tab-switcher .tab-btn {
      background: transparent;
      border: none;
      padding: 10px 0;
      width: 50%;
      border-radius: 50px;
      font-weight: 600;
      color: #6c757d;
      transition: var(--transition);
      position: relative;
      z-index: 2;
    }
    
    .tab-switcher .tab-btn.active {
      color: white;
    }
    
    .slider {
      position: absolute;
      height: calc(100% - 12px);
      width: calc(50% - 6px);
      background: #536dfe;
      border-radius: 50px;
      top: 6px;
      left: 6px;
      transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
      z-index: 1;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    .slider.right {
      left: calc(50% + 0px);
    }
    
    .slider.right {
      left: calc(50% + 0px);
    }
    
    .login-body {
      padding: 0 2rem 2rem;
    }
    
    .tab-content {
      display: none;
      opacity: 0;
      transform: translateY(20px);
      transition: var(--transition);
    }
    
    .tab-content.active {
      display: block;
      opacity: 1;
      transform: translateY(0);
    }
    
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .form-control {
      padding: 12px 15px 12px 55px;
      height: auto;
      border-radius: 50px;
      border: 1px solid rgba(230, 230, 230, 0.3);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      background-color: rgba(240, 243, 255, 0.2);
      backdrop-filter: blur(5px);
      text-align: center;
      color: #333;
    }
    
    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
      border-color: var(--primary);
      background-color: rgba(240, 243, 255, 0.3);
      transform: translateY(-2px);
    }
    
    .input-icon {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #3a57e8;
      transition: var(--transition);
      z-index: 2;
    }
    
    .form-control:focus + .input-icon {
      color: var(--primary);
      left: 18px;
    }
    
    /* Checkbox styling to match screenshot */
    .form-check-input {
      width: 18px;
      height: 18px;
      margin-top: 0.25em;
      background-color: rgba(255, 255, 255, 0.5);
      border: 1px solid #ccc;
      border-radius: 4px;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    
    .form-check-input:checked {
      background-color: #536dfe;
      border-color: #536dfe;
    }
    
    .form-check-label {
      cursor: pointer;
      font-size: 0.9rem;
      padding-left: 0.25rem;
    }
    
    .forgot-link {
      color: #536dfe;
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
    }
    
    .forgot-link:hover {
      color: #3a4ce0;
      text-decoration: underline;
    }
    
    .submit-btn {
      background: #536dfe;
      border: none;
      border-radius: 50px;
      padding: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 4px 10px rgba(83, 109, 254, 0.25);
      position: relative;
      overflow: hidden;
      color: white;
    }
    
    .submit-btn::after {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }
    
    .submit-btn:hover {
      background: #4a5fe0;
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(83, 109, 254, 0.3);
      color: white;
    }
    
    .submit-btn:hover::after {
      left: 100%;
    }
    
    .submit-btn:active {
      transform: translateY(0);
      box-shadow: 0 3px 10px rgba(83, 109, 254, 0.2);
    }
    
    .error-text {
      color: #dc3545;
      font-size: 0.8rem;
      margin-top: 5px;
      margin-left: 15px;
    }
    
    .alert {
      border-radius: var(--border-radius);
      margin-bottom: 1.5rem;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    
    .animated-btn {
      animation: pulse 1.5s infinite;
    }
    
    /* Add floating labels effect */
    .form-floating {
      position: relative;
    }
    
    .form-floating label {
      position: absolute;
      left: 0;
      right: 0;
      text-align: center;
      top: 12px;
      color: #5770e0;
      transition: var(--transition);
      pointer-events: none;
      opacity: 0.8;
    }
    
    .form-floating input:focus ~ label,
    .form-floating input:not(:placeholder-shown) ~ label {
      transform: translateY(-25px) scale(0.85);
      color: var(--primary);
      padding: 0 5px;
      left: 0;
      right: 0;
      margin: 0 auto;
      width: fit-content;
      background: transparent;
      opacity: 1;
    }
    
    /* Additional interactive elements */
    .form-group {
      position: relative;
      z-index: 1;
    }
    
    .form-control, .form-check-input, .tab-btn, .forgot-link {
      cursor: pointer;
    }
    
    .input-focus-effect {
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: 0.3s;
    }
    
    .form-control:focus ~ .input-focus-effect {
      width: 100%;
      left: 0;
    }
    
    /* Floating particles */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }
    
    .particle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
      pointer-events: none;
      z-index: 0;
    }
    
    /* Add responsive adjustments */
    @media (max-width: 576px) {
      .login-container {
        border-radius: var(--border-radius);
        max-width: 90%;
        margin: 0 auto;
      }
      
      body {
        padding: 0;
      }
    }
  </style>
</head>

<body>
  <div class="particles" id="particles"></div>
  <div class="login-container">
    <div class="login-header">
      <div class="logo-container" id="logoContainer">
        <img src="{{ asset('assets/images/logo/Kolej-UNITI.png') }}" alt="Kolej UNITI">
        <span style="font-size: 2rem; font-weight: bold; margin: 0 0.5rem; color: #333;">
          <span style="color: #333;">U</span>
          <span style="color: #ff9800;">C</span>
          <span style="color: #ff9800;">M</span>
          <span style="color: #ff9800;">S</span>
        </span>
      </div>
      
      <h5 class="mb-4" style="color: #95959597;">Sign in to continue</h5>
      
      <div class="tab-switcher">
        <span class="slider" id="slider"></span>
        <button class="tab-btn active" id="staffTab">Staff/Lecturer</button>
        <button class="tab-btn" id="studentTab">Students</button>
      </div>
    </div>
    
    <div class="login-body">
      @if(session()->has('message'))
      <div class="alert alert-danger text-center">
        {{ session()->get('message') }}
      </div>
      @endif
      
      <div class="tab-content active" id="staffContent">
        <form action="{{ route('login.custom') }}" method="post" id="staffForm">
          @csrf
          <div class="form-group form-floating">
            <input type="email" name="email" class="form-control" id="staffEmail" placeholder=" ">
            <div class="input-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <label for="staffEmail">Email address</label>
            @if ($errors->has('email'))
            <span class="error-text">{{ $errors->first('email') }}</span>
            @endif
          </div>
          
          <div class="form-group form-floating">
            <input type="password" name="password" class="form-control" id="staffPassword" placeholder=" ">
            <div class="input-icon">
              <i class="fas fa-lock"></i>
            </div>
            <label for="staffPassword">Password</label>
            <i class="fas fa-eye toggle-password" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #3a57e8; z-index: 2;"></i>
            @if ($errors->has('password'))
            <span class="error-text">{{ $errors->first('password') }}</span>
            @endif
          </div>
          
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="staffRemember" value="1">
              <label class="form-check-label" for="staffRemember">
                Remember me
              </label>
            </div>
            <a href="javascript:void(0)" class="forgot-link">Forgot password?</a>
          </div>
          
          @if($errors->any())
          <div class="mb-4">
            <hr>
            <span class="error-text">{{$errors->first('message')}}</span>
          </div>
          @endif
          
          <button type="submit" class="btn w-100 submit-btn" id="staffSubmit">
            SIGN IN
          </button>
        </form>
      </div>
      
      <div class="tab-content" id="studentContent">
        <form action="{{ route('login.student.custom') }}" method="post" id="studentForm">
          @csrf
          <div class="form-group form-floating">
            <input type="text" name="ic" class="form-control" id="studentMatric" placeholder=" ">
            <div class="input-icon">
              <i class="fas fa-id-card"></i>
            </div>
            <label for="studentMatric">No. Matric</label>
            @if ($errors->has('ic'))
            <span class="error-text">{{ $errors->first('ic') }}</span>
            @endif
          </div>
          
          <div class="form-group form-floating">
            <input type="password" name="password" class="form-control" id="studentPassword" placeholder=" ">
            <div class="input-icon">
              <i class="fas fa-lock"></i>
            </div>
            <label for="studentPassword">Password</label>
            <i class="fas fa-eye toggle-password" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #3a57e8; z-index: 2;"></i>
            @if ($errors->has('password'))
            <span class="error-text">{{ $errors->first('password') }}</span>
            @endif
          </div>
          
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="studentRemember" value="1">
              <label class="form-check-label" for="studentRemember">
                Remember me
              </label>
            </div>
            <a href="javascript:void(0)" class="forgot-link">Forgot password?</a>
          </div>
          
          @if($errors->any())
          <div class="mb-4">
            <hr>
            <span class="error-text">{{$errors->first('message')}}</span>
          </div>
          @endif
          
          <button type="submit" class="btn w-100 submit-btn" id="studentSubmit">
            SIGN IN
          </button>
        </form>
      </div>
    </div>
  </div>
  
  <!-- JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Check if student session exists
    var student = "{{ (Session::get('StudInfo') != null) ? Session::get('StudInfo') : '' }}";
    
    $(document).ready(function() {
      if(student != '') {
        location.href = "/student";
      }
      
      // Initial animation for container
      setTimeout(function() {
        $('.login-container').css('transform', 'translateY(0)').css('opacity', '1');
      }, 300);
      
      // Tab switching with enhanced animation
      $('#staffTab').click(function() {
        $('#slider').removeClass('right');
        $('#staffTab').addClass('active');
        $('#studentTab').removeClass('active');
        
        // Animate content switching
        $('#staffContent').css('position', 'relative');
        $('#studentContent').css('position', 'absolute');
        $('#studentContent').css('opacity', '0');
        $('#studentContent').css('transform', 'translateX(20px)');
        setTimeout(function() {
          $('#staffContent').addClass('active');
          $('#studentContent').removeClass('active');
          setTimeout(function() {
            $('#staffContent').css('opacity', '1');
            $('#staffContent').css('transform', 'translateX(0)');
          }, 50);
        }, 200);
      });
      
      $('#studentTab').click(function() {
        $('#slider').addClass('right');
        $('#studentTab').addClass('active');
        $('#staffTab').removeClass('active');
        
        // Animate content switching
        $('#studentContent').css('position', 'relative');
        $('#staffContent').css('position', 'absolute');
        $('#staffContent').css('opacity', '0');
        $('#staffContent').css('transform', 'translateX(-20px)');
        setTimeout(function() {
          $('#studentContent').addClass('active');
          $('#staffContent').removeClass('active');
          setTimeout(function() {
            $('#studentContent').css('opacity', '1');
            $('#studentContent').css('transform', 'translateX(0)');
          }, 50);
        }, 200);
      });
      
      // Enhanced password toggle
      $('.toggle-password').click(function() {
        const passwordField = $(this).parent().find('input');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        
        if (type === 'text') {
          $(this).removeClass('fa-eye').addClass('fa-eye-slash');
          $(this).css('color', 'var(--primary-light)');
        } else {
          $(this).removeClass('fa-eye-slash').addClass('fa-eye');
          $(this).css('color', 'var(--primary)');
        }
      });
      
      // Form validation and enhanced submit animation
      $('#staffForm, #studentForm').submit(function() {
        const btn = $(this).find('.submit-btn');
        btn.prop('disabled', true)
           .html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Signing in...');
        btn.css('background', 'linear-gradient(45deg, var(--primary) 0%, var(--secondary) 100%)');
        
        // Add ripple effect on submit
        const ripple = $('<span></span>').css({
          'position': 'absolute',
          'background': 'rgba(255, 255, 255, 0.3)',
          'width': '100%',
          'height': '100%',
          'left': '0',
          'top': '0',
          'opacity': '1',
          'border-radius': '50px',
          'transform': 'scale(0)',
          'transition': 'all 0.5s ease-out'
        });
        
        btn.css('position', 'relative').css('overflow', 'hidden').append(ripple);
        ripple.css('transform', 'scale(3)').css('opacity', '0');
      });
      
      // Enhanced logo hover effect
      $('#logoContainer').hover(
        function() {
          $(this).find('img:first-child').css('transform', 'scale(1.1) rotate(-3deg)');
          $(this).find('img:last-child').css('transform', 'scale(1.1) rotate(3deg)');
        },
        function() {
          $(this).find('img').css('transform', 'scale(1) rotate(0)');
        }
      );
      
      // Add input animation and focus effects
      $('.form-group').append('<span class="input-focus-effect"></span>');
      
      $('.form-control').focus(function() {
        $(this).parent().find('.input-focus-effect').css('width', '100%').css('left', '0');
        $(this).parent().find('.input-icon').css('color', 'var(--primary)');
        $(this).css('border-color', 'var(--primary)');
      }).blur(function() {
        $(this).parent().find('.input-focus-effect').css('width', '0').css('left', '50%');
        if (!$(this).val()) {
          $(this).parent().find('.input-icon').css('color', '#ccc');
          $(this).css('border-color', 'rgba(234, 234, 234, 0.8)');
        }
      });
      
      // Create floating particles for background effect
      function createParticles() {
        const particles = $('#particles');
        const maxParticles = 30;
        
        for (let i = 0; i < maxParticles; i++) {
          const size = Math.random() * 5 + 1;
          const speed = Math.random() * 2 + 0.5;
          const left = Math.random() * 100;
          const delay = Math.random() * 15;
          
          const particle = $('<div class="particle"></div>');
          particle.css({
            'width': size + 'px',
            'height': size + 'px',
            'left': left + '%',
            'top': '-10px',
            'opacity': Math.random() * 0.5 + 0.2,
            'animation': 'float ' + speed + 's linear ' + delay + 's infinite'
          });
          
          particles.append(particle);
        }
      }
      
      // Add floating animation for particles
      $('<style>')
        .text('@keyframes float { 0% { top: -10px; } 100% { top: 100%; } }')
        .appendTo('head');
      
      createParticles();
      
      // Add hover interaction to the form elements
      $('.form-control, .tab-btn, .forgot-link').hover(
        function() {
          $(this).css('transition', 'all 0.3s ease');
          if ($(this).hasClass('form-control')) {
            $(this).css('border-color', 'rgba(67, 97, 238, 0.5)');
          }
        },
        function() {
          if ($(this).hasClass('form-control') && !$(this).is(':focus')) {
            $(this).css('border-color', 'rgba(234, 234, 234, 0.8)');
          }
        }
      );
      
      // Interactive form checkbox
      $('.form-check-input').change(function() {
        if ($(this).is(':checked')) {
          $(this).next().css('color', 'var(--primary)');
        } else {
          $(this).next().css('color', '');
        }
      });
      
      // Add dynamic background effect on mouse move
      $(document).mousemove(function(e) {
        const moveX = (e.pageX * -1 / 30);
        const moveY = (e.pageY * -1 / 30);
        $('body').css('background-position', moveX + 'px ' + moveY + 'px');
      });
    });
  </script>
</body>
</html>