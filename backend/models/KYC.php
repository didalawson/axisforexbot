<?php
class KYC {
    private $conn;
    private $db;

    public function __construct() {
        // Try to get the global connection first
        global $conn;
        
        if ($conn) {
            // Admin dashboard connection
            $this->conn = $conn;
        } else {
            // User dashboard connection - use Database class
            require_once __DIR__ . '/../config/config.php';
            $this->db = new Database();
        }
    }

    public function submitKYC($data) {
        if ($this->conn) {
            // Using mysqli directly
            $sql = "INSERT INTO kyc_submissions (
                user_id, full_name, date_of_birth, address, phone_number,
                document_type, document_number, document_front, document_back,
                selfie, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("issssssssss", 
                    $data['user_id'],
                    $data['full_name'],
                    $data['date_of_birth'],
                    $data['address'],
                    $data['phone_number'],
                    $data['document_type'],
                    $data['document_number'],
                    $data['document_front'],
                    $data['document_back'],
                    $data['selfie'],
                    $data['status']
                );
                
                return $stmt->execute();
            }
            
            return false;
        } else {
            // Using Database class
            $sql = "INSERT INTO kyc_submissions (
                user_id, full_name, date_of_birth, address, phone_number,
                document_type, document_number, document_front, document_back,
                selfie, status
            ) VALUES (
                :user_id, :full_name, :date_of_birth, :address, :phone_number,
                :document_type, :document_number, :document_front, :document_back,
                :selfie, :status
            )";

            $params = [
                ':user_id' => $data['user_id'],
                ':full_name' => $data['full_name'],
                ':date_of_birth' => $data['date_of_birth'],
                ':address' => $data['address'],
                ':phone_number' => $data['phone_number'],
                ':document_type' => $data['document_type'],
                ':document_number' => $data['document_number'],
                ':document_front' => $data['document_front'],
                ':document_back' => $data['document_back'],
                ':selfie' => $data['selfie'],
                ':status' => $data['status']
            ];

            return $this->db->query($sql, $params);
        }
    }

    public function getKYCStatus($user_id) {
        if ($this->conn) {
            // Using mysqli directly
            $sql = "SELECT status, rejection_reason FROM kyc_submissions 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC LIMIT 1";
            
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result->fetch_assoc();
            }
            
            return null;
        } else {
            // Using Database class
            $sql = "SELECT status, rejection_reason FROM kyc_submissions 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC LIMIT 1";
            
            $result = $this->db->query($sql, [':user_id' => $user_id]);
            
            if ($result) {
                return [
                    'status' => $result['status'],
                    'rejection_reason' => $result['rejection_reason']
                ];
            }
            
            return null;
        }
    }

    public function getAllKYCSubmissions($status = null) {
        $sql = "SELECT k.*, u.username, u.email 
                FROM kyc_submissions k 
                JOIN users u ON k.user_id = u.id";
        
        if ($this->conn) {
            // Using mysqli directly
            if ($status) {
                $sql .= " WHERE k.status = ?";
                $sql .= " ORDER BY k.created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("s", $status);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    return $result->fetch_all(MYSQLI_ASSOC);
                }
            } else {
                $sql .= " ORDER BY k.created_at DESC";
                $result = $this->conn->query($sql);
                if ($result) {
                    return $result->fetch_all(MYSQLI_ASSOC);
                }
            }
            
            return [];
        } else {
            // Using Database class
            if ($status) {
                $sql .= " WHERE k.status = :status";
            }
            
            $sql .= " ORDER BY k.created_at DESC";
            
            $params = $status ? [':status' => $status] : [];
            
            return $this->db->queryAll($sql, $params);
        }
    }

    public function updateKYCStatus($kyc_id, $status, $rejection_reason = null) {
        if ($this->conn) {
            // Using mysqli directly
            $sql = "UPDATE kyc_submissions 
                    SET status = ?, 
                        rejection_reason = ?,
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssi", $status, $rejection_reason, $kyc_id);
                return $stmt->execute();
            }
            
            return false;
        } else {
            // Using Database class
            $sql = "UPDATE kyc_submissions 
                    SET status = :status, 
                        rejection_reason = :rejection_reason,
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = :id";
            
            $params = [
                ':id' => $kyc_id,
                ':status' => $status,
                ':rejection_reason' => $rejection_reason
            ];
            
            return $this->db->query($sql, $params);
        }
    }

    public function getKYCById($kyc_id) {
        $sql = "SELECT k.*, u.username, u.email 
                FROM kyc_submissions k 
                JOIN users u ON k.user_id = u.id 
                WHERE k.id = ?";
        
        if ($this->conn) {
            // Using mysqli directly
            $stmt = $this->conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $kyc_id);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result->fetch_assoc();
            }
            
            return null;
        } else {
            // Using Database class
            $sql = "SELECT k.*, u.username, u.email 
                    FROM kyc_submissions k 
                    JOIN users u ON k.user_id = u.id 
                    WHERE k.id = :id";
            
            return $this->db->query($sql, [':id' => $kyc_id]);
        }
    }
} 