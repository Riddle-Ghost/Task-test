<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use JasonGrimes\Paginator;
use Respect\Validation\Validator as v;

class TasksController extends Controller
{

  public function index(Request $request)
  {
    
    $page = $request->query->get('page') ?? 1;
    $sort = $request->query->get('sort');
    $order = $request->query->get('order');
    $limit = 3;

    if ( !in_array($sort, Task::SORT_COLUMNS) ) {
      $sort = 'id';
    }
    if ( !in_array($order, ['asc', 'desc']) ) {
      $order = 'desc';
    }

    $urlPattern = "/?sort=$sort&order=$order&page=(:num)";
    $count = Task::_count();

    $data['request'] = $request->query->all();
    $data['tasks'] = Task::_paginate($limit, $page, $sort, $order);

    if ( $count > $limit ) {

      $data['paginator'] = new Paginator($count, $limit, $page, $urlPattern);
    }

    $data['statuses'] = Task::STATUS_NAMES;

    echo $this->view->generate('tasks/index', $data);
  }

  public function create()
  {
    echo $this->view->generate('tasks/create');
  }

  public function store(Request $request)
  {
    $data = $this->getValidatedRequest($request, "/tasks/add");
    
    Task::_new([
      'name' => $data['name'],
      'email' => $data['email'],
      'text' => $data['text'],
    ]);

    return $this->flash->success('Таск успешно добавлен', '/');
  }

  public function edit(Request $request)
  {
    if ( !User::_isAdmin() ) {
      return redirect('/login');
    }

    $id = $request->query->get('id');

    $data['task'] = Task::_find($id);

    $data['statuses'] = Task::STATUS_NAMES;

    echo $this->view->generate('tasks/edit', $data);
  }

  public function update(Request $request)
  {
    if ( !User::_isAdmin() ) {
      return redirect('/login');
    }

    $id = $request->query->get('id');

    $task = Task::_find($id);

    $data = $this->getValidatedRequest($request, "/tasks/edit?id=$id");
    
    $edited = ( $data['text'] === $task['text'] ) ? 0 : 1;
    
    Task::_update($id, [
      'name' => $data['name'],
      'email' => $data['email'],
      'text' => $data['text'],
      'status' => $data['status'],
      'edited' => $edited,
    ]);

    return $this->flash->success('Таск успешно отредактирован', '/');

  }
  public function destroy(Request $request)
  {
    if ( !User::_isAdmin() ) {
      return redirect('/login');
    }

    $id = $request->query->get('id');

    Task::_delete($id);

    return $this->flash->success('Таск успешно удален', '/');
  }

  private function getValidatedRequest(Request $request, string $redirect)
  {
    $id = $request->query->get('id') ?? null;
    $name = trim( htmlEntities($request->request->get('name'), ENT_QUOTES) );
    $email = trim( htmlEntities($request->request->get('email'), ENT_QUOTES) );
    $text = trim( htmlEntities($request->request->get('text'), ENT_QUOTES) );
    $status = trim( htmlEntities($request->request->get('status'), ENT_QUOTES) );

    if ( !v::notBlank()->validate($name) ||
        !v::notBlank()->validate($email) ||
        !v::notBlank()->validate($text) ) {
            
        return $this->flash->error('Все поля обязательны для заполнения!', $redirect);
    }

    errors_clear();

    if ( !v::stringType()->length(3, 15, true)->validate($name) ) {

        errors_set('name', 'Имя некорректной длины!');
    }
    if ( !v::email()->validate($email) ) {

        errors_set('email', 'Email неверного формата!');
    }
    if ( !v::stringType()->length(3, 200, true)->validate($text) ) {

        errors_set('text', 'Текст некорректной длины!');
    }
    
    if (errors_exist()) {

        return redirect($redirect);
    }

    $data['id'] = $id;
    $data['name'] = $name;
    $data['email'] = $email;
    $data['text'] = $text;
    $data['status'] = $status;

    return $data;
  }
}
