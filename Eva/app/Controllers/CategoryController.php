<?php
require_once __DIR__ . '/../Models/CategoryManager.php';

class CategoryController {
    private CategoryManager $categoryManager;

    public function __construct() {
        $this->categoryManager = new CategoryManager();
    }

    // ── Show category management page ────────────────────────────────────────
    public function showCategories(): void {
        $categories = $this->categoryManager->getAll();
        $error      = null;
        $success    = null;
        require __DIR__ . '/../Views/Editcategory.php';
    }

    // ── Add category ─────────────────────────────────────────────────────────
    public function addCategory(): void {
        $name       = trim($_POST['name'] ?? '');
        $categories = $this->categoryManager->getAll();
        $error      = null;
        $success    = null;

        if (empty($name)) {
            $error = 'Le nom de la catégorie est requis.';
        } elseif (strlen($name) > 100) {
            $error = 'Le nom ne peut pas dépasser 100 caractères.';
        } elseif ($this->categoryManager->nameExists($name)) {
            $error = 'Cette catégorie existe déjà.';
        } else {
            if ($this->categoryManager->create($name)) {
                $success = 'Catégorie créée avec succès.';
                $categories = $this->categoryManager->getAll();
            } else {
                $error = 'Erreur lors de la création.';
            }
        }

        require __DIR__ . '/../Views/Editcategory.php';
    }

    // ── Delete category ──────────────────────────────────────────────────────
    public function deleteCategory(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->categoryManager->delete($id);
        header('Location: index.php?action=categories&msg=cat_deleted');
        exit;
    }

    // ── Update category ──────────────────────────────────────────────────────
    public function updateCategory(): void {
        $id         = (int)($_POST['cat_id'] ?? 0);
        $name       = trim($_POST['name'] ?? '');
        $categories = $this->categoryManager->getAll();
        $error      = null;
        $success    = null;

        if (empty($name)) {
            $error = 'Le nom est requis.';
        } elseif ($this->categoryManager->nameExists($name)) {
            $error = 'Ce nom existe déjà.';
        } else {
            $this->categoryManager->update($id, $name);
            $success = 'Catégorie mise à jour.';
            $categories = $this->categoryManager->getAll();
        }

        require __DIR__ . '/../Views/Editcategory.php';
    }
}
