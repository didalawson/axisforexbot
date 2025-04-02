<?php
require_once __DIR__."/includes/dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?= require_once __DIR__."/includes/styles.php"; ?>

</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?= require_once __DIR__ . "/sidebar.php";  ?>

    <div class="main-content">
        <div class="header">
            <h4>Dashboard</h4>
            <div class="user-info">
                <img src="<?= ASSET_URL?>/assets/avatar-1.png" alt="User Avatar" style="width: 28px; height: 28px; border-radius: 50%; margin-right: 8px; object-fit: cover;">
                <span>Hi, <?php echo htmlspecialchars($username); ?></span>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Referrer ID Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="referrer-section">
                        <h6 class="mb-3">Referrer Id</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($referralLink); ?>" readonly>
                            <button class="btn copy-btn" onclick="copyReferralLink()">COPY</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="balance-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="balance-title">Balance</div>
                                <div class="balance-amount">$<?php echo number_format($balance, 2); ?></div>
                            </div>
                            <button class="btn btn-light" style="width: 40px; height: 40px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="balance-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="balance-title">Active Deposit</div>
                                <div class="balance-amount">$<?php echo number_format($activeDeposit, 2); ?></div>
                            </div>
                            <button class="btn btn-light" style="width: 40px; height: 40px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="balance-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="balance-title">Profit</div>
                                <div class="balance-amount">$<?php echo number_format($profit, 2); ?></div>
                            </div>
                            <button class="btn btn-light" style="width: 40px; height: 40px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="balance-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="balance-title">Referral Bonus</div>
                                <div class="balance-amount">$<?php echo number_format($referralBonus, 2); ?></div>
                            </div>
                            <button class="btn btn-light" style="width: 40px; height: 40px;">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Market Data Section -->
            <div class="row">
                <!-- TradingView BTC/USD Chart -->
                <div class="col-12 mb-4">
                    <div class="chart-container crypto-chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container h-100">
                            <div id="tradingview_btc" style="height: 100%;"></div>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>

                <!-- TradingView Forex Widget -->
                <div class="col-md-8 mb-4">
                    <div class="chart-container forex-chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container h-100">
                            <div id="tradingview_forex" style="height: 100%;"></div>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>

                <!-- TradingView Market Overview Widget -->
                <div class="col-md-4 mb-4">
                    <div class="chart-container market-chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container h-100">
                            <div id="tradingview_market" style="height: 100%;"></div>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                </div>
            </div>

            <!-- Make an Investment Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Make an Investment</h5>
                            <p class="card-text">Start investing with our proven trading strategies</p>
                            <a href="make_investment.php" class="btn btn-primary">MAKE INVESTMENT</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TradingView Widget Script -->
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        mobileMenuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuToggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });

        // Close sidebar on window resize if open
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });

        // Initialize BTC/USD Chart
        new TradingView.widget({
            "autosize": true,
            "symbol": "BITSTAMP:BTCUSD",
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "light",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_btc",
            "hide_side_toolbar": false,
            "studies": [
                "MASimple@tv-basicstudies",
                "RSI@tv-basicstudies",
                "MACD@tv-basicstudies"
            ],
            "height": "100%",
            "width": "100%"
        });

        // Initialize Forex Widget
        new TradingView.widget({
            "autosize": true,
            "symbol": "FX:EURUSD",
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "light",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_forex",
            "hide_side_toolbar": true,
            "studies": ["MASimple@tv-basicstudies"],
            "height": "100%",
            "width": "100%"
        });

        // Initialize Market Overview Widget
        new TradingView.widget({
            "autosize": true,
            "symbol": "NASDAQ:AAPL",
            "interval": "D",
            "timezone": "Etc/UTC",
            "theme": "light",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_market",
            "hide_side_toolbar": true,
            "watchlist": [
                "NASDAQ:AAPL",
                "NYSE:PFE",
                "NASDAQ:NVDA"
            ],
            "height": "100%",
            "width": "100%"
        });

        function copyReferralLink() {
            const referralInput = document.querySelector('input[readonly]');
            referralInput.select();
            document.execCommand('copy');
            alert('Referral link copied to clipboard!');
        }
    </script>
</body>
</html>