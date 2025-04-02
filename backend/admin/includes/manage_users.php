<?php
// Enable debugging for development
//define('DEBUG_MODE', true);

require_once __DIR__.'/../admin_auth.php';
require_once __DIR__.'/../../config/database.php';

// Process user update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $userId = $_POST['user_id'] ?? 0;
    $column = $_POST['column'] ?? '';
    $value = $_POST['value'] ?? 0;

    if (empty($userId) || empty($column)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }

    // Validate column name to prevent SQL injection
    $allowedColumns = ['balance', 'active_deposit', 'profit', 'bonus'];
    if (!in_array($column, $allowedColumns)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid column name']);
        exit;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // First check if record exists
        $checkStmt = $conn->prepare("SELECT id FROM user_balance WHERE user_id = ?");
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE user_balance SET $column = ? WHERE user_id = ?");
            $stmt->bind_param("di", $value, $userId);
        } else {
            // Insert new record with defaults
            $insertData = [
                'balance' => 0,
                'active_deposit' => 0,
                'profit' => 0,
                'bonus' => 0
            ];
            $insertData[$column] = $value;

            $stmt = $conn->prepare("INSERT INTO user_balance (user_id, balance, active_deposit, profit, bonus) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idddd", $userId, $insertData['balance'], $insertData['active_deposit'], $insertData['profit'], $insertData['bonus']);
        }

        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully', 'value' => number_format($value, 2)]);
        } else {
            throw new Exception("Error executing query");
        }

        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Error updating data: ' . $e->getMessage()]);
        exit;
    }
}

// Check if user_balance table exists, create if not
try {
    // First verify database connection is working
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? 'Connection not established'));
    }

    // Try a simple query to ensure database connection is functional
    $testQuery = $conn->query("SELECT 1");
    if (!$testQuery) {
        throw new Exception("Database connection test failed: " . $conn->error);
    }

    $checkTable = $conn->query("SHOW TABLES LIKE 'user_balance'");
    if (!$checkTable) {
        throw new Exception("Error checking for user_balance table: " . $conn->error);
    }

    if ($checkTable->num_rows === 0) {
        $createTable = "CREATE TABLE user_balance (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            balance DECIMAL(15,2) DEFAULT 0,
            active_deposit DECIMAL(15,2) DEFAULT 0,
            profit DECIMAL(15,2) DEFAULT 0,
            bonus DECIMAL(15,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user (user_id)
        )";

        if (!$conn->query($createTable)) {
            throw new Exception("Error creating user_balance table: " . $conn->error);
        } else {
            error_log("Created user_balance table successfully");
        }
    }

    // Check if investments table exists, create if not
    $checkInvestmentsTable = $conn->query("SHOW TABLES LIKE 'investments'");
    if (!$checkInvestmentsTable) {
        throw new Exception("Error checking for investments table: " . $conn->error);
    }

    if ($checkInvestmentsTable->num_rows === 0) {
        $createInvestmentsTable = "CREATE TABLE investments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            amount DECIMAL(15,2) DEFAULT 0,
            plan VARCHAR(100) NOT NULL,
            status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
            profit_rate DECIMAL(5,2) DEFAULT 0,
            duration INT DEFAULT 0,
            returns DECIMAL(15,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            KEY idx_user_id (user_id)
        )";

        if (!$conn->query($createInvestmentsTable)) {
            throw new Exception("Error creating investments table: " . $conn->error);
        } else {
            error_log("Created investments table successfully");
        }
    }

    // Verify the users table exists
    $checkUsersTable = $conn->query("SHOW TABLES LIKE 'users'");
    if (!$checkUsersTable || $checkUsersTable->num_rows === 0) {
        throw new Exception("Error: The 'users' table does not exist. This is required for the user management page to function.");
    }

}
catch (Exception $e) {
    // Log error - this is critical and should be addressed
    error_log("Database table error: " . $e->getMessage());
    $error = "Database setup error: " . (DEBUG_MODE ? $e->getMessage() : "Please contact the administrator.");
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['entries']) ? max(1, (int)$_GET['entries']) : 10;
$offset = ($page - 1) * $perPage;

// Search functionality
$search = $_GET['search'] ?? '';
$searchCondition = '';
if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $searchCondition = " WHERE 
        u.first_name LIKE '%$safeSearch%' OR 
        u.last_name LIKE '%$safeSearch%' OR 
        u.email LIKE '%$safeSearch%' OR 
        u.username LIKE '%$safeSearch%'";
}

// Get users with balance data and investment information
try {
    // Verify database connection again
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection lost: " . ($conn->connect_error ?? 'Connection not established'));
    }

    // First check if the users table exists and has any data
    $checkUsersTable = $conn->query("SELECT COUNT(*) as count FROM users");
    if (!$checkUsersTable) {
        throw new Exception("Error accessing users table: " . $conn->error);
    }

    $userCount = $checkUsersTable->fetch_assoc()['count'];
//    die("user count: ". $userCount);
    if ($userCount === 0) {
        // No users, but not an error
        $result = null;
        $totalUsers = 0;
        $totalPages = 0;
    } else {
        // Get total user count for pagination
        $totalQuery = "SELECT COUNT(*) as total FROM users u" . $searchCondition;
        $totalResult = $conn->query($totalQuery);

        if (!$totalResult) {
            throw new Exception("Error executing total users query: " . $conn->error);
        }

        $totalUsers = $totalResult->fetch_assoc()['total'];

        $totalPages = ceil($totalUsers / $perPage);

        // Check if investment table exists
        $checkInvestmentTable = $conn->query("SHOW TABLES LIKE 'investments'");
        $investmentsTableExists = ($checkInvestmentTable && $checkInvestmentTable->num_rows > 0);

        // Check if user_balance table exists
        $checkBalanceTable = $conn->query("SHOW TABLES LIKE 'user_balance'");
        $balanceTableExists = ($checkBalanceTable && $checkBalanceTable->num_rows > 0);

        // Base query parts
        $selectBase = "SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.phone, u.country, u.state, u.referral_id, u.created_at";
        $fromBase = " FROM users u";
        $joinBalance = $balanceTableExists ?
            " LEFT JOIN user_balance ub ON u.id = ub.user_id" : "";
        $balanceFields = $balanceTableExists ?
            ", COALESCE(ub.balance, 0) as balance, COALESCE(ub.active_deposit, 0) as active_deposit, COALESCE(ub.profit, 0) as profit, COALESCE(ub.bonus, 0) as bonus" :
            ", 0 as balance, 0 as active_deposit, 0 as profit, 0 as bonus";

        // Build query based on available tables
        if ($investmentsTableExists) {
            // Check if there are any investments in the table
            $checkInvestmentsData = $conn->query("SELECT COUNT(*) as count FROM investments");
            if (!$checkInvestmentsData) {
                throw new Exception("Error checking investments data: " . $conn->error);
            }

            $hasInvestmentsData = ($checkInvestmentsData->fetch_assoc()['count'] > 0);

            if ($hasInvestmentsData) {
                // Full query with investments data
                $query = $selectBase . $balanceFields .
                    ", inv.amount as investment_amount, inv.plan as investment_plan, inv.status as investment_status, inv.created_at as investment_date" .
                    $fromBase . $joinBalance .
                    " LEFT JOIN (
                           SELECT i1.*
                           FROM investments i1
                           INNER JOIN (
                               SELECT user_id, MAX(id) as max_id 
                               FROM investments 
                               GROUP BY user_id
                           ) i2 ON i1.id = i2.max_id
                       ) inv ON u.id = inv.user_id" .
                    $searchCondition .
                    " ORDER BY u.id ASC" .
                    " LIMIT $offset, $perPage";
            } else {
                // No investments data, so use a simpler query
                $query = $selectBase . $balanceFields .
                    $fromBase . $joinBalance .
                    $searchCondition .
                    " ORDER BY u.id ASC" .
                    " LIMIT $offset, $perPage";
            }
        } else {
            // No investments table, use a basic query
            $query = $selectBase . $balanceFields .
                $fromBase . $joinBalance .
                $searchCondition .
                " ORDER BY u.id ASC" .
                " LIMIT $offset, $perPage";
        }

        error_log("Executing user query: " . str_replace("\n", " ", $query)); // Log the query for debugging
        $result = $conn->query($query);

        if (!$result) {
            throw new Exception("Error executing user query: " . $conn->error);
        }
    }
}
catch (Exception $e) {
    // Log error and set error message
    error_log("Database query error: " . $e->getMessage());
    $error = "An error occurred while retrieving user data. Please try again later.";
    $result = null;
    $totalUsers = 0;
    $totalPages = 0;
}

// Get investment data
$investments = [];

try {
    // This section is only used for legacy compatibility - we prefer the direct join in the main query
    if (isset($conn) && !$conn->connect_error) {
        // Check if investments table exists
        $checkInvestmentTable = $conn->query("SHOW TABLES LIKE 'investments'");

        if ($checkInvestmentTable && $checkInvestmentTable->num_rows > 0) {
            // Check table columns to ensure they match expected schema
            $checkColumns = $conn->query("SHOW COLUMNS FROM investments");
            $requiredColumns = ['id', 'user_id', 'amount', 'plan', 'status', 'created_at'];
            $foundColumns = [];

            if ($checkColumns && $checkColumns->num_rows > 0) {
                while ($column = $checkColumns->fetch_assoc()) {
                    $foundColumns[] = $column['Field'];
                }

                $missingColumns = array_diff($requiredColumns, $foundColumns);
                if (empty($missingColumns)) {
                    // All required columns exist, retrieve data
                    $investmentQuery = "SELECT user_id, MAX(created_at) as last_investment, status 
                                    FROM investments 
                                    GROUP BY user_id, status";
                    $investmentResult = $conn->query($investmentQuery);

                    if ($investmentResult && $investmentResult->num_rows > 0) {
                        while ($row = $investmentResult->fetch_assoc()) {
                            $investments[$row['user_id']] = $row;
                        }
                    }
                } else {
                    error_log("Investments table is missing required columns: " . implode(', ', $missingColumns));
                }
            }
        }
    }
}
catch (Exception $e) {
    // Log error but continue - this is not critical functionality
    error_log("Error fetching investment data: " . $e->getMessage());
}
?>