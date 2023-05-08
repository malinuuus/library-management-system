const modal = document.querySelector('.modal');
const closeBtn = document.querySelector('.close');

setTimeout(() => {
    closeModal();
}, 5000);

closeBtn.addEventListener('click', () => {
    closeModal();
})

function closeModal() {
    modal.style.opacity = '0';

    setTimeout(() => {
        modal.remove();
    }, 500);
}