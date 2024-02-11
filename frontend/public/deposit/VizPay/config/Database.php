<?php
class Database
{
     // TODO: Change all DB params
     // DB params
     private $host = '';
     private $db_name = '';
     private $username = '';
     private $password = '';

     private $conn;

     public function __construct($dbHost, $dbName, $dbUser, $dbPassword)
     {
          $this->host = $dbHost;
          $this->db_name = $dbName;
          $this->username = $dbUser;
          $this->password = $dbPassword;
     }
     // DB connect
     public function connect()
     {
          $this->conn = null;
          try {
               $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
               $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          } catch (PDOException $e) {
               echo 'Connection Error: ' . $e->getMessage();
          }
          return $this->conn;
     }
}
