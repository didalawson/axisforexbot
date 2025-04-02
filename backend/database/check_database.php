<?php
require_once '../config/database.php';

try {
    // Check if tables exist
    $tables = ['users', 'investments', 'transactions'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            echo "Table '$table' does not exist. Creating...\n";
            // Read and execute SQL file
            $sql = file_get_contents(__DIR__ . '/create_transactions_table.sql');
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    if (!$conn->query($statement)) {
                        throw new Exception("Error executing SQL: " . $conn->error);
                    }
                }
            }
        } else {
            echo "Table '$table' exists.\n";
        }
    }

    // Check columns in users table
    $columns = ['balance', 'active_deposit'];
    foreach ($columns as $column) {
        $result = $conn->query("SHOW COLUMNS FROM users LIKE '$column'");
        if ($result->num_rows == 0) {
            echo "Column '$column' does not exist in users table. Adding...\n";
            $sql = "ALTER TABLE users ADD COLUMN $column DECIMAL(20,2) DEFAULT 0.00";
            if (!$conn->query($sql)) {
                throw new Exception("Error adding column: " . $conn->error);
            }
        } else {
            echo "Column '$column' exists in users table.\n";
        }
    }

    // Check columns in investments table
    $columns = ['updated_at', 'rejection_reason', 'status'];
    foreach ($columns as $column) {
        $result = $conn->query("SHOW COLUMNS FROM investments LIKE '$column'");
        if ($result->num_rows == 0) {
            echo "Column '$column' does not exist in investments table. Adding...\n";
            $sql = "ALTER TABLE investments ADD COLUMN $column " . 
                   ($column === 'updated_at' ? "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP" : 
                   ($column === 'status' ? "VARCHAR(50) DEFAULT 'pending'" : "TEXT"));
            if (!$conn->query($sql)) {
                throw new Exception("Error adding column: " . $conn->error);
            }
        } else {
            echo "Column '$column' exists in investments table.\n";
        }
    }

    // Check if status column has correct values
    $result = $conn->query("SELECT DISTINCT status FROM investments");
    echo "Current status values in investments table:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['status'] . "\n";
    }

    echo "Database structure check completed successfully!\n";

} catch (Exception $e) {
    echo "Error checking database structure: " . $e->getMessage() . "\n";
    error_log("Database check error: " . $e->getMessage());
} 