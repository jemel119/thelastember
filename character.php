<?php

require_once __DIR__ . '/session.php';
require_login();

$classes = [
    'warrior' => ['label' => 'Warrior', 'health' => 100, 'strength' => 30, 'item' => 'Sword'],
    'mage' => ['label' => 'Mage', 'health' => 75, 'strength' => 12, 'item' => 'Ward'],
    'rogue' => ['label' => 'Rogue', 'health' => 85, 'strength' => 18, 'item' => 'Lockpick'],
];

$_SESSION['character_token'] = $_SESSION['character_token'] ?? bin2hex(random_bytes(32));

$name = '';
$selectedClass = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = is_string($_POST['hero_name'] ?? null) ? trim($_POST['hero_name']) : '';
    $selectedClass = is_string($_POST['hero_class'] ?? null) ? $_POST['hero_class'] : '';
    $token = $_POST['character_token'] ?? null;

    if (!is_string($token) || !hash_equals($_SESSION['character_token'], $token)) {
        $error = 'Your character form expired. Please try again.';
    } elseif (preg_match("/\\A[A-Za-z][A-Za-z0-9 '\\-]{0,29}\\z/", $name) !== 1) {
        $error = 'Use 1–30 characters for your hero name. Start with a letter and use only letters, numbers, spaces, apostrophes, or hyphens.';
    } elseif (!isset($classes[$selectedClass])) {
        $error = 'Choose Warrior, Mage, or Rogue.';
    } else {
        $class = $classes[$selectedClass];

        $_SESSION['hero'] = [
            'name' => $name,
            'class' => $class['label'],
            'health' => $class['health'],
            'strength' => $class['strength'],
            'inventory' => [$class['item']],
        ];
        $_SESSION['node'] = 'fortress_gate';
        $_SESSION['run_id'] = bin2hex(random_bytes(16));
        $_SESSION['choices_log'] = [];
        $_SESSION['score'] = 0;

        unset($_SESSION['character_token']);

        header('Location: play.php', true, 303);
        exit;
    }
}

?>
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

        <?php if ($error !== ''): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['hero'])): ?>
            <p><a href="play.php">Return to your adventure</a></p>
            <p>Beginning a new adventure replaces your current run.</p>
        <?php endif; ?>

        <form method="post" action="character.php">
            <input type="hidden" name="character_token"
                   value="<?= htmlspecialchars($_SESSION['character_token'], ENT_QUOTES, 'UTF-8') ?>">

            <fieldset>
                <legend>Character details</legend>

                <p>
                    <label for="hero-name">Hero name</label>
                    <input type="text" id="hero-name" name="hero_name" maxlength="30"
                           value="<?= htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                           required>
                </p>

                <p>
                    <label for="hero-class">Class</label>
                    <select id="hero-class" name="hero_class" required>
                        <option value="">Choose a class</option>
                        <?php foreach ($classes as $key => $class): ?>
                            <option value="<?= $key ?>" <?= $selectedClass === $key ? 'selected' : '' ?>>
                                <?= $class['label'] ?> · <?= $class['health'] ?> health ·
                                <?= $class['strength'] ?> strength · <?= $class['item'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <button type="submit">Begin adventure</button>
            </fieldset>
        </form>

        <p><a href="leaderboard.php">View leaderboard</a></p>
    </main>
</body>
</html>