<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    public function createUser(array $data)
    {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)');
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public function getByLogin(string $email)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserArticles(int $userId)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM articles WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveRememberToken(int $userId, ?string $token): bool
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        return $stmt->execute(['token' => $token, 'id' => $userId]);
    }

    public function getUserByRememberToken(string $token): ?array
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE remember_token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
