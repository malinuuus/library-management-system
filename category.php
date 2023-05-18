<?php
session_start();

// if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}

require_once "classes/User.php";
$user = new User($_SESSION["user_id"]);

// if user doesn't have admin rights
if (!$user->isAdmin) {
    header("location: index.php?page=categories");
    exit();
}

if (isset($_POST["category_id"])) {
    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT * FROM categories WHERE id = ?", [$_POST["category_id"]]);
    $category = $result->fetch_assoc();
    $updatingMode = true;
} else {
    $updatingMode = false;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/addform.css">
    <title>Add a category</title>
</head>
<body>
<div class="wrapper">
    <?php
    if ($updatingMode) {
        echo <<< UPDATEFORM
            <h3>Update a category</h3>
            <form action='scripts/updatecategory.php' method='post'>
                <div>
                    <input type='text' name='category' placeholder='Category name' value="$category[category]">
                </div>
                <input type="hidden" name="category_id" value="$category[id]">
                <button type='submit'>Update</button>
                <a href="index.php?page=categories">Cancel</a>
            </form>
        UPDATEFORM;
    } else {
        echo <<< ADDFORM
            <h3>Add a category to the database</h3>
            <form action='scripts/addcategory.php' method='post'>
                <div>
                    <input type='text' name='category' placeholder='Category name'>
                </div>
                <button type='submit'>Add</button>
                <a href="index.php?page=categories">Cancel</a>
            </form>
        ADDFORM;
    }
    ?>
</div>
<?php
require_once "notificationmodal.php";
?>
<script src="js/closeModal.js"></script>
</body>
</html>