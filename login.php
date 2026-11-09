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
        <p>Sign-in will be available once account handling is connected.</p>

        <form method="post" action="login.php">
            <fieldset disabled>
                <legend>Account details</legend>

                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           autocomplete="username" required>
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