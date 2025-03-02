
<?php
// Uruchomienie sesji
session_start();

// Sprawdzenie, czy kliknięto przycisk "Zapisz do sesji"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Przykładowe dane do zapisania w sesji
    $_SESSION['username'] = $_POST['username'] ?? 'Brak nazwy użytkownika';
    $_SESSION['user_id'] = rand(1, 1000);  // Losowy ID użytkownika dla testu
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Testowanie Sesji</title>
</head>
<body>
    <h1>Testowanie zapisywania do sesji</h1>

    <form method="POST">
        <label for="username">Podaj nazwę użytkownika:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Zapisz do sesji</button>
    </form>

    <h2>Wartości w sesji:</h2>
    <?php
    // Wyświetlenie wartości zapisanych w sesji
    if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
        echo "<p><strong>Witaj, " . htmlspecialchars($_SESSION['username']) . "!</strong></p>";
        echo "<p>Twój ID: " . $_SESSION['user_id'] . "</p>";
    } else {
        echo "<p>Brak zapisanych danych w sesji.</p>";
    }
    ?>

    <p><a href="test_session.php">Odśwież stronę</a> (Zresetuje dane sesji)</p>
</body>
</html>
