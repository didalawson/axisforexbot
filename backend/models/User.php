<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get user by ID
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }

    // Get user by username
    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        return $this->db->query($sql, [':username' => $username]);
    }

    // Get user by email
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->query($sql, [':email' => $email]);
    }

    // Register new user
    public function register($data) {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $data['password']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    // Login user
    public function login($username, $password) {
        $user = $this->getUserByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Update user profile
    public function updateProfile($id, $data) {
        $sql = "UPDATE users SET ";
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $sql .= "$key = :$key, ";
                $params[":$key"] = $value;
            }
        }
        
        $sql = rtrim($sql, ', ') . " WHERE id = :id";
        
        return $this->db->query($sql, $params);
    }

    // Get all users (for admin)
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        return $this->db->queryAll($sql);
    }
} 