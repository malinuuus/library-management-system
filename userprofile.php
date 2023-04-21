<h3>Your profile</h3>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT first_name, last_name, email, is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
$user = $result->fetch_assoc();

?>
<div class="profile-info">
    <label for="first-name">First name</label>
    <input type="text" id="first-name" value="<?php echo $user["first_name"] ?>" disabled>

    <label for="last-name">Last name</label>
    <input type="text" id="last-name" value="<?php echo $user["last_name"] ?>" disabled>

    <label for="email">Email</label>
    <input type="text" id="email" value="<?php echo $user["email"] ?>" disabled>
</div>