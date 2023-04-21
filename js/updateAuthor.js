const updateBtn = document.getElementById("update-author-btn");
const authorBio = document.querySelector('.author-info').querySelector('p');
const updateForm = document.getElementById('update-form');

const firstName = document.getElementById('author-first-name');
const lastName = document.getElementById('author-last-name');

const textArea = document.createElement('textarea');
textArea.value = authorBio.innerText;

const firstNameInput = document.createElement('input');
firstNameInput.value = firstName.innerText;
const lastNameInput = document.createElement('input');
lastNameInput.value = lastName.innerText;

let isClicked = false;
updateBtn.addEventListener('click', () => {
    if (!isClicked) {
        isClicked = true;
        updateBtn.innerText = "Update author";
        updateBtn.setAttribute('type', 'submit');
        authorBio.replaceWith(textArea);

        firstName.replaceWith(firstNameInput);
        lastName.replaceWith(lastNameInput);

        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'description');

        const hiddenFirstNameInput = document.createElement('input');
        hiddenFirstNameInput.setAttribute('type', 'hidden');
        hiddenFirstNameInput.setAttribute('name', 'first_name');

        const hiddenLastNameInput = document.createElement('input');
        hiddenLastNameInput.setAttribute('type', 'hidden');
        hiddenLastNameInput.setAttribute('name', 'last_name');

        textArea.addEventListener('input', () => {
            hiddenInput.setAttribute('value', textArea.value);
        });

        firstNameInput.addEventListener('input', () => {
            hiddenFirstNameInput.setAttribute('value', firstNameInput.value);
        });

        lastNameInput.addEventListener('input', () => {
            hiddenLastNameInput.setAttribute('value', lastNameInput.value);
        });

        updateForm.appendChild(hiddenInput);
        updateForm.appendChild(hiddenFirstNameInput);
        updateForm.appendChild(hiddenLastNameInput);

        setTimeout(() => updateForm.onsubmit = () => {}, 1);
    }
});