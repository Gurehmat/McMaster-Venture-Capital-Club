<?php
/**
 * POST /api/add_executive.php (Admin only — session-gated)
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

$fullName = trim($body['full_name'] ?? '');
$role = trim($body['role'] ?? '');
$bio = trim($body['bio'] ?? '');
$linkedinUrl = trim($body['linkedin_url'] ?? '');
$photoUrl = trim($body['photo_url'] ?? '');
$displayOrder = isset($body['display_order']) ? (int) $body['display_order'] : 0;

if ($fullName === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Executive name is required.']);
    exit;
}
if ($linkedinUrl !== '' && !filter_var($linkedinUrl, FILTER_VALIDATE_URL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'LinkedIn URL must be valid.']);
    exit;
}
if ($photoUrl !== '' && !filter_var($photoUrl, FILTER_VALIDATE_URL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Photo URL must be valid.']);
    exit;
}
if ($displayOrder < 0 || $displayOrder > 255) {
    $displayOrder = 0;
}

$db = getDB();
$stmt = $db->prepare(
    'INSERT INTO executives (full_name, role, bio, linkedin_url, photo_url, display_order)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->execute([
    $fullName,
    $role ?: null,
    $bio ?: null,
    $linkedinUrl ?: null,
    $photoUrl ?: null,
    $displayOrder,
]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Executive added.']);
