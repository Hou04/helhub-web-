<?php include_once '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Donateur - HelpHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Inscription Donateur</h4>
                    </div>
                    <div class="card-body">
                        <form action="signup.php" method="POST">
                            <input type="hidden" name="user_type" value="donor">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="cin" class="form-label">CIN (8 chiffres)</label>
                                <input type="text" class="form-control" id="cin" name="cin" 
                                       pattern="\d{8}" title="8 chiffres exactement" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Pseudo (lettres seulement)</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       pattern="[A-Za-z]+" title="Lettres seulement" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       pattern="[A-Za-z\d]{7,}[$#]" 
                                       title="8+ caractères (lettres/chiffres) se terminant par $ ou #" required>
                                <small class="text-muted">Doit contenir au moins 8 caractères (lettres/chiffres) et se terminer par $ ou #</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <p>Déjà inscrit? <a href="login.php">Connectez-vous</a></p>
                            <p>Association? <a href="signup.php?type=manager">Inscrivez votre association</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>