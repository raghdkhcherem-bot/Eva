<?php
class Task {
    public int $id;
    public string $title;
    public string $status;
    public string $created_at;
    public int $id_users;
    public ?int $category_id;
    public ?string $category_name;
    public ?string $file = null;

    public function __construct(array $data) {
        $this->id            = (int)($data['id'] ?? 0);
        $this->title         = $data['title'] ?? '';
        $this->status        = $data['status'] ?? 'en cours';
        $this->created_at    = $data['created_at'] ?? '';
        $this->id_users      = (int)($data['id_users'] ?? 0);
        $this->category_id   = isset($data['category_id']) ? (int)$data['category_id'] : null;
        $this->category_name = $data['category_name'] ?? null;
    }

    public function isDone(): bool {
        return $this->status === 'terminé';
    }
}