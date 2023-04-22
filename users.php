<h3>Users list</h3>
<table class="users-list">
    <tr>
        <th>First name</th>
        <th>Last name</th>
        <th>Admin rights</th>
        <th>Number of borrowed books</th>
    </tr>

    <?php
    require_once "classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult("SELECT id, email, first_name, last_name, is_admin FROM users");

    while ($user = $result->fetch_assoc()) {
        $admin = $user["is_admin"] ? "admin" : "";
        $booksCountResult = $db->getResult("SELECT COUNT(r.id) AS count FROM users u JOIN reservations r on u.id = r.user_id WHERE u.id = $user[id]");
        $booksCount = $booksCountResult->fetch_assoc()["count"];

        echo <<< USER
            <tr class="user-info" onclick="window.location='index.php?page=userProfile&id=$user[id]'">
                <td>$user[first_name]</td>
                <td>$user[last_name]</td>
                <td>$admin</td>
                <td>$booksCount</td>
            </tr>
        USER;
    }

    $db->close();
    ?>
</table>