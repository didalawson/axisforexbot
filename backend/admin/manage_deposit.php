<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';
require_once '../../controllers/DepositController.php';

$auth = new AuthController($conn);
$deposit = new DepositController($conn);

// Check if user is logged in and is admin
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Handle deposit status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['deposit_id'])) {
    $depositId = $_POST['deposit_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve' || $action === 'reject') {
        $status = $action === 'approve' ? 'completed' : 'cancelled';
        $result = $deposit->updateStatus($depositId, $status);
        
        if ($result['success']) {
            $_SESSION['success_message'] = "Deposit request " . ($action === 'approve' ? 'approved' : 'rejected') . " successfully.";
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
        
        header('Location: manage_deposit.php');
        exit();
    }
}

// Get all deposit requests
$deposits = $deposit->getAllDeposits();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Deposits - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            border-top: none;
            background-color: #f8f9fa;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin: 0 2px;
        }
        
        .receipt-image {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AxisBot Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_deposit.php">Manage Deposits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="navbar-text me-3">
                        Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!
                    </span>
                    <a href="../logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Manage Deposit Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deposits as $deposit): ?>
                            <tr>
                                <td><?php echo $deposit['id']; ?></td>
                                <td><?php echo htmlspecialchars($deposit['user_name']); ?></td>
                                <td>$<?php echo number_format($deposit['amount'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $deposit['status']; ?>">
                                        <?php echo ucfirst($deposit['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($deposit['receipt_path']): ?>
                                        <img src="<?php echo htmlspecialchars($deposit['receipt_path']); ?>" 
                                             class="receipt-image" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#receiptModal<?php echo $deposit['id']; ?>"
                                             alt="Receipt">
                                    <?php else: ?>
                                        <span class="text-muted">No receipt</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($deposit['created_at'])); ?></td>
                                <td>
                                    <?php if ($deposit['status'] === 'pending'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="deposit_id" value="<?php echo $deposit['id']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm action-btn">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="deposit_id" value="<?php echo $deposit['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm action-btn">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <!-- Receipt Modal -->
                            <?php if ($deposit['receipt_path']): ?>
                            <div class="modal fade" id="receiptModal<?php echo $deposit['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Receipt for Deposit #<?php echo $deposit['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="<?php echo htmlspecialchars($deposit['receipt_path']); ?>" 
                                                 class="modal-image" 
                                                 alt="Receipt">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 