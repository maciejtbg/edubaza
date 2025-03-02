<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Logowanie za pomocą email i hasła
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Sprawdź, czy istnieje użytkownik o danym emailu
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prosta weryfikacja hasła (UWAGA: w praktyce należy używać hashowania!)
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: game.php");
        exit;
    } else {
        $error = "Nieprawidłowe dane logowania";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <title>Logowanie</title>

    <!-- Bootstrap CSS (opcjonalnie) -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome do ikon -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <!-- Nasz własny plik CSS -->
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    <div class="login-background">
        <div class="login-container">
            <!-- Opcjonalnie logo gry / napis "Total Battle"
        <div class="logo-container">
            <img src="img/login_logo.png" alt="Total Battle" class="logo-img" />
        </div> -->

            <h2 class="login-title">Logowanie</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło:</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required />
                </div>
                <button type="submit" class="btn btn-primary w-100 login-btn">
                    Zaloguj się
                </button>
            </form>

            <p class="text-center mt-3">Lub zaloguj się za pomocą:</p>
            <div class="social-login d-flex justify-content-center gap-3 mb-3">
                <!-- Ikona Google -->
                <a href="google_login.php" class="social-link">
                    <i class="fab fa-google fa-2x"></i>
                </a>
                <!-- Dodaj inne, np. Facebook/VK, jeśli chcesz -->
                <a href="#" class="social-link">
                    <i class="fab fa-facebook-f fa-2x"></i>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-vk fa-2x"></i>
                </a>
            </div>

            <p class="text-center">
                Nie masz konta? <a href="register.php">Zarejestruj się</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap JS (opcjonalnie) -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <!-- jQuery (opcjonalnie) -->
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        crossorigin="anonymous">
    </script>

</body>

</html>