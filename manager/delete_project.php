<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$project_id = $_GET['id'];

try {
    // Verify project belongs to manager's association
    $stmt = $pdo->prepare('
        SELECT p.id 
        FROM projects p
        JOIN associations a ON p.association_id = a.id
        WHERE p.id = ? AND a.manager_id = ?
    ');
    $stmt->execute([$project_id, $_SESSION['user_id']]);
    $project = $stmt->fetch();

    if (!$project) {
        throw new Exception('Project not found or unauthorized');
    }

    // Check if project has any donations
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM donations WHERE project_id = ?');
    $stmt->execute([$project_id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Cannot delete project with existing donations');
    }

    // Delete project
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
    $stmt->execute([$project_id]);

    $_SESSION['success'] = 'Project deleted successfully';
    header('Location: dashboard.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: dashboard.php');
    exit;
}
?> 