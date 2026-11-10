<?php

header('Content-Type: text/plain; charset=UTF-8');

require_once __DIR__ . '/accounts.php';

set_error_handler(function ($severity, $message) {
    throw new ErrorException($message, 0, $severity);
});

try {
    $directory = ember_account_directory();
    $probe = $directory . '/write-check-' . bin2hex(random_bytes(8));
    $handle = fopen($probe, 'x');

    if ($handle === false) {
        throw new RuntimeException('Could not create the test file.');
    }

    fclose($handle);
    unlink($probe);

    echo "PASS: PHP found the private folder and can create files there.\n";
} catch (Throwable $error) {
    $message = $error->getMessage();

    if (stripos($message, 'open_basedir') !== false) {
        echo "BLOCKED: PHP's open_basedir setting restricts access.\n";
    } elseif (stripos($message, 'permission denied') !== false) {
        echo "BLOCKED: PHP was denied access by filesystem permissions.\n";
    } elseif ($message === 'Could not locate public_html.') {
        echo "PATH: PHP's project path has no public_html parent.\n";
    } elseif ($message === 'The private account folder is unavailable.') {
        echo "PATH: PHP cannot see the expected private folder as a regular directory.\n";
    } else {
        echo "FAIL: The private-folder write check could not complete.\n";
    }
}