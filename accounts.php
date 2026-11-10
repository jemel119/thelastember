<?php

function ember_account_directory(): string
{
    $directory = __DIR__;

    while (basename($directory) !== 'public_html') {
        $parent = dirname($directory);

        if ($parent === $directory) {
            throw new RuntimeException('Could not locate public_html.');
        }

        $directory = $parent;
    }

    $privateDirectory = dirname($directory) . '/last_ember_private';

    if (!is_dir($privateDirectory) || is_link($privateDirectory)) {
        throw new RuntimeException('The private account folder is unavailable.');
    }

    return $privateDirectory;
}

function ember_valid_username(string $username): bool
{
    return preg_match('/\A[a-z][a-z0-9_]{2,23}\z/', $username) === 1;
}

function ember_create_account(string $username, string $password): bool
{
    if (!ember_valid_username($username)) {
        throw new InvalidArgumentException('Invalid username.');
    }

    $directory = ember_account_directory();
    $accountPath = $directory . '/' . $username . '.hash';
    $lock = fopen($directory . '/registration.lock', 'c');

    if ($lock === false) {
        throw new RuntimeException('Could not open the registration lock.');
    }

    $temporary = null;

    try {
        if (!flock($lock, LOCK_EX)) {
            throw new RuntimeException('Could not lock account storage.');
        }

        if (file_exists($accountPath)) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $temporary = tempnam($directory, 'account-');

        if ($temporary === false) {
            throw new RuntimeException('Could not create an account file.');
        }

        if (file_put_contents($temporary, $hash) !== strlen($hash) ||
            !rename($temporary, $accountPath)) {
            throw new RuntimeException('Could not save the account.');
        }

        return true;
    } finally {
        if (is_string($temporary) && is_file($temporary)) {
            unlink($temporary);
        }

        fclose($lock);
    }
}