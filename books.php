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
        <th></th>
        <th>title</th>
        <th>author</th>
        <th>category</th>
        <th>No. of available copies</th>
    </tr>
<?php
$query = "SELECT * FROM (SELECT b.id, b.title, b.cover_file_name, a.id as author_id, a.first_name, a.last_name, c.category, COUNT(CASE is_available WHEN 1 THEN 1 ELSE NULL END) as num_copies
          FROM books b
          LEFT JOIN authors a on b.author_id = a.id
          LEFT JOIN categories c on b.category_id = c.id
          LEFT JOIN copies c2 on b.id = c2.book_id
          GROUP BY b.id) AS t1 ORDER BY t1.title";
$result = $db->getResult($query);

while ($book = $result->fetch_assoc()) {
    $imagePath = "images/books/$book[cover_file_name]";

    if (!isset($book["cover_file_name"]) || !file_exists($imagePath)) {
        $imagePath = "images/blank.jpg";
    }

    echo <<< BOOKROW
        <tr>
            <td><img src=$imagePath alt="book cover" width="100"></td>
            <td>$book[title]</td>
            <td><a href="index.php?page=authors&id=$book[author_id]">$book[first_name] $book[last_name]</a></td>
            <td>$book[category]</td>
            <td>$book[num_copies]</td>
    BOOKROW;

    if ($user["is_admin"]) {
        echo "</tr>";
    } else if ($book["num_copies"] == 0) {
        echo "<td><button disabled>Reserve</button></td></tr>";
    } else {
        echo <<< RESERVEFORM
                <td>
                    <form action="scripts/reservebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book[id]">
                        <button type="submit">Reserve</button>
                    </form>
                </td>
            </tr>
        RESERVEFORM;
    }
}

$db->close();
?>
</table>
<?php
if (isset($_SESSION["err"])) {
    echo "<p>$_SESSION[err]</p>";
    unset($_SESSION["err"]);
}