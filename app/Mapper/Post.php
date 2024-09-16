<?php

namespace App\Mapper;

use App\FileSystem;

class Post
{
    public ?int $id;
    public ?int $dateTime;
    public ?string $title;
    public ?string $content;
    public ?string $quote;
    public ?string $picture;
    public ?string $author;
    public ?int $viewsCount;
    public ?int $userId;
    public ?bool $isRepost;
    public ?int $originalPostId;
    public ?bool $isHidden;
    public ?int $postType;
    public ?string $tags;
    public ?int $repostCount = 0;
    public ?int $likesCount = 0;
    /**
     * @var int|mixed
     */
    public ?int $commentsCount = 0;

    public function __construct(
        ?int    $id = null,
        ?int    $dateTime = null,
        ?string $title = null,
        ?string $content = null,
        ?string $quote = null,
        ?string $picture = null,
        ?string $author = null,
        ?int    $viewsCount = 0,
        ?int    $userId = 0,
        ?bool   $isRepost = false,
        ?int    $originalPostId = null,
        ?bool   $isHidden = false,
        ?int    $postType = 0,
        ?string $tags = null,
    )
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->title = $title;
        $this->content = $content;
        $this->quote = $quote;
        $this->picture = $picture;
        $this->author = $author;
        $this->viewsCount = $viewsCount;
        $this->userId = $userId;
        $this->isRepost = $isRepost;
        $this->originalPostId = $originalPostId;
        $this->isHidden = $isHidden;
        $this->postType = $postType;
        $this->tags = $tags;
    }

    public function timeAgo($timestamp): string
    {
        $time = time() - $timestamp;

        if ($time < 60) {
            $seconds = $time;
            return $seconds . ' секунд' . $this->prepareManyFormat($seconds, 'у', 'ы', '') . ' назад';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . ' минут' . $this->prepareManyFormat($minutes, 'у', 'ы', '') . ' назад';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . ' час' . $this->prepareManyFormat($hours, '', 'а', 'ов') . ' назад';
        } elseif ($time < 2592000) { // 30 дней
            $days = floor($time / 86400);
            return $days . ' д' . $this->prepareManyFormat($days, 'ень ', 'ня ', 'ней') . ' назад';
        } elseif ($time < 31536000) { // 365 дней
            $months = floor($time / 2592000);
            return $months . ' месяц' . $this->prepareManyFormat($months, '', 'а', 'ев') . ' назад';
        } else {
            $years = floor($time / 31536000);
            return $years . $this->prepareManyFormat($years, 'год', 'года', 'лет') . ' назад';
        }
    }

    public function getUserNameById(?int $id): string
    {
        if ($id === null) {
            return 'Неизвестный автор';
        }

        $fileSystem = new FileSystem();
        $users = $fileSystem->getUsersFileData();


        foreach ($users as $user) {
            if ($user->id === $id) {
                return $user->userName;
            }
        }

        return 'Неизвестный автор';
    }

    public function _prepareTags(?string $tag): array
    {
        if (empty($tag)) {
            return [];
        }

        return explode(',', trim($tag));
    }

    public function prepareManyFormat(int $number, string $one, string $two, string $many): string
    {
        $number = (int)$number;
        $mod10 = $number % 10;

        return match (true) {
            $mod10 === 1 => $one,
            $mod10 >= 2 && $mod10 <= 4 => $two,
            default => $many,
        };
    }
}