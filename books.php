<h3>Books</h3>
<div class="books-header header">
    <div class="search-bar">
        <label class="search-bar-label" for="search-bar">🔍</label>
        <input type="text" id="search-bar" placeholder="Search...">
    </div>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "classes/User.php";
    $user = new User($_SESSION["user_id"]);

    if ($user->isAdmin) {
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
require_once "classes/Book.php";
require_once "classes/Database.php";
require_once "classes/File.php";

$db = new Database("library_db");
$result = $db->getResult("SELECT id FROM books ORDER BY title");

while ($bookResult = $result->fetch_assoc()) {
    $book = new Book($bookResult["id"]);
    $author = $book->get_author();
    $category = $book->get_category();
    $copiesCount = $book->get_available_copies_count();
    $imageData = $book->image->get_file();

    echo <<< BOOKROW
        <tr class="book-row">
            <td class="book-row-img"><img src=$imageData alt="book cover"></td>
            <td class="book-row-title">$book->title</td>
            <td class="book-row-author"><a href="index.php?page=authors&id=$author->id">$author->firstName $author->lastName</a></td>
            <td class="book-row-category">$category</td>
            <td class="book-row-copies">$copiesCount</td>
    BOOKROW;

    if ($user->isAdmin) {
        require_once "modal.php";
        echo <<< DELETEFORM
                <td class="book-row-buttons">
                    <form action="scripts/updatebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book->id">
                        <button type="submit" name="submit" value="1" class="btn update-btn">Update</button>
                    </form>
                    <button class="btn delete-btn">Delete</button>
        DELETEFORM;
        showModal("book", $book->id);
        echo "</td></tr>";
    } else if ($copiesCount == 0) {
        echo "<td><button disabled class='btn reserve-btn'>Reserve</button></td></tr>";
    } else {
        echo <<< RESERVEFORM
                <td>
                    <form action="scripts/reservebook.php" method="post">
                        <input type="hidden" name="book_id" value="$book->id">
                        <button type="submit" class="btn active reserve-btn">Reserve</button>
                    </form>
                </td>
            </tr>
        RESERVEFORM;
    }
}
?>
</table>
<?php
require_once "notificationmodal.php";
?>
<script src="js/filterData.js"></script>
<script src="js/modal.js"></script>