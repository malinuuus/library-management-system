<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    require_once "../classes/Database.php";
    require_once "../classes/Book.php";
    require_once "../classes/User.php";

    $user = new User($_SESSION["user_id"]);
    $db = new Database("library_db");

    $searchValue = '%'.$_POST["searchValue"].'%';

    $result = $db->getResult(
        "SELECT b.id FROM books b INNER JOIN authors a ON b.author_id = a.id INNER JOIN categories c ON b.category_id = c.id WHERE b.title LIKE ? OR CONCAT(a.first_name, ' ', a.last_name) LIKE ? OR c.category LIKE ? ORDER BY b.title LIMIT ? OFFSET ?",
        [$searchValue, $searchValue, $searchValue, $_POST["booksCount"], $_POST["booksOffset"]]
    );

    while ($row = $result->fetch_assoc()) {
        $book = new Book($row["id"]);
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
            require_once "../modal.php";
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
}