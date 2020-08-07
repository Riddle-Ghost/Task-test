<?php
return [
  "host" => $_ENV['DB_HOST'] ?? "127.0.0.1",
  "dbname" => $_ENV['DB_NAME'] ?? "forge",
  "user" => $_ENV['DB_USER'] ?? "root",
  "password" => $_ENV['DB_PASSWORD'] ?? "",
  'charset' => "UTF8",
  "opt" => array(
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES => false,
    \PDO::MYSQL_ATTR_FOUND_ROWS => true
  )
];