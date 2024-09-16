<div class="modal modal--adding modal--active">
    <div class="modal__wrapper">
        <button class="modal__close-button button" type="button">
            <svg class="modal__close-icon" width="18" height="18">
                <use xlink:href="#icon-close"></use>
            </svg>
            <span class="visually-hidden">Закрыть модальное окно</span></button>
        <div class="modal__content">
            <h1 class="modal__title"><?=$title ?? '' ?></h1>
            <p class="modal__desc">
                <?=$content ?? ''?>
            </p>
            <div class="modal__buttons">
                <a class="modal__button button button--main" href="#">Понятно</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.querySelector('.modal');
        const closeButton = document.querySelector('.modal__close-button');
        const modalButton = document.querySelector('.modal__button');

        const closeModal = () => {
            modal.classList.remove('modal--active');
        };

        closeButton.addEventListener('click', closeModal);
        modalButton.addEventListener('click', closeModal);
    });
</script>