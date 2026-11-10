<?php
require_once __DIR__ . '/session.php';
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fortress Gate | The Last Ember</title>
</head>
<body>
    <main>
        <h1>Fortress gate</h1>
        <p>Beyond the ruined gate, a faint orange light flickers through a crack in the stone.</p>

        <section aria-labelledby="hero-heading">
            <h2 id="hero-heading">Your hero</h2>
            <p>Class, health, strength, and inventory will appear here after you create a hero.</p>
        </section>

        <form method="post" action="play.php">
            <fieldset disabled>
                <legend>Choose your path</legend>
                <button type="submit" name="choice" value="enter_gate">Enter through the gate</button>
                <button type="submit" name="choice" value="search_wall">Search along the wall</button>
            </fieldset>
        </form>

        <p><a href="character.php">Back to character setup</a></p>
    </main>
</body>
</html>