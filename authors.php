<?php
require_once "classes/Database.php";
require_once "classes/Author.php";
require_once "classes/File.php";
$db = new Database("library_db");

if (isset($_GET["id"])) {
    $author = new Author($_GET["id"]);

    // checking if author with given id exists
    if (!isset($author->id)) {
        header("location: index.php?page=authors");
        exit();
    }

    $imagePath = $author->image->get_file();

    echo <<< AUTHORINFO
        <h3>Authors &nbsp > &nbsp
            <span id="author-first-name">$author->firstName</span>
            <span id="author-last-name">$author->lastName</span>
        </h3>
        <img class="author-image" src=$imagePath alt="author photo">
        <p class="author-bio">$author->description</p>
    AUTHORINFO;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $user = new User($_SESSION["user_id"]);

    if ($user->isAdmin) {
        echo <<< AUTHORINFO
            <div class="author-buttons">
                <form action="scripts/deleteauthor.php" method="post">
                    <input type="hidden" name="author_id" value="$author->id">
                    <button type="submit">Delete author</button>
                </form>
                <form action="scripts/updateauthor.php" method="post" id="update-form" onsubmit="return false">
                    <input type="hidden" name="author_id" value="$author->id">
                    <button type="button" id="update-author-btn">Edit author</button>
                </form>
            </div>
        AUTHORINFO;

        if (isset($_SESSION["err"])) {
            echo $_SESSION["err"];
            unset($_SESSION["err"]);
        }
    }

    echo "<h3>Books:</h3>";

    foreach ($author->get_books() as $book) {
        $imageData = $book->image->get_file();
        $category = $book->get_category();

        echo <<< BOOK
            <div class="book-info">
                <img src=$imageData alt="book cover">
                <div>
                    <p>$book->title</p>
                    <p class="category">$category</p>
                </div>
            </div>
        BOOK;
    }

    echo "<script src='js/updateAuthor.js'></script>";
} else {
    ?>
    <h3>Authors</h3>
    <div class="authors-header header">
        <div class="search-bar">
            <label for="search-bar">🔍</label>
            <input type="text" id="search-bar" placeholder="Search...">
        </div>
        <?php
        require_once "classes/User.php";
        $user = new User($_SESSION["user_id"]);

        if ($user->isAdmin) {
            echo "<a href='author.php'>Add an author</a>";
        }
        ?>
    </div>
    <?php
    $result = $db->getResult("SELECT id FROM authors ORDER BY last_name AND first_name");

    while ($authorResult = $result->fetch_assoc()) {
        $author = new Author($authorResult["id"]);
        $imagePath = $author->image->get_file();
        $description = $author->get_short_bio(300);

        echo <<< AUTHOR
            <div class="author-info">
                <img src=$imagePath alt="author photo">
                <a href="index.php?page=authors&id=$author->id">
                    <h4 class="author-name">$author->firstName $author->lastName</h4>
                    <p class="author-description">$description...</p>
                </a>
            </div>
        AUTHOR;
    }

    if (isset($_SESSION["err"])) {
        echo $_SESSION["err"];
        unset($_SESSION["err"]);
    }

    echo "<script src='js/filterData.js'></script>";
}