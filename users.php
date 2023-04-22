<h3>Users list</h3>
<table class="users-list">
    <?php
    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT id, email, first_name, last_name, is_admin FROM users");

    while ($user = $result->fetch_assoc()) {
        $admin = $user["is_admin"] ? "admin" : "";
        echo <<< USER
            <tr class="user-info">
                <td><a href="index.php?page=userProfile&id=$user[id]">$user[first_name] $user[last_name]</a></td>
                <td>$admin</td>
            </tr>
        USER;
    }

    $db->close();
    ?>
</table>