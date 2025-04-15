<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a donor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (empty($project_id) || empty($amount)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: dashboard.php');
        exit;
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Get project details and verify it's active
        $stmt = $pdo->prepare('
            SELECT p.*, 
                   COALESCE(SUM(d.amount), 0) as collected_amount
            FROM projects p
            LEFT JOIN donations d ON p.id = d.project_id
            WHERE p.id = ? AND p.deadline > CURDATE()
            GROUP BY p.id
        ');
        $stmt->execute([$project_id]);
        $project = $stmt->fetch();

        if (!$project) {
            throw new Exception('Project not found or expired');
        }

        // Check if project is fully funded
        if ($project['collected_amount'] >= $project['target_amount']) {
            throw new Exception('Project is already fully funded');
        }

        // Check if donation amount exceeds remaining amount
        $remaining = $project['target_amount'] - $project['collected_amount'];
        if ($amount > $remaining) {
            throw new Exception('Donation amount cannot exceed remaining amount');
        }

        // Insert donation
        $stmt = $pdo->prepare('
            INSERT INTO donations (project_id, donor_id, amount)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$project_id, $_SESSION['user_id'], $amount]);

        $pdo->commit();
        $_SESSION['success'] = 'Donation successful! Thank you for your contribution.';
        header('Location: dashboard.php');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
?>