<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $target_amount = $_POST['target_amount'] ?? '';
    $deadline = $_POST['deadline'] ?? '';

    if (empty($title) || empty($description) || empty($target_amount) || empty($deadline)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: dashboard.php');
        exit;
    }

    try {
        // Get association ID
        $stmt = $pdo->prepare('SELECT id FROM associations WHERE manager_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $association = $stmt->fetch();

        if (!$association) {
            throw new Exception('Association not found');
        }

        // Insert project
        $stmt = $pdo->prepare('
            INSERT INTO projects (association_id, title, description, target_amount, deadline)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $association['id'],
            $title,
            $description,
            $target_amount,
            $deadline
        ]);

        $_SESSION['success'] = 'Project created successfully';
        header('Location: dashboard.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred while creating the project';
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?> 