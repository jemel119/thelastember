<?php

require_once __DIR__ . '/session.php';
require_login();

$story = require __DIR__ . '/story.php';
$node = $_SESSION['node'] ?? '';

if (!isset($_SESSION['hero'], $_SESSION['run_id'], $story[$node])) {
    header('Location: character.php', true, 303);
    exit;
}

$ending = $story[$node];

if (isset($ending['choices'])) {
    header('Location: play.php', true, 303);
    exit;
}

$hero = $_SESSION['hero'];
$score = (int) ($_SESSION['score'] ?? 0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($ending['title'], ENT_QUOTES, 'UTF-8') ?> | The Last Ember</title>
</head>
<body>
    <main>
        <h1><?= htmlspecialchars($ending['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars($ending['text'], ENT_QUOTES, 'UTF-8') ?></p>

        <section aria-labelledby="result-heading">
            <h2 id="result-heading">Adventure results</h2>
            <dl>
                <dt>Hero</dt>
                <dd><?= htmlspecialchars($hero['name'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Class</dt>
                <dd><?= htmlspecialchars($hero['class'], ENT_QUOTES, 'UTF-8') ?></dd>

                <dt>Health remaining</dt>
                <dd><?= (int) $hero['health'] ?></dd>

                <dt>Final story points</dt>
                <dd><?= $score ?></dd>
            </dl>
        </section>

        <nav aria-label="Ending options">
            <ul>
                <li><a href="character.php">Set up a new adventure</a></li>
                <li><a href="leaderboard.php">View leaderboard</a></li>
            </ul>
        </nav>
    </main>
</body>
</html>