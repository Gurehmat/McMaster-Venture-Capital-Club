<?php
/**
 * admin/dashboard.php
 * Session-gated admin panel. Tabs: Members | Events | Partners
 */
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../db/config.php';
$db = getDB();

$activeTab = $_GET['tab'] ?? 'members';
if (!in_array($activeTab, ['members', 'events', 'partners'], true)) {
    $activeTab = 'members';
}

// ── Inline data for Members tab ─────────────────────────────
$members = $db->query(
    'SELECT id, full_name, email, program, year_of_study, interest_area, joined_at
     FROM members ORDER BY joined_at DESC'
)->fetchAll();

// ── Events (for events tab listing) ─────────────────────────
$events = $db->query(
    'SELECT id, title, event_date, description, image_url FROM events ORDER BY event_date DESC'
)->fetchAll();

// ── Partners ────────────────────────────────────────────────
$partners = $db->query(
    "SELECT id, name, website_url, logo_url, category FROM partners ORDER BY category, id"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | MVCC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    body { background: var(--black); }
    .dash-layout { display:flex; min-height:100vh; padding-top:0; }
    .dash-sidebar {
      width:220px; flex-shrink:0;
      background:var(--surface);
      border-right:1px solid var(--border);
      position:sticky; top:0; height:100vh; overflow-y:auto;
      display:flex; flex-direction:column;
    }
    .dash-sidebar-logo {
      font-family:'Playfair Display',serif;
      font-size:1.1rem; color:var(--white);
      padding:1.5rem 1.2rem;
      border-bottom:1px solid var(--border);
    }
    .dash-sidebar-logo span { color:var(--gold); }
    .dash-main { flex:1; padding:2rem; overflow-y:auto; }
    .dash-header {
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:2rem;
      padding-bottom:1rem;
      border-bottom:1px solid var(--border);
    }
    .dash-header h1 { font-size:1.6rem; margin:0; }
    .dash-welcome { font-size:.8rem; color:var(--text-muted); }
    .tab-pane-content { display:none; }
    .tab-pane-content.active { display:block; }
    /* Responsive sidebar collapse on small screens */
    @media (max-width: 768px) {
      .dash-layout { flex-direction:column; }
      .dash-sidebar { width:100%; height:auto; position:relative; flex-direction:row; flex-wrap:wrap; align-items:center; padding:.5rem; }
      .dash-sidebar-logo { padding:.5rem 1rem; border-bottom:none; border-right:1px solid var(--border); }
      .dash-sidebar nav { display:flex; flex-wrap:wrap; }
      .admin-nav-link { border-left:none; border-bottom:2px solid transparent; padding:.5rem .9rem; font-size:.75rem; }
      .admin-nav-link.active { border-bottom-color:var(--gold); border-left-color:transparent; }
    }
    .form-section {
      background:var(--surface-2); border:1px solid var(--border);
      border-radius:4px; padding:1.8rem;
      margin-top:2rem;
    }
    .form-section h3 { font-size:1.1rem; margin-bottom:1.4rem; color:var(--white); }
  </style>
</head>
<body>
<div class="dash-layout">

  <!-- ── Sidebar ─────────────────────────────────────────── -->
  <aside class="dash-sidebar" aria-label="Admin sidebar">
    <div class="dash-sidebar-logo"><span>MVCC</span> Admin</div>
    <nav class="flex-column w-100 pt-2" aria-label="Dashboard tabs">
      <a href="?tab=members"  class="admin-nav-link <?= $activeTab==='members'  ? 'active' : '' ?>">Members</a>
      <a href="?tab=events"   class="admin-nav-link <?= $activeTab==='events'   ? 'active' : '' ?>">Events</a>
      <a href="?tab=partners" class="admin-nav-link <?= $activeTab==='partners' ? 'active' : '' ?>">Partners</a>
      <a href="/index.php"    class="admin-nav-link mt-auto" style="color:var(--text-muted)">← Site</a>
      <a href="logout.php"    class="admin-nav-link" style="color:#ff6b6b;">Logout</a>
    </nav>
  </aside>

  <!-- ── Main content ────────────────────────────────────── -->
  <main class="dash-main" id="dashMain">
    <div class="dash-header">
      <h1><?= ucfirst($activeTab) ?></h1>
      <span class="dash-welcome">Signed in as <strong style="color:var(--gold);"><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
    </div>

    <!-- ════════════════════════════════════════════════════
         TAB: MEMBERS
    ════════════════════════════════════════════════════ -->
    <?php if ($activeTab === 'members'): ?>
    <div>
      <p class="mb-3" style="color:var(--text-muted); font-size:.875rem;">
        <?= count($members) ?> registered member<?= count($members) !== 1 ? 's' : '' ?>
      </p>
      <div style="overflow-x:auto;">
        <table class="w-100 admin-table" style="border-collapse:collapse;">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Program</th>
              <th>Year</th>
              <th>Interest</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($members)): ?>
              <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No members yet.</td></tr>
            <?php else: ?>
              <?php foreach ($members as $i => $m): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($m['full_name']) ?></td>
                <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>" style="color:var(--gold);"><?= htmlspecialchars($m['email']) ?></a></td>
                <td><?= htmlspecialchars($m['program'] ?? '—') ?></td>
                <td><?= htmlspecialchars($m['year_of_study'] ?? '—') ?></td>
                <td><?= htmlspecialchars($m['interest_area'] ?? '—') ?></td>
                <td style="white-space:nowrap;"><?= date('M j, Y', strtotime($m['joined_at'])) ?></td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- ════════════════════════════════════════════════════
         TAB: EVENTS
    ════════════════════════════════════════════════════ -->
    <?php if ($activeTab === 'events'): ?>
    <div>
      <!-- Existing events list -->
      <div style="overflow-x:auto;">
        <table class="w-100 admin-table" id="events-table" style="border-collapse:collapse;">
          <thead>
            <tr>
              <th>Title</th>
              <th>Date</th>
              <th>Description</th>
              <th>Image</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="events-tbody">
            <?php if (empty($events)): ?>
              <tr id="events-no-row"><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">No events yet.</td></tr>
            <?php else: ?>
              <?php foreach ($events as $ev): ?>
              <tr id="event-row-<?= $ev['id'] ?>">
                <td><?= htmlspecialchars($ev['title']) ?></td>
                <td style="white-space:nowrap;"><?= htmlspecialchars($ev['event_date']) ?></td>
                <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($ev['description'] ?? '') ?></td>
                <td>
                  <?php if ($ev['image_url']): ?>
                    <a href="<?= htmlspecialchars($ev['image_url']) ?>" target="_blank" style="color:var(--gold);font-size:.78rem;">View</a>
                  <?php else: ?>
                    <span style="color:var(--text-muted);font-size:.78rem;">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="badge-red" style="cursor:pointer;border:none;"
                          onclick="deleteEvent(<?= $ev['id'] ?>)">Delete</button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Add event form -->
      <div class="form-section">
        <h3>Add New Event</h3>
        <div id="event-form-msg" style="display:none; margin-bottom:1rem; font-size:.875rem; padding:.7rem 1rem; border-radius:2px;"></div>
        <form id="add-event-form" class="form-mvcc" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Event Title *</label>
              <input type="text" name="title" class="form-control" required maxlength="255" placeholder="e.g. Build Canada x MVCC">
            </div>
            <div class="col-md-4">
              <label class="form-label">Event Date *</label>
              <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" maxlength="2000"></textarea>
            </div>
            <div class="col-12">
              <label class="form-label">Event Image (optional)</label>
              <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
            </div>
            <div class="col-12">
              <button type="submit" id="add-event-btn" class="btn-mvcc-primary btn-mvcc-sm">Add Event</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php endif; ?>

    <!-- ════════════════════════════════════════════════════
         TAB: PARTNERS
    ════════════════════════════════════════════════════ -->
    <?php if ($activeTab === 'partners'): ?>
    <div>
      <div style="overflow-x:auto;">
        <table class="w-100 admin-table" style="border-collapse:collapse;">
          <thead>
            <tr>
              <th>Name</th>
              <th>Website</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="partners-tbody">
            <?php if (empty($partners)): ?>
              <tr id="partners-no-row"><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">No partners yet.</td></tr>
            <?php else: ?>
              <?php foreach ($partners as $p): ?>
              <tr id="partner-row-<?= $p['id'] ?>">
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>
                  <?php if ($p['website_url']): ?>
                    <a href="<?= htmlspecialchars($p['website_url']) ?>" target="_blank" style="color:var(--gold);font-size:.83rem;">
                      <?= htmlspecialchars($p['website_url']) ?>
                    </a>
                  <?php else: ?>—<?php endif; ?>
                </td>
                <td>
                  <span class="<?= $p['category'] === 'partner' ? 'badge-gold' : 'badge-red' ?>">
                    <?= htmlspecialchars($p['category']) ?>
                  </span>
                </td>
                <td>
                  <button class="badge-red" style="cursor:pointer;border:none;"
                          onclick="deletePartner(<?= $p['id'] ?>)">Delete</button>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Add partner form -->
      <div class="form-section">
        <h3>Add Partner / Associate</h3>
        <div id="partner-form-msg" style="display:none; margin-bottom:1rem; font-size:.875rem; padding:.7rem 1rem; border-radius:2px;"></div>
        <form id="add-partner-form" class="form-mvcc">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name *</label>
              <input type="text" name="name" class="form-control" required maxlength="200" placeholder="e.g. Golden Ventures">
            </div>
            <div class="col-md-6">
              <label class="form-label">Category *</label>
              <select name="category" class="form-select">
                <option value="partner">Partner</option>
                <option value="associated">Associated</option>
              </select>
            </div>
            <div class="col-md-7">
              <label class="form-label">Website URL</label>
              <input type="url" name="website_url" class="form-control" placeholder="https://…">
            </div>
            <div class="col-md-5">
              <label class="form-label">Logo URL</label>
              <input type="url" name="logo_url" class="form-control" placeholder="https://… (optional)">
              <!-- TODO: swap in real image -->
            </div>
            <div class="col-12">
              <button type="submit" id="add-partner-btn" class="btn-mvcc-primary btn-mvcc-sm">Add Partner</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php endif; ?>

  </main><!-- /.dash-main -->
</div><!-- /.dash-layout -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ── Admin JS ──────────────────────────────────────────────── */

function showMsg(elId, msg, type) {
  const el = document.getElementById(elId);
  if (!el) return;
  el.textContent = msg;
  el.style.display = 'block';
  el.style.background = type === 'success' ? 'rgba(45,122,45,.15)' : 'rgba(122,0,0,.2)';
  el.style.border     = type === 'success' ? '1px solid #2d7a2d' : '1px solid #a00000';
  el.style.color      = type === 'success' ? '#6bc46b' : '#ff6b6b';
}

/* ── Delete Event ─────────────────────────────────────────── */
async function deleteEvent(id) {
  if (!confirm('Delete this event?')) return;
  try {
    const res  = await fetch('/api/delete_event.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id }),
    });
    const data = await res.json();
    if (data.success) {
      const row = document.getElementById('event-row-' + id);
      if (row) row.remove();
    } else {
      alert(data.error || 'Delete failed.');
    }
  } catch { alert('Network error.'); }
}

/* ── Add Event form ───────────────────────────────────────── */
document.getElementById('add-event-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn  = document.getElementById('add-event-btn');
  const form = e.target;
  btn.disabled = true; btn.textContent = 'Adding…';

  try {
    const fd  = new FormData(form);
    const res = await fetch('/api/add_event.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      showMsg('event-form-msg', 'Event added successfully!', 'success');
      form.reset();
      // Reload the page after a short delay so the new event appears in the table
      setTimeout(() => location.reload(), 900);
    } else {
      showMsg('event-form-msg', data.error || 'Failed to add event.', 'error');
    }
  } catch { showMsg('event-form-msg', 'Network error.', 'error'); }
  finally { btn.disabled = false; btn.textContent = 'Add Event'; }
});

/* ── Delete Partner ───────────────────────────────────────── */
async function deletePartner(id) {
  if (!confirm('Delete this partner?')) return;
  try {
    const res  = await fetch('/api/delete_partner.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id }),
    });
    const data = await res.json();
    if (data.success) {
      const row = document.getElementById('partner-row-' + id);
      if (row) row.remove();
    } else {
      alert(data.error || 'Delete failed.');
    }
  } catch { alert('Network error.'); }
}

/* ── Add Partner form ─────────────────────────────────────── */
document.getElementById('add-partner-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn  = document.getElementById('add-partner-btn');
  const form = e.target;
  btn.disabled = true; btn.textContent = 'Adding…';

  const formData = new FormData(form);
  const body = {};
  formData.forEach((v, k) => { body[k] = v; });

  try {
    const res  = await fetch('/api/add_partner.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body),
    });
    const data = await res.json();
    if (data.success) {
      showMsg('partner-form-msg', 'Partner added successfully!', 'success');
      form.reset();
      setTimeout(() => location.reload(), 900);
    } else {
      showMsg('partner-form-msg', data.error || 'Failed to add partner.', 'error');
    }
  } catch { showMsg('partner-form-msg', 'Network error.', 'error'); }
  finally { btn.disabled = false; btn.textContent = 'Add Partner'; }
});
</script>
</body>
</html>
