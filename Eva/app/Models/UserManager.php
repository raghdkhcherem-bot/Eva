<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/User.php';

class UserManager {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function create(string $name, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$name, $email, $hash]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }
}
