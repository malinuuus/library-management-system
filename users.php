<h3>Users list</h3>
<div class="users">
    <div>
        <label class="search-bar-label" for="search-bar">🔍</label>
        <input type="text" id="search-bar" placeholder="Search...">
    </div>
    <table class="users-table">
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
            $booksCountResult = $db->getResult("SELECT COUNT(r.id) AS count FROM users u INNER JOIN reservations r on u.id = r.user_id WHERE u.id = $user[id] AND r.return_date IS NULL");
            $booksCount = $booksCountResult->fetch_assoc()["count"];

            echo <<< USER
                <tr class="user-info" onclick="window.location='index.php?page=userProfile&id=$user[id]'">
                    <td class="user-info-first-name">$user[first_name]</td>
                    <td class="user-info-last-name">$user[last_name]</td>
                    <td class="user-info-admin">$admin</td>
                    <td class="user-info-books">$booksCount</td>
                </tr>
            USER;
        }

        $db->close();
        ?>
    </table>
</div>
<script src="js/filterData.js"></script>