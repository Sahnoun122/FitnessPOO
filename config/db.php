<?php
class DbConnection{
 
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'gym';
   
    protected $connection;
   
    public function __construct(){
        if (!isset($this->connection)) {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if (!$this->connection) {
                echo 'Cannot connect to database server';
                exit;
            }            
        }    
    }
   
    public function getConnection() {
        return $this->connection;
    }
}
?>

