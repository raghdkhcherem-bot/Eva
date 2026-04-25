<?php
require_once __DIR__ . '/../Models/UserManager.php';

class AuthController {
    private UserManager $userManager;

    public function __construct() {
        $this->userManager = new UserManager();
    }

    // ── Show login page ──────────────────────────────────────────────────────
    public function showLogin(): void {
        require __DIR__ . '/../Views/Login.php';
    }

    // ── Process login ────────────────────────────────────────────────────────
    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error    = null;

        if (empty($email) || empty($password)) {
            $error = 'Veuillez remplir tous les champs.';
        } else {
            $user = $this->userManager->findByEmail($email);

            if ($user && password_verify($password, $user->password)) {
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user->id;
                $_SESSION['user_name'] = $user->name;
                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect.';
            }
        }

        require __DIR__ . '/../Views/Login.php';
    }

    // ── Show signup page ─────────────────────────────────────────────────────
    public function showSignup(): void {
        require __DIR__ . '/../Views/Signup.php';
    }

    // ── Process signup ───────────────────────────────────────────────────────
    public function signup(): void {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';
        $error    = null;

        if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Veuillez remplir tous les champs.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Adresse email invalide.';
        } elseif (strlen($password) < 6) {
            $error = 'Le mot de passe doit contenir au moins 6 caractères.';
        } elseif ($password !== $confirm) {
            $error = 'Les mots de passe ne correspondent pas.';
        } elseif ($this->userManager->emailExists($email)) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            if ($this->userManager->create($name, $email, $password)) {
                $user = $this->userManager->findByEmail($email);
                session_regenerate_id(true);
                $_SESSION['user_id']   = $user->id;
                $_SESSION['user_name'] = $user->name;
                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $error = 'Erreur lors de la création du compte.';
            }
        }

        require __DIR__ . '/../Views/Signup.php';
    }

    // ── Logout ───────────────────────────────────────────────────────────────
    public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
