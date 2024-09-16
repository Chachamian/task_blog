<?php
require_once ('autoload.php');

$blade = new \App\Blade();
$postSystem = new \App\PostSystem();
$authSystem = new \App\Authorize();
$user = $authSystem->user();

if ($user === null) {
    header('location: /main');
}

$type = $_GET['type'] ?? '0';
$errors = [];
$success = false;
$post = $postSystem->getPostById($_GET['postId'] ?? null);

if ($post != null) {
    $type = $post->postType;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['type'] = $type;
    if ($post !== null) {
        $postSystem->editPost($_POST, $post->id);
        $errors = $postSystem->errors();
        if (empty($errors)) {
            $post = $postSystem->getPostById($_GET['postId'] ?? null);
        }
    } else {
        $postSystem->addPost($_POST);
    }

    $errors = $postSystem->errors();
    if (empty($errors)) {
        $success = true;
    }
}

$template = $blade->getTemplate('add-post', ['type' => $type, 'errors' => $errors, 'post' => $post]);
$layout   = $blade->getTemplate('layout', ['user' => $user, 'content' => $template]);
echo $layout;

if ($success) {
    echo $blade->getTemplate('modal', ['title' => 'Пост добавлен', 'content' => 'Ваш пост успешно добавлен и теперь отображается в ленте']);
}