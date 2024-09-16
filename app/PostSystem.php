<?php

namespace App;

use App\Exception\ValidateException;
use App\Mapper\AccessPost;
use App\Mapper\Comment;
use App\Mapper\Like;
use App\Mapper\Post;
use App\Mapper\User;
use App\Mapper\View;

class PostSystem
{
    private array $_errors;
    private FileSystem $_fileSystem;
    private Authorize $_authSystem;
    private FlashSystem $_flashSystem;

    public function __construct()
    {
        $this->_fileSystem  = new FileSystem();
        $this->_authSystem = new Authorize();
        $this->_errors     = [];
        $this->_flashSystem = new FlashSystem();
    }

    public function allowPost(User $user, $allowId): void
    {
        if ($allowId == null) {
            return;
        }

        $accessPosts = $this->_fileSystem->getAccessPostFileData();
        foreach ($accessPosts as $key => $accessPost) {
            if ($accessPost->id == $allowId && $user->id == $accessPost->ownerId) {
                $accessPosts[$key]->isAllowed = !$accessPost->isAllowed;
                $this->_fileSystem->writeAccessPostFileData($accessPosts, true);
                return;
            }
        }
    }

    public function addView(Post $postChange)
    {
        $posts = $this->_fileSystem->getPostFileData();
        $user  = $this->_authSystem->user();
        $views = $this->_fileSystem->getViewsFileData();

        foreach ($views as $view) {
            if ($view->postId === $postChange->id && $user->id === $view->userId) {
                return;
            }
        }

        foreach ($posts as $post) {
            if ($post->id === $postChange->id && $postChange->userId !== $user->id) {
                $post->viewsCount += 1;
            }
        }

        $this->_fileSystem->writePostFileData($posts, true);
        $views[] = new View(
            $this->_fileSystem->GetIncrement('views'),
            $user->id,
            $postChange->id,
        );
        $this->_fileSystem->writeViewsFileData($views);
    }

    public function requestAllowShowPost($postId): void
    {
        if ($postId === null) {
            return;
        }

        $post = $this->getPostById($postId);

        if ($post === null) {
            return;
        }
        $user         = $this->_authSystem->user();
        $allowedPosts = $this->_fileSystem->getAccessPostFileData();

        foreach ($allowedPosts as $allowedPost) {
            if ($allowedPost->postId === $post->id && $user->id === $allowedPost->userId) {
                return;
            }
        }

        $allowedPosts[] = new AccessPost(
            $this->_fileSystem->GetIncrement('access_posts'),
            $user->id,
            $post->id,
            $post->userId,
            false,
            time(),
        );

        $this->_fileSystem->writeAccessPostFileData($allowedPosts);
    }

    public function repostPost(Post $post)
    {
        $this->_errors = [];
        $tempId = $post->id;

        $user = $this->_authSystem->user();
        if ($post->userId === $user->id) {
            $this->_errors['user'] = 'Вы не можете репостить свой пост';
            return;
        }

        $posts = $this->_fileSystem->getPostFileData();

        foreach ($posts as $postData) {
            if ($postData->originalPostId === $post->id && $postData->userId === $user->id) {
                $this->_errors['user'] = 'Вы уже зарепостили пост';
                return;
            }
        }

        $post->id = $this->_fileSystem->GetIncrement('posts');
        $post->originalPostId = $tempId;
        $post->userId = $user->id;
        $post->viewsCount = 0;
        $post->dateTime = time();

        $posts[] = $post;
        $this->_fileSystem->writePostFileData($posts);
    }

    public function addComment($postId, $comment): void
    {
        if (empty($postId) || empty($comment)) {
            $this->_flashSystem->setMessage("Поле обязательно к заполнению");
            return;
        }

        $comments = $this->_fileSystem->getCommentsFileData();
        $user = $this->_authSystem->user();
        $comments[] = new Comment(
            $this->_fileSystem->GetIncrement('comments'),
            $user->id,
            $postId,
            $user->userName,
            time(),
            $comment
        );

        $this->_fileSystem->writeCommentsFileData($comments);
    }

    /**
     * @param User $user
     * @return User[]
     */
    public function getRequestPost(User $user): array
    {
        $accessPosts = $this->_fileSystem->getAccessPostFileData();
        $result = [];
        foreach ($accessPosts as $accessPost) {
            if ($accessPost->ownerId === $user->id) {
                $result[] = $accessPost;
            }
        }

        return $result;
    }

    public function getPostById($id): ?Post
    {
        if ($id === null) {
            return null;
        }

        $posts = $this->_fileSystem->getPostFileData();
        $posts = $this->_prepareRepostCount($posts);
        $user  = $this->_authSystem->user();

        foreach ($posts as $post) {
            if ($post->id === (int)$id) {
                $allowedPost = $this->_fileSystem->getAccessPostFileData();
                /** @var AccessPost[][] $allowedPost */
                $allowedPost = $this->_flashSystem->groupByKey($allowedPost, 'postId');

                $accessPosts = $allowedPost[$post->id] ?? null;

                if ($post->userId === $user->id) {
                    $post->isHidden = false;
                }

                if ($accessPosts != null && $post->isHidden) {
                    foreach ($accessPosts as $accessPost) {
                        if (($accessPost->userId === $user->id && $accessPost->isAllowed)) {
                            $post->isHidden = false;
                        }
                    }
                }

                $post = $this->_prepareLikesCount([$post], $this->_fileSystem->getLikesFileData())[0];
                $post = $this->_prepareCommentsCount([$post], $this->_fileSystem->getCommentsFileData());
                return $post[0];
            }
        }

        return null;
    }

    /**
     * @param $postId
     * @return Comment[]
     */
    public function getAllCommentByPostId($postId): array
    {
        $comments = $this->_fileSystem->getCommentsFileData();

        return array_filter($comments, function ($comment) use ($postId) {
            if ((int)$comment->post_id === (int)$postId) {
                return true;
            }
            return false;
        });
    }

    public function getPostWithPagination($page = 0, $sorting = null, $ask = false, $type = 'all')
    {
        $limit  = 6;
        $offset = $page * $limit;

        $posts    = $this->_fileSystem->getPostFileData();
        $likes    = $this->_fileSystem->getLikesFileData();
        $comments = $this->_fileSystem->getCommentsFileData();
        $allowedPost = $this->_fileSystem->getAccessPostFileData();
        /** @var AccessPost[][] $allowedPost */
        $allowedPost = $this->_flashSystem->groupByKey($allowedPost, 'postId');

        $posts    = $this->_prepareRepostCount($posts);
        $posts    = $this->_prepareLikesCount($posts, $likes);
        $posts    = $this->_prepareCommentsCount($posts, $comments);
        $user     = $this->_authSystem->user();

        $filteredPosts = [];
        foreach ($posts as $post) {
            $accessPosts = $allowedPost[$post->id] ?? null;

            if ($post->userId === $user->id) {
                $post->isHidden = false;
            }

            if ($accessPosts != null && $post->isHidden) {
                foreach ($accessPosts as $accessPost) {
                    if (($accessPost->userId === $user->id && $accessPost->isAllowed)) {
                        $post->isHidden = false;
                    }
                }
            }

            if ($type === 'all' || (int)$post->postType === (int)$type) {
                $filteredPosts[] = $post;
            }
        }

        if ($sorting === 'tags') {
            usort($filteredPosts, function($a, $b) use ($ask) {
                $tagsA = array_map('trim', explode(',', $a->tags));
                $tagsB = array_map('trim', explode(',', $b->tags));

                if (empty(implode(',', $tagsA)) && !empty(implode(',', $tagsB))) {
                    return 1;
                } elseif (!empty(implode(',', $tagsA)) && empty(implode(',', $tagsB))) {
                    return -1;
                }

                $comparison = strcmp(implode(',', $tagsA), implode(',', $tagsB));
                return $ask ? -$comparison : $comparison;
            });
        } elseif ($sorting) {
            usort($filteredPosts, function($object1, $object2) use ($sorting, $ask) {
                if ($object1->$sorting == $object2->$sorting) return 0;
                return $ask ? (($object1->$sorting > $object2->$sorting) ? 1 : -1)
                    : (($object1->$sorting < $object2->$sorting) ? 1 : -1);
            });
        }

        $result['lastPage'] = ceil(count($filteredPosts) / $limit) - 1;
        $result['posts']    =  array_slice($filteredPosts, $offset, $limit);

        return $result;
    }

    public function addLike(?int $postId)
    {
        if ($postId === null) {
            return;
        }

        $likes   = $this->_fileSystem->getLikesFileData();
        $user    = $this->_authSystem->user();
        $newLike = new Like(
            $this->_fileSystem->GetIncrement('likes'),
            $user->id,
            $postId
        );

        foreach ($likes as $key => $like) {
            if ($like->post_id === $postId && $like->user_id === $user->id) {
                unset($likes[$key]);
                $this->_fileSystem->writeLikesFileData(array_values($likes), true);
                return;
            }
        }

        $likes[] = $newLike;

        $this->_fileSystem->writeLikesFileData($likes);
    }

    public function editPost(array $data, $postId)
    {
        $postForSave = $this->tryInitializePost($data);
        if (!$postForSave) {
            return;
        }
        $posts = $this->_fileSystem->getPostFileData();
        $postForSave->id = $postId;

        foreach ($posts as $key => $postDB) {
            if ($postDB->id === $postId) {
                $posts[$key] = $postForSave;
            }
        }

        $this->_fileSystem->writePostFileData($posts, true);
    }

    public function addPost(array $data): void
    {
        $post = $this->tryInitializePost($data);
        if (!$post) {
            return;
        }

        $post->id = $this->_fileSystem->GetIncrement('posts');
        $posts    = $this->_fileSystem->getPostFileData();
        $posts[]  = $post;

        $this->_fileSystem->writePostFileData($posts);
    }

    /**
     * @param Post[] $posts
     * @return array
     */
    private function _prepareRepostCount(array $posts): array
    {
        $repostCountMap = [];

        foreach ($posts as $post) {
            if (property_exists($post, 'originalPostId')) {
                $originalPostId = $post->originalPostId;
                if (!isset($repostCountMap[$originalPostId])) {
                    $repostCountMap[$originalPostId] = 0;
                }
                $repostCountMap[$originalPostId]++;
            }
        }

        foreach ($posts as $post) {
            $post->repostCount = $repostCountMap[$post->id] ?? 0;
        }

        return $posts;
    }

    /**
     * @param Post[] $posts
     * @param Like[] $likes
     * @return array
     */
    private function _prepareLikesCount(array $posts, array $likes): array
    {
        $likesCountMap = [];
        foreach ($likes as $like) {
            if (isset($like->post_id)) {
                if (!isset($likesCountMap[$like->post_id])) {
                    $likesCountMap[$like->post_id] = 0;
                }
                $likesCountMap[$like->post_id]++;
            }
        }

        foreach ($posts as $post) {
            $post->likesCount = $likesCountMap[$post->id] ?? 0;
        }

        return $posts;
    }

    /**
     * @param Post[] $posts
     * @param Comment[] $comments
     * @return array
     */
    private function _prepareCommentsCount(array $posts, array $comments): array
    {
        $commentsCountMap = [];
        foreach ($comments as $comment) {
            if (isset($comment->post_id)) {
                if (!isset($commentsCountMap[$comment->post_id])) {
                    $commentsCountMap[$comment->post_id] = 0;
                }
                $commentsCountMap[$comment->post_id]++;
            }
        }

        foreach ($posts as $post) {
            $post->commentsCount = $commentsCountMap[$post->id] ?? 0;
        }

        return $posts;
    }

    private function tryInitializePost(array $data): bool|Post
    {
        $user  = $this->_authSystem->user();
        $link  = $data['link'] ?? null;
        $title = $data['title'] ?? null;
        $tags  = $data['tags'] ?? null;
        $isHidden = !empty($data['isHidden']) && $data['isHidden'] == 1;
        $content = $data['content'] ?? null;
        $author = $data['author'] ?? null;
        $quote = $data['quote'] ?? null;

        try {
            $type = $data['type'] ?? null;

            if ($type === null) {
                throw new ValidateException();
            }
            return match (intval($type)) {
                0 => $this->_initPhotoPost($title, $link, $user, $type, $tags, $isHidden),
                1 => $this->_initTextPost($title, $content, $user, $tags, $type, $isHidden),
                2 => $this->_initQuotePost($title, $author, $quote, $user, $type, $tags, $isHidden),
                3 => $this->_initLinkPost($title, $link, $user, $type, $tags, $isHidden),
                default => throw new ValidateException(),
            };

        } catch (ValidateException $e) {
            return false;
        }
    }

    private function _initLinkPost($title, $link, $user, $type, $tags, $isHidden): Post
    {
        if (empty($title)) {
            $this->_errors[] = 'Заголовок обязателен к заполнению';
            throw new ValidateException();
        }

        if (filter_var($link, FILTER_VALIDATE_URL) === FALSE) {
            $this->_errors[] = 'Ссылка не корректная';
            throw new ValidateException();
        }

        return new Post(
            $this->_fileSystem->GetIncrement('posts'),
            time(),
            $title,
            $link,
            null,
            null,
            null,
            0,
            $user->id,
            false,
            null,
            $isHidden,
            $type,
            $tags,
        );
    }

    private function _initQuotePost($title, $author, $quote, $user, $type, $tags, $isHidden): Post
    {
        if (empty($title)) {
            $this->_errors[] = 'Заголовок обязателен к заполнению';
            throw new ValidateException();
        }
        if (empty($quote)) {
            $this->_errors[] = 'Цитата поста обязателен к заполнению';
            throw new ValidateException();
        }
        if (empty($author)) {
            $this->_errors[] = 'Автор цитаты обязателен к заполнению';
            throw new ValidateException();
        }

        return new Post(
            $this->_fileSystem->GetIncrement('posts'),
            time(),
            $title,
            null,
            $quote,
            null,
            $author,
            0,
            $user->id,
            false,
            null,
            $isHidden,
            $type,
            $tags,
        );
    }

    private function _initPhotoPost($title, $link, $user, $type, $tags, $isHidden): Post
    {
        if (empty($title)) {
            $this->_errors[] = 'Заголовок обязателен к заполнению';
            throw new ValidateException();
        }

        $fileName = $this->_isImageUrl($link) ? $link : $this->_validatePhoto();

        return new Post(
            $this->_fileSystem->GetIncrement('posts'),
            time(),
            $title,
            null,
            null,
            $fileName,
            null,
            0,
            $user->id,
            false,
            null,
            $isHidden,
            $type,
            $tags,
        );
    }

    private function _initTextPost($title, $content, $user, $tags, $type, $isHidden): Post
    {
        if (empty($title)) {
            $this->_errors[] = 'Заголовок обязателен к заполнению';
            throw new ValidateException();
        }
        if (empty($content)) {
            $this->_errors[] = 'Текст поста обязателен к заполнению';
            throw new ValidateException();
        }

        return new Post(
            $this->_fileSystem->GetIncrement('posts'),
            time(),
            $title,
            $content,
            null,
            null,
            null,
            0,
            $user->id,
            false,
            null,
            $isHidden,
            $type,
            $tags,
        );
    }

    public function deletePost(Post $post)
    {
        $posts = $this->_fileSystem->getPostFileData();

        foreach ($posts as $key => $postData) {
            if ($post->id === $postData->id) {
                unset($posts[$key]);
            }
        }

        $this->_fileSystem->writePostFileData($posts, true);
        header('Location: /');
    }

    private function _validatePhoto(): string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 10 * 1024 * 1024;

        if (isset($_FILES['userpic-file']) && $_FILES['userpic-file']['error'] === 0) {
            if (!in_array($_FILES['userpic-file']['type'], $allowedTypes)) {
                $this->_errors[] = 'Неподдерживаемый тип файла. Допустимы только JPG, PNG и GIF.';
                throw new ValidateException();
            }

            if ($_FILES['userpic-file']['size'] > $maxFileSize) {
                $this->_errors[] = 'Размер файла превышает 10 МБ.';
                throw new ValidateException();
            }

            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            $file_name = uniqid() . '-' . basename($_FILES['userpic-file']['name']);
            move_uploaded_file($_FILES['userpic-file']['tmp_name'], $file_path . $file_name);
            return '/uploads/' . $file_name;
        } else {
            $this->_errors[] = 'Необходимо прикрепить картинку или ссылку на неё. Либо произошла ошибка';
            throw new ValidateException();
        }
    }

    private function _isImageUrl($url): bool {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }

        $imageInfo = @getimagesize($url);
        return $imageInfo !== false;
    }

    public function errors(): array
    {
        return $this->_errors;
    }

    /**
     * @param User|int $user
     * @return Post[]
     */
    public function getPostsForCurrentUser(User|int $user): array
    {
        if (is_int($user)) {
            $user = $this->_authSystem->getUserById($user);
        }
        $posts = $this->_fileSystem->getPostFileData();
        $result = [];
        foreach ($posts as $post) {
            if ($post->userId === $user->id) {
                $result[] = $post;
            }
        }

        return $result;
    }
}