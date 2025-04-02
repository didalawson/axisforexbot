<?php
require_once '../config/database.php';

try {
    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/update_tables.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            if (!$conn->query($statement)) {
                throw new Exception("Error executing SQL: " . $conn->error);
            }
        }
    }
    
    echo "Database updated successfully!\n";
    
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
    error_log("Database update error: " . $e->getMessage());
}

$conn->close(); 