<?php

namespace App\Models;

use App\Core\Model;

class Task extends Model
{

  protected $table = 'tasks';

  public const STATUS_NOT_COMPLETED = 'not_completed';
  public const STATUS_COMPLETED = 'completed';

  public const STATUS_NAMES = [
    self::STATUS_NOT_COMPLETED => 'Не выполнено',
    self::STATUS_COMPLETED => 'Выполнено',
  ];


  public function new(array $data)
  {
    $this->create([
      'name' => $data['name'],
      'email' => $data['email'],
      'text' => $data['text'],
      'status' => self::STATUS_NOT_COMPLETED,
    ]);
  }
}
