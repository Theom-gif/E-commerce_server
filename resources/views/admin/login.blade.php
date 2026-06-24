<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: #6C63FF;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    /* ── Shell ───────────────────────────────────── */
    .login {
      background: #fff;
      border-radius: 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      width: 100%;
      max-width: 900px;
      min-height: 520px;
      overflow: hidden;
      box-shadow: 0 24px 64px rgba(108, 99, 255, .25);
    }
    @media (max-width: 640px) {
      .login { grid-template-columns: 1fr; }
      .login-left { display: none; }
    }

    /* ── Left – illustration panel ───────────────── */
    .login-left {
      background: #F0EFFF;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }
    .login-left svg {
      width: 100%;
      max-width: 280px;
    }

    /* ── Right – form panel ──────────────────────── */
    .login-right {
      padding: 44px 48px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    /* Logo */
    .login-logo {
      position: absolute;
      top: 28px;
      left: 28px;
      display: flex;
      align-items: center;
      gap: 7px;
      color: #6C63FF;
      font-weight: 700;
      font-size: 13px;
      letter-spacing: .1em;
      text-decoration: none;
    }
    .login-logo i { font-size: 15px; }

    /* Top signup link */
    .login-signup {
      position: absolute;
      top: 28px;
      right: 28px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 12px;
      color: #999;
    }
    .login-signup a {
      border: 1.5px solid #ddd;
      border-radius: 999px;
      padding: 5px 18px;
      font-size: 12px;
      font-weight: 600;
      color: #444;
      text-decoration: none;
      transition: border-color .15s, color .15s;
    }
    .login-signup a:hover { border-color: #6C63FF; color: #6C63FF; }

    /* Headings */
    .login-right h1 {
      font-size: 26px;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 4px;
      letter-spacing: -.3px;
    }
    .login-right .subtitle {
      font-size: 13px;
      color: #9e9ec8;
      margin-bottom: 28px;
    }

    /* Alert */
    .alert {
      background: #FCEBEB;
      color: #7F1D1D;
      border: 1px solid #FECACA;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 12px;
      margin-bottom: 18px;
    }

    /* Fields */
    .field { margin-bottom: 18px; }
    .field label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #4a4a6a;
      margin-bottom: 7px;
    }
    .input-wrap { position: relative; }
    .input-wrap input {
      width: 100%;
      border: 1.5px solid #e8e8f0;
      border-radius: 10px;
      padding: 12px 40px 12px 14px;
      font-size: 13px;
      color: #333;
      background: #fafafa;
      outline: none;
      transition: border-color .18s, background .18s;
    }
    .input-wrap input::placeholder { color: #c0bfe0; }
    .input-wrap input:focus {
      border-color: #6C63FF;
      background: #fff;
    }
    .input-wrap .toggle-pw {
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #c0bfe0;
      font-size: 14px;
      cursor: pointer;
      background: none;
      border: none;
      padding: 0;
      line-height: 1;
    }
    .input-wrap .toggle-pw:hover { color: #6C63FF; }

    /* Forgot */
    .forgot {
      font-size: 12px;
      color: #9e9ec8;
      text-align: right;
      margin-top: -10px;
      margin-bottom: 22px;
    }
    .forgot a { color: inherit; text-decoration: none; }
    .forgot a:hover { color: #6C63FF; }

    /* Submit button */
    .btn-login {
      width: 100%;
      background: #6C63FF;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 13px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      letter-spacing: .02em;
      transition: background .18s, transform .12s;
    }
    .btn-login:hover { background: #5a52e0; transform: translateY(-1px); }
    .btn-login:active { transform: scale(.98); }

    /* Social login */
    .social-row {
      margin-top: 28px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .social-label { font-size: 12px; color: #b0afd0; white-space: nowrap; }
    .social-btn {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 1.5px solid #e8e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
      transition: border-color .15s, background .15s;
    }
    .social-btn:hover { border-color: #6C63FF; background: #F0EFFF; }
    .social-fb { color: #1877F2; }
    .social-li { color: #0A66C2; }
    .social-gg { color: #EA4335; }
  </style>
</head>
<body>

<div class="login">

  {{-- Left: illustration --}}
  <div class="login-left">
    <svg viewBox="0 0 280 260" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <ellipse cx="140" cy="200" rx="100" ry="22" fill="#DDD9FF" opacity=".6"/>
      <rect x="60" y="30" width="110" height="150" rx="10" fill="#fff" stroke="#C4BFFF" stroke-width="1.5"/>
      <rect x="60" y="30" width="110" height="22" rx="10" fill="#6C63FF"/>
      <rect x="60" y="40" width="110" height="12" rx="0" fill="#6C63FF"/>
      <circle cx="115" cy="41" r="5" fill="#fff" opacity=".5"/>
      <rect x="75" y="72" width="50" height="6" rx="3" fill="#E8E6FF"/>
      <rect x="75" y="85" width="80" height="4" rx="2" fill="#F0EFFF"/>
      <rect x="75" y="95" width="70" height="4" rx="2" fill="#F0EFFF"/>
      <rect x="75" y="105" width="60" height="4" rx="2" fill="#F0EFFF"/>
      <circle cx="90" cy="60" r="10" fill="#E8E6FF"/>
      <circle cx="90" cy="57" r="5" fill="#C4BFFF"/>
      <path d="M80 68 Q90 65 100 68" stroke="#C4BFFF" stroke-width="1.5" fill="none"/>
      <rect x="148" y="90" width="55" height="72" rx="8" fill="#4FC3F7" opacity=".85"/>
      <circle cx="175" cy="115" r="10" fill="#B3E5FC"/>
      <circle cx="175" cy="112" r="5" fill="#81D4FA"/>
      <path d="M163 128 Q175 124 187 128" stroke="#81D4FA" stroke-width="1.5" fill="none"/>
      <rect x="90" y="175" width="60" height="5" rx="2.5" fill="#C4BFFF" opacity=".7"/>
      <rect x="98" y="183" width="45" height="4" rx="2" fill="#D9D6FF" opacity=".5"/>
      <line x1="82" y1="170" x2="70" y2="195" stroke="#F48FB1" stroke-width="3" stroke-linecap="round"/>
      <ellipse cx="69" cy="196" rx="5" ry="3" fill="#F06292" opacity=".7"/>
      <rect x="40" y="150" width="14" height="14" rx="3" fill="#C4BFFF" opacity=".4" transform="rotate(-20 40 150)"/>
      <rect x="200" y="70" width="10" height="10" rx="2" fill="#A5A0FF" opacity=".4" transform="rotate(15 200 70)"/>
      <rect x="175" y="185" width="12" height="12" rx="2" fill="#C4BFFF" opacity=".4" transform="rotate(-10 175 185)"/>
      <rect x="48" y="100" width="8" height="8" rx="2" fill="#A5A0FF" opacity=".3" transform="rotate(30 48 100)"/>
    </svg>
  </div>

  {{-- Right: form --}}
  <div class="login-right">

    <a href="/" class="login-logo">
      <i class="fas fa-asterisk"></i> FOCUS
    </a>

    <div class="login-signup">
      Don't have an account?
      <a href="/register">Sign up</a>
    </div>

    <h1>Welcome back</h1>
    <p class="subtitle">Login to your account</p>

    @if($errors->any())
      <div class="alert">{{ $errors->first('email') }}</div>
    @endif

    <form action="/admin/login" method="POST">
      @csrf

      <div class="field">
        <label for="email">Username</label>
        <div class="input-wrap">
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Your email"
            value="{{ old('email') }}"
            required
            autofocus
          >
        </div>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="input-wrap">
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Your password"
            required
          >
          <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Show password">
            <i class="fas fa-eye" id="eyeIcon"></i>
          </button>
        </div>
      </div>

      <div class="forgot">
        <a href="/admin/forgot-password">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="social-row">
      <span class="social-label">Login with</span>
      <a href="/auth/facebook" class="social-btn social-fb"><i class="fab fa-facebook-f"></i></a>
      <a href="/auth/linkedin"  class="social-btn social-li"><i class="fab fa-linkedin-in"></i></a>
      <a href="/auth/google"    class="social-btn social-gg"><i class="fab fa-google"></i></a>
    </div>

  </div>
</div>

<script>
  function togglePassword() {
    const pw   = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      pw.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }
</script>

</body>
</html>