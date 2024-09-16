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
$sorting = $_GET['sorting'] ?? null;
$ask = $_GET['ask'] ?? false;
$type = $_GET['type'] ?? 'all';

$postsLoaded = $postSystem->getPostWithPagination(
    $_GET['page'] ?? 0,
    $sorting,
    $ask,
    $type,
);

$template = $blade->getTemplate('index', [
    'flash' => $flash,
    'posts' => $postsLoaded['posts'] ?? [],
    'lastPage' => $postsLoaded['lastPage'],
    'page' => $_GET['page'] ?? 0,
    'ask' => $ask,
    'sorting' => $sorting,
    'type' => $type,
]);
$layout   = $blade->getTemplate('layout', ['user' => $user, 'content' => $template]);

echo $layout;