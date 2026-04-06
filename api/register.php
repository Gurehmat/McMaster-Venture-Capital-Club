<?php
/**
 * POST /api/register.php
 * Registers a new member. Returns JSON.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
// Support both JSON body and form-encoded
if (!$body) $body = $_POST;

$full_name     = trim($body['full_name']     ?? '');
$email         = strtolower(trim($body['email'] ?? ''));
$program       = trim($body['program']       ?? '');
$year_of_study = isset($body['year_of_study']) ? (int)$body['year_of_study'] : null;
$interest_area = trim($body['interest_area'] ?? '');

// ── Validation ──────────────────────────────────────────────────────────────
$errors = [];
if ($full_name === '') $errors[] = 'Full name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if ($email !== '' && !preg_match('/@mcmaster\.ca$/i', $email)) $errors[] = 'Please use a valid McMaster email address.';
if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$db = getDB();

// Duplicate email check
$stmt = $db->prepare('SELECT id FROM members WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'That email is already registered.']);
    exit;
}

// Insert
$stmt = $db->prepare(
    'INSERT INTO members (full_name, email, program, year_of_study, interest_area)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$full_name, $email, $program ?: null, $year_of_study ?: null, $interest_area ?: null]);

echo json_encode(['success' => true, 'message' => 'Welcome to MVCC! You have been registered.']);
