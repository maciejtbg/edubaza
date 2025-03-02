<?php
session_start();
require_once 'db.php';
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    $userData = ["error" => "Błąd: użytkownik niezalogowany!"];
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT u.username, u.points, u.level, u.gender, u.streak_days, r.name AS race 
                           FROM users u 
                           JOIN races r ON u.race_id = r.id 
                           WHERE u.id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $userData = ["error" => "Błąd: użytkownik nie istnieje!"];
    } else {
        // Oryginalna ścieżka
        $originalPath = "assets/" . strtolower($user['race']) . "_" . $user['gender'] . ".png";

        // Jeśli plik nie istnieje, ustawiamy placeholder,
        // a w osobnym polu przekażemy nazwę brakującego pliku
        if (!file_exists($originalPath)) {
            $userData = [
                "username"       => $user['username'],
                "race"           => $user['race'],
                "level"          => (int)$user['level'],
                "points"         => (int)$user['points'],
                "streakDays"     => (int)$user['streak_days'],
                "characterImage" => "assets/placeholder.png", // Zawsze istniejący plik
                "missingFile"    => $originalPath            // Nazwa brakującego
            ];
        } else {
            // Plik istnieje, więc wczytujemy go normalnie
            $userData = [
                "username"       => $user['username'],
                "race"           => $user['race'],
                "level"          => (int)$user['level'],
                "points"         => (int)$user['points'],
                "streakDays"     => (int)$user['streak_days'],
                "characterImage" => $originalPath, // Istniejący plik
                "missingFile"    => null
            ];
        }
    }
}

$userJson = json_encode($userData);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Gra RPG – Bez błędu w konsoli</title>
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.60.0/dist/phaser.js"></script>
    <style>
        body { margin: 0; overflow: hidden; background-color: #222; }
        canvas { display: block; }
    </style>
</head>
<body>
<script>
// Dane z PHP
const userData = <?= $userJson ?>;

const config = {
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    backgroundColor: "#444",
    scene: {
        preload: preload,
        create: create
    }
};

let game = new Phaser.Game(config);

function preload() {
    if (userData.error) return;
    // Ładujemy TYLKO plik, który na pewno istnieje (albo oryginalny, albo placeholder)
    this.load.image("character", userData.characterImage);
}

function create() {
    if (userData.error) {
        this.add.text(50, 50, userData.error, { fontSize: "20px", color: "#ff0000" });
        return;
    }

    // Rysujemy postać
    // Tu nie będzie błędu w konsoli, bo "characterImage" zawsze istnieje
    this.add.text(50, 20, "Moja Gra RPG", { fontSize: "24px", color: "#ffffff" });

    // Jeśli missingFile != null, to znaczy, że oryginalny plik nie istniał
    if (userData.missingFile) {
        // Wyświetlamy prostokąt i komunikat z nazwą brakującego pliku
        let rect = this.add.rectangle(400, 300, 200, 200, 0x777777);
        rect.setOrigin(0.5);
        this.add.text(300, 290, "Brak pliku:", { fontSize: "14px", color: "#ffffff" });
        this.add.text(300, 310, userData.missingFile, { fontSize: "12px", color: "#ffffff" });
    } else {
        // Jeśli plik istnieje, normalnie wyświetlamy postać
        let charSprite = this.add.image(400, 300, "character");
        charSprite.setScale(1.5);
    }

    // Dalszy interfejs gry...
    this.add.text(50, 80, `Gracz: ${userData.username}`, { fontSize: "16px", color: "#fff" });
    // ...
}
</script>
</body>
</html>
