<?php

class Database {
    private $conn;
    private $statement;

    public function __construct($database, $hostname = "localhost", $username = "root", $password = "") {
        $this->conn = new mysqli($hostname, $username, $password, $database);
    }

    public function getResult($query, $parameters = array()) {
        $this->statement = $this->conn->prepare($query);

        if (sizeof($parameters) != 0) {
            $types = "";

            foreach ($parameters as $value) {
                switch (gettype($value)) {
                    case "boolean":
                    case "integer": $types .= "i"; break;
                    case "double": $types .= "d"; break;
                    case "string": $types .= "s"; break;
                    default: $types .= "b"; break;
                }
            }

            $this->statement->bind_param($types, ...$parameters);
        }

        $this->statement->execute();
        return $this->statement->get_result();
    }

    public function checkAffectedRows($checkValue): bool {
        return $this->conn->affected_rows === $checkValue;
    }

    public function close() {
        if (isset($this->statement)) {
            $this->statement->close();
        }
        $this->conn->close();
    }
}