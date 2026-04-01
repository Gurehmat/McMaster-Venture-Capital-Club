<?php
/**
 * POST /api/delete_event.php  (Admin only — session-gated)
 * Body: { "id": <int> }
 */

header('Content-Type: application/json');
session_start();
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized.']);
    exit;
}

require_once __DIR__ . '/../db/config.php';

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) $body = $_POST;

$id = isset($body['id']) ? (int)$body['id'] : 0;
if ($id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Invalid event ID.']);
    exit;
}

$db = getDB();

// Delete associated file if present
$stmt = $db->prepare('SELECT image_url FROM events WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$row = $stmt->fetch();
if ($row && $row['image_url']) {
    $path = __DIR__ . '/..' . $row['image_url'];
    if (file_exists($path)) @unlink($path);
}

$stmt = $db->prepare('DELETE FROM events WHERE id = ?');
$stmt->execute([$id]);

echo json_encode(['success' => true, 'message' => 'Event deleted.']);
