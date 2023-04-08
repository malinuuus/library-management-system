<h3>Books</h3>
<div>
    <label class="search-bar">
        <span>🔍</span>
        <input type="text">
    </label>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
    $user = $result->fetch_assoc();

    if ($user["is_admin"]) {
        echo "<a href='./addbook.php'>Add a book</a>";
    }
    ?>
</div>
<table class="books-table">
    <tr>
        <th>title</th>
        <th>author</th>
        <th>category</th>
        <th>No of copies</th>
    </tr>
<?php
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
