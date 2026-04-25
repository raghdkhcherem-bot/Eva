<?php
require_once __DIR__ . '/../Models/TaskManager.php';
require_once __DIR__ . '/../Models/CategoryManager.php';

class TaskController {
    private TaskManager     $taskManager;
    private CategoryManager $categoryManager;

    public function __construct() {
        $this->taskManager     = new TaskManager();
        $this->categoryManager = new CategoryManager();
    }

    // ── Dashboard ────────────────────────────────────────────────────────────
    public function dashboard(): void {
        $userId     = (int)$_SESSION['user_id'];
        $tasks      = $this->taskManager->getAllByUser($userId);
        $stats      = $this->taskManager->getStats($userId);
        $categories = $this->categoryManager->getAll();

        // Attach file info to each task
        foreach ($tasks as $task) {
            $task->file = $this->taskManager->getFile($task->id);
        }

        // Optional filter by category or status
        $filterCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        $filterStatus   = $_GET['status'] ?? '';

        if ($filterCategory) {
            $tasks = array_filter($tasks, fn($t) => $t->category_id === $filterCategory);
        }
        if ($filterStatus !== '') {
            $tasks = array_filter($tasks, fn($t) => $t->status === $filterStatus);
        }

        require __DIR__ . '/../Views/Dashboard.php';
    }

    // ── Show AddTask form ────────────────────────────────────────────────────
    public function showAddTask(): void {
        $categories = $this->categoryManager->getAll();
        $error      = null;
        require __DIR__ . '/../Views/AddTask.php';
    }

    // ── Process add task ─────────────────────────────────────────────────────
    public function addTask(): void {
        $userId     = (int)$_SESSION['user_id'];
        $title      = trim($_POST['title'] ?? '');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $file       = $_FILES['task_file'] ?? null;
        $error      = null;
        $categories = $this->categoryManager->getAll();

        if (empty($title)) {
            $error = 'Le titre de la tâche est requis.';
        } elseif ($file && $file['error'] !== UPLOAD_ERR_NO_FILE && $file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Erreur lors de l\'upload du fichier.';
        } else {
            $fileArg = ($file && $file['error'] === UPLOAD_ERR_OK) ? $file : null;

            if ($this->taskManager->create($title, $userId, $categoryId, $fileArg)) {
                header('Location: index.php?action=dashboard&msg=task_added');
                exit;
            } else {
                $error = 'Erreur lors de l\'ajout de la tâche.';
            }
        }

        require __DIR__ . '/../Views/AddTask.php';
    }

    // ── Show EditTask form ───────────────────────────────────────────────────
    public function showEditTask(): void {
        $userId     = (int)$_SESSION['user_id'];
        $taskId     = (int)($_GET['id'] ?? 0);
        $task       = $this->taskManager->getById($taskId, $userId);
        $categories = $this->categoryManager->getAll();
        $error      = null;

        if (!$task) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $task->file = $this->taskManager->getFile($task->id);
        require __DIR__ . '/../Views/EditTask.php';
    }

    // ── Process edit task ────────────────────────────────────────────────────
    public function editTask(): void {
        $userId     = (int)$_SESSION['user_id'];
        $taskId     = (int)($_POST['task_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $categoryId = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $file       = $_FILES['task_file'] ?? null;
        $error      = null;
        $categories = $this->categoryManager->getAll();
        $task       = $this->taskManager->getById($taskId, $userId);

        if (!$task) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $task->file = $this->taskManager->getFile($task->id);

        if (empty($title)) {
            $error = 'Le titre de la tâche est requis.';
        } else {
            $fileArg = ($file && $file['error'] === UPLOAD_ERR_OK) ? $file : null;

            if ($this->taskManager->update($taskId, $userId, $title, $categoryId, $fileArg)) {
                header('Location: index.php?action=dashboard&msg=task_updated');
                exit;
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        }

        require __DIR__ . '/../Views/EditTask.php';
    }

    // ── Toggle task status ───────────────────────────────────────────────────
    public function toggleStatus(): void {
        $userId = (int)$_SESSION['user_id'];
        $taskId = (int)($_GET['id'] ?? 0);
        $this->taskManager->toggleStatus($taskId, $userId);
        header('Location: index.php?action=dashboard');
        exit;
    }

    // ── Delete task ──────────────────────────────────────────────────────────
    public function deleteTask(): void {
        $userId = (int)$_SESSION['user_id'];
        $taskId = (int)($_GET['id'] ?? 0);
        $this->taskManager->delete($taskId, $userId);
        header('Location: index.php?action=dashboard&msg=task_deleted');
        exit;
    }

    // ── Download file ────────────────────────────────────────────────────────
    public function downloadFile(): void {
        $userId  = (int)$_SESSION['user_id'];
        $taskId  = (int)($_GET['id'] ?? 0);
        $task    = $this->taskManager->getById($taskId, $userId);

        if (!$task) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $filename = $this->taskManager->getFile($taskId);
        if (!$filename) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $filepath = __DIR__ . '/../../public/uploads/' . $filename;
        if (!file_exists($filepath)) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $mime = mime_content_type($filepath) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}
