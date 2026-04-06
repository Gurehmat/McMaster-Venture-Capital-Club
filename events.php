<?php
require_once __DIR__ . '/includes/site.php';
$baseUrl = mvcc_base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="McMaster Venture Capital Club — Events archive.">
  <title>Events | McMaster Venture Capital Club</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>assets/css/style.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏛️</text></svg>">
</head>
<body>

<nav id="mainNav" class="navbar navbar-expand-lg fixed-top" aria-label="Main navigation">
  <div class="container">
    <a class="navbar-brand" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php">
      <span>MVCC</span> &mdash; McMaster Venture Capital
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#roadmap">Roadmap</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>events.php" aria-current="page">Events</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#team">Team</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#partners">Partners</a></li>
        <li class="nav-item ms-lg-2">
           <a class="nav-link btn-mvcc-primary btn-mvcc-sm d-inline-block mt-1 mt-lg-0"
             href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#join">Join Us</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div style="padding-top:100px; padding-bottom:60px; background:var(--surface); border-bottom:1px solid var(--border);">
  <div class="container">
    <p class="section-subtitle">McMaster Venture Capital Club</p>
    <h1 class="section-title" style="font-size:clamp(2rem,5vw,3.4rem);">Events</h1>
    <hr class="section-divider" style="margin:1rem 0 0;">
  </div>
</div>

<section class="mvcc-section">
  <div class="container">

    <div class="d-flex align-items-center gap-3 mb-5 flex-wrap">
      <input type="search" id="event-filter" class="form-control form-mvcc"
             style="max-width:320px;" placeholder="Search events…" aria-label="Filter events">
      <span id="event-count" style="color:var(--text-muted);font-size:.83rem;"></span>
    </div>

    <div id="events-all-grid" class="row g-4" aria-live="polite">

    </div>

    <div id="events-empty" class="text-center py-5" style="display:none; color:var(--text-muted);">
      No events match your search.
    </div>

  </div>
</section>

<footer>
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-md-6">
        <div class="footer-brand"><span>MVCC</span> McMaster Venture Capital Club</div>
        <p class="footer-text">© <?= date('Y') ?> McMaster Venture Capital Club. Hamilton, Ontario.</p>
      </div>
      <div class="col-md-6">
        <ul class="footer-links">
          <li><a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#about">About</a></li>
          <li><a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php#join">Join Us</a></li>
          <li><a href="https://linktr.ee/macventurecapital" target="_blank" rel="noopener">Linktree</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.MVCC_BASE_URL = <?= json_encode($baseUrl) ?>;</script>
<script>
(function () {
  const grid    = document.getElementById('events-all-grid');
  const empty   = document.getElementById('events-empty');
  const counter = document.getElementById('event-count');
  const filter  = document.getElementById('event-filter');
  let allEvents = [];

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
              .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }
  function resolveMediaUrl(value) {
    if (!value) return '';
    if (/^(?:https?:)?\/\//i.test(value) || value.startsWith('data:')) return value;
    const base = window.MVCC_BASE_URL || '/';
    return `${base}${String(value).replace(/^\/+/, '')}`;
  }
  function formatDate(d) {
    return new Date(d + 'T00:00:00').toLocaleDateString('en-CA', { year:'numeric', month:'long', day:'numeric' });
  }

  function render(events) {
    if (!events.length) {
      grid.innerHTML = '';
      empty.style.display = 'block';
      counter.textContent = '0 events';
      return;
    }
    empty.style.display = 'none';
    counter.textContent = `${events.length} event${events.length !== 1 ? 's' : ''}`;
    grid.innerHTML = events.map(ev => {
      const imgHtml = ev.image_url
        ? `<img src="${escapeHtml(resolveMediaUrl(ev.image_url))}" alt="${escapeHtml(ev.title)}" class="mvcc-card-img" style="height:220px;object-fit:cover;">`
        : `<div class="mvcc-card-img" style="height:220px;background:var(--surface);display:flex;align-items:center;justify-content:center;">
             <span style="color:var(--text-muted);font-size:.75rem;letter-spacing:.1em;text-transform:uppercase;">No Image</span>
           </div>`;
      return `
      <div class="col-md-6 col-lg-4 event-item" data-title="${escapeHtml(ev.title.toLowerCase())}">
        <div class="mvcc-card h-100">
          ${imgHtml}
          <div class="mvcc-card-body">
            <div class="mvcc-card-date">${formatDate(ev.event_date)}</div>
            ${ev.location ? `<div class="mvcc-card-meta">${escapeHtml(ev.location)}</div>` : ''}
            <h3 class="mvcc-card-title h5">${escapeHtml(ev.title)}</h3>
            <p class="mvcc-card-text">${escapeHtml(ev.description || '')}</p>
          </div>
        </div>
      </div>`;
    }).join('');
  }

  async function loadAllEvents() {
    grid.innerHTML = `
      <div class="col-md-4">${skeletonCard()}</div>
      <div class="col-md-4">${skeletonCard()}</div>
      <div class="col-md-4">${skeletonCard()}</div>`;

    try {
      const res  = await fetch(`${window.MVCC_BASE_URL || '/'}api/get_events.php`);
      const data = await res.json();
      allEvents  = data.events || [];
      render(allEvents);
    } catch {
      grid.innerHTML = '<p style="color:#ff6b6b;">Failed to load events.</p>';
    }
  }

  function skeletonCard() {
    return `<div class="mvcc-card">
      <div class="skeleton" style="height:220px;"></div>
      <div class="mvcc-card-body">
        <div class="skeleton mb-2" style="height:12px;width:35%;"></div>
        <div class="skeleton mb-2" style="height:18px;width:80%;"></div>
        <div class="skeleton" style="height:12px;width:95%;"></div>
      </div>
    </div>`;
  }

  filter?.addEventListener('input', () => {
    const q = filter.value.trim().toLowerCase();
    if (!q) { render(allEvents); return; }
    render(allEvents.filter(ev =>
      ev.title.toLowerCase().includes(q) ||
      (ev.location || '').toLowerCase().includes(q) ||
      (ev.description || '').toLowerCase().includes(q)
    ));
  });

  loadAllEvents();
})();
</script>
</body>
</html>
