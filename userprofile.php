<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "classes/User.php";
$loggedUser = new User($_SESSION["user_id"]);
$user = new User($_GET["id"]);

// deny access to other profiles for users without admin rights
if (empty($user) || ($loggedUser->id != $user->id && !$loggedUser->isAdmin)) {
    header("location: index.php");
    exit();
}

if ($loggedUser->id == $user->id) {
    echo "<h3>Your profile</h3>";
} else {
    echo "<h3>$user->firstName $user->lastName's profile</h3>";
}
?>
<form action="scripts/updateuser.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $user->id ?>">
    <div class="profile-info">
        <div>
            <label for="first-name">First name:</label>
            <input type="text" name="first_name" id="first-name" value="<?php echo $user->firstName ?>" disabled>
        </div>

        <div>
            <label for="last-name">Last name:</label>
            <input type="text" name="last_name" id="last-name" value="<?php echo $user->lastName ?>" disabled>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $user->email ?>" disabled>
        </div>
        <?php
        if ($loggedUser->isAdmin) {
            $isChecked = $user->isAdmin ? "checked" : "";

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
require_once "notificationmodal.php";
?>
<script src="js/editUser.js"></script>