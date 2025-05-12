const closeModalButtons = document.getElementsByClassName("form-modal-close");
const overlay = document.querySelector(".overlay");

const goHome = () => {
    location.href = '/';
}

[...closeModalButtons]
    .map(button => button.addEventListener('click', goHome));

overlay.addEventListener('click', goHome);