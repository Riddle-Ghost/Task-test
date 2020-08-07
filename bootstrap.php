<?php

use DI\ContainerBuilder;
use Twig\Loader\FilesystemLoader;
use Plasticbrain\FlashMessages\FlashMessages;
use Twig\Environment;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

session_start();
require_once VENDOR_PATH . '/autoload.php';
require_once APP_PATH . '/helpers.php';
Dotenv::createImmutable(__DIR__)->load();


$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([

    Environment::class =>  function() {
        
        $twig =  new Environment( new FilesystemLoader( config('view', 'path') ) );
        $twig->addGlobal('session', $_SESSION);
        $twig->addGlobal('flash', new FlashMessages);
        $twig->addFunction(
          new \Twig\TwigFunction('errors_has', function ($error) {
            return isset( $_SESSION['errors'][$error] );
          })
        );
        $twig->addFunction(
          new \Twig\TwigFunction('errors_get', function ($error) {
            $text = $_SESSION['errors'][$error];
            unset( $_SESSION['errors'][$error] );
            return $text;
          })
        );
        $twig->addFunction(
          new \Twig\TwigFunction('is_admin', function () {
            return \App\Models\User::_isAdmin();
          })
        );
        
        return $twig;
    },
    Request::class => function() {

      return Request::createFromGlobals();
    },
    
]);

$container = $containerBuilder->build();

$dispatcher = require_once APP_PATH . '/routes.php';

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:
      echo '404';exit;
      break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
      $allowedMethods = $routeInfo[1];
      echo '405 Method Not Allowed';exit;
      break;
  case FastRoute\Dispatcher::FOUND:
      $handler = $routeInfo[1];
      $vars = $routeInfo[2];
      
      $container->call($handler, $vars);

      break;
}