const searchBar = document.getElementById('search-bar');
let booksArray, authorsArray, usersArray;

function loadElements() {
    booksArray = document.querySelectorAll('.book-row');
    authorsArray = document.querySelectorAll('.author-info');
    usersArray = document.querySelectorAll('.user-info');
}

loadElements();

function filterRows(nodeList, tagName, filterColumns) {
    nodeList.forEach(item => {
        item.style.display = "none";
        const tdArray = item.getElementsByTagName(tagName);

        for (let i = 0; i < tdArray.length; i++) {
            if (
                filterColumns.some(column => tdArray[i].className.includes(column)) &&
                tdArray[i].textContent.toLowerCase().includes(searchBar.value.toLowerCase())
            ) {
                item.style.display = "";
                break;
            }
        }
    })
}

filterRows(booksArray, 'td', ['title', 'author', 'category']);

searchBar.addEventListener('keyup', () => {
    if (booksArray.length !== 0) {
        filterRows(booksArray, 'td', ['title', 'author', 'category']);
    } else if (authorsArray.length !== 0) {
        filterRows(authorsArray, 'h4', ['name']);
    } else if (usersArray.length !== 0) {
        filterRows(usersArray, 'td', ['first-name', 'last-name'])
    }
})