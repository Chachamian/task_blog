<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--likes">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="/img/userpic-medium.jpg" alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?=$user->userName?></span>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?=count($posts)?></span>
                        <span class="profile__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?=count($subscribes)?></span>
                        <span class="profile__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>
                <form action="/subscribe.php?ownerId=<?=$userId?>" method="post" class="post-details__user-buttons user__buttons">
                    <?php if ($user->id !== $userId) :?>
                        <button class="user__button user__button--subscription button button--main" type="submit">
                            <?php echo $isSubscribe ? "Отписаться" : "Подписаться"; ?>
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <?php if ($isCurrentUser): ?>
                        <li class="profile__tabs-item filters__item tabs__item">
                            <a href="<?=$flash->correctCurrentLink('type', 'requests')?>" class="profile__tabs-link filters__button button <?php if ($type === 'requests') : ?> filters__button--active <?php endif?>">Запросы</a>
                        </li>
                        <?php endif; ?>
                        <li class="profile__tabs-item filters__item tabs__item">
                            <a class="profile__tabs-link filters__button button <?php if ($type === 'subscribes') : ?> filters__button--active <?php endif?>" href="<?=$flash->correctCurrentLink('type', 'subscribes')?>">Подписки</a>
                        </li>
                    </ul>
                </div>
                <form class="profile__tab-content">
                    <?php if ($type === 'requests') : ?>
                    <section class="profile__likes tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Запросы</h2>
                        <ul class="profile__likes-list">
                            <?php foreach ($accessPosts as $accessPost): ?>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="/img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span><?=(new \App\Mapper\Post())->getUserNameById($accessPost->userId)?></span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Запросил доступ к публикации</span>
                                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20"><?=(new \App\Mapper\Post())->timeAgo($accessPost->dateTime)?></time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <form action="/access-post.php?access=<?=$accessPost->id?>" method="post">
                                        <button type="submit" class="post-mini__link button" title="Перейти на публикацию">
                                            <span class="post-mini__image-wrapper">
                                                <?php if ($accessPost->isAllowed): ?>
                                                    <img class="post-mini__image" src="/img/access.png" width="109" height="109" alt="Превью публикации">
                                                <?php else :?>
                                                    <img class="post-mini__image" src="/img/stop.png" width="109" height="109" alt="Превью публикации">
                                                <?php endif;?>
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </section>
                    <?php endif;?>
                    <?php if ($type === 'subscribes') : ?>
                    <section class="profile__subscriptions tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Подписки</h2>
                        <ul class="profile__subscriptions-list">
                            <?php foreach ($subscribes as $subscribe) :?>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture" src="/img/userpic-petro.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name" href="#">
                                            <span><?=(new \App\Mapper\Post())->getUserNameById($subscribe->userId)?></span>
                                        </a>
                                        <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount"><?=count((new \App\PostSystem())->getPostsForCurrentUser($subscribe->userId))?></span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount"><?=count($authSystem->getSubscribesForUser($subscribe->userId))?></span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <form action="/subscribe.php?ownerId=<?=$subscribe->userId?>&userId=<?=$user->id?>" method="post" class="">
                                        <button class="post-mini__user-button user__button user__button--subscription button button--main" type="submit">
                                            <?=$authSystem->isSubscribeCurrentUser($subscribe->userId, $subscribe->ownerId) ? 'Отписаться' : 'Подписаться'?>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </section>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</main>
