<?php

require_once 'autoload.php';

$blade = new \App\Blade();
$authSystem = new \App\Authorize();
$user = $authSystem->user();

if ($user !== null) {
    header('location: /');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';
    $authSystem = new \App\Authorize($username, null, $password);
    $authSystem->auth();
}

$template = $blade->getTemplate('main', ['errors' => $authSystem->getError()]);
echo $template;