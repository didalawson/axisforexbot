<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change to your database username if different
define('DB_PASS', ''); // Change to your database password if any
define('DB_NAME', 'axisfore_db');

// URL Configuration
define('BASE_URL', 'http://localhost/public_html');
define('ASSET_URL', BASE_URL . '/assets');

// Application Configuration
define('APP_NAME', 'AxisforexBot');
define('APP_EMAIL', 'support@axisforex.com');

// Initialize Database Connection
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;
    
    public function __construct() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        
        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Connection Error: ' . $this->error;
        }
    }
    
    // Prepare statement with query
    public function query($sql, $params = []) {
        $this->stmt = $this->dbh->prepare($sql);
        
        // Bind values
        if(!empty($params)) {
            foreach($params as $param => $value) {
                $this->stmt->bindValue($param, $value);
            }
        }
        
        // Execute the statement
        try {
            $this->stmt->execute();
            return $this->stmt->fetch();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Query Error: ' . $this->error;
            return false;
        }
    }
    
    // Get all records as an array
    public function queryAll($sql, $params = []) {
        $this->stmt = $this->dbh->prepare($sql);
        
        // Bind values
        if(!empty($params)) {
            foreach($params as $param => $value) {
                $this->stmt->bindValue($param, $value);
            }
        }
        
        // Execute the statement
        try {
            $this->stmt->execute();
            return $this->stmt->fetchAll();
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo 'Query Error: ' . $this->error;
            return false;
        }
    }
    
    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Get last inserted ID
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
} 