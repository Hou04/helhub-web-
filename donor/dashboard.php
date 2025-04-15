<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is a donor
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header('Location: ../index.php');
    exit;
}

try {
    // Get donor's total donations
    $stmt = $pdo->prepare('
        SELECT COALESCE(SUM(d.amount), 0) as total_donations,
               COUNT(d.id) as donation_count
        FROM donations d
        WHERE d.donor_id = ?
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $donation_stats = $stmt->fetch();

    // Get active projects
    $stmt = $pdo->prepare('
        SELECT p.*, 
               r.nom_association as association_name,
               COALESCE(SUM(d.amount), 0) as collected_amount,
               COUNT(d.id) as donation_count
        FROM projects p
        JOIN responsable_association r ON p.manager_id = r.id_responsable
        LEFT JOIN donations d ON p.id = d.project_id
        WHERE p.deadline > CURDATE()
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ');
    $stmt->execute();
    $projects = $stmt->fetchAll();

    // Get donor's recent donations
    $stmt = $pdo->prepare('
        SELECT d.*, p.title as project_title, r.nom_association as association_name
        FROM donations d
        JOIN projects p ON d.project_id = p.id
        JOIN responsable_association r ON p.manager_id = r.id_responsable
        WHERE d.donor_id = ?
        ORDER BY d.created_at DESC
        LIMIT 5
    ');
    $stmt->execute([$_SESSION['user_id']]);
    $recent_donations = $stmt->fetchAll();
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
                        <h5 class="mb-0">Statistiques des dons</h5>
                    </div>
                    <div class="card-body">
                        <h6>Total des dons</h6>
                        <p>$<?php echo number_format($donation_stats['total_donations'], 2); ?></p>
                        
                        <h6>Nombre de dons</h6>
                        <p><?php echo $donation_stats['donation_count']; ?></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Dons récents</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_donations)): ?>
                            <p>Aucun don récent.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($recent_donations as $donation): ?>
                                    <li class="list-group-item">
                                        <strong><?php echo htmlspecialchars($donation['project_title']); ?></strong><br>
                                        <small>Association: <?php echo htmlspecialchars($donation['association_name']); ?></small><br>
                                        <small>Montant: $<?php echo number_format($donation['amount'], 2); ?></small><br>
                                        <small>Date: <?php echo date('d/m/Y', strtotime($donation['created_at'])); ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Projets actifs</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($projects)): ?>
                            <p>Aucun projet actif pour le moment.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Association</th>
                                            <th>Objectif</th>
                                            <th>Collecté</th>
                                            <th>Date limite</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($projects as $project): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($project['title']); ?></td>
                                                <td><?php echo htmlspecialchars($project['association_name']); ?></td>
                                                <td>$<?php echo number_format($project['target_amount'], 2); ?></td>
                                                <td>$<?php echo number_format($project['collected_amount'], 2); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($project['deadline'])); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary donate-btn" 
                                                            data-bs-toggle="modal" data-bs-target="#donateModal"
                                                            data-project-id="<?php echo $project['id']; ?>"
                                                            data-project-title="<?php echo htmlspecialchars($project['title']); ?>"
                                                            data-remaining="<?php echo $project['target_amount'] - $project['collected_amount']; ?>">
                                                        Faire un don
                                                    </button>
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

    <!-- Donate Modal -->
    <div class="modal fade" id="donateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Faire un don</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="donationForm" action="donate.php" method="POST">
                        <input type="hidden" name="project_id" id="projectId">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Projet</label>
                            <input type="text" class="form-control" id="projectTitle" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="donationAmount" class="form-label">Montant ($)</label>
                            <input type="number" class="form-control" id="donationAmount" name="amount" 
                                   min="1" step="0.01" required>
                            <input type="hidden" id="remainingAmount">
                            <small class="form-text text-muted">
                                Montant maximum: $<span id="maxAmount">0</span>
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirmer le don</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.donate-btn').forEach(button => {
            button.addEventListener('click', function() {
                const projectId = this.dataset.projectId;
                const projectTitle = this.dataset.projectTitle;
                const remaining = this.dataset.remaining;
                
                document.getElementById('projectId').value = projectId;
                document.getElementById('projectTitle').value = projectTitle;
                document.getElementById('remainingAmount').value = remaining;
                document.getElementById('maxAmount').textContent = remaining;
                document.getElementById('donationAmount').max = remaining;
            });
        });
    </script>
</body>
</html> 