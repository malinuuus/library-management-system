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

function createHiddenInput(name, input) {
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', name);
    hiddenInput.setAttribute('value', input.value);

    input.addEventListener('input', () => {
        hiddenInput.setAttribute('value', input.value);
    });

    updateForm.appendChild(hiddenInput);
}

let isClicked = false;
updateBtn.addEventListener('click', () => {
    if (!isClicked) {
        isClicked = true;
        updateBtn.innerText = "Update author";
        updateBtn.setAttribute('type', 'submit');
        authorBio.replaceWith(textArea);

        firstName.replaceWith(firstNameInput);
        lastName.replaceWith(lastNameInput);

        createHiddenInput('description', textArea);
        createHiddenInput('first_name', firstNameInput);
        createHiddenInput('last_name', lastNameInput);

        setTimeout(() => updateForm.onsubmit = () => {}, 1);
    }
});