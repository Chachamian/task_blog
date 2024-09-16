<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="/register.php" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-email" type="email" name="email" placeholder="Укажите эл.почту">
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-login" type="text" name="username" placeholder="Укажите логин">
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
                        <div class="form__input-section">
                            <input class="registration__input form__input" id="registration-password" type="password" name="password" placeholder="Придумайте пароль">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Заголовок сообщения</h3>
                                <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($errors)): ?>
                <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                        <?php foreach ($errors as $error) : ?>
                            <?php if (!empty($error)) : ?>
                                <li class="form__invalid-item"><?=$error?></li>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
