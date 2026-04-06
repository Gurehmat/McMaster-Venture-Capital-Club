<?php
/**
 * POST /api/delete_executive.php (Admin only — session-gated)
 * Body: { "id": <int> }
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

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) $body = $_POST;

if (!mvcc_verify_csrf($body['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null))) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
    exit;
}

$id = isset($body['id']) ? (int) $body['id'] : 0;
if ($id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Invalid executive ID.']);
    exit;
}

$db = getDB();
$stmt = $db->prepare('DELETE FROM executives WHERE id = ?');
$stmt->execute([$id]);

echo json_encode(['success' => true, 'message' => 'Executive deleted.']);
