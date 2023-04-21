const editBtn = document.getElementById('profile-edit-btn');
const saveBtn = document.getElementById('profile-save-btn');
const profileInputs = document.querySelectorAll('.profile-info input')
let isClicked = false;

editBtn.addEventListener('click', () => {
    if (isClicked) {
        editBtn.innerText = 'Edit';
        saveBtn.setAttribute('disabled', '');

        profileInputs.forEach(input => {
            input.setAttribute('disabled', '');
        })
    } else {
        editBtn.innerText = 'Cancel';
        saveBtn.removeAttribute('disabled');

        profileInputs.forEach(input => {
            input.removeAttribute('disabled');
        })
    }

    isClicked = !isClicked;
})