<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
$loggedUser = $result->fetch_assoc();

$result = $db->getResult("SELECT * FROM users WHERE id = ?", array($_GET["id"]));
$user = $result->fetch_assoc();

// deny access to other profiles for users without admin rights
if (empty($user) || ($_SESSION["user_id"] != $_GET["id"] && !$loggedUser["is_admin"])) {
    header("location: index.php");
    exit();
}

if ($_SESSION["user_id"] == $_GET["id"]) {
    echo "<h3>Your profile</h3>";
} else {
    echo "<h3>$user[first_name] $user[last_name]'s profile</h3>";
}
?>
<form action="scripts/updateuser.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $_GET["id"] ?>">
    <div class="profile-info">
        <div>
            <label for="first-name">First name:</label>
            <input type="text" name="first_name" id="first-name" value="<?php echo $user["first_name"] ?>" disabled>
        </div>

        <div>
            <label for="last-name">Last name:</label>
            <input type="text" name="last_name" id="last-name" value="<?php echo $user["last_name"] ?>" disabled>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $user["email"] ?>" disabled>
        </div>
        <?php
        if ($loggedUser["is_admin"]) {
            $isChecked = $user["is_admin"] ? "checked" : "";

            echo <<< PASSWORDINPUT
                <div>
                    <label for="password1">New password:</label>
                    <input type="password" name="new_password1" id="password1" disabled>
                </div>
                
                <div>
                    <label for="password2">Repeat new password:</label>
                    <input type="password" name="new_password2" id="password2" disabled>
                </div>

                <div>
                    <label for="admin-rights">Admin rights</label>
                    <input type="checkbox" name="admin_rights" id="admin-rights" $isChecked disabled>
                </div>
            PASSWORDINPUT;
        }
        ?>
    </div>
    <div class="profile-buttons">
        <button type="button" id="profile-edit-btn">Edit</button>
        <button type="submit" id="profile-save-btn" disabled>Save</button>
    </div>
</form>
<?php
if (isset($_SESSION["err"])) {
    echo $_SESSION["err"];
    unset($_SESSION["err"]);
}
?>
<script src="js/editUser.js"></script>