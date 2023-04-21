const updateBtn = document.getElementById("update-author-btn");
const authorBio = document.querySelector('.author-info').querySelector('p');
const updateForm = document.getElementById('update-form');

const textArea = document.createElement('textarea');
textArea.innerText = authorBio.innerText;

let isClicked = false;
updateBtn.addEventListener('click', () => {
    if (!isClicked) {
        isClicked = true;
        updateBtn.innerText = "Update author";
        updateBtn.setAttribute('type', 'submit');
        authorBio.replaceWith(textArea);

        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'description');

        textArea.addEventListener('input', () => {
            hiddenInput.setAttribute('value', textArea.value);
        })

        updateForm.appendChild(hiddenInput);

        setTimeout(() => updateForm.onsubmit = () => {}, 1);
    }
})