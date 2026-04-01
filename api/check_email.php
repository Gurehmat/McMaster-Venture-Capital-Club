<?php
/**
 * GET /api/check_email.php?email=...
 * Returns JSON indicating whether the email is already registered.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

$email = strtolower(trim($_GET['email'] ?? ''));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'error' => 'Invalid email address.']);
    exit;
}

$db   = getDB();
$stmt = $db->prepare('SELECT id FROM members WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$exists = (bool)$stmt->fetch();

echo json_encode(['available' => !$exists]);
