<h3>Books</h3>
<div class="books-header">
    <div class="search-bar">
        <label class="search-bar-label" for="search-bar">🔍</label>
        <input type="text" id="search-bar" placeholder="Search...">
    </div>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
    $user = $result->fetch_assoc();

    if ($user["is_admin"]) {
        echo "<a href='book.php?mode=add'>Add a book</a>";
    }
    ?>
</div>
<table class="table books-table">
    <tr>
        <th></th>
        <th>title</th>
        <th>author</th>
        <th>category</th>
        <th>No. of available copies</th>
        <th></th>
    </tr>
<?php
$query = "SELECT * FROM (SELECT b.id, b.title, b.image, a.id as author_id, a.first_name, a.last_name, c.category, COUNT(CASE is_available WHEN 1 THEN 1 ELSE NULL END) as num_copies
          FROM books b
          LEFT JOIN authors a on b.author_id = a.id
          LEFT JOIN categories c on b.category_id = c.id
          LEFT JOIN copies c2 on b.id = c2.book_id
          GROUP BY b.id) AS t1 ORDER BY t1.title";
$result = $db->getResult($query);

while ($book = $result->fetch_assoc()) {
    $imageData = "data:image/jpeg;base64,".base64_encode($book["image"]);

    echo <<< BOOKROW
        <tr class="book-row">
            <td class="book-row-img"><img src=$imageData alt="book cover"></td>
            <td class="book-row-title">$book[title]</td>
            <td class="book-row-author"><a href="index.php?page=authors&id=$book[author_id]">$book[first_name] $book[last_name]</a></td>
            <td class="book-row-category">$book[category]</td>
            <td class="book-row-copies">$book[num_copies]</td>
    BOOKROW;

    if ($user["is_admin"]) {
        echo <<< DELETEFORM
                <td class="book-row-buttons">
                    <form action="scripts/updatebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book[id]">
                        <button type="submit" name="submit" value="1" class="btn update-btn">Update</button>
                    </form>
                    <form action="scripts/deletebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book[id]">
                        <button type="submit" class="btn delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        DELETEFORM;
    } else if ($book["num_copies"] == 0) {
        echo "<td><button disabled class='btn reserve-btn'>Reserve</button></td></tr>";
    } else {
        echo <<< RESERVEFORM
                <td>
                    <form action="scripts/reservebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book[id]">
                        <button type="submit" class="btn active reserve-btn">Reserve</button>
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
?>
<script src="js/filterData.js"></script>