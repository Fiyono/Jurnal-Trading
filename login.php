<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  
  <title>Login • Trading Journal Pro</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    /* ======================
       VARIABLES & RESET
    ====================== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #6366f1;
      --primary-light: #818cf8;
      --primary-dark: #4f46e5;
      --secondary: #8b5cf6;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --dark: #0b1120;
      --dark-card: #1a2332;
      --dark-light: #2d3748;
      --text-primary: #f8fafc;
      --text-secondary: #94a3b8;
      --text-muted: #64748b;
      --border-color: #334155;
      --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      --gradient-glow: linear-gradient(135deg, rgba(99, 102, 241, 0.5) 0%, rgba(139, 92, 246, 0.5) 100%);
      --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
      --shadow-lg: 0 30px 60px -15px rgba(0, 0, 0, 0.6);
      --shadow-glow: 0 0 30px rgba(99, 102, 241, 0.3);
      --blur: blur(12px);
      --radius-sm: 16px;
      --radius-md: 24px;
      --radius-lg: 32px;
      --radius-full: 9999px;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      position: relative;
      overflow: hidden;
    }

    /* ======================
       BACKGROUND FOTO dari assets/login.png
    ====================== */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('assets/login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      z-index: -2;
    }

    /* OPSI 1: Dengan overlay gelap (lebih fokus ke card) */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                  url('assets/login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      z-index: -2;
    }

    /* OPSI 2: Dengan overlay gradient warna primary (lebih artistik) */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.7), rgba(139, 92, 246, 0.7)), 
                  url('assets/login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      z-index: -2;
    }

    /* OPSI 3: Efek blur pada background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('assets/login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      filter: blur(5px);
      transform: scale(1.1);
      z-index: -2;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                  url('assets/login.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      filter: blur(1px);
      transform: scale(1.05);
      z-index: -2;
    }

    /* Hapus atau komen bagian orb animation */
    .orb-2 {
      display: none;
    }

    /* Main Container */
    .login-container {
      width: 100%;
      max-width: 440px;
      perspective: 1000px;
    }

    /* Login Card */
    .login-card {
      background: rgba(26, 35, 50, 0.85);
      backdrop-filter: var(--blur);
      -webkit-backdrop-filter: var(--blur);
      border-radius: var(--radius-lg);
      padding: 40px 32px;
      box-shadow: var(--shadow-lg), var(--shadow-glow);
      border: 1px solid rgba(255, 255, 255, 0.1);
      position: relative;
      overflow: hidden;
      animation: cardAppear 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
      transform-style: preserve-3d;
    }

    @keyframes cardAppear {
      0% {
        opacity: 0;
        transform: translateY(30px) rotateX(-10deg);
      }
      100% {
        opacity: 1;
        transform: translateY(0) rotateX(0);
      }
    }

    /* Decorative Elements */
    .card-glow {
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
      z-index: -1;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Logo/Brand Section */
    .brand {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo {
      width: 80px;
      height: 80px;
      background: var(--gradient);
      border-radius: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 20px 30px -10px rgba(99, 102, 241, 0.5);
      position: relative;
      animation: logoFloat 6s ease infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .logo i {
      font-size: 2.5rem;
      color: white;
    }

    .brand h1 {
      font-size: 2rem;
      font-weight: 800;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: -0.5px;
      margin-bottom: 8px;
    }

    .brand p {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    /* Form */
    .login-form {
      margin-bottom: 24px;
    }

    /* Input Groups */
    .input-group {
      margin-bottom: 20px;
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1.2rem;
      transition: all 0.3s ease;
      z-index: 1;
    }

    .input-field {
      width: 100%;
      padding: 18px 18px 18px 50px;
      background: rgba(45, 55, 72, 0.6);
      border: 2px solid var(--border-color);
      border-radius: var(--radius-md);
      color: var(--text-primary);
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: all 0.3s ease;
      backdrop-filter: var(--blur);
    }

    .input-field:focus {
      outline: none;
      border-color: var(--primary);
      background: rgba(45, 55, 72, 0.8);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
      padding-left: 52px;
    }

    .input-field:focus + .input-icon {
      color: var(--primary);
      transform: translateY(-50%) scale(1.1);
    }

    .input-field::placeholder {
      color: var(--text-muted);
      opacity: 0.7;
    }

    /* Floating Label Effect */
    .input-group {
      position: relative;
    }

    .input-group label {
      position: absolute;
      left: 50px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1rem;
      pointer-events: none;
      transition: all 0.3s ease;
      opacity: 0;
    }

    .input-field:focus ~ label,
    .input-field:not(:placeholder-shown) ~ label {
      top: 0;
      transform: translateY(-50%) scale(0.85);
      left: 16px;
      background: var(--dark-card);
      padding: 0 8px;
      color: var(--primary);
      opacity: 1;
    }

    /* Password Strength Indicator (optional) */
    .password-strength {
      height: 4px;
      background: var(--border-color);
      border-radius: var(--radius-full);
      margin-top: 8px;
      overflow: hidden;
    }

    .strength-bar {
      height: 100%;
      width: 0;
      background: var(--gradient);
      transition: width 0.3s ease;
    }

    /* Options Row */
    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--text-secondary);
      font-size: 0.9rem;
      cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--primary);
      border-radius: 6px;
      cursor: pointer;
    }

    .forgot-password {
      color: var(--primary-light);
      text-decoration: none;
      font-size: 0.9rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .forgot-password:hover {
      color: var(--primary);
      text-decoration: underline;
    }

    /* Login Button */
    .login-btn {
      width: 100%;
      padding: 18px;
      background: var(--gradient);
      border: none;
      border-radius: var(--radius-md);
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      position: relative;
      overflow: hidden;
    }

    .login-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }

    .login-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 30px -5px rgba(99, 102, 241, 0.7);
    }

    .login-btn:hover::before {
      left: 100%;
    }

    .login-btn i {
      font-size: 1.2rem;
      transition: transform 0.3s ease;
    }

    .login-btn:hover i {
      transform: translateX(5px);
    }

    /* Loading State */
    .login-btn.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    .login-btn.loading i {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Divider */
    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      color: var(--text-muted);
      font-size: 0.9rem;
      margin: 24px 0;
    }

    .divider-line {
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--border-color), transparent);
    }

    /* Social Login (optional) */
    .social-login {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .social-btn {
      background: rgba(45, 55, 72, 0.6);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-sm);
      padding: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-secondary);
      text-decoration: none;
      transition: all 0.3s ease;
      backdrop-filter: var(--blur);
    }

    .social-btn:hover {
      background: rgba(45, 55, 72, 0.8);
      border-color: var(--primary);
      transform: translateY(-3px);
      color: white;
    }

    .social-btn i {
      font-size: 1.3rem;
    }

    /* Register Link */
    .register-link {
      text-align: center;
      margin-top: 24px;
      color: var(--text-secondary);
      font-size: 0.95rem;
    }

    .register-link a {
      color: var(--primary-light);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      color: var(--primary);
      text-decoration: underline;
    }

    /* Alert Message */
    .alert {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      border-radius: var(--radius-sm);
      padding: 12px 16px;
      margin-bottom: 20px;
      color: #fecaca;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: shake 0.5s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .alert i {
      font-size: 1.2rem;
      color: var(--danger);
    }

    .alert.success {
      background: rgba(16, 185, 129, 0.1);
      border-color: rgba(16, 185, 129, 0.3);
      color: #a7f3d0;
    }

    .alert.success i {
      color: var(--success);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-card {
        padding: 30px 20px;
      }

      .brand h1 {
        font-size: 1.8rem;
      }

      .logo {
        width: 70px;
        height: 70px;
      }

      .logo i {
        font-size: 2rem;
      }

      .options-row {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
      }

      .social-login {
        gap: 8px;
      }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: var(--dark);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--border-color);
      border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary);
    }
  </style>
</head>
<body>
  <!-- Hapus bagian orb animation -->
  <!-- 
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>
  -->

  <div class="login-container">
    <div class="login-card">
      <div class="card-glow"></div>
      
      <!-- Brand Section -->
      <div class="brand">
        <div class="logo">
          <i class="fas fa-chart-line"></i>
        </div>
        <h1>TRADING</h1>
      </div>

      <!-- Alert Message -->
      <?php if (isset($_GET['error'])): ?>
      <div class="alert">
        <i class="fas fa-exclamation-circle"></i>
        <span>Username atau password salah!</span>
      </div>
      <?php endif; ?>

      <?php if (isset($_GET['success'])): ?>
      <div class="alert success">
        <i class="fas fa-check-circle"></i>
        <span>Login berhasil! Mengalihkan...</span>
      </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="cek_login.php" method="POST" class="login-form">
        <!-- Username Input -->
        <div class="input-group">
          <i class="fas fa-user input-icon"></i>
          <input 
            type="text" 
            name="username" 
            class="input-field" 
            placeholder="Username"
            required
            autocomplete="username"
          >
          <label>Username</label>
        </div>

        <!-- Password Input -->
        <div class="input-group">
          <i class="fas fa-lock input-icon"></i>
          <input 
            type="password" 
            name="password" 
            class="input-field" 
            placeholder="Password"
            required
            autocomplete="current-password"
            id="password"
          >
          <label>Password</label>
        </div>

        <!-- Password Strength -->
        <div class="password-strength">
          <div class="strength-bar" id="strengthBar"></div>
        </div>

        <!-- Options Row -->
        <div class="options-row">
          <label class="remember-me">
            <input type="checkbox" name="remember">
            <span>Ingat saya</span>
          </label>
          <a href="#" class="forgot-password">Lupa password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="login-btn" id="loginBtn">
          <span>Masuk</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <!-- Divider -->
      <div class="divider">
        <span class="divider-line"></span>
        <span>atau masuk dengan</span>
        <span class="divider-line"></span>
      </div>

      <!-- Social Login -->
      <div class="social-login">
        <a href="#" class="social-btn">
          <i class="fab fa-google"></i>
        </a>
        <a href="#" class="social-btn">
          <i class="fab fa-github"></i>
        </a>
        <a href="#" class="social-btn">
          <i class="fab fa-facebook-f"></i>
        </a>
      </div>

      <!-- Register Link -->
      <div class="register-link">
        Belum punya akun? <a href="register.php">Daftar sekarang</a>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');

    if (passwordInput && strengthBar) {
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length > 0) strength += 20;
        if (password.length >= 8) strength += 20;
        if (password.match(/[a-z]/)) strength += 20;
        if (password.match(/[A-Z]/)) strength += 20;
        if (password.match(/[0-9]/)) strength += 20;
        
        strengthBar.style.width = strength + '%';
        
        if (strength <= 40) {
          strengthBar.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        } else if (strength <= 60) {
          strengthBar.style.background = 'linear-gradient(135deg, #f59e0b, #d97706)';
        } else {
          strengthBar.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        }
      });
    }

    // Loading state on form submit
    const loginForm = document.querySelector('.login-form');
    const loginBtn = document.getElementById('loginBtn');

    if (loginForm && loginBtn) {
      loginForm.addEventListener('submit', function(e) {
        loginBtn.classList.add('loading');
        loginBtn.innerHTML = '<i class="fas fa-spinner"></i><span>Memproses...</span>';
      });
    }

    // Smooth entrance animation
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('.input-field');
      inputs.forEach((input, index) => {
        input.style.animation = `fadeInUp 0.5s ease forwards ${index * 0.1}s`;
      });
    });
  </script>

  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .input-group {
      animation: fadeInUp 0.5s ease forwards;
      opacity: 0;
    }

    .input-group:nth-child(1) { animation-delay: 0.1s; }
    .input-group:nth-child(2) { animation-delay: 0.2s; }
    .options-row { animation: fadeInUp 0.5s ease forwards 0.3s; opacity: 0; }
    .login-btn { animation: fadeInUp 0.5s ease forwards 0.4s; opacity: 0; }
    .divider { animation: fadeInUp 0.5s ease forwards 0.5s; opacity: 0; }
    .social-login { animation: fadeInUp 0.5s ease forwards 0.6s; opacity: 0; }
    .register-link { animation: fadeInUp 0.5s ease forwards 0.7s; opacity: 0; }
  </style>
</body>
</html>