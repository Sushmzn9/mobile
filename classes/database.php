<?php
$absPath = '../';
include $absPath . "config.php";


class Database
{

    public $result;
    public $prepareSql;
    public $connection;

    // Constructor to accept query and establish connection
    public function __construct()
    {
        $this->connection = $this->connect();
    }

    // Establish MySQL connection safely
    private function connect()
    {
        global $conn; // Using the mysqli object from config.php

        if (!$conn || $conn->connect_error) {
            throw new \Exception("Database connection failed: " . $conn->connect_error);
        }
        return $conn;
    }


    // Execute the query
    public function query($query)
    {
        $this->result = mysqli_query($this->connection, $query);

        if (!$this->result) {
            throw new Exception("Query failed: " . mysqli_error($this->connection));
        }

        return $this->result;
    }

    // Fetch all rows as an array
    public function getResult()
    {
        $data = [];

        if ($this->result) {
            while ($row = mysqli_fetch_assoc($this->result)) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
