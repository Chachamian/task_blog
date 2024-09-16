<?php
require_once 'autoload.php';

$blade = new \App\Blade();
$authSystem = new \App\Authorize();
$user = $authSystem->user();

if ($user === null) {
    header('location: /main.php');
}

$flash = new \App\FlashSystem();
$postSystem = new \App\PostSystem();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postSystem->allowPost($user, $_GET['access'] ?? null);
    $flash->back();
}
