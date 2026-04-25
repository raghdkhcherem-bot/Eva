<?php
define('EVA_APP', true);
$pageTitle = 'Nouvelle tâche';
require __DIR__ . '/layout/header.php';
?>

<div class="form-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <a href="index.php?action=dashboard" class="back-link">← Retour</a>
            <h1>Nouvelle tâche</h1>
            <p>Ajoutez une tâche à votre liste</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="index.php?action=do_add_task" method="POST"
              enctype="multipart/form-data" class="task-form" novalidate>

            <div class="form-group">
                <label for="title">Titre de la tâche <span class="required">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Ex : Préparer la réunion de lundi"
                    value="<?= e($_POST['title'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="category_id">Catégorie <span class="hint">(optionnel)</span></label>
                <select id="category_id" name="category_id">
                    <option value="">— Aucune catégorie —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>"
                            <?= (($_POST['category_id'] ?? '') == $cat->id) ? 'selected' : '' ?>>
                            <?= e($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-hint">
                    <a href="index.php?action=categories">Gérer les catégories →</a>
                </div>
            </div>

            <div class="form-group">
                <label for="task_file">Fichier joint <span class="hint">(optionnel, max 5 Mo)</span></label>
                <div class="file-drop-zone" id="dropZone">
                    <input
                        type="file"
                        id="task_file"
                        name="task_file"
                        accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.txt,.zip"
                        class="file-input"
                    >
                    <div class="file-drop-label">
                        <span class="file-drop-icon">📎</span>
                        <span class="file-drop-text">Cliquez ou glissez un fichier ici</span>
                        <span class="file-drop-hint">PDF, JPG, PNG, DOC, TXT, ZIP</span>
                    </div>
                    <div class="file-selected" id="fileSelected" style="display:none;"></div>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php?action=dashboard" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Ajouter la tâche</button>
            </div>
        </form>
    </div>
</div>

<script>
const input = document.getElementById('task_file');
const selected = document.getElementById('fileSelected');
const label = document.querySelector('.file-drop-label');

input.addEventListener('change', () => {
    if (input.files.length > 0) {
        selected.textContent = '📎 ' + input.files[0].name;
        selected.style.display = 'block';
        label.style.display = 'none';
    }
});
</script>

<?php require __DIR__ . '/layout/footer.php'; ?>
