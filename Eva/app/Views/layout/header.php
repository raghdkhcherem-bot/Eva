<?php
// Prevent direct access
if (!defined('EVA_APP')) {
    header('Location: index.php');
    exit;
}

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Eva') ?> — Eva</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
<nav class="navbar">
    <a href="index.php?action=dashboard" class="nav-brand">
        <span class="brand-icon">◈</span>
        Eva
    </a>
    <div class="nav-links">
        <a href="index.php?action=dashboard" class="nav-link <?= (($_GET['action'] ?? '') === 'dashboard' || !isset($_GET['action'])) ? 'active' : '' ?>">
            Tableau de bord
        </a>
        <a href="index.php?action=add_task" class="nav-link <?= ($_GET['action'] ?? '') === 'add_task' ? 'active' : '' ?>">
            + Tâche
        </a>
        <a href="index.php?action=categories" class="nav-link <?= ($_GET['action'] ?? '') === 'categories' ? 'active' : '' ?>">
            Catégories
        </a>
    </div>
    <div class="nav-user">
        <span class="user-greeting">👋 <?= e($_SESSION['user_name'] ?? '') ?></span>
        <a href="index.php?action=logout" class="btn btn-ghost btn-sm">Déconnexion</a>
    </div>
</nav>
<?php endif; ?>
<main class="main-content">
