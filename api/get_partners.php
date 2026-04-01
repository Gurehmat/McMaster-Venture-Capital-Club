<?php
/**
 * GET /api/get_partners.php
 * Returns partners grouped by category as JSON.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

$db   = getDB();
$stmt = $db->query('SELECT id, name, website_url, logo_url, category FROM partners ORDER BY category, id');
$rows = $stmt->fetchAll();

$partners   = [];
$associated = [];
foreach ($rows as $row) {
    if ($row['category'] === 'partner') {
        $partners[] = $row;
    } else {
        $associated[] = $row;
    }
}

echo json_encode([
    'success'    => true,
    'partners'   => $partners,
    'associated' => $associated,
]);
