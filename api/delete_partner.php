<?php
/**
 * POST /api/delete_partner.php  (Admin only — session-gated)
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
    echo json_encode(['success' => false, 'error' => 'Invalid partner ID.']);
    exit;
}

$db   = getDB();
$stmt = $db->prepare('DELETE FROM partners WHERE id = ?');
$stmt->execute([$id]);

echo json_encode(['success' => true, 'message' => 'Partner deleted.']);
