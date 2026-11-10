<?php

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_name('LASTEMBERSESSID');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (!session_start()) {
    http_response_code(500);
    exit('The Last Ember could not start your session. Please try again.');
}

function require_login(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: login.php', true, 303);
        exit;
    }
}