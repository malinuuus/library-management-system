const searchBar = document.getElementById('search-bar');
const booksArray = document.querySelectorAll('.book-row');
const filterColumns = ['title', 'author', 'category'];

searchBar.addEventListener('keyup', () => {
    booksArray.forEach(book => {
        book.style.display = "none";
        const tdArray = book.getElementsByTagName('td');

        for (let i = 0; i < tdArray.length; i++) {
            if (
                filterColumns.some(column => tdArray[i].className.includes(column)) &&
                tdArray[i].textContent.toLowerCase().includes(searchBar.value.toLowerCase())
            ) {
                book.style.display = "";
                break;
            }
        }
    })
})