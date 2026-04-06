<?php
/**
 * Database connection — PDO (MySQL)
 * Override via environment variables in production.
 */

define('DB_HOST', getenv('MVCC_DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('MVCC_DB_PORT') ?: '3306');
define('DB_NAME', getenv('MVCC_DB_NAME') ?: 'mvcc');
define('DB_USER', getenv('MVCC_DB_USER') ?: 'root');
define('DB_PASS', getenv('MVCC_DB_PASS') ?: '');
define('DB_CHARSET', getenv('MVCC_DB_CHARSET') ?: 'utf8mb4');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Never expose raw DB errors to the browser in production.
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
            exit;
        }
    }
    return $pdo;
}
