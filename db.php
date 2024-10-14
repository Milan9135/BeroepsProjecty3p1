<?php

class DB
{

    public $dbh;
    // verbinding met de database

    protected $stmt;
    // huidige statement

    public function __construct($db = "tandartsdb", $host = "localhost:3307", $user = "root", $pass = "")
    {
        try {
            $this->dbh = new PDO("mysql:host=$host; dbname=$db;", $user, $pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
    }

    public function execute($sql, $placeholders = null)
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($placeholders);
        return $stmt;
    }

    public function select($sql, $placeholders = null)
    {
        $stmt = $this->execute($sql, $placeholders);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($sql, $placeholders = null)
    {
        $stmt = $this->execute($sql, $placeholders);
        return $stmt->rowCount();
    }

    public function delete($sql, $placeholders = null)
    {
        $stmt = $this->execute($sql, $placeholders);
        return $stmt->rowCount();
    }
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
}
