<?php
define('EVA_APP', true);
$pageTitle = 'Tableau de bord';
require __DIR__ . '/layout/header.php';

// Flash messages
$msgs = [
    'task_added'   => ['success', '✓ Tâche ajoutée avec succès.'],
    'task_updated' => ['success', '✓ Tâche mise à jour.'],
    'task_deleted' => ['success', '✓ Tâche supprimée.'],
    'cat_deleted'  => ['success', '✓ Catégorie supprimée.'],
];
$msgKey = $_GET['msg'] ?? '';
?>

<div class="dashboard-wrapper">

    <!-- Stats bar -->
    <div class="stats-bar">
        <div class="stat-card">
            <span class="stat-number"><?= (int)($stats['total'] ?? 0) ?></span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-card stat-pending">
            <span class="stat-number"><?= (int)($stats['pending'] ?? 0) ?></span>
            <span class="stat-label">En cours</span>
        </div>
        <div class="stat-card stat-done">
            <span class="stat-number"><?= (int)($stats['done'] ?? 0) ?></span>
            <span class="stat-label">Terminées</span>
        </div>
        <?php
        $total = (int)($stats['total'] ?? 0);
        $done  = (int)($stats['done'] ?? 0);
        $pct   = $total > 0 ? round($done / $total * 100) : 0;
        ?>
        <div class="stat-card stat-progress">
            <span class="stat-number"><?= $pct ?>%</span>
            <span class="stat-label">Progression</span>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $pct ?>%"></div>
            </div>
        </div>
    </div>

    <?php if (isset($msgs[$msgKey])): ?>
        <div class="alert alert-<?= $msgs[$msgKey][0] ?>"><?= $msgs[$msgKey][1] ?></div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="filters-bar">
        <div class="filters-left">
            <span class="filters-title">Filtrer :</span>
            <a href="index.php?action=dashboard"
               class="filter-chip <?= (!isset($_GET['status']) && !isset($_GET['category'])) ? 'active' : '' ?>">
                Toutes
            </a>
            <a href="index.php?action=dashboard&status=en+cours"
               class="filter-chip <?= ($_GET['status'] ?? '') === 'en cours' ? 'active' : '' ?>">
                En cours
            </a>
            <a href="index.php?action=dashboard&status=termin%C3%A9"
               class="filter-chip <?= ($_GET['status'] ?? '') === 'terminé' ? 'active' : '' ?>">
                Terminées
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?action=dashboard&category=<?= $cat->id ?>"
                   class="filter-chip <?= ($filterCategory === $cat->id) ? 'active' : '' ?>">
                    <?= e($cat->name) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="index.php?action=add_task" class="btn btn-primary btn-sm">
            + Nouvelle tâche
        </a>
    </div>

    <!-- Task list -->
    <?php if (empty($tasks)): ?>
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3>Aucune tâche trouvée</h3>
            <p>Commencez par créer une nouvelle tâche.</p>
            <a href="index.php?action=add_task" class="btn btn-primary">Créer une tâche</a>
        </div>
    <?php else: ?>
        <div class="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task-card <?= $task->isDone() ? 'task-done' : '' ?>">
                    <div class="task-check">
                        <a href="index.php?action=toggle_task&id=<?= $task->id ?>"
                           class="toggle-btn <?= $task->isDone() ? 'checked' : '' ?>"
                           title="<?= $task->isDone() ? 'Marquer en cours' : 'Marquer terminé' ?>">
                            <?= $task->isDone() ? '✓' : '' ?>
                        </a>
                    </div>

                    <div class="task-body">
                        <div class="task-title"><?= e($task->title) ?></div>
                        <div class="task-meta">
                            <?php if ($task->category_name): ?>
                                <span class="badge badge-category"><?= e($task->category_name) ?></span>
                            <?php endif; ?>
                            <span class="badge badge-status <?= $task->isDone() ? 'badge-done' : 'badge-pending' ?>">
                                <?= e($task->status) ?>
                            </span>
                            <span class="task-date">
                                <?= date('d/m/Y', strtotime($task->created_at)) ?>
                            </span>
                            <?php if (!empty($task->file)): ?>
                                <a href="index.php?action=download_file&id=<?= $task->id ?>"
                                   class="file-chip" title="Télécharger le fichier">
                                    📎 <?= e($task->file) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="task-actions">
                        <a href="index.php?action=edit_task&id=<?= $task->id ?>"
                           class="action-btn action-edit" title="Modifier">✏️</a>
                        <a href="index.php?action=delete_task&id=<?= $task->id ?>"
                           class="action-btn action-delete"
                           title="Supprimer"
                           onclick="return confirm('Supprimer cette tâche ?')">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
