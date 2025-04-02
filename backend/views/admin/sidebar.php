<div class="sidebar">
    <div class="logo p-3">
        <a href="dashboard.php">
            <img src="<?= ASSET_URL?>/logos/logo1.png" alt="AxisBot Logo" class="img-fluid">
        </a>
    </div>
    <ul class="nav-menu">
        <li><a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
        <li><a href="investments.php"><i class="fas fa-money-bill-wave"></i> Investments</a></li>
        <li><a href="transactions.php"><i class="fas fa-history"></i> Transactions</a></li>
        <li><a href="kyc_management.php"><i class="fas fa-id-card"></i> KYC Management</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="<?= BASE_URL?>/backend/views/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
    <div class="help-section">
        <h5>Need help?</h5>
        <p>Please check our docs</p>
        <button class="btn btn-light">DOCUMENTATION</button>
    </div>
</div> 