<?php

require_once __DIR__ . '/session.php';
require_login();

$_SESSION['logout_token'] = $_SESSION['logout_token'] ?? bin2hex(random_bytes(32));
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['logout_token'] ?? null;

    if (!is_string($token) || !hash_equals($_SESSION['logout_token'], $token)) {
        $error = 'Your sign-out form expired. Please try again.';
    } else {
        $_SESSION = array_intersect_key($_SESSION, ['leaderboard' => true]);

        if (!session_regenerate_id(true)) {
            http_response_code(500);
            exit('The Last Ember could not renew your session. Please close this browser window.');
        }

        header('Location: login.php', true, 303);
        exit;
    }
}

$entries = array_values($_SESSION['leaderboard'] ?? []);

usort($entries, function (array $first, array $second): int {
    $byScore = $second['score'] <=> $first['score'];

    return $byScore !== 0 ? $byScore : $first['order'] <=> $second['order'];
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaderboard | The Last Ember</title>
</head>
<body>
    <main>
        <h1>Leaderboard</h1>

        <table>
            <caption>Completed adventures for this visit</caption>
            <thead>
                <tr>
                    <th scope="col">Rank</th>
                    <th scope="col">Username</th>
                    <th scope="col">Story points</th>
                    <th scope="col">Ending</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($entries === []): ?>
                    <tr>
                        <td colspan="4">No scores yet. Complete an adventure to post your first score.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($entries as $index => $entry): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($entry['username'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int) $entry['score'] ?></td>
                            <td><?= htmlspecialchars($entry['ending'], ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <nav aria-label="Leaderboard options">
            <ul>
                <li><a href="character.php">Back to character setup</a></li>
            </ul>
        </nav>

        <?php if ($error !== ''): ?>
            <p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" action="leaderboard.php">
            <input type="hidden" name="logout_token"
                   value="<?= htmlspecialchars($_SESSION['logout_token'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Sign out</button>
        </form>
    </main>
</body>
</html>