<?php
/**
 * Contact Controller
 * Handles contact form submissions
 */
class ContactController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    /**
     * Submit a new contact message
     * 
     * @param string $name Sender's name
     * @param string $email Sender's email
     * @param string $subject Message subject
     * @param string $message Message content
     * @return array Success status and message
     */
    public function submitMessage($name, $email, $subject, $message) {
        // Validate input
        if (empty($name) || empty($email) || empty($message)) {
            return ['success' => false, 'message' => 'Name, email and message are required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Insert message into database
        $stmt = $this->conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Message sent successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to send message'];
        }
    }
    
    /**
     * Get all contact messages (for admin panel)
     * 
     * @return array Messages or error
     */
    public function getAllMessages() {
        $result = $this->conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        
        if ($result) {
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
            return ['success' => true, 'data' => $messages];
        } else {
            return ['success' => false, 'message' => 'Failed to retrieve messages'];
        }
    }
    
    public function getMessages($status = null, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $whereClause = $status ? "WHERE status = ?" : "";
        
        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM contact_messages " . $whereClause;
        $stmt = $this->conn->prepare($countQuery);
        if ($status) {
            $stmt->bind_param("s", $status);
        }
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        
        // Get messages
        $query = "SELECT * FROM contact_messages " . $whereClause . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        if ($status) {
            $stmt->bind_param("sii", $status, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }
        $stmt->execute();
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return [
            'success' => true,
            'data' => [
                'messages' => $messages,
                'total' => $total,
                'pages' => ceil($total / $limit),
                'current_page' => $page
            ]
        ];
    }
    
    public function updateMessageStatus($messageId, $status) {
        if (!in_array($status, ['new', 'read', 'replied'])) {
            return ['success' => false, 'message' => 'Invalid status'];
        }
        
        $stmt = $this->conn->prepare("UPDATE contact_messages SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $messageId);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Status updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update status'];
        }
    }
}
?> 