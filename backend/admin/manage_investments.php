<?php require_once __DIR__ . "/includes/manage_users.php";
require_once __DIR__ . '/../config/database.php';

// Check database structure
try {
    // Check status column
    $check_status = $conn->query("SHOW COLUMNS FROM investments LIKE 'status'");
    if ($check_status->num_rows > 0) {
        $status_column = $check_status->fetch_assoc();
        error_log("Status column type: " . $status_column['Type']);
        
        // Check distinct status values
        $status_values = $conn->query("SELECT DISTINCT status FROM investments");
        error_log("Distinct status values in database:");
        while ($row = $status_values->fetch_assoc()) {
            error_log("Status: '" . $row['status'] . "'");
        }
    }
    
    // Check receipt_path column
    $check_receipt = $conn->query("SHOW COLUMNS FROM investments LIKE 'receipt_path'");
    if ($check_receipt->num_rows > 0) {
        $receipt_column = $check_receipt->fetch_assoc();
        error_log("Receipt column type: " . $receipt_column['Type']);
    }
    
    // Check for any investments with receipt but no status
    $check_null_status = $conn->query("SELECT id, receipt_path, status FROM investments WHERE receipt_path IS NOT NULL AND (status IS NULL OR status = '')");
    if ($check_null_status->num_rows > 0) {
        error_log("Found investments with receipt but no status:");
        while ($row = $check_null_status->fetch_assoc()) {
            error_log("Investment ID: " . $row['id'] . " Receipt: " . $row['receipt_path'] . " Status: " . ($row['status'] ?? 'NULL'));
        }
    }
} catch (\Error $err) {
    error_log("Database structure check error: " . $err->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <title>User Management - AxisBot Admin</title>-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.min.css">



    <?php require_once __DIR__ . "/includes/styles.php"; ?>
    <style>
        /* Table styles */
        .table th {
            background-color: #f8f9fa;
            white-space: nowrap;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .btn-sm {
                display: block;
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
        .receipt-preview {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <?php require_once __DIR__ . "/includes/admin_sidebar.php" ?>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            $title = "Manage Investments";
            require_once __DIR__ . "/includes/header.php"; ?>

            <div class="content-container">
                <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">All Users</h3>
                    <div class="alert-container"></div>
                </div> -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="investments">
                        <thead>
                            <tr>
                                <!-- <th>#</th> -->
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Deposit</th>
                                <!-- <th>Active Deposit</th> -->
                                <!-- <th>Profit</th> -->
                                <!-- <th>Bonus</th> -->
                                <th>Package</th>
                                <th>Investment Date</th>
                                <th>Status</th>
                                <th>Receipt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->prepare('SELECT i.*, u.first_name, u.last_name, u.email, u.country 
                                                      FROM `investments` i 
                                                      JOIN `users` u ON i.user_id = u.id 
                                                      ORDER BY i.created_at DESC');
                                $stmt->execute();
                                $investmentr = $stmt->get_result();
                                
                                // Debug log for all investments
                                if ($investmentr && $investmentr->num_rows > 0) {
                                    while ($row = $investmentr->fetch_assoc()) {
                                        error_log("Investment ID: " . $row['id'] . 
                                                 " Status: " . (isset($row['status']) ? $row['status'] : 'not set') . 
                                                 " Receipt: " . (isset($row['receipt_path']) ? $row['receipt_path'] : 'not set'));
                                    }
                                    $investmentr->data_seek(0); // Reset the pointer
                                }
                            } catch (\Error $err) {
                                error_log("Database error: " . $err->getMessage());
                            }
                            ?>
                            <?php
                            if ($investmentr && $investmentr->num_rows > 0) {
                                while ($investment = $investmentr->fetch_assoc()) {

                            ?>
                                    <tr>
                                        <!-- <td><?php echo $count++; ?></td> -->
                                        <td><?php echo htmlspecialchars($investment['id']); ?></td>
                                        <td><?php echo htmlspecialchars($investment['first_name'] . ' ' . $investment['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($investment['email']); ?></td>
                                        <td><?php echo htmlspecialchars($investment['country'] ?? 'Not set'); ?></td>
                                        <td>$<?= number_format($investment['amount'], 2) ?></td>


                                        </td>
                                        <td><?= $investment['plan']; ?></td>
                                        <td><?= date('j-m-Y', strtotime($investment['created_at'])); ?></td>
                                        <td>
                                            <?php if (isset($investment['status'])): ?>
                                                <span class="badge <?php 
                                                    $status = strtolower(trim($investment['status']));
                                                    echo $status == 'active' ? 'bg-success' : 
                                                        ($status == 'pending' ? 'bg-warning' : 
                                                        ($status == 'rejected' ? 'bg-danger' : 
                                                        ($status == 'inactive' ? 'bg-secondary' : 'bg-secondary'))); 
                                                ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($investment['receipt_path'])): ?>
                                                <button class="btn btn-sm btn-info" onclick="viewReceipt('<?php echo htmlspecialchars($investment['receipt_path']); ?>')">
                                                    <i class="fas fa-file-invoice"></i> View
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No receipt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = isset($investment['status']) ? strtolower(trim($investment['status'])) : 'pending';
                                            $has_receipt = !empty($investment['receipt_path']);
                                            
                                            // If there's a receipt but no status or pending_approval, treat as pending
                                            if ($has_receipt && (empty($status) || $status === 'null' || $status === 'pending_approval')) {
                                                $status = 'pending';
                                                // Update the status in the database
                                                $update_status = $conn->prepare("UPDATE investments SET status = 'pending' WHERE id = ?");
                                                $update_status->bind_param("i", $investment['id']);
                                                $update_status->execute();
                                            }
                                            
                                            error_log("Processing Investment ID: " . $investment['id'] . 
                                                     " Status: " . $status . 
                                                     " Has Receipt: " . ($has_receipt ? 'Yes' : 'No'));
                                            
                                            // Always show approve/reject buttons for pending investments
                                            if ($status === 'pending' || $status === 'pending_approval'): ?>
                                                <button class="btn btn-sm btn-success mb-1" onclick="updateInvestmentStatus(<?php echo $investment['id']; ?>, 'active')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button class="btn btn-sm btn-danger mb-1" onclick="rejectInvestment(<?php echo $investment['id']; ?>)">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            <?php elseif ($status === 'active'): ?>
                                                <button class="btn btn-sm btn-secondary mb-1" onclick="updateInvestmentStatus(<?php echo $investment['id']; ?>, 'inactive')">
                                                    <i class="fas fa-ban"></i> Deactivate
                                                </button>
                                            <?php elseif ($status === 'rejected' || $status === 'inactive'): ?>
                                                <button class="btn btn-sm btn-outline-secondary mb-1" onclick="updateInvestmentStatus(<?php echo $investment['id']; ?>, 'active')">
                                                    <i class="fas fa-sync"></i> Reactivate
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Unknown Status (<?php echo htmlspecialchars($investment['status']); ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="15" class="text-center">
                                        <?php if ($result === null): ?>
                                            <div class="alert alert-danger m-3">
                                                <i class="fas fa-exclamation-circle me-2"></i>
                                                Error retrieving user data. Please check database connection.
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info m-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No users found matching your criteria. Try adjusting your search.
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="receiptPreview" src="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Investment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectionForm">
                        <input type="hidden" id="rejectInvestmentId">
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejectionReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitRejection()">Reject</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let table = $('#investments').DataTable();

    // View Receipt
    function viewReceipt(path) {
        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        document.getElementById('receiptPreview').src = "../uploads/receipts/" + path;
        modal.show();
    }

    // Update Investment Status
    function updateInvestmentStatus(investmentId, status) {
        if (!confirm(`Are you sure you want to ${status === 'active' ? 'approve' : (status === 'inactive' ? 'deactivate' : 'reactivate')} this investment?`)) {
            return;
        }

        fetch('../controllers/admin/investment_controller.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                investment_id: investmentId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error updating investment status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating investment status');
        });
    }

    // Show Rejection Modal
    function rejectInvestment(investmentId) {
        document.getElementById('rejectInvestmentId').value = investmentId;
        const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        modal.show();
    }

    // Submit Rejection
    function submitRejection() {
        const investmentId = document.getElementById('rejectInvestmentId').value;
        const reason = document.getElementById('rejectionReason').value;

        if (!reason) {
            alert('Please provide a rejection reason');
            return;
        }

        fetch('../controllers/admin/investment_controller.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                investment_id: investmentId,
                status: 'rejected',
                rejection_reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Error rejecting investment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting investment');
        });
    }
</script>

</html>