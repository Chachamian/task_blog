<?php
$flash = $flash ?? new \App\FlashSystem();
?>
<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item <?php if ($sorting === 'repostCount'): ?> sorting__item--popular <?php endif;?>">
                        <a class="sorting__link <?php if ($sorting === 'repostCount'): ?> sorting__link--active <?php endif;?>"
                           href="<?=$flash->massCorrectCurrentLink([
                                   'sorting' => 'repostCount',
                               'ask' => $sorting === 'repostCount' && !$ask,
                           ])?>">
                            <span>Репосты</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item <?php if ($sorting === 'likesCount'): ?> sorting__item--popular <?php endif;?>">
                        <a class="sorting__link <?php if ($sorting === 'likesCount'): ?> sorting__link--active <?php endif;?>"
                           href="<?=$flash->massCorrectCurrentLink([
                               'sorting' => 'likesCount',
                               'ask' => $sorting === 'likesCount' && !$ask,
                           ])?>">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item <?php if ($sorting === 'dateTime'): ?> sorting__item--popular <?php endif;?>">
                        <a class="sorting__link <?php if ($sorting === 'dateTime'): ?> sorting__link--active <?php endif;?>"
                           href="<?=$flash->massCorrectCurrentLink([
                               'sorting' => 'dateTime',
                               'ask' => $sorting === 'dateTime' && !$ask,
                           ])?>">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item <?php if ($sorting === 'tags'): ?> sorting__item--popular <?php endif;?>">
                        <a class="sorting__link <?php if ($sorting === 'tags'): ?> sorting__link--active <?php endif;?>"
                           href="<?=$flash->massCorrectCurrentLink([
                               'sorting' => 'tags',
                               'ask' => $sorting === 'tags' && !$ask,
                           ])?>">
                            <span>Теги</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all <?php if($type=='all'):?> filters__button--active <?php endif;?>"
                           href="<?=$flash->correctCurrentLink('type', 'all')?>">
                            <span>Все</span>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--photo button <?php if($type=='0'):?> filters__button--active <?php endif;?>"
                           href="<?=$flash->correctCurrentLink('type', '0')?>">
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--text button <?php if($type=='1'):?> filters__button--active <?php endif;?>"
                           href="<?=$flash->correctCurrentLink('type', '1')?>">
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--quote button <?php if($type=='2'):?> filters__button--active <?php endif;?>"
                           href="<?=$flash->correctCurrentLink('type', '2')?>">
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--link button <?php if($type=='3'):?> filters__button--active <?php endif;?>"
                           href="<?=$flash->correctCurrentLink('type', '3')?>">
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php
            /** @var App\Mapper\Post[] $posts */
            $posts = $posts ?? [];
            ?>
            <?php foreach ($posts as $post) : ?>
                <?php if ($post->isHidden) : ?>
                    <article class="popular__post post post-photo">
                        <header class="post__header">
                            <h2><a href="/post-detail.php?post_id=<?=$post->id?>">Запросите доступ к контенту</a></h2>
                        </header>
                        <div class="post__main">
                            <div class="post-photo__image-wrapper">
                                <img src="/img/access.png" alt="Фото от пользователя" width="360" height="240">
                            </div>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="/profile.php?userId=<?=$post->userId?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="/img/userpic-larisa-small.jpg"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?=$post->getUserNameById($post->userId)?></b>
                                        <time class="post__time"><?=$post->timeAgo($post->dateTime)?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <form action="/add-like.php?postId=<?=$post->id?>" method="post">
                                        <button type="submit" class="post__indicator post__indicator--likes button" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                                 height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><span><?=$post->likesCount?></span></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </button>
                                    </form>

                                    <a class="post__indicator post__indicator--comments button" href="/post-detail.php?post_id=<?=$post->id?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?=$post->commentsCount?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
            <?php endif;?>
                <?php if ($post->postType == 0 && !$post->isHidden): ?>
                    <article class="popular__post post post-photo">
                        <header class="post__header">
                            <h2><a href="/post-detail.php?post_id=<?=$post->id?>"><?=$post->title?></a></h2>
                        </header>
                        <div class="post__main">
                            <div class="post-photo__image-wrapper">
                                <img src="<?=$post->picture?>" alt="Фото от пользователя" width="360" height="240">
                            </div>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="/profile.php?userId=<?=$post->userId?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="/img/userpic-larisa-small.jpg"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?=$post->getUserNameById($post->userId)?></b>
                                        <time class="post__time"><?=$post->timeAgo($post->dateTime)?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <form action="/add-like.php?postId=<?=$post->id?>" method="post">
                                        <button type="submit" class="post__indicator post__indicator--likes button" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                                 height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><span><?=$post->likesCount?></span></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </button>
                                    </form>

                                    <a class="post__indicator post__indicator--comments button" href="/post-detail.php?post_id=<?=$post->id?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?=$post->commentsCount?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endif; ?>

                <?php if ($post->postType == 1 && !$post->isHidden) : ?>
                    <article class="popular__post post post-text">
                <header class="post__header">
                    <h2><a href="/post-detail.php?post_id=<?=$post->id?>"><?=$post->title?></a></h2>
                </header>
                <div class="post__main">
                    <p>
                        <?=$post->content?>
                    </p>
                </div>
                <footer class="post__footer">
                    <div class="post__author">
                        <a class="post__author-link" href="/profile.php?userId=<?=$post->userId?>" title="Автор">
                            <div class="post__avatar-wrapper">
                                <img class="post__author-avatar" src="/img/userpic-larisa-small.jpg"
                                     alt="Аватар пользователя">
                            </div>
                            <div class="post__info">
                                <b class="post__author-name"><?=$post->getUserNameById($post->userId)?></b>
                                <time class="post__time"><?=$post->timeAgo($post->dateTime)?></time>
                            </div>
                        </a>
                    </div>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <form action="/add-like.php?postId=<?=$post->id?>" method="post">
                                <button type="submit" class="post__indicator post__indicator--likes button" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                         height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><span><?=$post->likesCount?></span></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </button>
                            </form>
                            <a class="post__indicator post__indicator--comments button" href="/post-detail.php?post_id=<?=$post->id?>" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?=$post->commentsCount?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
                <?php endif;?>

                <?php if ($post->postType == 2 && !$post->isHidden) : ?>
                    <article class="popular__post post post-quote">
                        <header class="post__header">
                            <h2><a href="/post-detail.php?post_id=<?=$post->id?>"><?=$post->title?></a></h2>
                        </header>
                        <div class="post__main">
                            <blockquote>
                                <p>
                                    <?=$post->quote?>
                                </p>
                                <cite><?=$post->author?></cite>
                            </blockquote>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="/profile.php?userId=<?=$post->userId?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="/img/userpic-larisa-small.jpg"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?=$post->getUserNameById($post->userId)?></b>
                                        <time class="post__time"><?=$post->timeAgo($post->dateTime)?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <form action="/add-like.php?postId=<?=$post->id?>" method="post">
                                        <button type="submit" class="post__indicator post__indicator--likes button" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                                 height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><span><?=$post->likesCount?></span></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </button>
                                    </form>
                                    <a class="post__indicator post__indicator--comments button" href="/post-detail.php?post_id=<?=$post->id?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?=$post->commentsCount?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endif; ?>

                <?php if ($post->postType == 3 && !$post->isHidden) : ?>
                    <article class="popular__post post post-link">
                        <header class="post__header">
                            <h2><a href="/post-detail.php?post_id=<?=$post->id?>"><?=$post->title?></a></h2>
                        </header>
                        <div class="post__main">
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="<?=$post->content?>" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post->content) ?>" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?=$flash->getTitleFromUrl($post->content)?></h3>
                                        </div>
                                    </div>
                                    <span><?=$post->content?></span>
                                </a>
                            </div>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="/profile.php?userId=<?=$post->userId?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="/img/userpic-larisa-small.jpg"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?=$post->getUserNameById($post->userId)?></b>
                                        <time class="post__time"><?=$post->timeAgo($post->dateTime)?></time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <form action="/add-like.php?postId=<?=$post->id?>" method="post">
                                        <button type="submit" class="post__indicator post__indicator--likes button" title="Лайк">
                                            <svg class="post__indicator-icon" width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                                 height="17">
                                                <use xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span><span><?=$post->likesCount?></span></span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </button>
                                    </form>
                                    <a class="post__indicator post__indicator--comments button" href="/post-detail.php?post_id=<?=$post->id?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?=$post->commentsCount?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endif;?>
            <?php endforeach; ?>
        </div>
        <div class="popular__page-links">
            <?php if ($page > 0) : ?>
            <a class="popular__page-link popular__page-link--prev button button--gray" href="<?=$flash->correctCurrentLink('page', $page - 1) ?>">Предыдущая страница</a>
            <?php endif;?>
            <?php if ($page < $lastPage): ?>
            <a class="popular__page-link popular__page-link--next button button--gray"
               href="<?=$flash->correctCurrentLink('page', $page + 1) ?>">Следующая страница</a>
            <?php endif;?>
        </div>
    </div>
</section>