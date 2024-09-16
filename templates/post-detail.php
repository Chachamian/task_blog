<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication">
            <?php if ($post->isHidden): ?>
            Запросите доступ к публикации;
            <?php else:?>
            <?=$post->title?>
            <?php endif;?>
        </h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper post-photo post-text post-quote post-link">
                <div class="post-details__main-block post post--details">
                    <?php if ($post->isHidden): ?>
                        <a href="/get-access-post.php?postId=<?=$post->id?>" style="cursor: pointer">
                            <div class="post-details__image-wrapper post-photo__image-wrapper">
                                <img src="/img/access.png" alt="Фото от пользователя" width="760" height="507">
                            </div>
                        </a>

                    <?php endif;?>
                    <?php if ($post->postType === 0 && !$post->isHidden) :?>
                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <img src="<?=$post->picture?>" alt="Фото от пользователя" width="760" height="507">
                    </div>
                    <?php endif;?>
                    <?php if ($post->postType === 1 && !$post->isHidden) : ?>
                    <div class="post__main">
                        <p style="color: black">
                            <?=htmlspecialchars($post->content)?>
                        </p>
                    </div>
                    <?php endif;?>
                    <?php if ($post->postType === 2 && !$post->isHidden) : ?>
                        <div class="post__main">
                            <blockquote>
                                <p>
                                    <?=$post->quote?>
                                </p>
                                <cite><?=$post->author?></cite>
                            </blockquote>
                        </div>
                    <?php endif;?>
                    <?php if ($post->postType === 3 && !$post->isHidden) : ?>
                        <div class="post__main">
                            <div class="post-link__wrapper" style="border-top: none;">
                                <a class="post-link__external" href="<?= htmlspecialchars($post->content) ?>" title="Перейти по ссылке">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post->content) ?>" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?= htmlspecialchars($post->content) ?></h3>
                                    </div>
                                    <svg class="post-link__arrow" width="11" height="16">
                                        <use xlink:href="#icon-arrow-right-ad"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="/add-like.php?postId=<?=$post->id?>" title="Лайк">
                                <svg class="post__indicator-icon" width="20" height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?=$post->likesCount?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button" href="" title="Комментарии">
                                <svg class="post__indicator-icon" width="19" height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?=$post->commentsCount?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>
                            <form action="<?=$flash->correctCurrentLink('type', 'repost')?>" method="post">
                                <button type="submit" class="post__indicator post__indicator--repost button" title="Репост">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-repost"></use>
                                    </svg>
                                    <span><?=$post->repostCount?></span>
                                    <span class="visually-hidden">количество репостов</span>
                                </button>
                            </form>

                        </div>
                        <span class="post__view"><?=$post->viewsCount?> просмотров</span>
                    </div>
                    <ul class="post__tags">
                        <?php foreach($post->_prepareTags($post->tags) as $tag) : ?>
                        <li><a href="">#<?=$tag?></a></li>
                        <?php endforeach;?>
                    </ul>
                    <div class="comments">
                        <?php if (!$post->isHidden) : ?>
                        <form class="comments__form form" action="/add-comment.php?postId=<?=$post->id?>" method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture" src="/img/userpic-medium.jpg" alt="Аватар пользователя">
                            </div>
                            <div class="form__input-section <?php if(!empty($error)): ?> form__input-section--error <?php endif?>">
                                <textarea name="comment" class="comments__textarea form__textarea form__input"
                                          placeholder="Ваш комментарий"></textarea>
                                <label class="visually-hidden">Ваш комментарий</label>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc"><?=$error?></p>
                                </div>
                            </div>
                            <button class="comments__submit button button--green" type="submit">Отправить</button>
                        </form>
                        <?php endif;?>
                        <div class="comments__list-wrapper">
                            <?php if (!$post->isHidden) : ?>
                            <ul class="comments__list">
                                <?php foreach ($comments as $comment) :?>
                                <li class="comments__item user">
                                    <div class="comments__avatar">
                                        <a class="user__avatar-link" href="">
                                            <img class="comments__picture" src="/img/userpic-larisa.jpg" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="comments__info">
                                        <div class="comments__name-wrapper">
                                            <a class="comments__user-name" href="#">
                                                <span><?=$comment->author?></span>
                                            </a>
                                            <time class="comments__time" datetime="2019-03-20"><?=$post->timeAgo($comment->dateTime)?></time>
                                        </div>
                                        <p class="comments__text">
                                            <?=$comment->comment?>
                                        </p>
                                    </div>
                                </li>
                                <?php endforeach;?>
                            </ul>
                            <?php endif?>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link" href="#">
                                <img class="post-details__picture user__picture" src="/img/userpic-elvira.jpg" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name" href="#">
                                <span><?=$post->getUserNameById($post->userId)?></span>
                            </a>
                            <time class="post-details__time user__time" datetime="2014-03-20">
                                Опубликовано <?=$post->timeAgo($post->dateTime)?>
                            </time>
                        </div>
                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-details__rating-amount user__rating-amount"><?=count($subscribes)?></span>
                            <span class="post-details__rating-text user__rating-text">подписчиков</span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-details__rating-amount user__rating-amount"><?=count($posts)?></span>
                            <span class="post-details__rating-text user__rating-text">
                                <?=$post->prepareManyFormat(count($posts), 'Публикация', 'Публикации', 'Публикаций') ?>
                            </span>
                        </p>
                    </div>
                    <form action="/subscribe.php?ownerId=<?=$post->userId?>" method="post" class="post-details__user-buttons user__buttons">
                        <?php if ($user->id !== $post->userId) :?>
                        <button class="user__button user__button--subscription button button--main" type="submit">
                            <?php echo $isSubscribe ? "Отписаться" : "Подписаться"; ?>
                        </button>
                        <?php endif; ?>
                        <?php if ($user->id === $post->userId) :?>
                        <a class="user__button user__button--writing button button--green" href="/add-post.php?postId=<?=$post->id?>">Редактировать</a>
                        <a class="user__button user__button--writing button button--green" href="/post-detail.php?postId=<?=$post->id?>&action=delete">Удалить</a>
                        <?php endif;?>
                    </form>
                </div>
            </div>
        </section>
    </div>
</main>