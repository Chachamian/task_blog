<?php
require_once ('autoload.php');
$blade = new \App\Blade();
$authSystem = new \App\Authorize();
$user = $authSystem->user();

if ($user === null) {
    header('location: /main.php');
}

$postSystem = new \App\PostSystem();
$post       = $postSystem->getPostById($_GET['post_id'] ?? null);
$flash       = new \App\FlashSystem();
$error      = $flash->tryGetFlashMessage();

if ($post === null) {
    $template = $blade->getTemplate('404');
} else {
    $action = $_GET['action'] ?? null;
    if ($action === 'delete') {
        $postSystem->deletePost($post);
    }
    $postSystem->addView($post);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_GET['type'] ?? null;

        if ($type === 'repost') {
            $postSystem->repostPost($post);
            $errorData = $postSystem->errors();
            if (!empty($errorData)) {
                echo $blade->getTemplate('modal',
                    [
                        'title' => 'Ошибка добавления поста',
                        'content' => $errorData['user'] ?? 'Ошибка'
                    ]
                );
            } else {
                $post = $postSystem->getPostById($_GET['post_id']);
                echo $blade->getTemplate('modal',
                    [
                        'title' => 'Пост добавлен',
                        'content' => 'Репост прошел успешно!'
                    ]
                );
            }
        }
    }

    $comments = $postSystem->getAllCommentByPostId($_GET['post_id']);
    $template = $blade->getTemplate('post-detail', [
        'user' => $user, 'post' => $post,
        'flash' => $flash,
        'comments' => $comments,
        'error' => $error,
        'subscribes' => $authSystem->getSubscribesForUser($post->userId),
        'isSubscribe' => $authSystem->isSubscribeCurrentUser($post->userId),
        'posts' => $postSystem->getPostsForCurrentUser($post->userId)
    ]);
}


$layout = $blade->getTemplate('layout', ['user' => $user, 'content' => $template]);
echo $layout;
