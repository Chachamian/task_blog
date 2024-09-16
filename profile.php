<?php

require_once 'autoload.php';

$blade = new \App\Blade();
$authSystem = new \App\Authorize();
$user = $authSystem->user();
$postSystem = new \App\PostSystem();

if ($user === null) {
    header('location: /main.php');
}

$userRequest   = $_GET['userId'] ?? null;
$isCurrentUser = $userRequest == $user->id || $userRequest === null;

$checkUser = $authSystem->getUserById($userRequest);

if (!empty($_GET['userId']) && $checkUser === null) {
    $template = $blade->getTemplate('404');
} else {
    $getRequestPosts = $isCurrentUser ? $postSystem->getRequestPost($user) : [];
    $type = $_GET['type'] ?? 'subscribes';
    $template = $blade->getTemplate('profile', [
        'authSystem'    => $authSystem,
        'user'          => $user,
        'subscribes'    => $authSystem->getSubscribesForUser($_GET['userId'] ?? $user->id),
        'isSubscribe'   => $authSystem->isSubscribeCurrentUser($_GET['userId'] ?? $user->id),
        'posts'         => $postSystem->getPostsForCurrentUser($_GET['userId'] ?? $user->id),
        'userId'        => $_GET['userId'] ?? $user->id,
        'accessPosts'   => $getRequestPosts,
        'isCurrentUser' => $isCurrentUser,
        'type'          => !$isCurrentUser && $type === 'requests' ? 'posts' : $type,
        'flash'          => new \App\FlashSystem(),
    ]);
}


$layout = $blade->getTemplate('layout', ['user' => $user, 'content' => $template]);
echo $layout;