<?php
/**
 * POST /api/add_partner.php  (Admin only — session-gated)
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

$name        = trim($body['name']        ?? '');
$website_url = trim($body['website_url'] ?? '');
$logo_url    = trim($body['logo_url']    ?? '');
$category    = trim($body['category']    ?? 'partner');

if ($name === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Partner name is required.']);
    exit;
}
if (!in_array($category, ['partner', 'associated'], true)) {
    $category = 'partner';
}

$db   = getDB();
$stmt = $db->prepare(
    'INSERT INTO partners (name, website_url, logo_url, category) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$name, $website_url ?: null, $logo_url ?: null, $category]);

echo json_encode(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Partner added.']);
