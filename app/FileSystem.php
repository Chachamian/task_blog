<?php

namespace App;

use App\Mapper\AccessPost;
use App\Mapper\Book;
use App\Mapper\Comment;
use App\Mapper\Like;
use App\Mapper\Post;
use App\Mapper\Subscribe;
use App\Mapper\Transaction;
use App\Mapper\User;
use App\Mapper\View;

class FileSystem
{
    private const string APP_BD_PREFIX = '/bd/';

    /**
     * @return User[]
     */
    public function getUsersFileData(): array
    {
        $users =  $this->getInfoInDb('users') ?? [];

        return array_map(function($user) {
            return new User(
                $user->id,
                $user->userName,
                $user->email,
                $user->password,
                $user->role,
            );
        }, $users);
    }

    public function writeUsersFileData(array $data)
    {
        $this->writeInfoInDb('users', $data);
    }

    /**
     * @return Like[]
     */
    public function getLikesFileData(): array
    {
        $likes =  $this->getInfoInDb('likes') ?? [];

        return array_map(function($like) {
            return new Like(
                $like->id,
                $like->user_id,
                $like->post_id
            );
        }, $likes);
    }

    public function writeLikesFileData(array $data, $isUpdate = false)
    {
        $this->writeInfoInDb('likes', $data, $isUpdate);
    }

    /**
     * @return Comment[]
     */
    public function getCommentsFileData(): array
    {
        $comments =  $this->getInfoInDb('comments') ?? [];

        return array_map(function($comment) {
            return new Comment(
                $comment->id,
                $comment->user_id,
                $comment->post_id,
                $comment->author,
                $comment->dateTime,
                $comment->comment
            );
        }, $comments);
    }

    public function writeCommentsFileData(array $data, $isUpdate = false): void
    {
        $this->writeInfoInDb('comments', $data, $isUpdate);
    }

    /**
     * @return View[]
     */
    public function getViewsFileData(): array
    {
        $views =  $this->getInfoInDb('views') ?? [];

        return array_map(function($comment) {
            return new View(
                $comment->id,
                $comment->userId,
                $comment->postId,
            );
        }, $views);
    }

    public function writeViewsFileData(array $data, $isUpdate = false): void
    {
        $this->writeInfoInDb('views', $data, $isUpdate);
    }

    /**
     * @return AccessPost[]
     */
    public function getAccessPostFileData(): array
    {
        $accessPosts =  $this->getInfoInDb('access_posts') ?? [];

        return array_map(function($accessPost) {
            return new AccessPost(
                $accessPost->id,
                $accessPost->userId,
                $accessPost->postId,
                $accessPost->ownerId,
                $accessPost->isAllowed,
                $accessPost->dateTime,
            );
        }, $accessPosts);
    }

    public function writeAccessPostFileData(array $data, $isUpdate = false): void
    {
        $this->writeInfoInDb('access_posts', $data, $isUpdate);
    }

    public function writeSubscribesFileData(array $data, $isUpdate = false): void
    {
        $this->writeInfoInDb('subscribes', $data, $isUpdate);
    }

    /**
     * @return Subscribe[]
     */
    public function getSubscribesFileData(): array
    {
        $subscribes =  $this->getInfoInDb('subscribes') ?? [];

        return array_map(function($subscribe) {
            return new Subscribe(
                $subscribe->id,
                $subscribe->userId,
                $subscribe->ownerId,
            );
        }, $subscribes);
    }

    /**
     * @return Post[]
     * @throws \Exception
     */
    public function getPostFileData(): array
    {
        $posts = $this->getInfoInDb('posts') ?? [];

        return array_map(function($post) {
            return new Post(
                $post->id,
                $post->dateTime,
                $post->title,
                $post->content,
                $post->quote,
                $post->picture,
                $post->author,
                $post->viewsCount,
                $post->userId,
                $post->isRepost,
                $post->originalPostId,
                $post->isHidden,
                $post->postType,
                $post->tags,
            );
        }, $posts);
    }

    public function writePostFileData(array $data): void
    {
        $this->writeInfoInDb('posts', $data);
    }

    public function AddIncrement(string $table): void
    {
        $data = $this->getInfoInDb('increment', true);
        $increment = $data[$table]['value'] ?? 0;
        $data[$table]['value'] = $increment + 1;
        $this->writeInfoInDb('increment', $data);
    }

    public function GetIncrement(string $table)
    {
        $data = $this->getInfoInDb('increment', true);
        $increment = $data[$table]['value'] ?? 0;
        return $increment + 1;
    }

    public function getInfoInDb($fileName, $isArray = false)
    {
        return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . self::APP_BD_PREFIX . "{$fileName}.json"), $isArray);
    }

    public function writeInfoInDb($fileName, $data, $isUpdate = false): void
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . self::APP_BD_PREFIX . "{$fileName}.json", json_encode($data, JSON_UNESCAPED_UNICODE));
        if ($fileName !== 'increment' && !$isUpdate) {
            $this->AddIncrement($fileName);
        }
    }
}