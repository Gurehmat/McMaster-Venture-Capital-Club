<?php
/**
 * POST /api/add_event.php  (Admin only — session-gated)
 * Accepts multipart/form-data with optional image upload.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../includes/site.php';
mvcc_start_session();
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

require_once __DIR__ . '/../db/config.php';

if (!mvcc_verify_csrf($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
    exit;
}

$title       = trim($_POST['title']       ?? '');
$event_date  = trim($_POST['event_date']  ?? '');
$location    = trim($_POST['location']    ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $event_date === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Title and date are required.']);
    exit;
}

// ── Optional image upload ─────────────────────────────────────────────────
$image_url = null;
if (!empty($_FILES['image']['tmp_name'])) {
    $allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($_FILES['image']['tmp_name']);

    if (!in_array($mimeType, $allowed, true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Invalid image type.']);
        exit;
    }

    $uploadDir = __DIR__ . '/../assets/uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Upload directory is not writable.']);
        exit;
    }

    $filename = bin2hex(random_bytes(8)) . '.' . $extensions[$mimeType];
    $dest     = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Image upload failed.']);
        exit;
    }
    $image_url = 'assets/uploads/' . $filename;
}

$db   = getDB();
$stmt = $db->prepare(
    'INSERT INTO events (title, event_date, location, description, image_url) VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$title, $event_date, $location ?: null, $description ?: null, $image_url]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Event added.']);
