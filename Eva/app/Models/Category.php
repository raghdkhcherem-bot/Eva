<?php
class Category {
    public int $id;
    public string $name;

    public function __construct(array $data) {
        $this->id   = (int)($data['id'] ?? 0);
        $this->name = $data['name'] ?? '';
    }
}
