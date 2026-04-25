<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/Task.php';

class TaskManager {
    private PDO $pdo;
    private string $uploadDir;

    public function __construct() {
        $this->pdo = Database::getInstance();
        $this->uploadDir = __DIR__ . '/../../public/uploads/';
    }

    public function getAllByUser(int $userId): array {
        $stmt = $this->pdo->prepare(
            'SELECT t.*, c.name AS category_name
             FROM tasks t
             LEFT JOIN categories c ON t.category_id = c.id
             WHERE t.id_users = ?
             ORDER BY t.created_at DESC'
        );
        $stmt->execute([$userId]);
        return array_map(fn($row) => new Task($row), $stmt->fetchAll());
    }

    public function getById(int $taskId, int $userId): ?Task {
        $stmt = $this->pdo->prepare(
            'SELECT t.*, c.name AS category_name
             FROM tasks t
             LEFT JOIN categories c ON t.category_id = c.id
             WHERE t.id = ? AND t.id_users = ?'
        );
        $stmt->execute([$taskId, $userId]);
        $row = $stmt->fetch();
        return $row ? new Task($row) : null;
    }

    public function create(string $title, int $userId, ?int $categoryId, ?array $file): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO tasks (title, id_users, category_id) VALUES (?, ?, ?)'
        );
        $ok = $stmt->execute([$title, $userId, $categoryId]);

        if ($ok && $file && $file['error'] === UPLOAD_ERR_OK) {
            $taskId = (int)$this->pdo->lastInsertId();
            $this->saveFile($taskId, $file);
        }

        return $ok;
    }

    public function update(int $taskId, int $userId, string $title, ?int $categoryId, ?array $file): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE tasks SET title = ?, category_id = ? WHERE id = ? AND id_users = ?'
        );
        $ok = $stmt->execute([$title, $categoryId, $taskId, $userId]);

        if ($ok && $file && $file['error'] === UPLOAD_ERR_OK) {
            $this->deleteFile($taskId);
            $this->saveFile($taskId, $file);
        }

        return $ok;
    }

    public function toggleStatus(int $taskId, int $userId): bool {
        $task = $this->getById($taskId, $userId);
        if (!$task) return false;

        $newStatus = $task->isDone() ? 'en cours' : 'terminé';
        $stmt = $this->pdo->prepare(
            'UPDATE tasks SET status = ? WHERE id = ? AND id_users = ?'
        );
        return $stmt->execute([$newStatus, $taskId, $userId]);
    }

    public function delete(int $taskId, int $userId): bool {
        $this->deleteFile($taskId);
        $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = ? AND id_users = ?');
        return $stmt->execute([$taskId, $userId]);
    }

    public function saveFile(int $taskId, array $file): bool {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'txt', 'zip'];
        if (!in_array($ext, $allowed, true)) return false;
        if ($file['size'] > 5 * 1024 * 1024) return false;

        $safeName = $taskId . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($file['name']));
        return move_uploaded_file($file['tmp_name'], $this->uploadDir . $safeName);
    }

    public function getFile(int $taskId): ?string {
        $files = glob($this->uploadDir . $taskId . '_*');
        return !empty($files) ? basename($files[0]) : null;
    }

    public function deleteFile(int $taskId): void {
        foreach (glob($this->uploadDir . $taskId . '_*') as $f) {
            @unlink($f);
        }
    }

    public function getStats(int $userId): array {
        $stmt = $this->pdo->prepare(
            'SELECT
                COUNT(*) AS total,
                SUM(status = "terminé") AS done,
                SUM(status = "en cours") AS pending
             FROM tasks WHERE id_users = ?'
        );
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}