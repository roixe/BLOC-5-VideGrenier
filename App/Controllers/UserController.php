<?php
namespace App\Controllers;

use App\Models\User;
use \Core\View;

class UserController
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function loginAction(array $post = null)
    {
        $post = $post ?? $_POST;
        $errors = [];

        if (!empty($post)) {
            $user = $this->userModel->getByLogin($post['email'] ?? '');

            if (!$user) {
                $errors[] = 'Utilisateur introuvable';
            } else {
                $hashedPassword = hash('sha256', $post['password'] . $user['salt']);
                if ($hashedPassword !== $user['password']) {
                    $errors[] = 'Mot de passe incorrect';
                } else {
                    // Auth success : start session and set user data
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    $_SESSION['user'] = $user;
                    header('Location: /account');
                    exit;
                }
            }
        }

        // Render login form with errors if any
        View::renderTemplate('User/login.html', ['errors' => $errors]);
    }

    public function logoutAction()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        header('Location: /login');
        exit;
    }

    public function registerAction(array $post = null)
    {
        $post = $post ?? $_POST;
        $errors = [];

        if (!empty($post)) {
            if (($post['password'] ?? '') !== ($post['password_confirm'] ?? '')) {
                $errors[] = 'Les mots de passe ne correspondent pas.';
            } else {
                $salt = bin2hex(random_bytes(16));
                $passwordHash = hash('sha256', $post['password'] . $salt);

                $userData = [
                    'username' => $post['username'] ?? '',
                    'email' => $post['email'] ?? '',
                    'password' => $passwordHash,
                    'salt' => $salt
                ];

                $userId = $this->userModel->createUser($userData);

                if ($userId) {
                    header('Location: /login');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création du compte.';
                }
            }
        }

        View::renderTemplate('User/register.html', ['errors' => $errors]);
    }

    public function accountAction(array $session = null)
    {
        $session = $session ?? $_SESSION;

        if (!isset($session['user'])) {
            header('Location: /login');
            exit;
        }

        // Supposons que le modèle User a une méthode getUserArticles()
        $articles = $this->userModel->getUserArticles($session['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }
}
