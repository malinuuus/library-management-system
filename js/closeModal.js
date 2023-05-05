const modal = document.querySelector('.modal');
const closeBtn = document.querySelector('.close');

setTimeout(() => {
    modal.style.display = "none";
}, 5000);

closeBtn.addEventListener('click', () => {
    modal.style.display = "none";
})