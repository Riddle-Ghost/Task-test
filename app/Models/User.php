<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{

  protected $table = 'users';

  public const ROLE_USER = 'user';
  public const ROLE_ADMIN = 'admin';

  public function register(array $user)
  {
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    $user['role'] = self::ROLE_USER;

    return $this->create($user);
  }

  public function auth(string $name, string $password)
  {

    $user = $this->findByName($name);
    
    if( !$user || !password_verify ( $password , $user['password'] ) ) {

      return false;
    }

    $_SESSION['user'] = $user;

    return true;
  }

  public function logout()
  {
    unset($_SESSION['user']);
  }

  public function isLoggedIn()
  {
    return isset($_SESSION['user']);
  }

  public function isAdmin()
  {

    return $this->isLoggedIn() && $_SESSION['user']['role'] === self::ROLE_ADMIN;
  }

  public function findByEmail(string $email)
  {

    $table = $this->table;

    $sql = "SELECT * FROM $table
            WHERE `email`= :email
            LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $user;
  }

  public function findByName(string $name)
  {

    $table = $this->table;

    $sql = "SELECT * FROM $table
            WHERE `name`= :name
            LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $user;
  }
}
