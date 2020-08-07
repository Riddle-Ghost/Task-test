<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

class RegisterController extends Controller
{

  public function index()
  {
    if ( User::_isLoggedIn() ) {
        return redirect('/');
    }

    echo $this->view->generate('auth/register');
  }

  public function register(Request $request)
  {
    if ( User::_isLoggedIn() ) {
        return redirect('/');
    }
    
    
    $name = trim( htmlEntities($request->request->get('name'), ENT_QUOTES) );
    $email = trim( htmlEntities($request->request->get('email'), ENT_QUOTES) );
    $password = trim( $request->request->get('password') );
    $password_confirmation = trim( $request->request->get('password_confirmation') );

    if ( !v::notBlank()->validate($name) ||
        !v::notBlank()->validate($email) ||
        !v::notBlank()->validate($password) ||
        !v::notBlank()->validate($password_confirmation) ) {
            
        return $this->flash->error('Все поля обязательны для заполнения!', '/register');
    }

    errors_clear();

    if ( !v::equals($password)->validate($password_confirmation) ) {

        errors_set('password_confirmation', 'Пароли не совпадают!');
    }
    
    if ( !v::email()->validate($email) ) {

        errors_set('email', 'Email неверного формата!');
    }

    if ( !v::stringType()->length(3, 15, true)->validate($name) ) {

        errors_set('name', 'Имя неверного формата!');
    }

    if ( !v::stringType()->length(3, null, true)->validate($password) ) {

        errors_set('password', 'Пароль должен быть длиннее!');
    }

    if ( User::_findByName($name) ) {

        errors_set('name', 'Такой логин уже существует!');
    }

    if (errors_exist()) {

        return redirect('/register');
    }

    User::_register([
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ]);

    User::_auth($name, $password);
    
    return redirect('/');
  }
}
