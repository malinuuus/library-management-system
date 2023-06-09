<?php
require_once "Database.php";
require_once "Person.php";
require_once "File.php";
require_once "Book.php";

class Author extends Person {
    public string $description;
    public File $image;
    private Database $db;

    public function __construct($id) {
        $this->db = new Database("library_db");
        $result = $this->db->getResult("SELECT * FROM authors WHERE id = ?", array($id));

        if ($result->num_rows === 1) {
            $author = $result->fetch_assoc();
            $this->id = $id;
            $this->firstName = $author["first_name"];
            $this->lastName = $author["last_name"];
            $this->description = $author["description"];
            $this->image = new File($author["image"], "images/blank_author.jpg");
        }
    }

    /**
     * @return Book[]
     */
    public function get_books(): array {
        $result = $this->db->getResult("SELECT b.id FROM books b INNER JOIN authors a ON b.author_id = a.id INNER JOIN categories c on b.category_id = c.id WHERE a.id = ?", array($this->id));
        $arr = array();

        while ($bookResult = $result->fetch_assoc()) {
            array_push($arr, new Book($bookResult["id"]));
        }

        return $arr;
    }

    public function get_short_bio(int $letters): string {
        return substr($this->description, 0, $letters);
    }

    public function delete(): bool {
        // add checking for books
        $this->db->getResult("DELETE FROM authors WHERE id = ?", array($this->id));
        return $this->db->checkAffectedRows(1);
    }

    public function update($firstName, $lastName, $description): bool {
        $this->db->getResult("UPDATE authors SET first_name = ?, last_name = ?, description = ? WHERE id = ?", array($firstName, $lastName, $description, $this->id));
        return $this->db->checkAffectedRows(1);
    }
}