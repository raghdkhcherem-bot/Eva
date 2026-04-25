<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/Category.php';

class CategoryManager {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM categories ORDER BY name ASC');
        return array_map(fn($row) => new Category($row), $stmt->fetchAll());
    }

    public function getById(int $id): ?Category {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new Category($row) : null;
    }

    public function create(string $name): bool {
        $stmt = $this->pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        return $stmt->execute([trim($name)]);
    }

    public function update(int $id, string $name): bool {
        $stmt = $this->pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
        return $stmt->execute([trim($name), $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function nameExists(string $name): bool {
        $stmt = $this->pdo->prepare('SELECT id FROM categories WHERE name = ? LIMIT 1');
        $stmt->execute([trim($name)]);
        return (bool)$stmt->fetch();
    }
}
