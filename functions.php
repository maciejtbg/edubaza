<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUser($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
