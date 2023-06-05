const deleteModal = document.querySelector('.delete-modal');
let deleteButtons, cancelButtons;

addModalListeners();
let currentModal;

function addModalListeners() {
    deleteButtons = document.querySelectorAll('.delete-btn');
    cancelButtons = document.querySelectorAll('.modal-cancel');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentModal = btn.nextElementSibling;
            currentModal.classList.remove('hide');
        });
    });

    cancelButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentModal.classList.add('hide');
        });
    });
}

function removeModalListeners() {
    deleteButtons.forEach(btn => {
        let newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    cancelButtons.forEach(btn => {
        let newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });
}