<?php

const EMBER_CHECKPOINT_COOKIE = 'last_ember_checkpoint';

function ember_save_checkpoint(array $story): bool
{
    $node = $_SESSION['node'] ?? null;

    if (!is_string($node) ||
        !isset($story[$node]['choices'], $_SESSION['hero'], $_SESSION['run_id'])) {
        return false;
    }

    $token = bin2hex(random_bytes(32));
    $cookiePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

    if (!setcookie(EMBER_CHECKPOINT_COOKIE, $token, [
        'path' => $cookiePath,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ])) {
        return false;
    }

    $_SESSION['checkpoint'] = [
        'token' => $token,
        'username' => $_SESSION['user'],
        'node' => $node,
        'hero' => $_SESSION['hero'],
        'run_id' => $_SESSION['run_id'],
        'score' => $_SESSION['score'] ?? 0,
        'choices_log' => $_SESSION['choices_log'] ?? [],
    ];

    return true;
}

function ember_get_checkpoint(array $story): ?array
{
    $checkpoint = $_SESSION['checkpoint'] ?? null;
    $token = $_COOKIE[EMBER_CHECKPOINT_COOKIE] ?? null;

    if ($checkpoint === null ||
        !is_string($token) ||
        !hash_equals($checkpoint['token'], $token) ||
        $checkpoint['username'] !== $_SESSION['user'] ||
        !isset($story[$checkpoint['node']]['choices']) ||
        isset($_SESSION['leaderboard'][$checkpoint['run_id']])) {
        return null;
    }

    return $checkpoint;
}