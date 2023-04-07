<?php

class Database {
    private $conn;

    public function __construct($database, $hostname = "localhost", $username = "root", $password = "") {
        $this->conn = new mysqli($hostname, $username, $password, $database);
    }

    public function getResult($query) {
        return $this->conn->query($query);
    }

    public function close() {
        $this->conn->close();
    }
}