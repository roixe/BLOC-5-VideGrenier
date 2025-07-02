<?php
namespace App\Controllers;

use App\Models\User;
use Core\View;

class UserController
{
    private User $userModel;
    protected View $view;

    public function __construct(User $userModel = null)
    {
        $this->userModel = $userModel ?? new User();
        $this->view = new View();
    }

    public function loginAction(array $post = null)
    {
        $post = $post ?? $_POST;
        $errors = [];

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!empty($_SESSION['user'])) {
            header('Location: /account');
            exit;
        }

        if (empty($_SESSION['user']) && !empty($_COOKIE['remember_me'])) {
            $user = $this->userModel->getUserByRememberToken($_COOKIE['remember_me']);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: /account');
                exit;
            } else {
                setcookie('remember_me', '', time() - 3600, '/', '', false, true);
            }
        }

        if (!empty($post)) {
            $user = $this->userModel->getByLogin($post['email'] ?? '');

            if (!$user) {
                $errors[] = 'Utilisateur introuvable';
            } else {
                $hashedPassword = hash('sha256', $post['password'] . $user['salt']);
                if ($hashedPassword !== $user['password']) {
                    $errors[] = 'Mot de passe incorrect';
                } else {
                    $_SESSION['user'] = $user;

                    // Gestion "Se souvenir de moi"
                    if (!empty($post['remember'])) {
                        $token = bin2hex(random_bytes(32));
                        $this->userModel->saveRememberToken($user['id'], $token);
                        setcookie('remember_me', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
                    }

                    header('Location: /account');
                    exit;
                }
            }
        }

        $this->view->renderTemplate('User/login.html', ['errors' => $errors]);
    }

 public function logoutAction()
{
    // Supprimer toutes les données de session
    $_SESSION = [];

    // Supprimer le cookie remember_me

     setcookie('remember_me', '', time() - 3600, '/', '', false, true);
    // Détruire la session
    session_destroy();

   
    
    // Redirection
   $this->view->renderTemplate('Home/index.html', [
        'message' => 'Vous avez été déconnecté avec succès.'
    ]);
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
                    'salt' => $salt,
                    'remember_token' => null 
                ];

                $userId = $this->userModel->createUser($userData);

                if ($userId) {
                    
                    $user = $this->userModel->getByLogin($post['email']);

                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    $_SESSION['user'] = $user;

                    header('Location: /account');
                    exit;
                } else {
                    $errors[] = 'Erreur lors de la création du compte.';
                }
            }
        }

        $this->view->renderTemplate('User/register.html', ['errors' => $errors]);
    }

    public function accountAction(array $session = null)
    {
        $session = $session ?? $_SESSION;

        if (!isset($session['user'])) {
            header('Location: /login');
            exit;
        }

        $articles = $this->userModel->getUserArticles($session['user']['id']);

        $this->view->renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }
}
