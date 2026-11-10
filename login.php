<?php

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/accounts.php';

if (isset($_SESSION['user'])) {
    header('Location: character.php', true, 303);
    exit;
}

$_SESSION['login_token'] = $_SESSION['login_token'] ?? bin2hex(random_bytes(32));

$username = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = is_string($_POST['username'] ?? null)
        ? strtolower(trim($_POST['username']))
        : '';
    $password = $_POST['password'] ?? null;
    $token = $_POST['login_token'] ?? null;

    if (!is_string($token) || !hash_equals($_SESSION['login_token'], $token)) {
        $error = 'Your form expired. Please try again.';
    } elseif (!ember_valid_username($username) ||
              !is_string($password) ||
              strlen($password) < 12 ||
              strlen($password) > 72 ||
              strpos($password, "\0") !== false) {
        $error = 'The username or password is incorrect.';
    } else {
        try {
            $directory = ember_account_directory();

            if (!is_readable($directory) || !is_executable($directory)) {
                throw new RuntimeException('The account directory is inaccessible.');
            }

            $accountPath = $directory . '/' . $username . '.hash';
            $hash = is_file($accountPath)
                ? @file_get_contents($accountPath)
                : null;

            if ($hash === false) {
                throw new RuntimeException('Could not read the account file.');
            }

            if (is_string($hash) && password_verify($password, $hash)) {
                if (!session_regenerate_id()) {
                    throw new RuntimeException('Could not renew the session.');
                }

                $_SESSION['user'] = $username;
                unset($_SESSION['login_token']);

                header('Location: character.php', true, 303);
                exit;
            }

            $error = 'The username or password is incorrect.';
        } catch (Throwable $exception) {
            error_log('The Last Ember sign-in: ' . $exception->getMessage());
            $error = 'Sign-in is temporarily unavailable. Please try again later.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | The Last Ember</title>
</head>
<body>
    <main>
        <h1>The Last Ember</h1>
        <p>The ember that protects your village is fading. Its last spark lies inside a fallen fortress.</p>

        <h2>Sign in</h2>

        <?php if ($error !== ''): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <input type="hidden" name="login_token"
                   value="<?= htmlspecialchars($_SESSION['login_token'], ENT_QUOTES, 'UTF-8') ?>">

            <fieldset>
                <legend>Account details</legend>

                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                           autocomplete="username" maxlength="24" required>
                </p>

                <p>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           autocomplete="current-password" required>
                </p>

                <button type="submit">Sign in</button>
            </fieldset>
        </form>

        <p><a href="register.php">Create an account</a></p>
    </main>
</body>
</html>