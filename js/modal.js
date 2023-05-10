const deleteModal = document.querySelector('.delete-modal');
deleteModal.classList.add('hide');

const deleteBtn = document.getElementById('delete-btn');
const cancelBtn = document.querySelector('.modal-cancel');

deleteBtn.addEventListener('click', () => {
    deleteModal.classList.remove('hide');
})

cancelBtn.addEventListener('click', () => {
    deleteModal.classList.add('hide');
})