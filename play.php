<?php

require_once __DIR__ . '/session.php';
require_login();

$story = require __DIR__ . '/story.php';
$node = $_SESSION['node'] ?? '';

if (!isset($_SESSION['hero'], $_SESSION['run_id'], $story[$node])) {
    header('Location: character.php', true, 303);
    exit;
}

$scene = $story[$node];

if (!isset($scene['choices'])) {
    header('Location: ending.php', true, 303);
    exit;
}

$hero = $_SESSION['hero'];
$runId = $_SESSION['run_id'];
$_SESSION['choice_token'] = $_SESSION['choice_token'] ?? bin2hex(random_bytes(32));
$error = '';

function choice_requirement(array $choice, array $hero): string
{
    if (isset($choice['strength']) && $hero['strength'] < $choice['strength']) {
        return 'Requires ' . $choice['strength'] . ' Strength. You have ' . $hero['strength'] . '.';
    }

    if (isset($choice['item']) && !in_array($choice['item'], $hero['inventory'], true)) {
        return 'Requires ' . $choice['item'] . '.';
    }

    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['choice_token'] ?? null;
    $choiceId = $_POST['choice'] ?? null;

    if (!is_string($token) ||
        !hash_equals($_SESSION['choice_token'], $token) ||
        ($_POST['run_id'] ?? null) !== $runId) {
        $error = 'This choice form is out of date. Choose from the current scene.';
    } elseif (!is_string($choiceId) || !isset($scene['choices'][$choiceId])) {
        $error = 'Choose an option from the current scene.';
    } else {
        $choice = $scene['choices'][$choiceId];
        $error = choice_requirement($choice, $hero);

        if ($error === '') {
            $hero['health'] = max(0, $hero['health'] + ($choice['health'] ?? 0));

            if (isset($choice['gain']) && !in_array($choice['gain'], $hero['inventory'], true)) {
                $hero['inventory'][] = $choice['gain'];
            }

            $nextNode = $hero['health'] === 0 ? 'tragic_failure' : $choice['next'];

            $_SESSION['hero'] = $hero;
            $_SESSION['node'] = $nextNode;
            $_SESSION['score'] += $choice['points'] ?? 0;
            $_SESSION['choices_log'][] = [
                'node' => $node,
                'label' => $choice['label'],
                'alignment' => $choice['alignment'] ?? 0,
            ];

            unset($_SESSION['choice_token']);

            $destination = isset($story[$nextNode]['choices']) ? 'play.php' : 'ending.php';
            header('Location: ' . $destination, true, 303);
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($scene['title'], ENT_QUOTES, 'UTF-8') ?> | The Last Ember</title>
</head>
<body>
    <main>
        <h1><?= htmlspecialchars($scene['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p><?= htmlspecialchars($scene['text'], ENT_QUOTES, 'UTF-8') ?></p>

        <section aria-labelledby="hero-heading">
            <h2 id="hero-heading"><?= htmlspecialchars($hero['name'], ENT_QUOTES, 'UTF-8') ?></h2>
            <dl>
                <dt>Class</dt>
                <dd><?= htmlspecialchars($hero['class'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Health</dt>
                <dd><?= (int) $hero['health'] ?></dd>
                <dt>Strength</dt>
                <dd><?= (int) $hero['strength'] ?></dd>
                <dt>Inventory</dt>
                <dd><?= htmlspecialchars(implode(', ', $hero['inventory']), ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Story points</dt>
                <dd><?= (int) $_SESSION['score'] ?></dd>
            </dl>
        </section>

        <?php if ($error !== ''): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="play.php">
            <input type="hidden" name="choice_token"
                   value="<?= htmlspecialchars($_SESSION['choice_token'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="run_id"
                   value="<?= htmlspecialchars($runId, ENT_QUOTES, 'UTF-8') ?>">

            <fieldset>
                <legend>Choose your path</legend>

                <?php foreach ($scene['choices'] as $id => $choice): ?>
                    <?php $requirement = choice_requirement($choice, $hero); ?>
                    <p>
                        <button type="submit" name="choice"
                                value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
                                <?= $requirement !== '' ? 'disabled' : '' ?>>
                            <?= htmlspecialchars($choice['label'], ENT_QUOTES, 'UTF-8') ?>
                        </button>
                        <?php if ($requirement !== ''): ?>
                            <span><?= htmlspecialchars($requirement, ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </p>
                <?php endforeach; ?>
            </fieldset>
        </form>

        <nav aria-label="Adventure options">
            <a href="character.php">Back to character setup</a>
            <a href="leaderboard.php">View leaderboard</a>
        </nav>
    </main>
</body>
</html>