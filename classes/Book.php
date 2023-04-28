<?php
require_once "Database.php";
require_once "Author.php";

class Book {
    public int $id;
    public string $title;
    public ?string $image = null;
    private Database $db;

    public function __construct($id) {
        $this->db = new Database("library_db");
        $result = $this->db->getResult("SELECT * FROM books WHERE id = ?", array($id));

        if ($result->num_rows === 1) {
            $book = $result->fetch_assoc();
            $this->id = $id;
            $this->title = $book["title"];

            if (isset($book["image"])) {
                $this->image = $book["image"];
            }
        }
    }

    public function delete(): bool {
        $this->db->getResult("DELETE FROM books WHERE id = ?", array($this->id));
        return $this->db->checkAffectedRows(1);
    }

    public function update($title, $authorId, $categoryId, $copiesCount, &$message): bool {
        $result = $this->db->getResult("SELECT COUNT(*) AS count FROM books b INNER JOIN copies c on b.id = c.book_id WHERE b.id = ?", array($this->id));
        $allCopiesCount = $result->fetch_assoc()["count"];
        $borrowedCopiesCount = $allCopiesCount - $this->get_available_copies_count();

        if ($copiesCount < $borrowedCopiesCount) {
            $message = "Number of copies must be equal or larger than the number of borrowed copies!";
            return false;
        } else if ($copiesCount < $allCopiesCount) {
            // delete available copies
            $this->db->getResult("DELETE FROM copies WHERE is_available = 1 AND book_id = ? LIMIT ?", array($this->id, $allCopiesCount - $copiesCount));

            if (!$this->db->checkAffectedRows($allCopiesCount - $copiesCount)) {
                $message = "Error occurred while deleting copies of the book!";
                return false;
            }
        } else if ($copiesCount > $allCopiesCount) {
            // add copies
            for ($i = 0; $i < $copiesCount - $allCopiesCount; $i++) {
                $this->db->getResult("INSERT INTO copies (book_id, is_available) VALUES (?, 1)", array($this->id));
            }

            if (!$this->db->checkAffectedRows($copiesCount - $allCopiesCount)) {
                $message = "Error occurred while adding copies of the book!";
                return false;
            }
        }

        $this->db->getResult(
            "UPDATE books SET title = ?, author_id = ?, category_id = ? WHERE id = ?",
            array($title, $authorId, $categoryId, $this->id)
        );

        return true;
    }

    public function get_author(): Author {
        $result = $this->db->getResult("SELECT a.id FROM authors a INNER JOIN books b on a.id = b.author_id WHERE b.id = ?", array($this->id));
        $author = $result->fetch_assoc();
        return new Author($author["id"]);
    }

    public function get_category(): string {
        $result = $this->db->getResult("SELECT c.category FROM categories c INNER JOIN books b on c.id = b.category_id WHERE b.id = ?", array($this->id));
        return $result->fetch_assoc()["category"];
    }

    public function get_available_copies_count(): int {
        $result = $this->db->getResult("SELECT COUNT(IF(is_available = 1, 1, NULL)) AS num_copies FROM books b INNER JOIN copies c ON b.id = c.book_id WHERE b.id = ?", array($this->id));
        return $result->fetch_assoc()["num_copies"];
    }
}