<style>
    :root {
        --sidebar-width: 250px;
        --header-height: 60px;
        --primary-color: #3a7bd5;
        --secondary-color: #6c7ae0;
    }
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: white;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        z-index: 1000;
        padding: 20px 0;
        overflow-y: auto;
    }
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 20px;
    }
    .header {
        height: var(--header-height);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        background: white;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    .logo {
        padding: 0 20px;
        margin-bottom: 20px;
    }
    .logo img {
        max-width: 150px;
        height: auto;
    }
    .nav-menu {
        list-style: none;
        padding: 0;
        margin: 20px 0;
    }
    .nav-menu li a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #666;
        text-decoration: none;
        transition: all 0.3s;
        white-space: nowrap;
    }
    .nav-menu li a:hover,
    .nav-menu li a.active {
        background: #f8f9fa;
        color: var(--primary-color);
        border-left: 3px solid var(--primary-color);
    }
    .nav-menu li a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    /*Dashboard Styles*/
    .chart-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        height: 100%;
    }
    .tradingview-widget-container {
        height: 100%;
        width: 100%;
    }
    .crypto-chart {
        min-height: 600px;
    }
    .forex-chart {
        min-height: 500px;
    }
    .market-chart {
        min-height: 500px;
    }
    .help-section {
        background: var(--secondary-color);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin: 20px;
    }
    .help-section h5 {
        margin-bottom: 10px;
    }
    .help-section .btn {
        background: white;
        color: var(--secondary-color);
        width: 100%;
        margin-top: 10px;
    }
    .balance-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .balance-title {
        color: #666;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .balance-amount {
        color: #333;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    .copy-btn {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        border: none;
        color: white;
    }
    .copy-btn:hover {
        background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        color: white;
    }
    .referrer-section {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .referrer-section input {
        background: #f8f9fa;
        border: 1px solid #eee;
    }
    /*End of Dashboard Styles*/
    /*Account Settings Styles*/
    .help-section {
        background: var(--secondary-color);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin: 20px;
    }
    .help-section h5 {
        margin-bottom: 10px;
    }
    .help-section .btn {
        background: white;
        color: var(--secondary-color);
        width: 100%;
        margin-top: 10px;
    }
    .settings-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .settings-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .profile-header {
        position: relative;
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        border-radius: 10px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        overflow: hidden;
    }
    .avatar-upload {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        border-radius: 50%;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.1);
        border: 3px solid rgba(255, 255, 255, 0.3);
    }
    .avatar-upload img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-upload .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        cursor: pointer;
    }
    .avatar-upload:hover .upload-overlay {
        opacity: 1;
    }
    .avatar-upload .upload-overlay i {
        font-size: 24px;
        color: white;
    }
    .avatar-upload input[type="file"] {
        display: none;
    }
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('path/to/wave-pattern.png') repeat;
        opacity: 0.1;
    }
    .save-btn {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        border: none;
        color: white;
        padding: 10px 30px;
        border-radius: 5px;
        transition: all 0.3s;
    }
    .save-btn:hover {
        background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        color: white;
    }
    .form-control {
        border: 1px solid #eee;
        padding: 10px 15px;
        border-radius: 5px;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: none;
    }
    .form-label {
        color: #666;
        margin-bottom: 8px;
    }
    /*End of Account Settings Styles*/

</style>

<style>
    /*Beginning of Styles for Make Investment*/
    .investment-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .investment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .plan-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .plan-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 0 10px rgba(58, 123, 213, 0.1);
    }
    .plan-card.selected {
        border-color: var(--primary-color);
        background: rgba(58, 123, 213, 0.05);
    }
    .plan-card h5 {
        margin-bottom: 10px;
        color: var(--primary-color);
    }
    .plan-card .price {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .plan-card .features {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .plan-card .features li {
        margin-bottom: 5px;
        color: #666;
    }
    .plan-card .features li i {
        color: var(--primary-color);
        margin-right: 5px;
    }
    .invest-btn {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        transition: all 0.3s;
        width: 100%;
        margin-top: 20px;
    }
    .invest-btn:hover {
        background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        color: white;
    }
    .form-control {
        border: 1px solid #eee;
        padding: 10px 15px;
        border-radius: 5px;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: none;
    }
    .form-label {
        color: #666;
        margin-bottom: 8px;
    }
    .transactions-section {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .transaction-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .transaction-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .transaction-amount {
        font-size: 1.2em;
        font-weight: bold;
        color: var(--primary-color);
    }
    .transaction-date {
        color: #666;
        font-size: 0.9em;
    }
    .transaction-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    .transaction-plan {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
        color: #666;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: 500;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #ddd;
    }
</style>

<style>
    /*Start of transactions styles*/
    .transactions-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .transaction-card {
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .transaction-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .transaction-amount {
        font-size: 1.2em;
        font-weight: bold;
        color: var(--primary-color);
    }
    .transaction-date {
        color: #666;
        font-size: 0.9em;
    }
    .transaction-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    .transaction-plan {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
        color: #666;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: 500;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #ddd;
    }
</style>

<style>
    /*
    Start of Withdraw Funds
    */
    .help-section {
        background: var(--secondary-color);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin: 20px;
    }
    .help-section h5 {
        margin-bottom: 10px;
    }
    .help-section .btn {
        background: white;
        color: var(--secondary-color);
        width: 100%;
        margin-top: 10px;
    }
    .withdraw-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .history-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-header {
        margin-bottom: 20px;
        font-weight: 600;
        color: #333;
    }
    .submit-btn {
        background: #3a7bd5;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 5px;
        transition: all 0.3s;
        text-transform: uppercase;
        font-weight: 500;
        width: 100%;
    }
    .submit-btn:hover {
        background: #2a6ac4;
    }
    .transaction-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }
    .transaction-item:last-child {
        border-bottom: none;
    }
    .transaction-date {
        color: #666;
        font-size: 0.85em;
    }
    .transaction-amount {
        font-weight: 600;
        color: #3a7bd5;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: 500;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-approved {
        background: #d4edda;
        color: #155724;
    }
    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
        font-style: italic;
    }
    .balance-display {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        color: white;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .balance-display .balance-label {
        font-size: 14px;
        font-weight: 400;
    }
</style>

<style>
    /*Start of Invoice*/
    .invoice-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .invoice-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .crypto-address {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .crypto-address h5 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    .address-box {
        background: white;
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 10px;
        word-break: break-all;
    }
    .copy-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 5px 15px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .copy-btn:hover {
        background: var(--secondary-color);
    }
    .investment-details {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .investment-details h5 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    .receipt-upload {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
    }
    .receipt-upload h5 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    .upload-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .upload-btn:hover {
        background: var(--secondary-color);
    }
</style>

<style>
    /* Mobile Menu Toggle Button */
    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 15px;
        right: 15px;
        z-index: 1001;
        background: white;
        border: none;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .mobile-menu-toggle {
            display: block;
        }

        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 300px;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding: 15px;
            width: 100%;
        }

        .header {
            padding: 0 15px;
            margin-bottom: 15px;
            position: relative;
            z-index: 999;
        }

        .logo {
            padding: 10px 15px;
        }

        .logo img {
            max-width: 120px;
        }

        .nav-menu {
            margin: 10px 0;
        }

        .nav-menu li a {
            padding: 12px 15px;
        }

        .help-section {
            margin: 15px;
            padding: 15px;
        }

        .balance-card {
            margin-bottom: 15px;
            padding: 15px;
        }

        .crypto-chart,
        .forex-chart,
        .market-chart {
            min-height: 300px;
        }

        .chart-container {
            padding: 15px;
        }

        .referrer-section {
            padding: 10px;
        }

        .balance-amount {
            font-size: 1.2rem;
        }

        .user-info {
            font-size: 0.9rem;
        }

        .user-info img {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        /* Add overlay when sidebar is active */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    }

    @media (max-width: 576px) {
        .header {
            flex-direction: column;
            height: auto;
            padding: 10px;
        }

        .header h4 {
            margin-bottom: 10px;
        }

        .user-info {
            width: 100%;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .balance-card {
            padding: 15px;
        }

        .copy-btn {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .btn-light {
            width: 35px;
            height: 35px;
        }

        .col-md-8, .col-md-4 {
            padding: 0 15px;
        }
    }
</style>