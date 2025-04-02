<?php
require_once 'includes/header.php';
require_once '../../controllers/SettingsController.php';

$settingsController = new SettingsController($conn);

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_general'])) {
        $result = $settingsController->updateGeneralSettings([
            'site_name' => $_POST['site_name'],
            'site_description' => $_POST['site_description'],
            'admin_email' => $_POST['admin_email'],
            'support_email' => $_POST['support_email']
        ]);
    } elseif (isset($_POST['update_withdrawal'])) {
        $result = $settingsController->updateWithdrawalSettings([
            'min_withdrawal' => $_POST['min_withdrawal'],
            'max_withdrawal' => $_POST['max_withdrawal'],
            'withdrawal_fee' => $_POST['withdrawal_fee'],
            'withdrawal_processing_time' => $_POST['withdrawal_processing_time']
        ]);
    } elseif (isset($_POST['update_deposit'])) {
        $result = $settingsController->updateDepositSettings([
            'min_deposit' => $_POST['min_deposit'],
            'max_deposit' => $_POST['max_deposit'],
            'deposit_fee' => $_POST['deposit_fee']
        ]);
    }

    if ($result['success']) {
        $_SESSION['success_message'] = "Settings updated successfully";
        header('Location: settings.php');
        exit();
    } else {
        $_SESSION['error_message'] = $result['message'];
    }
}

// Get current settings
$generalSettings = $settingsController->getGeneralSettings();
$withdrawalSettings = $settingsController->getWithdrawalSettings();
$depositSettings = $settingsController->getDepositSettings();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- General Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Site Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="text" class="form-control" name="site_name" value="<?php echo htmlspecialchars($generalSettings['site_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Admin Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="admin_email" value="<?php echo htmlspecialchars($generalSettings['admin_email']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Site Description</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <textarea class="form-control" name="site_description" rows="3"><?php echo htmlspecialchars($generalSettings['site_description']); ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Support Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-headset"></i></span>
                                <input type="email" class="form-control" name="support_email" value="<?php echo htmlspecialchars($generalSettings['support_email']); ?>" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" name="update_general" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save General Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Withdrawal Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Withdrawal Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Minimum Withdrawal Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input type="number" class="form-control" name="min_withdrawal" value="<?php echo htmlspecialchars($withdrawalSettings['min_withdrawal']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maximum Withdrawal Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input type="number" class="form-control" name="max_withdrawal" value="<?php echo htmlspecialchars($withdrawalSettings['max_withdrawal']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Withdrawal Fee (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                    <input type="number" step="0.01" class="form-control" name="withdrawal_fee" value="<?php echo htmlspecialchars($withdrawalSettings['withdrawal_fee']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Processing Time (hours)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                    <input type="number" class="form-control" name="withdrawal_processing_time" value="<?php echo htmlspecialchars($withdrawalSettings['withdrawal_processing_time']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" name="update_withdrawal" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Withdrawal Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Deposit Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Deposit Settings</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Minimum Deposit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input type="number" class="form-control" name="min_deposit" value="<?php echo htmlspecialchars($depositSettings['min_deposit']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maximum Deposit Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input type="number" class="form-control" name="max_deposit" value="<?php echo htmlspecialchars($depositSettings['max_deposit']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deposit Fee (%)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                <input type="number" step="0.01" class="form-control" name="deposit_fee" value="<?php echo htmlspecialchars($depositSettings['deposit_fee']); ?>" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" name="update_deposit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Deposit Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 