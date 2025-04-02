<?php
require_once 'includes/header.php';
require_once '../../controllers/WithdrawalController.php';

$withdrawalController = new WithdrawalController($conn);

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['withdrawal_id'])) {
    $withdrawalId = $_POST['withdrawal_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $result = $withdrawalController->updateStatus($withdrawalId, 'completed');
        if ($result['success']) {
            $_SESSION['success_message'] = "Withdrawal request approved successfully";
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
    } elseif ($action === 'reject') {
        $result = $withdrawalController->updateStatus($withdrawalId, 'cancelled');
        if ($result['success']) {
            $_SESSION['success_message'] = "Withdrawal request cancelled successfully";
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
    }
    
    header('Location: manage_withdrawals.php');
    exit();
}

// Get all withdrawals
$withdrawals = $withdrawalController->getAllWithdrawals();

// Get statistics
$totalPendingAmount = $withdrawalController->getTotalPendingAmount();
$totalCompletedAmount = $withdrawalController->getTotalCompletedAmount();
$totalCancelledAmount = $withdrawalController->getTotalCancelledAmount();
$pendingCount = $withdrawalController->getTotalPendingCount();
?>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingCount; ?></div>
                            <div class="text-xs text-gray-500 mt-1">Total Amount: $<?php echo number_format($totalPendingAmount, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($totalCompletedAmount, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Cancelled Withdrawals</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($totalCancelledAmount, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Withdrawal Requests</h6>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                    ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="withdrawalsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td><?php echo $withdrawal['id']; ?></td>
                                <td><?php echo htmlspecialchars($withdrawal['user_name']); ?></td>
                                <td>$<?php echo number_format($withdrawal['amount'], 2); ?></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($withdrawal['status']) {
                                        case 'pending':
                                            $statusClass = 'warning';
                                            break;
                                        case 'completed':
                                            $statusClass = 'success';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'danger';
                                            break;
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?>">
                                        <?php echo ucfirst($withdrawal['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($withdrawal['created_at'])); ?></td>
                                <td>
                                    <?php if ($withdrawal['status'] === 'pending'): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="withdrawal_id" value="<?php echo $withdrawal['id']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this withdrawal?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="withdrawal_id" value="<?php echo $withdrawal['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this withdrawal?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#withdrawalsTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 25
    });
});
</script>

<?php require_once 'includes/footer.php'; ?> 