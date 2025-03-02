<?php
session_start();
require_once 'db.php';
require_once 'functions.php';
echo "<p>Twój ID: " . $_SESSION['user_id'] . "</p>";
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = getUser($pdo, $_SESSION['user_id']);

// Sprawdzamy bonus za dzienne logowanie – jeśli data ostatniego logowania nie jest dzisiejsza, dodajemy punkty.
$today = date("Y-m-d");
$lastLogin = date("Y-m-d", strtotime($user['last_login']));
if ($today != $lastLogin) {
    $bonus = 10;
    $multiplier = 1.2;
    $newPoints = $user['points'] + $bonus;
    
    $stmt = $pdo->prepare("UPDATE users SET points = ?, last_login = NOW() WHERE id = ?");
    $stmt->execute([$newPoints, $user['id']]);
    $user['points'] = $newPoints;
    $dailyBonusMsg = "Dziś zalogowałeś się po raz pierwszy! Otrzymujesz bonus: +$bonus punktów (mnożnik: $multiplier).";
}

// Obliczanie levelu – przykładowo: level = floor(points/100) + 1
$level = floor($user['points'] / 100) + 1;
if ($level != $user['level']) {
    $stmt = $pdo->prepare("UPDATE users SET level = ? WHERE id = ?");
    $stmt->execute([$level, $user['id']]);
    $user['level'] = $level;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Strona Główna - Edukacyjna Gra RPG</title>
    <!-- Ładujemy Phaser -->
    <script src="https://cdn.jsdelivr.net/npm/phaser@3/dist/phaser.js"></script>
    <script src="phaser_game.js"></script> <!-- Plik z kodem gry w Phaserze -->
    <style>
      body { font-family: Arial, sans-serif; }
      .character-info { border: 1px solid #ccc; padding: 10px; margin: 10px; }
      .buttons { margin: 10px; }
    </style>
</head>
<body>
    <h1>Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    
    <div class="character-info">
        <h2>Twoja Postać</h2>
        <p>Rasa: <?php echo htmlspecialchars($user['race']); ?></p>
        <p>Płeć: <?php echo htmlspecialchars($user['gender']); ?></p>
        <p>Punkty: <?php echo $user['points']; ?></p>
        <p>Level: <?php echo $user['level']; ?></p>
    </div>
    
    <?php if(isset($dailyBonusMsg)) { echo "<div class='daily-bonus'><p>$dailyBonusMsg</p></div>"; } ?>
    
    <div class="buttons">
        <button onclick="location.href='math_game.php'">Minigra Matematyczna (Test ABC)</button>
        <button onclick="location.href='duel.php'">Losuj Pojedynek</button>
        <button onclick="location.href='logout.php'">Wyloguj się</button>
    </div>
    
    <!-- Opcjonalnie: miejsce na wyświetlenie płótna Phasera -->
    <div id="game-container"></div>
</body>
</html>
