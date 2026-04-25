<?php
define('EVA_APP', true);
$pageTitle = 'Catégories';
require __DIR__ . '/layout/header.php';
?>

<div class="form-wrapper form-wrapper--wide">
    <div class="form-card">
        <div class="form-card-header">
            <a href="index.php?action=dashboard" class="back-link">← Retour</a>
            <h1>Gestion des catégories</h1>
            <p>Créez et organisez vos catégories de tâches</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>
        <?php if (($_GET['msg'] ?? '') === 'cat_deleted'): ?>
            <div class="alert alert-success">✓ Catégorie supprimée.</div>
        <?php endif; ?>

        <!-- Add category form -->
        <div class="category-add-block">
            <h2>Ajouter une catégorie</h2>
            <form action="index.php?action=add_category" method="POST"
                  class="inline-form" novalidate>
                <div class="inline-form-group">
                    <input
                        type="text"
                        name="name"
                        placeholder="Nom de la catégorie"
                        maxlength="100"
                        required
                        autofocus
                    >
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>

        <!-- Category list -->
        <div class="category-list">
            <h2>Catégories existantes <span class="count">(<?= count($categories) ?>)</span></h2>

            <?php if (empty($categories)): ?>
                <div class="empty-state empty-state--small">
                    <p>Aucune catégorie pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="categories-grid">
                    <?php foreach ($categories as $cat): ?>
                        <div class="category-item">
                            <form action="index.php?action=update_category" method="POST"
                                  class="category-edit-form">
                                <input type="hidden" name="cat_id" value="<?= $cat->id ?>">
                                <input
                                    type="text"
                                    name="name"
                                    value="<?= e($cat->name) ?>"
                                    maxlength="100"
                                    required
                                    class="category-input"
                                >
                                <div class="category-item-actions">
                                    <button type="submit" class="btn btn-ghost btn-sm"
                                            title="Enregistrer">✓ Sauvegarder</button>
                                    <a href="index.php?action=delete_category&id=<?= $cat->id ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Supprimer cette catégorie ? Les tâches associées n\'auront plus de catégorie.')"
                                       title="Supprimer">🗑️ Supprimer</a>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
