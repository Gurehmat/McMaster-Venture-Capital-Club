<?php
/**
 * Shared site helpers.
 */

function mvcc_base_url(): string {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $basePath = str_starts_with($scriptName, '/admin/')
        ? dirname(dirname($scriptName))
        : dirname($scriptName);

    $basePath = rtrim($basePath, '/');
    return $basePath === '' ? '/' : $basePath . '/';
}

function mvcc_public_url(string $path = ''): string {
    $baseUrl = mvcc_base_url();
    $normalized = ltrim($path, '/');

    return $normalized === '' ? $baseUrl : $baseUrl . $normalized;
}

function mvcc_resolve_media_url(?string $value): ?string {
    if ($value === null) {
        return null;
    }

    $trimmed = trim($value);
    if ($trimmed === '') {
        return null;
    }

    if (preg_match('#^(?:https?:)?//#i', $trimmed) || str_starts_with($trimmed, 'data:')) {
        return $trimmed;
    }

    return mvcc_public_url($trimmed);
}

function mvcc_is_https(): bool {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    return (($_SERVER['SERVER_PORT'] ?? null) === '443');
}

function mvcc_start_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => mvcc_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function mvcc_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function mvcc_verify_csrf(?string $token): bool {
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
