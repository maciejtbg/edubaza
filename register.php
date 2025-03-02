<?php
session_start();
require_once 'db.php';

// Pobierz listę ras do formularza
$stmt = $pdo->query("SELECT id, name FROM races");
$races = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password']; // Bez hashowania, ale to niezalecane!
    $race_id  = intval($_POST['race']); // ID rasy, nie nazwa
    $gender   = $_POST['gender'];

    // Sprawdzenie, czy email już istnieje
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Ten email jest już zajęty.";
    } else {
        // Sprawdzenie, czy rasa istnieje w bazie
        $stmt = $pdo->prepare("SELECT id FROM races WHERE id = ?");
        $stmt->execute([$race_id]);
        if (!$stmt->fetch()) {
            $error = "Nieprawidłowa rasa.";
        } else {
            // Wstawienie użytkownika do bazy
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, race_id, gender, points, level, last_login) 
                                   VALUES (?, ?, ?, ?, ?, 0, 1, NOW())");
            if ($stmt->execute([$username, $email, $password, $race_id, $gender])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                header("Location: index.php");
                exit;
            } else {
                $error = "Błąd rejestracji.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
</head>
<body>
    <h2>Rejestracja</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    
    <form method="POST" action="register.php">
        <label>Nazwa użytkownika:</label>
        <input type="text" name="username" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        
        <label>Hasło:</label>
        <input type="password" name="password" required><br>
        
        <label>Wybierz rasę:</label>
        <select name="race" required>
            <?php foreach ($races as $race): ?>
                <option value="<?= htmlspecialchars($race['id']) ?>">
                    <?= htmlspecialchars($race['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        <label>Wybierz płeć:</label>
        <select name="gender" required>
            <option value="male">Mężczyzna</option>
            <option value="female">Kobieta</option>
        </select><br>
        
        <button type="submit">Zarejestruj się</button>
    </form>
    
    <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
</body>
</html>
