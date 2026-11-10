<?php

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/accounts.php';

$_SESSION['register_token'] = $_SESSION['register_token'] ?? bin2hex(random_bytes(32));

$username = '';
$error = '';
$registered = !empty($_SESSION['registration_complete']);
unset($_SESSION['registration_complete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = is_string($_POST['username'] ?? null)
        ? strtolower(trim($_POST['username']))
        : '';
    $password = $_POST['password'] ?? null;
    $confirmation = $_POST['confirm_password'] ?? null;
    $token = $_POST['register_token'] ?? null;

    if (!is_string($token) || !hash_equals($_SESSION['register_token'], $token)) {
        $error = 'Your form expired. Please try again.';
    } elseif (!ember_valid_username($username)) {
        $error = 'Use 3–24 letters, digits, or underscores. Start with a letter.';
    } elseif (!is_string($password) || strlen($password) < 12 ||
              strlen($password) > 72 || strpos($password, "\0") !== false) {
        $error = 'Use a password between 12 and 72 bytes.';
    } elseif ($password !== $confirmation) {
        $error = 'The passwords do not match.';
    } else {
        try {
            if (ember_create_account($username, $password)) {
                $_SESSION['registration_complete'] = true;
                unset($_SESSION['register_token']);

                header('Location: register.php', true, 303);
                exit;
            }

            $error = 'That username is already taken.';
        } catch (Throwable $exception) {
            error_log('The Last Ember registration: ' . $exception->getMessage());
            $error = 'Registration is temporarily unavailable. Please try again later.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create an Account | The Last Ember</title>
</head>
<body>
    <main>
        <h1>Create an account</h1>

        <?php if ($error !== ''): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if ($registered): ?>
            <p role="status">Account created. Keep your username for sign-in.</p>
        <?php else: ?>
            <form method="post" action="register.php">
                <input type="hidden" name="register_token"
                       value="<?= htmlspecialchars($_SESSION['register_token'], ENT_QUOTES, 'UTF-8') ?>">

                <fieldset>
                    <legend>New account details</legend>

                    <p>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"
                               value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
                               autocomplete="username" maxlength="24" required>
                    </p>

                    <p>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               autocomplete="new-password" minlength="12" required>
                    </p>

                    <p>
                        <label for="confirm-password">Confirm password</label>
                        <input type="password" id="confirm-password" name="confirm_password"
                               autocomplete="new-password" required>
                    </p>

                    <button type="submit">Create account</button>
                </fieldset>
            </form>
        <?php endif; ?>

        <p><a href="login.php">Back to sign in</a></p>
    </main>
</body>
</html>