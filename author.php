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
    header("location: index.php?page=books");
    exit();
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
    <title>Add an author</title>
</head>
<body>
    <div class="wrapper">
        <h3>Add an author to the database</h3>
        <form action='scripts/addauthor.php' method='post' enctype='multipart/form-data'>
            <div>
                <input type='text' name='first_name' placeholder='First name'>
            </div>
            <div>
                <input type='text' name='last_name' placeholder='Last name'>
            </div>
            <div>
                <textarea name="description" placeholder="Description" class="textarea-bio" style="padding: 5px; font-size: 14px"></textarea>
            </div>
            <div>
                <label for="image">Image: </label>
                <input type="file" name="image" id="image" accept="image/*">
            </div>
            <button type='submit'>Add</button>
            <a href="index.php?page=authors">Cancel</a>
        </form>
    </div>
    <?php
    require_once "notificationmodal.php";
    ?>
    <script src="js/closeModal.js"></script>
</body>
</html>