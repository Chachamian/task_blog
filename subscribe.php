<?php
require_once 'autoload.php';

$blade = new \App\Blade();
$authSystem = new \App\Authorize();
$user = $authSystem->user();

if ($user === null) {
    header('location: /main.php');
}

$flash = new \App\FlashSystem();
$authSystem = new \App\Authorize();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authSystem->subscribe($_GET['ownerId'] ?? null, $_GET['userId'] ?? null);
    $flash->back();
}
