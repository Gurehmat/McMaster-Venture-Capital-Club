<?php
/**
 * tools/gen_hash.php
 * Utility to generate a bcrypt password hash for the admins table.
 * Run once from CLI or browser during initial setup, then DELETE this file.
 *
 * CLI usage:
 *   php tools/gen_hash.php yourpassword
 *
 * Browser usage:
 *   http://localhost/tools/gen_hash.php?p=yourpassword
 */

if (PHP_SAPI === 'cli') {
    $password = $argv[1] ?? 'changeme123';
} else {
    // Remove this file or restrict access in production!
    $password = $_GET['p'] ?? 'changeme123';
    header('Content-Type: text/plain');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password : $password\n";
echo "Hash     : $hash\n\n";
echo "SQL to update admin password:\n";
echo "UPDATE admins SET password_hash = '$hash' WHERE username = 'admin';\n";
