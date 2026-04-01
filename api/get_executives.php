<?php
/**
 * GET /api/get_executives.php
 * Returns all executives ordered by display_order as JSON.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

$db   = getDB();
$stmt = $db->query('SELECT id, full_name, role, linkedin_url, photo_url FROM executives ORDER BY display_order');
$executives = $stmt->fetchAll();

echo json_encode(['success' => true, 'executives' => $executives]);
