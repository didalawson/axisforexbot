<?php
// Don't start session here as it's already started in connect.php
// session_start();
// Don't require database.php as connection is passed in constructor
// require_once '../config/database.php';

class AuthController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function register($firstName, $lastName, $email, $password, $referralId = null) {
        // Validate input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt === false) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Generate verification token
        $verificationToken = md5(uniqid($email, true));
        $emailVerified = 0; // Default to not verified
        
        // Generate a unique referral ID for this user
        $uniqueReferralId = $this->generateUniqueReferralId($firstName, $lastName);
        
        // Check if the required columns exist first
        $columnsQuery = "SHOW COLUMNS FROM users LIKE 'verification_token'";
        $columnsResult = $this->conn->query($columnsQuery);
        $hasVerificationToken = ($columnsResult && $columnsResult->num_rows > 0);
        
        $columnsQuery = "SHOW COLUMNS FROM users LIKE 'email_verified'";
        $columnsResult = $this->conn->query($columnsQuery);
        $hasEmailVerified = ($columnsResult && $columnsResult->num_rows > 0);
        
        $columnsQuery = "SHOW COLUMNS FROM users LIKE 'referral_id'";
        $columnsResult = $this->conn->query($columnsQuery);
        $hasReferralId = ($columnsResult && $columnsResult->num_rows > 0);
        
        // Check if the my_referral_id column exists
        $columnsQuery = "SHOW COLUMNS FROM users LIKE 'my_referral_id'";
        $columnsResult = $this->conn->query($columnsQuery);
        $hasMyReferralId = ($columnsResult && $columnsResult->num_rows > 0);
        
        // Build the query dynamically based on available columns
        $sql = "INSERT INTO users (first_name, last_name, email, password";
        $paramTypes = "ssss"; // String, String, String, String
        $paramValues = [$firstName, $lastName, $email, $hashedPassword];
        
        if ($hasEmailVerified) {
            $sql .= ", email_verified";
            $paramTypes .= "i"; // Integer
            $paramValues[] = $emailVerified;
        }
        
        if ($hasVerificationToken) {
            $sql .= ", verification_token";
            $paramTypes .= "s"; // String
            $paramValues[] = $verificationToken;
        }
        
        if ($hasReferralId && $referralId) {
            $sql .= ", referral_id";
            $paramTypes .= "s"; // String
            $paramValues[] = $referralId;
        }
        
        if ($hasMyReferralId) {
            $sql .= ", my_referral_id";
            $paramTypes .= "s"; // String
            $paramValues[] = $uniqueReferralId;
        }
        
        $sql .= ") VALUES (?, ?, ?, ?";
        
        if ($hasEmailVerified) {
            $sql .= ", ?";
        }
        
        if ($hasVerificationToken) {
            $sql .= ", ?";
        }
        
        if ($hasReferralId && $referralId) {
            $sql .= ", ?";
        }
        
        if ($hasMyReferralId) {
            $sql .= ", ?";
        }
        
        $sql .= ")";
        
        // Prepare the statement with our dynamic query
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt === false) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }
        
        // Create the dynamic bind_param call
        $bindParams = [$paramTypes];
        foreach ($paramValues as $key => $value) {
            $bindParams[] = &$paramValues[$key];
        }
        
        call_user_func_array([$stmt, 'bind_param'], $bindParams);
        
        if ($stmt->execute()) {
            // Get the newly created user ID
            $userId = $this->conn->insert_id;
            
            // If my_referral_id column doesn't exist, we'll create a referral ID and try to update it
            if (!$hasMyReferralId) {
                // Try to add the column if it doesn't exist
                $this->conn->query("ALTER TABLE users ADD COLUMN my_referral_id VARCHAR(100) NULL");
                
                // Now try to update the user with the referral ID
                $updateStmt = $this->conn->prepare("UPDATE users SET my_referral_id = ? WHERE id = ?");
                if ($updateStmt) {
                    $updateStmt->bind_param("si", $uniqueReferralId, $userId);
                    $updateStmt->execute();
                }
            }
            
            // Return success with the verification token if available
            $result = [
                'success' => true, 
                'message' => 'Registration successful', 
                'role' => 'user', 
                'my_referral_id' => $uniqueReferralId
            ];
            
            if ($hasVerificationToken) {
                $result['verification_token'] = $verificationToken;
            }
            
            return $result;
        } else {
            return ['success' => false, 'message' => 'Registration failed: ' . $stmt->error];
        }
    }
    
    /**
     * Generate a unique referral ID for a user
     */
    private function generateUniqueReferralId($firstName, $lastName) {
        // Start with a base referral ID using name parts
        $firstPart = substr(strtolower($firstName), 0, 3);
        $lastPart = substr(strtolower($lastName), 0, 3);
        $baseId = $firstPart . $lastPart . rand(100, 999);
        
        // Check if this ID already exists
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE my_referral_id = ?");
        
        // If the statement failed (column might not exist), return a completely random ID
        if ($stmt === false) {
            return 'REF' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }
        
        $stmt->bind_param("s", $baseId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // If ID exists, generate a new one with different random numbers
        if ($result->num_rows > 0) {
            return $firstPart . $lastPart . rand(1000, 9999);
        }
        
        return $baseId;
    }
    
    public function login($email, $password) {
        // Validate input
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email and password are required'];
        }
        
        // Check if my_referral_id column exists
        $columnsQuery = "SHOW COLUMNS FROM users LIKE 'my_referral_id'";
        $columnsResult = $this->conn->query($columnsQuery);
        $hasMyReferralId = ($columnsResult && $columnsResult->num_rows > 0);
        
        // Build query based on available columns
        $sql = "SELECT id, first_name, last_name, email, password, role";
        if ($hasMyReferralId) {
            $sql .= ", my_referral_id";
        }
        $sql .= " FROM users WHERE email = ?";
        
        // Get user
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return ['success' => false, 'message' => 'Database error: ' . $this->conn->error];
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Store the user's referral ID in the session if available
                if ($hasMyReferralId && isset($user['my_referral_id']) && !empty($user['my_referral_id'])) {
                    $_SESSION['my_referral_id'] = $user['my_referral_id'];
                } else {
                    // Generate a referral ID on the fly if none exists
                    $referralId = $this->generateUniqueReferralId($user['first_name'], $user['last_name']);
                    $_SESSION['my_referral_id'] = $referralId;
                    
                    // Try to add the column if it doesn't exist
                    if (!$hasMyReferralId) {
                        $this->conn->query("ALTER TABLE users ADD COLUMN my_referral_id VARCHAR(100) NULL AFTER referral_id");
                    }
                    
                    // Update the user's record with this referral ID
                    $updateStmt = $this->conn->prepare("UPDATE users SET my_referral_id = ? WHERE id = ?");
                    if ($updateStmt) {
                        $updateStmt->bind_param("si", $referralId, $user['id']);
                        $updateStmt->execute();
                    }
                }
                
                return ['success' => true, 'message' => 'Login successful', 'role' => $user['role']];
            }
        }
        
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Logout successful'];
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
?> 