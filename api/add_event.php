<?php
/**
 * POST /api/add_event.php  (Admin only — session-gated)
 * Accepts multipart/form-data with optional image upload.
 */

header('Content-Type: application/json');
session_start();
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

require_once __DIR__ . '/../db/config.php';

$title       = trim($_POST['title']       ?? '');
$event_date  = trim($_POST['event_date']  ?? '');
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
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($_FILES['image']['tmp_name']);

    if (!in_array($mimeType, $allowed, true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Invalid image type.']);
        exit;
    }

    $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
    $dest     = __DIR__ . '/../assets/uploads/' . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Image upload failed.']);
        exit;
    }
    $image_url = '/assets/uploads/' . $filename;
}

$db   = getDB();
$stmt = $db->prepare(
    'INSERT INTO events (title, event_date, description, image_url) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$title, $event_date, $description ?: null, $image_url]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Event added.']);
