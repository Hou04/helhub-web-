<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $cin = $_POST['cin'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'donor';

    // Validation
    if (empty($name) || empty($prenom) || empty($cin) || empty($email) || empty($username) || empty($password)) {
        $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires';
        header('Location: ../index.php');
        exit;
    }

    // Validate CIN (8 digits)
    if (!preg_match('/^\d{8}$/', $cin)) {
        $_SESSION['error'] = 'Le CIN doit contenir exactement 8 chiffres';
        header('Location: ../index.php');
        exit;
    }

    // Validate username (letters only)
    if (!preg_match('/^[a-zA-Z]+$/', $username)) {
        $_SESSION['error'] = 'Le pseudo ne doit contenir que des lettres';
        header('Location: ../index.php');
        exit;
    }

    // Validate password (8+ chars, ends with $ or #)
    if (!preg_match('/^[A-Za-z\d]{7,}[$#]$/', $password)) {
        $_SESSION['error'] = 'Le mot de passe doit avoir au moins 8 caractères (lettres/chiffres) et se terminer par $ ou #';
        header('Location: ../index.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Check if username or email exists
        $stmt = $pdo->prepare('
            (SELECT pseudo, email FROM responsable_association WHERE pseudo = ? OR email = ?)
            UNION
            (SELECT pseudo, email FROM donateur WHERE pseudo = ? OR email = ?)
        ');
        $stmt->execute([$username, $email, $username, $email]);
        
        if ($stmt->fetch()) {
            throw new Exception('Ce pseudo ou email est déjà utilisé');
        }

        if ($user_type === 'manager') {
            // Additional manager validation
            $association_name = $_POST['association_name'] ?? '';
            $association_address = $_POST['association_address'] ?? '';
            $fiscal_id = $_POST['fiscal_id'] ?? '';

            if (empty($association_name) || empty($association_address) || empty($fiscal_id)) {
                throw new Exception('Veuillez remplir tous les champs de l\'association');
            }

            // Validate fiscal ID format ($ABC12)
            if (!preg_match('/^\$[A-Z]{3}\d{2}$/', $fiscal_id)) {
                throw new Exception('L\'identifiant fiscal doit être au format $ABC12 (3 lettres majuscules + 2 chiffres)');
            }

            // Insert manager
            $stmt = $pdo->prepare('
                INSERT INTO responsable_association 
                (nom, prenom, CIN, email, nom_association, adresse_association, matricule_fiscal, pseudo, pwrd)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $name, $prenom, $cin, $email, 
                $association_name, $association_address, $fiscal_id,
                $username, $password
            ]);
        } else {
            // Insert donor
            $stmt = $pdo->prepare('
                INSERT INTO donateur 
                (nom, prenom, CIN, email, pseudo, pwrd)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $name, $prenom, $cin, $email, 
                $username, $password
            ]);
        }

        $pdo->commit();
        $_SESSION['success'] = 'Inscription réussie! Vous pouvez maintenant vous connecter.';
        header('Location: ../index.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../index.php');
        exit;
    }
} elseif (isset($_GET['type']) && $_GET['type'] === 'manager') {
    // Display manager registration form
    include 'signup_manager.php';
    exit;
} else {
    // Display donor registration form
    include 'signup_donor.php';
    exit;
}
?>