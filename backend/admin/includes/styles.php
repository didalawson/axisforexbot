<style>
    :root {
        --primary-color: #4F46E5;
        --primary-light: #818CF8;
        --secondary-color: #3730A3;
        --success-color: #059669;
        --warning-color: #D97706;
        --danger-color: #DC2626;
        --info-color: #2563EB;
        --light-color: #F3F4F6;
        --dark-color: #111827;
        --sidebar-width: 280px;
        --card-border-radius: 16px;
        --transition-speed: 0.3s;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background: var(--light-color);
        color: var(--dark-color);
    }

    .admin-wrapper {
        display: flex;
        min-height: 100vh;
        background: #F9FAFB;
    }

    /* Sidebar Styles */
    .sidebar {
        width: var(--sidebar-width);
        background: white;
        color: var(--dark-color);
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        transition: all var(--transition-speed) ease;
        z-index: 1000;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
        padding-top: 0;
    }

    .sidebar .logo {
        padding: 24px;
        text-align: center;
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .sidebar .logo h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    .sidebar .nav-link {
        color: #4B5563;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        transition: all var(--transition-speed) ease;
        border-radius: 8px;
        margin: 4px 12px;
        font-weight: 500;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        color: var(--primary-color);
        background: rgba(79, 70, 229, 0.1);
        text-decoration: none;
        transform: translateX(4px);
    }

    .sidebar .nav-link i {
        width: 20px;
        margin-right: 12px;
        font-size: 18px;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 24px;
        transition: all var(--transition-speed) ease;
    }

    .top-bar {
        background: white;
        padding: 16px 24px;
        border-radius: var(--card-border-radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--light-color);
        border-radius: 100px;
        font-weight: 500;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .stats-card {
        background: white;
        border-radius: var(--card-border-radius);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all var(--transition-speed) ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .stats-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin-bottom: 16px;
        transition: all var(--transition-speed) ease;
    }

    .stats-card:hover .icon {
        transform: scale(1.1);
    }

    .stats-card h3 {
        font-size: 28px;
        margin: 0;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: var(--dark-color);
    }

    .stats-card p {
        margin: 4px 0 0;
        color: #6B7280;
        font-size: 14px;
        font-weight: 500;
    }

    /* Activity Cards */
    .activity-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .activity-card {
        background: white;
        border-radius: var(--card-border-radius);
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .activity-card h2 {
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E5E7EB;
        font-weight: 600;
        color: var(--dark-color);
    }

    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #E5E7EB;
        transition: all var(--transition-speed) ease;
    }

    .activity-item:hover {
        background: #F9FAFB;
        transform: translateX(4px);
        padding-left: 8px;
        padding-right: 8px;
        border-radius: 8px;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item .time {
        font-size: 13px;
        color: #6B7280;
        font-weight: 500;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 100px;
        font-weight: 500;
        font-size: 12px;
    }

    .alert {
        border-radius: var(--card-border-radius);
        padding: 16px 24px;
        margin-bottom: 24px;
        border: none;
    }

    @media (max-width: 768px) {
        :root {
            --sidebar-width: 0px;
        }

        .sidebar {
            transform: translateX(-100%);
            background: white;
        }

        .sidebar.active {
            transform: translateX(0);
            width: 280px;
        }

        .main-content {
            margin-left: 0;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .activity-grid {
            grid-template-columns: 1fr;
        }
    }

    .btn-toggle-menu {
        display: none;
        background: none;
        border: none;
        color: var(--dark-color);
        font-size: 24px;
        padding: 8px;
        margin-right: 16px;
        border-radius: 8px;
        transition: all var(--transition-speed) ease;
    }

    .btn-toggle-menu:hover {
        background: var(--light-color);
    }

    @media (max-width: 768px) {
        .btn-toggle-menu {
            display: block;
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #F3F4F6;
    }

    ::-webkit-scrollbar-thumb {
        background: #D1D5DB;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #9CA3AF;
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    .loading {
        animation: shimmer 2s infinite linear;
        background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
        background-size: 1000px 100%;
    }

    /* Navigation Section Styles */
    .nav-section {
        margin-bottom: 24px;
        padding: 0 12px;
    }

    .nav-label {
        color: #6B7280;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 12px;
        margin-bottom: 8px;
    }

    .nav-link {
        color: #4B5563;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        transition: all var(--transition-speed) ease;
        border-radius: 8px;
        margin: 4px 0;
        font-weight: 500;
        position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--primary-color);
        background: rgba(79, 70, 229, 0.1);
        text-decoration: none;
        transform: translateX(4px);
    }

    .nav-link i {
        width: 20px;
        margin-right: 12px;
        font-size: 18px;
        transition: all var(--transition-speed) ease;
    }

    .nav-link:hover i,
    .nav-link.active i {
        transform: scale(1.1);
    }
</style>

<style>
/*  Manage Users Styles  */
/*.content-container {*/
/*    background: white;*/
/*    border-radius: 5px;*/
/*    padding: 20px;*/
/*    margin-bottom: 20px;*/
/*    box-shadow: 0 2px 5px rgba(0,0,0,0.05);*/
/*}*/
.table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}
.table td, .table th {
    vertical-align: middle;
}
.currency {
    font-family: monospace;
}
.action-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    margin-right: 5px;
}
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
}
.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}
.btn-primary {
    background: linear-gradient(to right, #3a7bd5, #00d2ff);
    border: none;
}
.editable {
    position: relative;
    cursor: pointer;
}
.editable:hover {
    background-color: #f8f9fa;
}
.editable.editing {
    padding: 0;
}
.edit-input {
    width: 100%;
    height: 100%;
    padding: 8px;
    box-sizing: border-box;
}
.save-feedback {
    position: absolute;
    top: 0;
    right: 0;
    padding: 2px 5px;
    font-size: 12px;
    border-radius: 3px;
}
@media (max-width: 768px) {
    .sidebar {
        margin-left: -250px;
    }
    .sidebar.active {
        margin-left: 0;
    }
    .main-content {
        margin-left: 0;
        width: 100%;
    }
    .main-content.active {
        margin-left: 250px;
    }
}
</style>

<style>
    /*Company*/
    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        padding: 20px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }
    .header .user-info {
        display: flex;
        align-items: center;
    }
    .header .user-info .dropdown-toggle {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }
    .header .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .content-container {
        background: white;
        border-radius: 5px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .content-container h2 {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-control {
        height: 45px;
        border-radius: 5px;
    }
    .submit-btn {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .submit-btn:hover {
        background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        color: white;
    }
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -250px;
        }
        .sidebar.active {
            margin-left: 0;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
        }
        .main-content.active {
            margin-left: 250px;
        }
    }

</style>

<style>
    /*Wallet Settings*/
    .content-container {
        background: white;
        border-radius: 5px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .content-container h2 {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-control {
        height: 45px;
        border-radius: 5px;
    }
    .submit-btn {
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .submit-btn:hover {
        background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        color: white;
    }
    .wallet-card {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #eee;
    }
    .wallet-card .crypto-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 15px;
        background: linear-gradient(to right, #3a7bd5, #00d2ff);
        color: white;
    }
    .wallet-card .wallet-address {
        font-family: monospace;
        background: #fff;
        padding: 10px;
        border-radius: 5px;
        margin: 10px 0;
        word-break: break-all;
    }
    .wallet-card .network-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        background: #e9ecef;
        color: #495057;
    }
    .wallet-card .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        margin-left: 10px;
    }
    .wallet-card .status-badge.active {
        background: #d4edda;
        color: #155724;
    }
    .wallet-card .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .wallet-card .actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
    }
    .wallet-card .actions button {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
    }
    .wallet-card .actions .edit-btn {
        background: #3a7bd5;
        color: white;
    }
    .wallet-card .actions .delete-btn {
        background: #dc3545;
        color: white;
    }
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -250px;
        }
        .sidebar.active {
            margin-left: 0;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
        }
        .main-content.active {
            margin-left: 250px;
        }
    }
</style>