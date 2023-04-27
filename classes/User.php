<?php
require_once "Person.php";
require_once "Database.php";

class User extends Person {
    public string $email;
    private string $hashedPassword;
    public bool $isAdmin;
    private Database $db;

    public function __construct($id) {
        $this->db = new Database("library_db");
        $result = $this->db->getResult("SELECT * FROM users WHERE id = ?", array($id));

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $this->id = $id;
            $this->email = $user["email"];
            $this->hashedPassword = $user["password"];
            $this->firstName = $user["first_name"];
            $this->lastName = $user["last_name"];
            $this->isAdmin = $user["is_admin"];
        }
    }

    public function login($password): bool {
        return password_verify($password, $this->hashedPassword);
    }

    public function update($firstName, $lastName, $email, $password, $isAdmin): bool {
        $this->db->getResult(
            "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, is_admin = ? WHERE id = ?",
            array($firstName, $lastName, $email, $password, $isAdmin, $this->id)
        );

        return $this->db->checkAffectedRows(1);
    }

    public function borrowed_books_count(): int {
        $booksCountResult = $this->db->getResult(
            "SELECT COUNT(r.id) AS count FROM users u INNER JOIN reservations r on u.id = r.user_id WHERE u.id = ? AND r.return_date IS NULL",
            array($this->id)
        );
        return $booksCountResult->fetch_assoc()["count"];
    }
}