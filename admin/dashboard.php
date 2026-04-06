<?php
require_once __DIR__ . '/../includes/site.php';
mvcc_start_session();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../db/config.php';
$db = getDB();
$baseUrl = mvcc_base_url();
$csrfToken = mvcc_csrf_token();

$activeTab = $_GET['tab'] ?? 'members';
if (!in_array($activeTab, ['members', 'events', 'executives', 'partners'], true)) {
    $activeTab = 'members';
}

$members = $db->query(
    'SELECT id, full_name, email, program, year_of_study, interest_area, joined_at
     FROM members ORDER BY joined_at DESC'
)->fetchAll();

$events = $db->query(
    'SELECT id, title, event_date, location, description, image_url
     FROM events ORDER BY event_date DESC'
)->fetchAll();

$executives = $db->query(
    'SELECT id, full_name, role, bio, linkedin_url, photo_url, display_order
     FROM executives ORDER BY display_order ASC, id ASC'
)->fetchAll();

$partners = $db->query(
    'SELECT id, name, website_url, logo_url, category FROM partners ORDER BY category, id'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | MVCC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>assets/css/style.css">
</head>
<body class="admin-shell">
<div class="admin-dashboard">
  <aside class="admin-sidebar-panel" aria-label="Admin sidebar">
    <div class="admin-sidebar-brand">
      <h1><span>MVCC</span> Admin</h1>
      <p>Club operations</p>
    </div>
    <nav class="admin-sidebar-nav" aria-label="Dashboard tabs">
      <a href="?tab=members" class="admin-nav-link <?= $activeTab === 'members' ? 'active' : '' ?>">Members</a>
      <a href="?tab=events" class="admin-nav-link <?= $activeTab === 'events' ? 'active' : '' ?>">Events</a>
      <a href="?tab=executives" class="admin-nav-link <?= $activeTab === 'executives' ? 'active' : '' ?>">Executives</a>
      <a href="?tab=partners" class="admin-nav-link <?= $activeTab === 'partners' ? 'active' : '' ?>">Partners</a>
      <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php" class="admin-nav-link admin-nav-link--muted">Back to site</a>
      <a href="logout.php" class="admin-nav-link admin-nav-link--danger">Logout</a>
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-header">
      <div class="admin-header-title">
        <h2><?= ucfirst($activeTab) ?></h2>
        <p>Manage the live data powering the MVCC public site.</p>
      </div>
      <div class="admin-user-chip">Signed in as <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></div>
    </div>

    <?php if ($activeTab === 'members'): ?>
      <section class="admin-panel">
        <div class="admin-panel__header">
          <div class="admin-panel__eyebrow"><?= count($members) ?> registered member<?= count($members) !== 1 ? 's' : '' ?></div>
        </div>
        <div class="admin-panel__body admin-table-wrap">
          <table class="admin-table">
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
      </section>
    <?php endif; ?>

    <?php if ($activeTab === 'events'): ?>
      <section class="admin-panel">
        <div class="admin-panel__header">
          <div class="admin-panel__eyebrow">Published events</div>
        </div>
        <div class="admin-panel__body admin-table-wrap">
          <table class="admin-table" id="events-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="events-tbody">
              <?php if (empty($events)): ?>
                <tr id="events-no-row"><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No events yet.</td></tr>
              <?php else: ?>
                <?php foreach ($events as $ev): ?>
                  <tr id="event-row-<?= $ev['id'] ?>">
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td style="white-space:nowrap;"><?= htmlspecialchars($ev['event_date']) ?></td>
                    <td><?= htmlspecialchars($ev['location'] ?? '—') ?></td>
                    <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($ev['description'] ?? '') ?></td>
                    <td>
                      <?php if ($ev['image_url']): ?>
                        <a href="<?= htmlspecialchars(mvcc_resolve_media_url($ev['image_url']) ?? '') ?>" target="_blank" rel="noopener" style="color:var(--gold);font-size:.78rem;">View</a>
                      <?php else: ?>
                        <span style="color:var(--text-muted);font-size:.78rem;">—</span>
                      <?php endif; ?>
                    </td>
                    <td><button class="badge-red admin-button-link" onclick="deleteEvent(<?= $ev['id'] ?>)">Delete</button></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <div class="admin-form-section">
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
              <label class="form-label">Location</label>
              <input type="text" name="location" class="form-control" maxlength="255" placeholder="e.g. The Forge, McMaster University">
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" maxlength="2000"></textarea>
            </div>
            <div class="col-12">
              <label class="form-label">Event Image (optional)</label>
              <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
            </div>
            <div class="col-12">
              <button type="submit" id="add-event-btn" class="btn-mvcc-primary btn-mvcc-sm">Add Event</button>
            </div>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <?php if ($activeTab === 'executives'): ?>
      <section class="admin-panel">
        <div class="admin-panel__header">
          <div class="admin-panel__eyebrow">Executive roster</div>
        </div>
        <div class="admin-panel__body admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order</th>
                <th>Name</th>
                <th>Role</th>
                <th>LinkedIn</th>
                <th>Bio</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="executives-tbody">
              <?php if (empty($executives)): ?>
                <tr id="executives-no-row"><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem;">No executives yet.</td></tr>
              <?php else: ?>
                <?php foreach ($executives as $ex): ?>
                  <tr id="executive-row-<?= $ex['id'] ?>">
                    <td><?= (int) $ex['display_order'] ?></td>
                    <td><?= htmlspecialchars($ex['full_name']) ?></td>
                    <td><?= htmlspecialchars($ex['role'] ?? '—') ?></td>
                    <td>
                      <?php if (!empty($ex['linkedin_url'])): ?>
                        <a href="<?= htmlspecialchars($ex['linkedin_url']) ?>" target="_blank" rel="noopener" style="color:var(--gold);font-size:.83rem;">View</a>
                      <?php else: ?>—<?php endif; ?>
                    </td>
                    <td style="max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($ex['bio'] ?? '') ?></td>
                    <td><button class="badge-red admin-button-link" onclick="deleteExecutive(<?= $ex['id'] ?>)">Delete</button></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <div class="admin-form-section">
        <h3>Add Executive</h3>
        <div id="executive-form-msg" style="display:none; margin-bottom:1rem; font-size:.875rem; padding:.7rem 1rem; border-radius:2px;"></div>
        <form id="add-executive-form" class="form-mvcc">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" class="form-control" required maxlength="150">
            </div>
            <div class="col-md-6">
              <label class="form-label">Role</label>
              <input type="text" name="role" class="form-control" maxlength="150" placeholder="e.g. VP Operations">
            </div>
            <div class="col-md-4">
              <label class="form-label">Display Order</label>
              <input type="number" name="display_order" class="form-control" min="0" max="255" value="0">
            </div>
            <div class="col-md-8">
              <label class="form-label">LinkedIn URL</label>
              <input type="url" name="linkedin_url" class="form-control" placeholder="https://...">
            </div>
            <div class="col-md-12">
              <label class="form-label">Photo URL</label>
              <input type="url" name="photo_url" class="form-control" placeholder="https://...">
            </div>
            <div class="col-12">
              <label class="form-label">Bio</label>
              <textarea name="bio" class="form-control" rows="3" maxlength="2000" placeholder="Short executive bio"></textarea>
            </div>
            <div class="col-12">
              <button type="submit" id="add-executive-btn" class="btn-mvcc-primary btn-mvcc-sm">Add Executive</button>
            </div>
          </div>
        </form>
      </div>
    <?php endif; ?>

    <?php if ($activeTab === 'partners'): ?>
      <section class="admin-panel">
        <div class="admin-panel__header">
          <div class="admin-panel__eyebrow">Partners and associates</div>
        </div>
        <div class="admin-panel__body admin-table-wrap">
          <table class="admin-table">
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
                        <a href="<?= htmlspecialchars($p['website_url']) ?>" target="_blank" rel="noopener" style="color:var(--gold);font-size:.83rem;"><?= htmlspecialchars($p['website_url']) ?></a>
                      <?php else: ?>—<?php endif; ?>
                    </td>
                    <td>
                      <span class="<?= $p['category'] === 'partner' ? 'badge-gold' : 'badge-red' ?>">
                        <?= htmlspecialchars($p['category']) ?>
                      </span>
                    </td>
                    <td><button class="badge-red admin-button-link" onclick="deletePartner(<?= $p['id'] ?>)">Delete</button></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>

      <div class="admin-form-section">
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
              <input type="url" name="website_url" class="form-control" placeholder="https://...">
            </div>
            <div class="col-md-5">
              <label class="form-label">Logo URL</label>
              <input type="url" name="logo_url" class="form-control" placeholder="https://... (optional)">
            </div>
            <div class="col-12">
              <button type="submit" id="add-partner-btn" class="btn-mvcc-primary btn-mvcc-sm">Add Partner</button>
            </div>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.MVCC_BASE_URL = <?= json_encode($baseUrl) ?>;
window.MVCC_CSRF_TOKEN = <?= json_encode($csrfToken) ?>;

function showMsg(elId, msg, type) {
  const el = document.getElementById(elId);
  if (!el) return;
  el.textContent = msg;
  el.style.display = 'block';
  el.style.background = type === 'success' ? 'rgba(45,122,45,.15)' : 'rgba(122,0,0,.2)';
  el.style.border = type === 'success' ? '1px solid #2d7a2d' : '1px solid #a00000';
  el.style.color = type === 'success' ? '#6bc46b' : '#ff6b6b';
}

function adminJsonHeaders() {
  return {
    'Content-Type': 'application/json',
    'X-CSRF-Token': window.MVCC_CSRF_TOKEN || ''
  };
}

async function deleteEvent(id) {
  if (!confirm('Delete this event?')) return;
  try {
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/delete_event.php`, {
      method: 'POST',
      headers: adminJsonHeaders(),
      body: JSON.stringify({ id, csrf_token: window.MVCC_CSRF_TOKEN || '' }),
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('event-row-' + id)?.remove();
    } else {
      alert(data.error || 'Delete failed.');
    }
  } catch {
    alert('Network error.');
  }
}

document.getElementById('add-event-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('add-event-btn');
  const form = e.target;
  btn.disabled = true;
  btn.textContent = 'Adding...';

  try {
    const fd = new FormData(form);
    fd.append('csrf_token', window.MVCC_CSRF_TOKEN || '');
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/add_event.php`, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      showMsg('event-form-msg', 'Event added successfully!', 'success');
      form.reset();
      setTimeout(() => location.reload(), 900);
    } else {
      showMsg('event-form-msg', data.error || 'Failed to add event.', 'error');
    }
  } catch {
    showMsg('event-form-msg', 'Network error.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Add Event';
  }
});

async function deleteExecutive(id) {
  if (!confirm('Delete this executive?')) return;
  try {
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/delete_executive.php`, {
      method: 'POST',
      headers: adminJsonHeaders(),
      body: JSON.stringify({ id, csrf_token: window.MVCC_CSRF_TOKEN || '' }),
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('executive-row-' + id)?.remove();
    } else {
      alert(data.error || 'Delete failed.');
    }
  } catch {
    alert('Network error.');
  }
}

document.getElementById('add-executive-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('add-executive-btn');
  const form = e.target;
  btn.disabled = true;
  btn.textContent = 'Adding...';

  const formData = new FormData(form);
  const body = { csrf_token: window.MVCC_CSRF_TOKEN || '' };
  formData.forEach((v, k) => { body[k] = v; });

  try {
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/add_executive.php`, {
      method: 'POST',
      headers: adminJsonHeaders(),
      body: JSON.stringify(body),
    });
    const data = await res.json();
    if (data.success) {
      showMsg('executive-form-msg', 'Executive added successfully!', 'success');
      form.reset();
      setTimeout(() => location.reload(), 900);
    } else {
      showMsg('executive-form-msg', data.error || 'Failed to add executive.', 'error');
    }
  } catch {
    showMsg('executive-form-msg', 'Network error.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Add Executive';
  }
});

async function deletePartner(id) {
  if (!confirm('Delete this partner?')) return;
  try {
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/delete_partner.php`, {
      method: 'POST',
      headers: adminJsonHeaders(),
      body: JSON.stringify({ id, csrf_token: window.MVCC_CSRF_TOKEN || '' }),
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('partner-row-' + id)?.remove();
    } else {
      alert(data.error || 'Delete failed.');
    }
  } catch {
    alert('Network error.');
  }
}

document.getElementById('add-partner-form')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('add-partner-btn');
  const form = e.target;
  btn.disabled = true;
  btn.textContent = 'Adding...';

  const formData = new FormData(form);
  const body = { csrf_token: window.MVCC_CSRF_TOKEN || '' };
  formData.forEach((v, k) => { body[k] = v; });

  try {
    const res = await fetch(`${window.MVCC_BASE_URL || '/'}api/add_partner.php`, {
      method: 'POST',
      headers: adminJsonHeaders(),
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
  } catch {
    showMsg('partner-form-msg', 'Network error.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Add Partner';
  }
});
</script>
</body>
</html>
