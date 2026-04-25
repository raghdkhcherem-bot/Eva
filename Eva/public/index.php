<?php
session_start();

// ── Autoload controllers & models ────────────────────────────────────────────
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/TaskController.php';
require_once __DIR__ . '/../app/Controllers/CategoryController.php';

// ── Helpers ──────────────────────────────────────────────────────────────────
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireAuth(): void {
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit;
    }
}

// ── Router ───────────────────────────────────────────────────────────────────
$action = $_GET['action'] ?? 'login';
$method = $_SERVER['REQUEST_METHOD'];

// Public routes (no auth required)
$publicRoutes = ['login', 'do_login', 'signup', 'do_signup'];

if (!in_array($action, $publicRoutes, true)) {
    requireAuth();
}

// Dispatch
switch ($action) {

    // Auth
    case 'login':
        (new AuthController())->showLogin();
        break;
    case 'do_login':
        (new AuthController())->login();
        break;
    case 'signup':
        (new AuthController())->showSignup();
        break;
    case 'do_signup':
        (new AuthController())->signup();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // Tasks
    case 'dashboard':
        (new TaskController())->dashboard();
        break;
    case 'add_task':
        (new TaskController())->showAddTask();
        break;
    case 'do_add_task':
        (new TaskController())->addTask();
        break;
    case 'edit_task':
        (new TaskController())->showEditTask();
        break;
    case 'do_edit_task':
        (new TaskController())->editTask();
        break;
    case 'toggle_task':
        (new TaskController())->toggleStatus();
        break;
    case 'delete_task':
        (new TaskController())->deleteTask();
        break;
    case 'download_file':
        (new TaskController())->downloadFile();
        break;

    // Categories
    case 'categories':
        (new CategoryController())->showCategories();
        break;
    case 'add_category':
        (new CategoryController())->addCategory();
        break;
    case 'delete_category':
        (new CategoryController())->deleteCategory();
        break;
    case 'update_category':
        (new CategoryController())->updateCategory();
        break;

    default:
        header('Location: index.php?action=dashboard');
        exit;
}
