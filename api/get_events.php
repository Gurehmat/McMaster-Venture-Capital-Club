<?php
/**
 * GET /api/get_events.php
 * Returns all events ordered by event_date DESC as JSON.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../db/config.php';

$db   = getDB();
$stmt = $db->query('SELECT id, title, event_date, location, description, image_url, created_at FROM events ORDER BY event_date DESC');
$events = $stmt->fetchAll();

echo json_encode(['success' => true, 'events' => $events]);
