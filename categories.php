<h3>Categories</h3>
<p>Click to see books in this category</p>
<div class="categories">
    <?php
    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT category FROM categories ORDER BY category");

    while ($category = $result->fetch_assoc()) {
        $link = urlencode($category["category"]);

        echo <<< CATEGORY
            <a href="index.php?page=books&category=$link" class="category">
                <p>$category[category]</p>
            </a>
        CATEGORY;
    }
    ?>
</div>