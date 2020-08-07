<?php

function config($file, $field = null) {
    $config = require ROOT_PATH . "/config/$file.php";
    return $field ? $config[$field] : $config;
}
function dd($value) {

    var_dump($value);
    return die();
}
function redirect($path) {

    return header("Location: $path");
}
function errors_set($error, $text) {

    $_SESSION['errors'][$error] = $text;
}
function errors_exist() {

    return !empty( $_SESSION['errors'] );
}
function errors_clear() {

    unset($_SESSION['errors']);
}