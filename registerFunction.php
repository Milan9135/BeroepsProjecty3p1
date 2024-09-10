<?php

include '../homepage/db.php';
$myDb = new DB("Tandartsdb");
class user
{

    private $dbh;

    public function __construct(DB $dbh)
    {
        $this->dbh = $dbh;
    }
    public function insertUser($email, $wachtwoord)
    {
        return $this->dbh->execute("INSERT INTO users VALUES (null, ?, ?, null)", [$email, $wachtwoord]);
        
    }
}
