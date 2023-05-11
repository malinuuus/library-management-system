const deleteModal = document.querySelector('.delete-modal');
const deleteButtons = document.querySelectorAll('.delete-btn');
const cancelButtons = document.querySelectorAll('.modal-cancel');

let currentModal;

deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        currentModal = btn.nextElementSibling;
        currentModal.classList.remove('hide');
    })
})

cancelButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        currentModal.classList.add('hide');
    })
})