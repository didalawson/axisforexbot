<div class="sidebar">
    <div class="logo p-3">
        <a href="dashboard.php">
            <img src="<?= ASSET_URL?>/logos/logo1.png" alt="AxisBot Logo" class="img-fluid">
        </a>
    </div>
    <ul class="nav-menu">
        <li><a href="dashboard.php" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="account_settings.php"><i class="fas fa-user-cog"></i> Account Settings</a></li>
        <li><a href="make_investment.php"><i class="fas fa-money-bill-wave"></i> Make an Investment</a></li>
        <li><a href="transactions.php"><i class="fas fa-history"></i> Transactions</a></li>
        <li><a href="withdraw_funds.php"><i class="fas fa-wallet"></i> Withdraw Funds</a></li>
        <li><a href="kyc.php"><i class="fas fa-id-card"></i> KYC Verification</a></li>
        <li><a href="<?= BASE_URL?>/backend/views/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
    <div class="help-section">
        <h5>Need help?</h5>
        <p>Please check our docs</p>
        <button class="btn btn-light">DOCUMENTATION</button>
    </div>
</div>