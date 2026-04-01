/**
 * McMaster Venture Capital Club — main.js
 * Handles: events render, exec render, partners render,
 *          join-form AJAX submit, live email check.
 */

/* ── Helpers ──────────────────────────────────────────────── */
function formatDate(dateStr) {
  const d = new Date(dateStr + 'T00:00:00');
  return d.toLocaleDateString('en-CA', { year: 'numeric', month: 'long', day: 'numeric' });
}

function escapeHtml(str) {
  if (!str) return '';
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

/* ── Skeleton loaders ─────────────────────────────────────── */
function skeletonCard() {
  return `
  <div class="col-md-4">
    <div class="mvcc-card">
      <div class="mvcc-card-img skeleton" style="height:180px;"></div>
      <div class="mvcc-card-body">
        <div class="skeleton mb-2" style="height:12px;width:35%;"></div>
        <div class="skeleton mb-2" style="height:18px;width:80%;"></div>
        <div class="skeleton" style="height:12px;width:95%;"></div>
      </div>
    </div>
  </div>`;
}

function skeletonExec() {
  return `
  <div class="col-6 col-md-4 col-lg-3 mb-4">
    <div class="exec-card">
      <div class="exec-avatar skeleton mx-auto mb-3" style="width:80px;height:80px;border-radius:50%;"></div>
      <div class="skeleton mb-2 mx-auto" style="height:14px;width:70%;"></div>
      <div class="skeleton mx-auto" style="height:10px;width:50%;"></div>
    </div>
  </div>`;
}

/* ── Events ───────────────────────────────────────────────── */
async function loadEvents() {
  const container = document.getElementById('events-grid');
  if (!container) return;

  // Show 3 skeleton cards while loading
  container.innerHTML = [skeletonCard(), skeletonCard(), skeletonCard()].join('');

  try {
    const res  = await fetch('/api/get_events.php');
    const data = await res.json();

    if (!data.success || !data.events.length) {
      container.innerHTML = '<p class="text-center" style="color:var(--text-muted)">No events yet — check back soon.</p>';
      return;
    }

    container.innerHTML = data.events.map(ev => {
      const imgHtml = ev.image_url
        ? `<img src="${escapeHtml(ev.image_url)}" alt="${escapeHtml(ev.title)}" class="mvcc-card-img" style="height:200px;object-fit:cover;">` // TODO: swap in real image
        : `<div class="mvcc-card-img" style="height:200px;background:var(--surface);">
             <span style="color:var(--text-muted);font-size:.75rem;letter-spacing:.1em;text-transform:uppercase;margin:auto;display:block;text-align:center;padding-top:5.5rem;">
               No Image
             </span>
           </div>`;

      return `
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="mvcc-card">
          ${imgHtml}
          <div class="mvcc-card-body">
            <div class="mvcc-card-date">${formatDate(ev.event_date)}</div>
            <h4 class="mvcc-card-title">${escapeHtml(ev.title)}</h4>
            <p class="mvcc-card-text">${escapeHtml(ev.description || '')}</p>
          </div>
        </div>
      </div>`;
    }).join('');

  } catch (err) {
    container.innerHTML = '<p class="text-center" style="color:#ff6b6b;">Failed to load events.</p>';
    console.error('loadEvents:', err);
  }
}

/* ── Executives ───────────────────────────────────────────── */
async function loadExecutives() {
  const container = document.getElementById('exec-grid');
  if (!container) return;

  container.innerHTML = Array(7).fill(skeletonExec()).join('');

  try {
    const res  = await fetch('/api/get_executives.php');
    const data = await res.json();

    if (!data.success || !data.executives.length) {
      container.innerHTML = '<p class="text-center" style="color:var(--text-muted)">No executives listed yet.</p>';
      return;
    }

    container.innerHTML = data.executives.map(ex => {
      const initials = ex.full_name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
      const avatarHtml = ex.photo_url
        ? `<img src="${escapeHtml(ex.photo_url)}" alt="${escapeHtml(ex.full_name)}">` // TODO: swap in real image
        : `<span style="font-size:1.5rem;font-weight:700;color:var(--gold);line-height:80px;">${initials}</span>`;

      const linkedinHtml = ex.linkedin_url
        ? `<a href="${escapeHtml(ex.linkedin_url)}" target="_blank" rel="noopener" class="exec-linkedin">
             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
               <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
             </svg>
             LinkedIn
           </a>`
        : '';

      return `
      <div class="col-6 col-md-4 col-lg-3 mb-4">
        <div class="exec-card">
          <div class="exec-avatar">${avatarHtml}</div>
          <h5 class="exec-name">${escapeHtml(ex.full_name)}</h5>
          <div class="exec-role">${escapeHtml(ex.role || '')}</div>
          ${linkedinHtml}
        </div>
      </div>`;
    }).join('');

  } catch (err) {
    container.innerHTML = '<p class="text-center" style="color:#ff6b6b;">Failed to load team.</p>';
    console.error('loadExecutives:', err);
  }
}

/* ── Partners ─────────────────────────────────────────────── */
async function loadPartners() {
  const partnersContainer   = document.getElementById('partners-row');
  const associatedContainer = document.getElementById('associated-row');
  if (!partnersContainer) return;

  try {
    const res  = await fetch('/api/get_partners.php');
    const data = await res.json();

    const renderChips = (list) => list.map(p => {
      const inner = p.logo_url
        ? `<img src="${escapeHtml(p.logo_url)}" alt="${escapeHtml(p.name)}" class="partner-logo me-2">` // TODO: swap in real image
        : '';
      const tag = p.website_url
        ? `<a href="${escapeHtml(p.website_url)}" target="_blank" rel="noopener" class="partner-chip">${inner}${escapeHtml(p.name)}</a>`
        : `<span class="partner-chip">${inner}${escapeHtml(p.name)}</span>`;
      return tag;
    }).join('');

    partnersContainer.innerHTML   = renderChips(data.partners   || []);
    if (associatedContainer) {
      associatedContainer.innerHTML = renderChips(data.associated || []);
    }

  } catch (err) {
    if (partnersContainer) partnersContainer.innerHTML = '<span style="color:#ff6b6b;">Failed to load partners.</span>';
    console.error('loadPartners:', err);
  }
}

/* ── Join Form ────────────────────────────────────────────── */
let emailChecking = false;
let emailValid    = null; // null=unchecked, true=available, false=taken

async function checkEmail(email) {
  const input    = document.getElementById('input-email');
  const feedback = document.getElementById('email-feedback');
  if (!input || !email) return;

  emailChecking = true;
  emailValid    = null;
  input.classList.remove('is-valid', 'is-invalid');
  if (feedback) { feedback.textContent = 'Checking…'; feedback.style.display = 'block'; feedback.style.color = 'var(--text-muted)'; }

  try {
    const res  = await fetch(`/api/check_email.php?email=${encodeURIComponent(email)}`);
    const data = await res.json();
    emailValid = data.available;
    if (data.available) {
      input.classList.add('is-valid');
      if (feedback) { feedback.textContent = 'Email is available.'; feedback.style.color = '#6bc46b'; }
    } else {
      input.classList.add('is-invalid');
      if (feedback) { feedback.textContent = 'That email is already registered.'; feedback.style.color = '#ff6b6b'; }
    }
  } catch {
    emailValid = null;
    if (feedback) { feedback.textContent = ''; feedback.style.display = 'none'; }
  } finally {
    emailChecking = false;
  }
}

function initJoinForm() {
  const form      = document.getElementById('join-form');
  const emailInp  = document.getElementById('input-email');
  const msgBox    = document.getElementById('form-message');
  const submitBtn = document.getElementById('join-submit');
  if (!form) return;

  // Live email check on blur
  emailInp?.addEventListener('blur', () => {
    const val = emailInp.value.trim();
    if (val && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
      checkEmail(val);
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const fullName    = document.getElementById('input-fullname')?.value.trim() ?? '';
    const email       = emailInp?.value.trim() ?? '';
    const program     = document.getElementById('input-program')?.value.trim() ?? '';
    const year        = document.getElementById('input-year')?.value ?? '';
    const interest    = document.getElementById('input-interest')?.value.trim() ?? '';

    // Basic client-side validation
    let hasError = false;
    if (!fullName) {
      document.getElementById('input-fullname')?.classList.add('is-invalid');
      hasError = true;
    } else {
      document.getElementById('input-fullname')?.classList.remove('is-invalid');
    }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      emailInp?.classList.add('is-invalid');
      hasError = true;
    }
    if (hasError) return;

    if (emailValid === false) {
      showMessage('That email is already registered.', 'error');
      return;
    }

    submitBtn.disabled    = true;
    submitBtn.textContent = 'Submitting…';

    try {
      const res = await fetch('/api/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ full_name: fullName, email, program, year_of_study: year, interest_area: interest }),
      });
      const data = await res.json();

      if (data.success) {
        showMessage(data.message || 'You have been registered!', 'success');
        form.reset();
        emailInp?.classList.remove('is-valid', 'is-invalid');
        document.getElementById('email-feedback').style.display = 'none';
        emailValid = null;
      } else {
        const errMsg = data.error || (data.errors && data.errors.join(' ')) || 'Registration failed.';
        showMessage(errMsg, 'error');
      }
    } catch {
      showMessage('Network error. Please try again.', 'error');
    } finally {
      submitBtn.disabled    = false;
      submitBtn.textContent = 'Join MVCC';
    }
  });

  function showMessage(msg, type) {
    if (!msgBox) return;
    msgBox.textContent = msg;
    msgBox.className   = `show ${type}`;
    msgBox.id          = 'form-message';
    msgBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}

/* ── Navbar scroll shadow ─────────────────────────────────── */
function initNavbarScroll() {
  const nav = document.getElementById('mainNav');
  if (!nav) return;
  window.addEventListener('scroll', () => {
    nav.style.boxShadow = window.scrollY > 30
      ? '0 4px 30px rgba(0,0,0,.6)'
      : 'none';
  }, { passive: true });
}

/* ── Init ─────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  initNavbarScroll();
  loadEvents();
  loadExecutives();
  loadPartners();
  initJoinForm();
});
