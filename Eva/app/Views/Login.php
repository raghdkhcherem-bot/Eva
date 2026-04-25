<?php
define('EVA_APP', true);
$pageTitle = 'Connexion';
require __DIR__ . '/layout/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">◈</div>
            <h1>Bon retour !</h1>
            <p>Connectez-vous à votre espace Eva</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="index.php?action=do_login" method="POST" class="auth-form" novalidate>
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="vous@exemple.com"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Se connecter
            </button>
        </form>

        <div class="auth-footer">
            Pas encore de compte ?
            <a href="index.php?action=signup">Créer un compte</a>
        </div>
    </div>

    <div class="auth-visual">
        <div class="visual-content">
            <div class="visual-icon">◈</div>
            <h2>Eva</h2>
            <p>Organisez vos tâches quotidiennes avec simplicité et efficacité.</p>
            <ul class="feature-list">
                <li>✓ Gestion de tâches intuitive</li>
                <li>✓ Catégories personnalisées</li>
                <li>✓ Pièces jointes par tâche</li>
                <li>✓ Suivi de progression</li>
            </ul>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
