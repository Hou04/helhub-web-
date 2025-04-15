<?php
session_start();
require_once '../config/database.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'manager') {
        header('Location: ../manager/dashboard.php');
    } else {
        header('Location: ../donor/dashboard.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs';
        header('Location: login.php');
        exit;
    }

    try {
        // Check for manager
        $stmt = $pdo->prepare('
            SELECT r.id_responsable as id, r.pseudo as username, r.pwrd as password, "manager" as user_type
            FROM responsable_association r
            WHERE r.pseudo = ?
            UNION
            SELECT d.id_donateur as id, d.pseudo as username, d.pwrd as password, "donor" as user_type
            FROM donateur d
            WHERE d.pseudo = ?
        ');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] === 'manager') {
                    header('Location: ../manager/dashboard.php');
                } else {
                    header('Location: ../donor/dashboard.php');
                }
                exit;
            } else {
                $_SESSION['error'] = 'Mot de passe incorrect';
            }
        } else {
            $_SESSION['error'] = 'Utilisateur non trouvé';
        }
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Une erreur est survenue. Veuillez réessayer plus tard.';
        header('Location: login.php');
        exit;
    }
} else {
    include 'login_form.php';
    exit;
}
?>