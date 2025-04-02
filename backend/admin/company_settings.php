<?php
require_once 'admin_auth.php';
require_once '../config/database.php';

// Check if company_settings table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'company_settings'")->num_rows > 0;

// Create the table if it doesn't exist
if (!$tableExists) {
    $createTable = "CREATE TABLE IF NOT EXISTS company_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        setting_name VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($createTable);
    
    // Insert default values
    $defaultSettings = [
        ['company_name', 'AxisBot'],
        ['company_address', '123 Trading Street, Crypto City, 10001'],
        ['company_phone', '+1 (234) 567-8901'],
        ['company_email', 'support@axisbot.com'],
        ['support_email', 'help@axisbot.com'],
        ['company_website', 'https://axisbot.com']
    ];
    
    $stmt = $conn->prepare("INSERT INTO company_settings (setting_name, setting_value) VALUES (?, ?)");
    foreach ($defaultSettings as $setting) {
        $stmt->bind_param("ss", $setting[0], $setting[1]);
        $stmt->execute();
    }
}

// Handle form submission
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Company Information
    $companyName = $_POST['company_name'] ?? '';
    $companyAddress = $_POST['company_address'] ?? '';
    $companyPhone = $_POST['company_phone'] ?? '';
    $companyEmail = $_POST['company_email'] ?? '';
    $supportEmail = $_POST['support_email'] ?? '';
    $companyWebsite = $_POST['company_website'] ?? '';
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Update company settings
        $stmt = $conn->prepare("UPDATE company_settings SET setting_value = ? WHERE setting_name = ?");
        
        $settings = [
            ['company_name', $companyName],
            ['company_address', $companyAddress],
            ['company_phone', $companyPhone],
            ['company_email', $companyEmail],
            ['support_email', $supportEmail],
            ['company_website', $companyWebsite]
        ];
        
        foreach ($settings as $setting) {
            $stmt->bind_param("ss", $setting[1], $setting[0]);
            $stmt->execute();
        }
        
        // Commit transaction
        $conn->commit();
        $success = "Company settings updated successfully!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error = "An error occurred: " . $e->getMessage();
    }
}

// Fetch current settings
$settings = [];
$result = $conn->query("SELECT * FROM company_settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_name']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Settings - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!--    <style>-->
<!--    </style>-->
    <?php require_once __DIR__."/includes/styles.php"?>

</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <?php require_once __DIR__."/includes/admin_sidebar.php" ?>


        <!-- Main Content -->
        <div class="main-content">
            <?php
            $title = "Company Settings";
            require_once __DIR__."/includes/header.php"; ?>
            <div class="content-container">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <h2><i class="fas fa-building me-2"></i> Company Information</h2>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" value="<?php echo htmlspecialchars($settings['company_name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Company Website</label>
                                <input type="text" class="form-control" name="company_website" value="<?php echo htmlspecialchars($settings['company_website'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Company Address</label>
                        <textarea class="form-control" name="company_address" rows="3"><?php echo htmlspecialchars($settings['company_address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Company Email</label>
                                <input type="email" class="form-control" name="company_email" value="<?php echo htmlspecialchars($settings['company_email'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Support Email</label>
                        <input type="email" class="form-control" name="support_email" value="<?php echo htmlspecialchars($settings['support_email'] ?? ''); ?>">
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="submit-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 