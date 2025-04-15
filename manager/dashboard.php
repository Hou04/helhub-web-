<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'manager') {
    header('Location: ../index.php');
    exit;
}

try {
    // Get association details
    $stmt = $pdo->prepare('
        SELECT r.*, r.nom_association as name, r.adresse_association as address
        FROM responsable_association r
        WHERE r.id_responsable = ?
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $association = $stmt->fetch();

    // Get projects
    $stmt = $pdo->prepare('
        SELECT p.*, 
               COALESCE(SUM(d.amount), 0) as collected_amount,
               COUNT(d.id) as donation_count
        FROM projects p
        LEFT JOIN donations d ON p.id = d.project_id
        WHERE p.manager_id = ?
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error'] = 'Une erreur est survenue. Veuillez réessayer plus tard.';
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - HelpHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">HelpHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profil</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../auth/logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Informations de l'association</h5>
                    </div>
                    <div class="card-body">
                        <h6>Nom de l'association</h6>
                        <p><?php echo htmlspecialchars($association['name']); ?></p>
                        
                        <h6>Adresse</h6>
                        <p><?php echo htmlspecialchars($association['address']); ?></p>
                        
                        <h6>Email</h6>
                        <p><?php echo htmlspecialchars($association['email']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Projets</h5>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                            Nouveau projet
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (empty($projects)): ?>
                            <p>Aucun projet pour le moment.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Objectif</th>
                                            <th>Collecté</th>
                                            <th>Dons</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($projects as $project): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                                <td>$<?php echo number_format($project['target_amount'], 2); ?></td>
                                                <td>$<?php echo number_format($project['collected_amount'], 2); ?></td>
                                                <td><?php echo $project['donation_count']; ?></td>
                                                <td>
                                                    <?php if ($project['collected_amount'] >= $project['target_amount']): ?>
                                                        <span class="badge bg-success">Terminé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">En cours</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary">Voir</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Project Modal -->
    <div class="modal fade" id="newProjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau projet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="add_project.php" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Objectif ($)</label>
                            <input type="number" class="form-control" id="target_amount" name="target_amount" 
                                   min="1" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Date limite</label>
                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 