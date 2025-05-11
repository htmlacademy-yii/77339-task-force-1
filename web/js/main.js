const addListeners = () => {
    const FILE_TYPES = ['gif', 'jpg', 'jpeg', 'png'];
    const overlay = document.querySelector('.overlay');
    const popup = document.querySelector('.pop-up');
    const imgPreviewElement = document.querySelector('.avatar-preview');

    const actionButtons = document.querySelectorAll('.action-btn');

    actionButtons.forEach(function (el) {
        el.addEventListener('click', function (evt) {
            const modalType = evt.target.dataset.action;
            const taskId = evt.target.dataset.taskId;
            const modal = document.querySelector('.pop-up--' + modalType);
            if (modal) {
                modal.classList.remove('pop-up--close');
                modal.classList.add('pop-up--open');
                overlay.classList.add('db');

                let form = modal.querySelector('form');
                if (form) {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();

                        let action = toKebabCase(modalType);

                        let formData = new FormData(form);
                        formData.append('taskId', taskId);

                        let data = Object.fromEntries(formData);
                        fetchAction(action, data);
                    });
                } else {
                    let submitButton = modal.querySelector('.button--submit');

                    submitButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        let data = {
                            taskId: taskId,
                        }

                        let action = toKebabCase(modalType);
                        fetchAction(action, data);
                    });
                }
            }
        })
    });

    const buttonsClose = document.querySelectorAll('.button--close');

    buttonsClose.forEach(function (el) {
        el.addEventListener('click', function (evt) {
            const modalOpen = document.querySelector('.pop-up--open');
            modalOpen.classList.remove('pop-up--open');
            modalOpen.classList.add('pop-up--close');
            overlay.classList.remove('db');
        })
    });

    let buttonInput = document.querySelector('#button-input');

    if (buttonInput) {
        buttonInput.addEventListener('change', function (evt) {
            const file = evt.target.files[0];
            const fileName = file.name.toLowerCase();

            const matches = FILE_TYPES.some(function (it) {
                return fileName.endsWith(it);
            });
            if (matches) {
                const reader = new FileReader();
                reader.addEventListener('load', function () {
                    imgPreviewElement.src = reader.result;
                });
                reader.readAsDataURL(file);
            }
        });
    }

    let starRating = document.querySelector(".active-stars");
    if (starRating) {
        executeRating(starRating);
    }

    let acceptReactionButtons = document.querySelectorAll('.reaction-accept');
    if (acceptReactionButtons) {
        [...acceptReactionButtons].map(button => button.addEventListener('click', (event) => {
            event.preventDefault();

            let data = {
                taskId: event.target.dataset.taskId,
                reactionId: event.target.dataset.reactionId,
                workerId: event.target.dataset.workerId
            }

            let action = toKebabCase(event.target.dataset.action);
            fetchAction(action, data);
        }));
    }

    let rejectReactionButtons = document.querySelectorAll('.reaction-reject');
    if (rejectReactionButtons) {
        [...rejectReactionButtons].map(button => button.addEventListener('click', (event) => {
            event.preventDefault();

            let data = {
                taskId: event.target.dataset.taskId,
                reactionId: event.target.dataset.reactionId,
            }

            let action = toKebabCase(event.target.dataset.action);
            fetchAction(action, data);
        }));
    }
}

function toKebabCase(str) {
    return str.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase();
}

function executeRating(starRating) {
    const stars = [...starRating.getElementsByClassName('stars-rating__star')];
    const ratingInput = starRating.querySelector('.stars-rating__value');

    const starClassInactive = 'stars-rating__star';
    const starClassActive = 'stars-rating__star stars-rating__star--fill';
    const starsLength = stars.length;
    let i;

    stars.map(star => {
        star.onclick = () => {
            i = stars.indexOf(star);

            if (star.className === starClassInactive) {
                for (i; i >= 0; --i) {
                    stars[i].className = starClassActive;
                }
            } else {
                for (i; i < starsLength; ++i) {
                    stars[i].className = starClassInactive;
                }
            }

            ratingInput.value = stars.filter(star => star.classList.contains('stars-rating__star--fill')).length;
        }
    })
}

function fetchAction(action, data) {
    fetch('/tasks/' + action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest', // Устанавливаем заголовок для AJAX-запроса
            'Content-Type': 'application/json', // Устанавливаем тип контента как JSON
            'X-CSRF-Token': yii.getCsrfToken(),
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновление данных на странице
                document.getElementById('data-container').outerHTML = data.data;
                addListeners();

                const openModals = document.querySelectorAll('.pop-up--open');
                if (openModals.length) {
                    [...openModals].map(modal => {
                        modal.classList.remove('pop-up--open');
                        modal.classList.add('pop-up--close');
                    });
                    document.querySelector('.overlay').classList.remove('db');
                }
            } else {
                alert('Произошла ошибка при обновлении данных.');
            }
        })
        .catch(error => {
            console.error('Произошла ошибка:', error);
        });
}

addListeners();