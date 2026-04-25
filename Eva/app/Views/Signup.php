<?php
define('EVA_APP', true);
$pageTitle = 'Créer un compte';
require __DIR__ . '/layout/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">◈</div>
            <h1>Créer un compte</h1>
            <p>Rejoignez Eva et organisez vos tâches</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="index.php?action=do_signup" method="POST" class="auth-form" novalidate>
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Jean Dupont"
                    value="<?= e($_POST['name'] ?? '') ?>"
                    required
                    autocomplete="name"
                >
            </div>

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
                <label for="password">Mot de passe <span class="hint">(min. 6 caractères)</span></label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                >
            </div>

            <div class="form-group">
                <label for="confirm">Confirmer le mot de passe</label>
                <input
                    type="password"
                    id="confirm"
                    name="confirm"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Créer mon compte
            </button>
        </form>

        <div class="auth-footer">
            Déjà un compte ?
            <a href="index.php?action=login">Se connecter</a>
        </div>
    </div>

    <div class="auth-visual">
        <div class="visual-content">
            <div class="visual-icon">◈</div>
            <h2>Eva</h2>
            <p>Votre assistant personnel pour une productivité optimale.</p>
            <ul class="feature-list">
                <li>✓ Espace personnel sécurisé</li>
                <li>✓ Données confidentielles</li>
                <li>✓ Interface simple et rapide</li>
                <li>✓ Accès depuis partout</li>
            </ul>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
