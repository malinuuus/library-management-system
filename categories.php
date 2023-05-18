<h3>Categories</h3>
<div class="header">
    <span>Click to see books in this category</span>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "classes/User.php";
    $user = new User($_SESSION["user_id"]);

    if ($user->isAdmin) {
        echo "<a href='category.php'>Add a category</a>";
    }
    ?>
</div>
<div class="categories">
    <?php
    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT * FROM categories ORDER BY category");

    while ($category = $result->fetch_assoc()) {
        $link = urlencode($category["category"]);
        echo "<a href='index.php?page=books&category=$link' class='category'><span>$category[category]</span>";

        if ($user->isAdmin) {
            echo <<< DELETECATEGORY
                <form action="scripts/deletecategory.php" method="post" class="category-delete">
                    <input type="hidden" name="category_id" value="$category[id]">
                    <button type="submit">⛔</button>
                </form>
                <form action="category.php" method="post" class="category-update">
                    <input type="hidden" name="category_id" value="$category[id]">
                    <button type="submit">edit</button>
                </form>
            DELETECATEGORY;
        }

        echo "</a>";
    }
    ?>
</div>
<?php
require_once "notificationmodal.php";
?>
<script src="js/closeModal.js"></script>
