<h3>Books</h3>
<label class="search-bar">
    <span>🔍</span>
    <input type="text">
</label>
<table class="books-table">
    <tr>
        <th>title</th>
        <th>author</th>
        <th>category</th>
        <th>No of copies</th>
    </tr>
<?php
require_once "scripts/Database.php";
$db = new Database("library_db");
$query = "SELECT b.title, a.first_name, a.last_name, c.category FROM books b
          INNER JOIN authors a on b.author_id = a.id
          INNER JOIN categories c on b.category_id = c.id";
$result = $db->getResult($query);

while ($book = $result->fetch_assoc()) {
    echo <<< BOOKROW
        <tr>
            <td>$book[title]</td>
            <td>$book[first_name] $book[last_name]</td>
            <td>$book[category]</td>
            <td>-</td>
            <td><button>Reserve</button></td>
        </tr>
    BOOKROW;
}

$db->close();
?>
</table>
