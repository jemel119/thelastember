<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Your Hero | The Last Ember</title>
</head>
<body>
    <main>
        <h1>Create your hero</h1>
        <p>Choose who will enter the fallen fortress.</p>

        <form method="post" action="character.php">
            <fieldset disabled>
                <legend>Character details</legend>

                <p>
                    <label for="hero-name">Hero name</label>
                    <input type="text" id="hero-name" name="hero_name" required>
                </p>

                <p>
                    <label for="hero-class">Class</label>
                    <select id="hero-class" name="hero_class" required>
                        <option value="">Choose a class</option>
                        <option value="warrior">Warrior</option>
                        <option value="mage">Mage</option>
                        <option value="rogue">Rogue</option>
                    </select>
                </p>

                <button type="submit">Begin adventure</button>
            </fieldset>
        </form>

        <p><a href="play.php">Preview the opening scene</a></p>
        <p><a href="login.php">Back to sign in</a></p>
    </main>
</body>
</html>