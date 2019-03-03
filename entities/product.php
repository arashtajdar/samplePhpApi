<?php

class Product
{

    // Specify the table name
    private $table = "PRODUCTS";

    // columns
    public $id;
    public $name;
    public $price;

    // Instance of connection
    private $conn;

    public function __construct($conn)
    {
        $this->connection = $conn;
    }

    // Insert (CREATE)
    public function create($name, $price)
    {
        $query = "INSERT INTO " . $this->table . " SET NAME= :name,PRICE=" . $price;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $result = $this->connection->prepare($query);
        $result->bindValue(':name', $name, PDO::PARAM_STR);
        if ($result->execute()) {
            $res = $this->connection->lastInsertId();
        } else {
            $res = $result->errorInfo();
        }

        return $res;
    }

    // Select (READ)
    public function read($limit_start, $length)
    {
        $query = "SELECT * FROM " . $this->table . " LIMIT " . $limit_start . "," . $length;

        $result = $this->connection->prepare($query);
        $result->execute();

        return $result;
    }

    // SEARCH
    public function search($limit_start, $length, $name, $price_range)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE NAME LIKE '%" . $name . "%' " . $price_range . " LIMIT " . $limit_start . "," . $length;

        $result = $this->connection->prepare($query);
        $result->execute();

        return $result;
    }

    // Update
    public function update($id, $name, $price)
    {
        $updateVal = false;
        if ($name) {
            if ($updateVal) {
                $updateVal .= ",";
            }
            $updateVal .= " NAME = :name";
        }
        if ($price) {
            if ($updateVal) {
                $updateVal .= ",";
            }
            $updateVal .= " PRICE = " . $price;
        }
        $query = "UPDATE " . $this->table . " SET " . $updateVal . " WHERE ID = " . $id;
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $result = $this->connection->prepare($query);
        $result->bindValue(':name', $name, PDO::PARAM_STR);
        $result->execute();
        // if ($result->execute()){ 
        //     // $res = $this->connection->lastInsertId();
        //     $res = $result;
        // }else{
        //     $res = $result->errorInfo();
        // }
        return $result;
    }

    // Delete
    public function remove($idToDelete)
    {
        $query = "DELETE FROM " . $this->table . " WHERE ID = " . $idToDelete;
        $result = $this->connection->prepare($query);
        $result->execute();
        return $result;
    }
}