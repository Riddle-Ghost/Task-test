<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;
use Respect\Validation\Validator as v;

class LoginController extends Controller
{

  public function index()
  {
    if ( User::_isLoggedIn() ) {
      return redirect('/');
    }

    echo $this->view->generate('auth/login');
  }

  public function login(Request $request)
  {

    if ( User::_isLoggedIn() ) {
      return redirect('/');
    }

    $name = trim( $request->request->get('name') );
    $password = trim( $request->request->get('password') );

    if ( !v::notBlank()->validate($name) || !v::notBlank()->validate($password) ) {
            
      return $this->flash->error('Поля логин и пароль обязательны для заполнения!', '/login');
    }

    if (!User::_auth($name, $password)) {

      return $this->flash->error('Неверный логин или пароль!', '/login');
      
    }
    
    return redirect('/');
  }

  public function logout()
  {
    User::_logout();
    return redirect('/');
  }
}
