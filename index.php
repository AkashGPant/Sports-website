<?php
session_start();
$authUser = $_SESSION['auth_user'] ?? null;
$authJson = json_encode($authUser);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SSNAPP — Live Sports Streaming</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- ============================================================
     NAV
     ============================================================ -->
<nav id="nav">
  <a class="nav-logo" href="#">
    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="16" cy="16" r="15" fill="#f47920" opacity=".15"/>
      <path d="M8 10 L16 6 L24 10 L24 18 L16 26 L8 18 Z" fill="#f47920" opacity=".7"/>
      <circle cx="16" cy="16" r="5" fill="#f47920"/>
    </svg>
    SSN<span>APP</span>
  </a>

  <div class="nav-tabs" id="navTabs">
    <button class="nav-tab active" data-tab="home">Home</button>
    <button class="nav-tab" data-tab="cricket">Cricket</button>
    <button class="nav-tab" data-tab="football">Football</button>
    <button class="nav-tab" data-tab="f1">Formula 1</button>
    <button class="nav-tab" data-tab="scores">Live Scores</button>
  </div>

  <div class="nav-right">
    <div class="nav-search">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <input type="text" placeholder="Search teams, matches…" id="searchInput"/>
    </div>

    <!-- Logged OUT buttons -->
    <div id="authButtons">
      <button class="btn btn-ghost" onclick="openModal('login')">Sign In</button>
      <button class="btn btn-accent" onclick="openModal('register')">Join Free</button>
    </div>

    <!-- Logged IN avatar (hidden until auth) -->
    <div id="userMenu" style="display:none;position:relative">
      <div class="avatar" id="avatarBtn"></div>
      <div class="avatar-menu" id="avatarMenu">
        <div class="avatar-menu-name" id="menuName">
          User<br><span class="avatar-menu-sub" id="menuEmail"></span>
        </div>
        <div class="avatar-menu-item" onclick="showToast('Coming soon!','info')">
          <span>👤</span> My Profile
        </div>
        <div class="avatar-menu-item" onclick="showToast('Upgrade coming soon!','info')">
          <span>⚡</span> Upgrade Plan
        </div>
        <div class="avatar-menu-item" onclick="showToast('Settings coming soon!','info')">
          <span>⚙️</span> Settings
        </div>
        <hr style="border:none;border-top:1px solid var(--border);margin:6px 0">
        <div class="avatar-menu-item danger" onclick="doLogout()">
          <span>🚪</span> Sign Out
        </div>
      </div>
    </div>

    <!-- Hamburger -->
    <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- Mobile Nav -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-tab active" data-tab="home">🏠 Home</button>
  <button class="mobile-tab" data-tab="cricket">🏏 Cricket</button>
  <button class="mobile-tab" data-tab="football">⚽ Football</button>
  <button class="mobile-tab" data-tab="f1">🏎 Formula 1</button>
  <button class="mobile-tab" data-tab="scores">📊 Live Scores</button>
</div>

<!-- ============================================================
     HERO
     ============================================================ -->
<section id="hero">
  <div class="hero-gfx"></div>
  <div class="hero-content">
    <div class="hero-badge">🔴 3 Matches Live Now</div>
    <h1 class="hero-title">
      Your Sports.<br>
      <em>Your Stream.</em>
    </h1>
    <p class="hero-desc">
      Watch live Cricket, Football, Formula 1 and more in stunning HD. 
      Never miss a moment with real-time scores and match alerts.
    </p>
    <div class="hero-actions">
      <button class="btn btn-accent btn-lg" onclick="openModal('register')" id="heroJoinBtn">
        ▶ Start Watching
      </button>
      <button class="btn btn-ghost btn-lg" onclick="scrollToMatches()">
        See All Matches
      </button>
    </div>
  </div>

  <!-- Live Score Ticker -->
  <div class="ticker">
    <div class="ticker-label">🔴 Live</div>
    <div class="ticker-track" id="tickerTrack"></div>
  </div>
</section>

<!-- ============================================================
     MAIN
     ============================================================ -->
<main id="main">

  <!-- Promo Band -->
  <div class="promo-band">
    <div class="promo-left">
      <div class="promo-eyebrow">⚡ SSNAPP Pro</div>
      <div class="promo-h">Watch Every<br>Match in 4K HDR</div>
      <p class="promo-p">Get ad-free streams, multi-device access, DVR replay, and exclusive behind-the-scenes content.</p>
      <button class="btn btn-accent" onclick="showToast('Pro plan coming soon!','info')">Upgrade to Pro — ₹299/mo</button>
    </div>
    <div class="promo-right">
      <div class="promo-number">4K</div>
      <div style="display:flex;gap:40px;position:relative">
        <div class="promo-stat">
          <span class="promo-stat-n">50+</span>
          <div class="promo-stat-l">Sports</div>
        </div>
        <div class="promo-stat">
          <span class="promo-stat-n">1080p</span>
          <div class="promo-stat-l">Free Plan</div>
        </div>
        <div class="promo-stat">
          <span class="promo-stat-n">24/7</span>
          <div class="promo-stat-l">Live</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Pills -->
  <div class="filters" id="filterPills">
    <button class="pill active" data-filter="all">All Matches</button>
    <button class="pill" data-filter="live">🔴 Live</button>
    <button class="pill" data-filter="upcoming">Upcoming</button>
    <button class="pill" data-filter="completed">Completed</button>
  </div>

  <!-- ── Cricket Section ── -->
  <div class="sport-section" id="sec-cricket">
    <div class="section-head">
      <div class="section-title">
        <div class="icon" style="background:rgba(244,121,32,.15);font-size:20px">🏏</div>
        Cricket
        <span class="section-count" id="cricket-count">–</span>
      </div>
      <button class="see-all" onclick="filterBy('cricket')">View all →</button>
    </div>
    <div class="cards-grid" id="cricket-grid"></div>
  </div>

  <!-- ── Football Section ── -->
  <div class="sport-section" id="sec-football">
    <div class="section-head">
      <div class="section-title">
        <div class="icon" style="background:rgba(46,196,182,.12);font-size:20px">⚽</div>
        Football
        <span class="section-count" id="football-count">–</span>
      </div>
      <button class="see-all" onclick="filterBy('football')">View all →</button>
    </div>
    <div class="cards-grid" id="football-grid"></div>
  </div>

  <!-- ── Formula 1 Section ── -->
  <div class="sport-section" id="sec-f1">
    <div class="section-head">
      <div class="section-title">
        <div class="icon" style="background:rgba(230,57,70,.12);font-size:20px">🏎</div>
        Formula 1
        <span class="section-count" id="f1-count">–</span>
      </div>
      <button class="see-all" onclick="filterBy('f1')">View all →</button>
    </div>
    <div class="cards-grid" id="f1-grid"></div>
  </div>

</main>

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer>
  <div class="footer-brand">SSN<span>APP</span></div>
  <div class="footer-links">
    <span class="footer-link">About</span>
    <span class="footer-link">Help Center</span>
    <span class="footer-link">Privacy Policy</span>
    <span class="footer-link">Terms of Use</span>
    <span class="footer-link">Contact</span>
  </div>
  <div class="footer-copy">© 2025 SSNAPP. All rights reserved.</div>
</footer>

<!-- ============================================================
     MODALS — Login & Register
     ============================================================ -->
<div class="overlay" id="overlay" onclick="closeModalOutside(event)">

  <!-- Login Modal -->
  <div class="modal" id="loginModal">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="modal-logo">SSN<span>APP</span></div>
    <div class="modal-sub">Welcome back. Sign in to continue.</div>

    <div class="demo-hint">
      <strong>Demo account:</strong><br>
      demo@ssnapp.com / demo1234
    </div>

    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input class="form-input" type="email" id="loginEmail" placeholder="you@example.com" autocomplete="email"/>
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" id="loginPass" placeholder="••••••••" autocomplete="current-password"/>
    </div>
    <div class="form-error" id="loginError"></div>
    <button class="btn btn-accent btn-full" id="loginBtn" onclick="doLogin()">Sign In</button>

    <div class="modal-switch">
      Don't have an account? <a onclick="openModal('register')">Create one free</a>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal" id="registerModal" style="display:none">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="modal-logo">SSN<span>APP</span></div>
    <div class="modal-sub">Create your free account. No credit card needed.</div>

    <div class="form-group">
      <label class="form-label">Full Name</label>
      <input class="form-input" type="text" id="regName" placeholder="Virat Kohli" autocomplete="name"/>
    </div>
    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input class="form-input" type="email" id="regEmail" placeholder="you@example.com" autocomplete="email"/>
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" id="regPass" placeholder="Min 6 characters" autocomplete="new-password"/>
    </div>
    <div class="form-error" id="registerError"></div>
    <button class="btn btn-accent btn-full" id="registerBtn" onclick="doRegister()">Create Account</button>

    <div class="modal-switch">
      Already have an account? <a onclick="openModal('login')">Sign in</a>
    </div>
  </div>

</div>

<!-- Toast Container -->
<div id="toast-container"></div>

<!-- ============================================================
     JAVASCRIPT
     ============================================================ -->
<script>let currentUser = <?=$authJson?>;</script>
<script src="assets/js/app.js"></script>
</body>
</html>
