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
        <p>Registration will be available once account handling is connected.</p>

        <form method="post" action="register.php">
            <fieldset disabled>
                <legend>New account details</legend>

                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           autocomplete="username" required>
                </p>

                <p>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           autocomplete="new-password" required>
                </p>

                <p>
                    <label for="confirm-password">Confirm password</label>
                    <input type="password" id="confirm-password" name="confirm_password"
                           autocomplete="new-password" required>
                </p>

                <button type="submit">Create account</button>
            </fieldset>
        </form>

        <p><a href="login.php">Back to sign in</a></p>
    </main>
</body>
</html>