<?php
define('EVA_APP', true);
$pageTitle = 'Modifier la tâche';
require __DIR__ . '/layout/header.php';
?>

<div class="form-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <a href="index.php?action=dashboard" class="back-link">← Retour</a>
            <h1>Modifier la tâche</h1>
            <p>Mettez à jour les informations de votre tâche</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form action="index.php?action=do_edit_task" method="POST"
              enctype="multipart/form-data" class="task-form" novalidate>

            <input type="hidden" name="task_id" value="<?= $task->id ?>">

            <div class="form-group">
                <label for="title">Titre de la tâche <span class="required">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= e($_POST['title'] ?? $task->title) ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="category_id">Catégorie <span class="hint">(optionnel)</span></label>
                <select id="category_id" name="category_id">
                    <option value="">— Aucune catégorie —</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php
                        $selected_val = $_POST['category_id'] ?? $task->category_id;
                        $isSelected   = ($selected_val == $cat->id);
                        ?>
                        <option value="<?= $cat->id ?>" <?= $isSelected ? 'selected' : '' ?>>
                            <?= e($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Statut actuel</label>
                <div class="status-display">
                    <span class="badge badge-status <?= $task->isDone() ? 'badge-done' : 'badge-pending' ?>">
                        <?= e($task->status) ?>
                    </span>
                    <a href="index.php?action=toggle_task&id=<?= $task->id ?>"
                       class="btn btn-ghost btn-sm">
                        <?= $task->isDone() ? 'Remettre en cours' : 'Marquer terminé' ?>
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label for="task_file">
                    Fichier joint <span class="hint">(optionnel, max 5 Mo)</span>
                </label>

                <?php if (!empty($task->file)): ?>
                    <div class="current-file">
                        <span class="current-file-label">Fichier actuel :</span>
                        <a href="index.php?action=download_file&id=<?= $task->id ?>"
                           class="file-chip">
                            📎 <?= e($task->file) ?>
                        </a>
                        <span class="hint">— Un nouveau fichier remplacera l'actuel</span>
                    </div>
                <?php endif; ?>

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
                        <span class="file-drop-text">Cliquez ou glissez un nouveau fichier</span>
                        <span class="file-drop-hint">PDF, JPG, PNG, DOC, TXT, ZIP</span>
                    </div>
                    <div class="file-selected" id="fileSelected" style="display:none;"></div>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php?action=dashboard" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
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
