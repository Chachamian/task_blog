<style>
    .custom-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 18px;  /* Размер текста */
    }

    .custom-checkbox input[type="checkbox"] {
        display: none;  /* Скрываем стандартный чекбокс */
    }

    .checkbox {
        width: 60px;  /* Ширина чекбокса */
        height: 60px; /* Высота чекбокса */
        border: 2px solid #d0d9f5;  /* Цвет рамки */
        margin-right: 10px;  /* Отступ между чекбоксом и текстом */
        border-radius: 10px;  /* Скругленные углы */
        position: relative;
        background-color: white;
        transition: background-color 0.3s;
    }

    .custom-checkbox input[type="checkbox"]:checked + .checkbox {
        /*background-color: #007BFF; !* Цвет фона при выборе *!*/
        border-color: #d0d9f5;
    }

    .custom-checkbox input[type="checkbox"]:checked + .checkbox::after {
        content: '';
        position: absolute;
        left: 15px;  /* Настроить для центрирования */
        top: 15px;   /* Настроить для центрирования */
        width: 30px; /* Ширина иконки */
        height: 30px; /* Высота иконки */
        background: url('/img/icon-link-arrow.svg') no-repeat center center;
        background-size: contain;
        transform: rotate(90deg); /* Поворачиваем стрелку вправо */
    }
</style>

<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <?php if (empty($post) || $type === 0): ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a href="/add-post.php" class="adding-post__tabs-link filters__button filters__button--photo
                                <?php if((int)$type === 0) {echo 'filters__button--active tabs__item--active';} ?>
                             tabs__item button">
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-photo"></use>
                                </svg>
                                <span>Фото</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if (empty($post) || $type === 1): ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a href="/add-post.php?type=1" class="adding-post__tabs-link filters__button filters__button--text
                            <?php if((int)$type === 1) {echo 'filters__button--active tabs__item--active';} ?>
                            tabs__item button">
                                <svg class="filters__icon" width="20" height="21">
                                    <use xlink:href="#icon-filter-text"></use>
                                </svg>
                                <span>Текст</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if (empty($post) || $type === 2): ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a href="/add-post.php?type=2" class="adding-post__tabs-link filters__button filters__button--quote
                             <?php if((int)$type === 2) {echo 'filters__button--active tabs__item--active';} ?>
                             tabs__item button">
                                <svg class="filters__icon" width="21" height="20">
                                    <use xlink:href="#icon-filter-quote"></use>
                                </svg>
                                <span>Цитата</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if (empty($post) || $type === 3): ?>
                        <li class="adding-post__tabs-item filters__item">
                            <a href="/add-post.php?type=3" class="adding-post__tabs-link filters__button filters__button--link
                             <?php if((int)$type === 3) {echo 'filters__button--active tabs__item--active';} ?>
                             tabs__item button">
                                <svg class="filters__icon" width="21" height="18">
                                    <use xlink:href="#icon-filter-link"></use>
                                </svg>
                                <span>Ссылка</span>
                            </a>
                        </li>
                        <?php endif;?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <?php if ((int)$type === 0) :?>
                    <section class="adding-post__photo tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Форма добавления фото</h2>
                        <form class="adding-post__form form" action="<?=(new \App\FlashSystem())->correctCurrentLink('type', '0')?>" method="post" enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-heading" value="<?=!empty($post) ? $post->title : ''?>" type="text" name="title" placeholder="Введите заголовок">
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" id="photo-url" type="text" name="link" placeholder="Введите ссылку">
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->tags : ''?>" class="adding-post__input form__input" id="photo-tags" type="text" name="tags" placeholder="Введите теги через запятую">
                                        </div>
                                    </div>
                                    <label class="custom-checkbox adding-post__label form__label" style="margin-left: 0;">
                                        <input type="checkbox" name="isHidden" value="1" <?=!empty($post) && $post->isHidden ? 'checked' : ''?>>
                                        <span class="checkbox"></span>
                                        Сделать пост скрытым
                                    </label>
                                </div>
                                <?php if(!empty($errors)):?>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <?php foreach ($errors as $error): ?>
                                        <li class="form__invalid-item"><?=$error?></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                        <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file" title=" ">
                                        <div class="form__file-zone-text">
                                            <span>Выберите фото</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews" id="preview-container">

                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                    <?php endif; ?>
                    <?php if ((int)$type === 1) :?>
                    <section class="adding-post__text tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Форма добавления текста</h2>
                        <form class="adding-post__form form" action="<?=(new \App\FlashSystem())->correctCurrentLink('type', '1')?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->title : ''?>" class="adding-post__input form__input" id="text-heading" type="text" name="title" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="content" placeholder="Введите текст публикации"><?=!empty($post) ? $post->content : ''?></textarea>
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="post-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->tags : ''?>" class="adding-post__input form__input" id="post-tags" type="text" name="tags" placeholder="Введите теги">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="custom-checkbox adding-post__label form__label" style="margin-left: 0;">
                                        <input type="checkbox" name="isHidden"  value="1" <?=!empty($post) && $post->isHidden ? 'checked' : ''?>>
                                        <span class="checkbox"></span>
                                        Сделать пост скрытым
                                    </label>
                                </div>
                                <?php if(!empty($errors)):?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $error): ?>
                                                <li class="form__invalid-item"><?=$error?></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                    <?php endif; ?>
                    <?php if ((int)$type === 2) :?>
                    <section class="adding-post__quote tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Форма добавления цитаты</h2>
                        <form class="adding-post__form form" action="<?=(new \App\FlashSystem())->correctCurrentLink('type', '2')?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-heading">Заголовок <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input class="adding-post__input form__input" value="<?=!empty($post) ? $post->title : ''?>" id="quote-heading" type="text" name="title" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                                        <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" name="quote" id="cite-text" placeholder="Текст цитаты"><?=!empty($post) ? trim($post->quote) : ''?></textarea>
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->author : ''?>" class="adding-post__input form__input" id="quote-author" type="text" name="author">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="cite-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->tags : ''?>" class="adding-post__input form__input" id="cite-tags" type="text" name="tags" placeholder="Введите теги">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="custom-checkbox adding-post__label form__label" style="margin-left: 0;">
                                        <input type="checkbox" name="isHidden"  value="1" <?=!empty($post) && $post->isHidden ? 'checked' : ''?>>
                                        <span class="checkbox"></span>
                                        Сделать пост скрытым
                                    </label>
                                </div>
                                <?php if(!empty($errors)):?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $error): ?>
                                                <li class="form__invalid-item"><?=$error?></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                    <?php endif; ?>
                    <?php if ((int)$type === 3) :?>
                    <section class="adding-post__link tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Форма добавления ссылки</h2>
                        <form class="adding-post__form form" action="<?=(new \App\FlashSystem())->correctCurrentLink('type', '3')?>" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->title : ''?>" class="adding-post__input form__input" id="link-heading" type="text" name="title" placeholder="Введите заголовок">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->content : ''?>" class="adding-post__input form__input" id="post-link" type="text" name="link">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="adding-post__input-wrapper form__input-wrapper">
                                        <label class="adding-post__label form__label" for="link-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input value="<?=!empty($post) ? $post->tags : ''?>" class="adding-post__input form__input" id="link-tags" type="text" name="tags" placeholder="Введите ссылку">
                                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="custom-checkbox adding-post__label form__label" style="margin-left: 0;">
                                        <input type="checkbox" name="isHidden" value="1" <?=!empty($post) && $post->isHidden ? 'checked' : ''?>>
                                        <span class="checkbox"></span>
                                        Сделать пост скрытым
                                    </label>
                                </div>
                                <?php if(!empty($errors)):?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $error): ?>
                                                <li class="form__invalid-item"><?=$error?></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="adding-post__buttons">
                                <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                                <a class="adding-post__close" href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('userpic-file-photo').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = ''; // Очистка контейнера перед загрузкой нового изображения

            const files = event.target.files; // Получаем загруженные файлы

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Проверяем, является ли файл изображением
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader(); // Создаем новый объект FileReader

                    reader.onload = function(e) {
                        // Создаем элемент img и добавляем его в контейнер
                        const img = document.createElement('img');
                        img.src = e.target.result; // Устанавливаем путь к изображению
                        img.style.maxWidth = '100%'; // Ограничиваем ширину изображения
                        img.style.height = 'auto'; // Автоматически устанавливаем высоту
                        previewContainer.appendChild(img); // Добавляем изображение в контейнер
                    };

                    reader.readAsDataURL(file); // Читаем файл как Data URL
                }
            }
        });
    });
</script>