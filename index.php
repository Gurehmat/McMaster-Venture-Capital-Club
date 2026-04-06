<?php
/**
 * index.php — McMaster Venture Capital Club
 * Single-scroll public page.
 */
require_once __DIR__ . '/includes/site.php';
$baseUrl = mvcc_base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="McMaster Venture Capital Club — empowering the next generation of entrepreneurs and investors at McMaster University.">
  <title>McMaster Venture Capital Club</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles (overrides Bootstrap branding) -->
  <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>assets/css/style.css">

  <!-- Favicon placeholder -->
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏛️</text></svg>">
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════════ -->
<nav id="mainNav" class="navbar navbar-expand-lg fixed-top" aria-label="Main navigation">
  <div class="container">
    <a class="navbar-brand" href="#hero">
      <span>MVCC</span> &mdash; McMaster Venture Capital
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#roadmap">Roadmap</a></li>
        <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
        <li class="nav-item"><a class="nav-link" href="#partners">Partners</a></li>
        <li class="nav-item"><a class="nav-link" href="#join">Join Us</a></li>
        <li class="nav-item ms-lg-2">
           <a class="nav-link btn-mvcc-outline btn-mvcc-sm d-inline-block mt-1 mt-lg-0"
             href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>events.php">All Events</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 1 — HERO
═══════════════════════════════════════════════════════════ -->
<section id="hero">
  <div class="container">
    <div class="hero-content">
      <p class="hero-eyebrow">McMaster University · Hamilton, Ontario</p>
      <h1 class="hero-title">
        Shaping Tomorrow's<br>
        <span class="accent">Venture Leaders</span>
      </h1>
      <p class="hero-description">
        MVCC is McMaster's premier venture capital club — bridging students with founders,
        investors, and the innovations defining tomorrow's economy.
      </p>
      <div class="hero-cta">
        <a href="#join" class="btn-mvcc-primary">Join Us</a>
        <a href="https://linktr.ee/macventurecapital" target="_blank" rel="noopener"
           class="btn-mvcc-outline">Linktree ↗</a>
      </div>
    </div>
  </div>
  <div class="hero-scroll-hint" aria-hidden="true">
    <span>Scroll</span>
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
    </svg>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     STATS BAR
═══════════════════════════════════════════════════════════ -->
<div class="stats-bar">
  <div class="container">
    <div class="row g-0 text-center">
      <div class="col-4">
        <div class="stat-item">
          <div class="stat-number">7</div>
          <div class="stat-label">Executives</div>
        </div>
      </div>
      <div class="col-4">
        <div class="stat-item">
          <div class="stat-number">4</div>
          <div class="stat-label">Analysts</div>
        </div>
      </div>
      <div class="col-4">
        <div class="stat-item">
          <div class="stat-number">100<span style="font-size:1.2rem">+</span></div>
          <div class="stat-label">General Members</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 2 — HISTORY & MISSION
═══════════════════════════════════════════════════════════ -->
<section id="about" class="mvcc-section mvcc-section--dark">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="section-subtitle">Our Story</p>
        <h2 class="section-title">History &amp; Mission</h2>
        <hr class="section-divider">
      </div>
    </div>
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <div class="history-text">
          <p>
            McMaster Venture Capital Club was founded in 2024 by a group of passionate
            McMaster students who recognized a gap: world-class engineering, business,
            and science talent at Mac — yet no organized hub connecting that talent with
            the venture ecosystem.
          </p>
          <p>
            Co-founders Veer Sarin, Diya Shah, and Benicio Uhart set out to build a club
            that would do more than host networking events. MVCC was designed from day one
            to deliver real deal exposure, mentorship from active investors, and a pipeline
            of student-led projects worthy of seed funding.
          </p>
          <p>
            In our first semester we partnered with Build Canada, The Forge at McMaster,
            and Golden Ventures — signing on 100+ members and hosting flagship events
            featuring founders and VCs from across the Canadian ecosystem.
          </p>
          <p>
            Our mission is simple: <strong style="color:var(--gold)">democratize access to venture capital
            knowledge</strong> for every McMaster student, regardless of faculty or
            background.
          </p>
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <!-- Decorative quote block -->
        <blockquote style="border-left: 3px solid var(--gold); padding: 1.5rem 2rem; background:var(--surface-2); border-radius:0 4px 4px 0;">
          <p style="font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--white); margin:0 0 1rem;">
            "We didn't just want to learn about venture capital —<br>we wanted to <em>live</em> it."
          </p>
          <cite style="color:var(--gold); font-size:.83rem; letter-spacing:.1em; text-transform:uppercase;">
            — MVCC Co-Founders
          </cite>
        </blockquote>
        <div class="mt-4 d-flex gap-2 flex-wrap">
          <span class="badge-gold">Est. 2024</span>
          <span class="badge-gold">Hamilton, ON</span>
          <span class="badge-gold">McMaster University</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 3 — 5-YEAR ROADMAP
═══════════════════════════════════════════════════════════ -->
<section id="roadmap" class="mvcc-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="section-subtitle">Strategic Vision</p>
        <h2 class="section-title">5-Year Roadmap</h2>
        <hr class="section-divider">
      </div>
    </div>

    <ul class="roadmap-timeline list-unstyled" aria-label="MVCC Five-Year Roadmap">

      <!-- Year 1 -->
      <li class="roadmap-item">
        <div class="roadmap-card">
          <div class="roadmap-year">Year 1 <span style="font-size:.85rem;color:var(--text-muted)">· 2024–25</span></div>
          <h4>Foundation &amp; Awareness</h4>
          <p>
            Launch the club, recruit founding executives, onboard 100+ general members,
            establish brand identity, and host inaugural speaker series with Canadian VCs
            and startup founders.
          </p>
        </div>
        <div class="roadmap-dot" aria-hidden="true"></div>
      </li>

      <!-- Year 2 -->
      <li class="roadmap-item">
        <div class="roadmap-dot d-none d-md-block" aria-hidden="true"></div>
        <div class="roadmap-card">
          <div class="roadmap-year">Year 2 <span style="font-size:.85rem;color:var(--text-muted)">· 2025–26</span></div>
          <h4>Education &amp; Deal Flow</h4>
          <p>
            Launch structured VC education curriculum (due diligence, cap tables, term
            sheets), build a student analyst program, and source the first batch of
            McMaster student startup pitches for partner review.
          </p>
        </div>
      </li>

      <!-- Year 3 -->
      <li class="roadmap-item">
        <div class="roadmap-card">
          <div class="roadmap-year">Year 3 <span style="font-size:.85rem;color:var(--text-muted)">· 2026–27</span></div>
          <h4>Student Fund Pilot</h4>
          <p>
            Partner with McMaster Student Seed Fund and external LPs to pilot a
            student-managed micro-fund. Execute the club's first real investment
            into a student-founded company.
          </p>
        </div>
        <div class="roadmap-dot" aria-hidden="true"></div>
      </li>

      <!-- Year 4 -->
      <li class="roadmap-item">
        <div class="roadmap-dot d-none d-md-block" aria-hidden="true"></div>
        <div class="roadmap-card">
          <div class="roadmap-year">Year 4 <span style="font-size:.85rem;color:var(--text-muted)">· 2027–28</span></div>
          <h4>National Presence</h4>
          <p>
            Co-host the first McMaster x Toronto Tech Week student summit, build
            inter-university VC network (Waterloo, Queens, UofT), and launch
            a national student VC pitch competition.
          </p>
        </div>
      </li>

      <!-- Year 5 -->
      <li class="roadmap-item">
        <div class="roadmap-card">
          <div class="roadmap-year">Year 5 <span style="font-size:.85rem;color:var(--text-muted)">· 2028–29</span></div>
          <h4>Institutional Recognition</h4>
          <p>
            Establish MVCC as a nationally recognized student investment vehicle.
            Alumni-backed fund with $500k+ AUM. Full-time placement pipeline
            into top Canadian and US venture firms.
          </p>
        </div>
        <div class="roadmap-dot" aria-hidden="true"></div>
      </li>

    </ul>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 4 — PAST EVENTS
═══════════════════════════════════════════════════════════ -->
<section id="events" class="mvcc-section mvcc-section--dark">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="section-subtitle">What We've Been Up To</p>
        <h2 class="section-title">Past Events</h2>
        <hr class="section-divider">
      </div>
    </div>
    <div id="events-grid" class="row">
      <!-- Populated by main.js via /api/get_events.php -->
    </div>
    <div class="text-center mt-3">
      <a href="events.php" class="btn-mvcc-outline">View All Events →</a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 5 — EXECUTIVE TEAM
═══════════════════════════════════════════════════════════ -->
<section id="team" class="mvcc-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="section-subtitle">The People Behind MVCC</p>
        <h2 class="section-title">Executive Team</h2>
        <hr class="section-divider">
      </div>
    </div>
    <div id="exec-grid" class="row justify-content-center">
      <!-- Populated by main.js via /api/get_executives.php -->
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 6 — PARTNERS
═══════════════════════════════════════════════════════════ -->
<section id="partners" class="mvcc-section mvcc-section--dark">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center mb-5">
        <p class="section-subtitle">Our Network</p>
        <h2 class="section-title">Partners &amp; Associates</h2>
        <hr class="section-divider">
      </div>
    </div>

    <div class="mb-4">
      <p class="partner-row-title">Partners</p>
      <div id="partners-row" class="partners-flex">
        <!-- Populated by main.js via /api/get_partners.php -->
      </div>
    </div>

    <div>
      <p class="partner-row-title">Associated With</p>
      <div id="associated-row" class="partners-flex">
        <!-- Populated by main.js via /api/get_partners.php -->
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     SECTION 7 — JOIN US
═══════════════════════════════════════════════════════════ -->
<section id="join" class="mvcc-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 text-center mb-5">
        <p class="section-subtitle">Become a Member</p>
        <h2 class="section-title">Join MVCC</h2>
        <hr class="section-divider">
        <p style="color:var(--text-muted); font-size:.9rem;">
          Fill out the form below and we'll reach out with next steps. Membership is
          open to all McMaster students — any faculty, any year.
        </p>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-7">

        <div id="form-message" role="alert" aria-live="polite"></div>

        <form id="join-form" class="form-mvcc" novalidate autocomplete="off">
          <div class="mb-3">
            <label for="input-fullname" class="form-label">Full Name <span style="color:var(--red-light)">*</span></label>
            <input type="text" class="form-control" id="input-fullname"
                   placeholder="Jane Smith" required maxlength="150">
            <div class="invalid-feedback">Please enter your full name.</div>
          </div>

          <div class="mb-3">
            <label for="input-email" class="form-label">McMaster Email <span style="color:var(--red-light)">*</span></label>
            <input type="email" class="form-control" id="input-email"
                   placeholder="smithj@mcmaster.ca" required maxlength="255" pattern=".+@mcmaster\.ca$">
            <div id="email-feedback" style="display:none; font-size:.8rem; margin-top:.3rem;"></div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-sm-7">
              <label for="input-program" class="form-label">Program</label>
              <input type="text" class="form-control" id="input-program"
                     placeholder="e.g. Commerce, Software Engineering" maxlength="150">
            </div>
            <div class="col-sm-5">
              <label for="input-year" class="form-label">Year of Study</label>
              <select class="form-select" id="input-year">
                <option value="">Select year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
                <option value="5">Graduate</option>
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label for="input-interest" class="form-label">Area of Interest</label>
            <input type="text" class="form-control" id="input-interest"
                   placeholder="e.g. Deep Tech, SaaS, Climate, FinTech…" maxlength="150">
          </div>

          <button type="submit" id="join-submit" class="btn-mvcc-primary w-100">
            Join MVCC
          </button>

          <p class="text-center mt-3" style="font-size:.75rem; color:var(--text-muted);">
            We respect your privacy. Your information will never be sold or shared.
          </p>
        </form>

      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ -->
<footer>
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-md-6">
        <div class="footer-brand"><span>MVCC</span> McMaster Venture Capital Club</div>
        <p class="footer-text">© <?= date('Y') ?> McMaster Venture Capital Club. Hamilton, Ontario.</p>
      </div>
      <div class="col-md-6">
        <ul class="footer-links">
          <li><a href="#about">About</a></li>
          <li><a href="#events">Events</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="https://linktr.ee/macventurecapital" target="_blank" rel="noopener">Linktree</a></li>
          <li><a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>admin/login.php">Admin</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- MVCC main script -->
<script>window.MVCC_BASE_URL = <?= json_encode($baseUrl) ?>;</script>
<script src="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>assets/js/main.js"></script>
</body>
</html>
