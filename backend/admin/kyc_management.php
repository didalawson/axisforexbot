<?php
require_once '../config/database.php';
// config.php is likely already included in database.php
// require_once '../config/config.php';
require_once '../models/KYC.php';

// Check if user is logged in and is admin
session_start();
// Debug the session variables
error_log("User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));
error_log("User Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set'));

// For development purposes, allow access regardless of role
// Remove this in production and uncomment the check below
$kyc = new KYC();
$kyc_submissions = $kyc->getAllKYCSubmissions();

// Page title
$pageTitle = "KYC Management";

// Uncomment this for production
/*
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Management - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        /* Basic layout styles */
        body {
            overflow-x: hidden;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            padding: 1rem;
            transition: transform 0.3s ease;
        }
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .content-wrapper {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .nav-link {
            color: #ced4da;
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            text-decoration: none;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .nav-section {
            margin-bottom: 20px;
        }
        .nav-label {
            font-size: 0.8rem;
            color: #adb5bd;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        /* Mobile menu toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1001;
            background-color: #343a40;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        /* Enhanced mobile responsiveness */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
                width: 80%;
                max-width: 300px;
                position: fixed;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
                padding-top: 60px;
            }
            .content-wrapper {
                padding: 15px;
            }
            .filter-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }
            .filter-buttons .btn {
                margin: 0;
                flex: 1 0 45%;
                padding: 6px 10px;
                font-size: 14px;
            }
            .kyc-card {
                padding: 15px;
            }
            .kyc-card .row {
                flex-direction: column;
            }
            .col-md-6, .col-md-4 {
                margin-bottom: 15px;
            }
            .document-preview {
                max-width: 100%;
                height: auto;
            }
            h1 {
                font-size: 1.8rem;
            }
            h5 {
                font-size: 1.1rem;
            }
            /* Increase touch target sizes */
            .btn {
                padding: 10px 15px;
                margin-bottom: 5px;
            }
            .status-badge {
                padding: 6px 12px;
            }
        }
        
        /* Additional sidebar styles */
        .logo {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        .logo h3 {
            color: #fff;
            margin: 0;
            font-size: 1.5rem;
        }
        
        /* Existing styles */
        .kyc-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .document-container {
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .document-preview {
            max-width: 200px;
            max-height: 200px;
            margin: 0 auto;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .document-preview:hover {
            transform: scale(1.05);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
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
        .filter-buttons {
            margin-bottom: 20px;
        }
        .filter-buttons .btn {
            margin-right: 10px;
        }
        
        /* Table Styles */
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-right: 3px;
        }
        @media (max-width: 768px) {
            .table-responsive {
                border: 0;
            }
            .table th, .table td {
                padding: 0.5rem;
            }
            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.8rem;
                margin-bottom: 5px;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i> Menu
            </button>
    
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include_once 'includes/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <?php if (file_exists('includes/admin_header.php')) include_once 'includes/admin_header.php'; ?>

            <div class="content-wrapper">
                <div class="content-header">
                    <h1><?php echo $pageTitle; ?></h1>
                </div>

                <div class="content">
        <div class="filter-buttons">
            <button class="btn btn-primary" onclick="filterKYC('all')">All</button>
            <button class="btn btn-warning" onclick="filterKYC('pending')">Pending</button>
            <button class="btn btn-success" onclick="filterKYC('approved')">Approved</button>
            <button class="btn btn-danger" onclick="filterKYC('rejected')">Rejected</button>
        </div>

        <div id="kycList">
            <?php if (empty($kyc_submissions)): ?>
                <div class="alert alert-info">No KYC submissions found.</div>
            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Document</th>
                                            <th>Status</th>
                                            <th>Submitted Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php foreach ($kyc_submissions as $submission): ?>
                                            <tr class="kyc-row" data-status="<?php echo $submission['status']; ?>">
                                                <td>
                                                    <strong><?php echo htmlspecialchars($submission['username']); ?></strong><br>
                                                    <small><?php echo htmlspecialchars($submission['email']); ?></small>
                                                </td>
                                                <td>
                                                    <?php echo ucwords(str_replace('_', ' ', $submission['document_type'])); ?><br>
                                                    <small>#<?php echo htmlspecialchars($submission['document_number']); ?></small>
                                                </td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $submission['status']; ?>">
                                                        <?php echo ucfirst($submission['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo date('d M Y', strtotime($submission['created_at'])); ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info mb-1" onclick="viewDetails(<?php echo $submission['id']; ?>)">
                                                        <i class="fas fa-eye"></i> Details
                                                    </button>
                        <?php if ($submission['status'] === 'pending'): ?>
                                                        <button class="btn btn-sm btn-success mb-1" onclick="updateKYCStatus(<?php echo $submission['id']; ?>, 'approved')">
                                                            <i class="fas fa-check"></i> Approve
                                    </button>
                                                        <button class="btn btn-sm btn-danger mb-1" onclick="rejectKYC(<?php echo $submission['id']; ?>)">
                                                            <i class="fas fa-times"></i> Reject
                                    </button>
                        <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div class="modal fade" id="documentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="documentPreview" src="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- KYC Details Modal -->
    <div class="modal fade" id="kycDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">KYC Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="kycDetailsContent">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">User Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="35%">Username:</th>
                                            <td id="detail-username"></td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td id="detail-email"></td>
                                        </tr>
                                        <tr>
                                            <th>Full Name:</th>
                                            <td id="detail-fullname"></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td id="detail-dob"></td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td id="detail-phone"></td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td id="detail-address"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">Document Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="35%">Document Type:</th>
                                            <td id="detail-doctype"></td>
                                        </tr>
                                        <tr>
                                            <th>Document Number:</th>
                                            <td id="detail-docnumber"></td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td id="detail-status"></td>
                                        </tr>
                                        <tr>
                                            <th>Submitted:</th>
                                            <td id="detail-submitted"></td>
                                        </tr>
                                        <tr id="rejection-reason-row" style="display:none;">
                                            <th>Rejection Reason:</th>
                                            <td id="detail-rejection"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6>Document Front</h6>
                            <div class="document-container">
                                <img id="detail-front" src="" class="document-preview img-fluid" onclick="viewDocument(this.src)">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>Document Back</h6>
                            <div class="document-container">
                                <img id="detail-back" src="" class="document-preview img-fluid" onclick="viewDocument(this.src)">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>Bank Statement</h6>
                            <div class="document-container">
                                <img id="detail-bank" src="" class="document-preview img-fluid" onclick="viewDocument(this.src)">
                            </div>
                        </div>
                    </div>
                    <div id="detail-actions" class="mt-3 text-center"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject KYC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectionForm">
                        <input type="hidden" id="rejectKYCId">
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.querySelector('.sidebar');
            
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Close sidebar when clicking outside of it
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && event.target !== mobileMenuToggle) {
                    sidebar.classList.remove('show');
                }
            });
        });
        
        // Document Preview
        function viewDocument(src) {
            const modal = new bootstrap.Modal(document.getElementById('documentModal'));
            document.getElementById('documentPreview').src = src;
            modal.show();
        }

        // Store all KYC data in JavaScript for quick access
        const kycData = <?php echo json_encode($kyc_submissions); ?>;
        
        // View KYC Details 
        function viewDetails(kycId) {
            const submission = kycData.find(item => parseInt(item.id) === kycId);
            if (!submission) return;
            
            // Fill user information
            document.getElementById('detail-username').textContent = submission.username;
            document.getElementById('detail-email').textContent = submission.email;
            document.getElementById('detail-fullname').textContent = submission.full_name;
            document.getElementById('detail-dob').textContent = new Date(submission.date_of_birth).toLocaleDateString();
            document.getElementById('detail-phone').textContent = submission.phone_number;
            document.getElementById('detail-address').textContent = submission.address;
            
            // Fill document information
            document.getElementById('detail-doctype').textContent = submission.document_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            document.getElementById('detail-docnumber').textContent = submission.document_number;
            
            const statusElement = document.getElementById('detail-status');
            statusElement.innerHTML = `<span class="status-badge status-${submission.status}">${submission.status.charAt(0).toUpperCase() + submission.status.slice(1)}</span>`;
            
            document.getElementById('detail-submitted').textContent = new Date(submission.created_at).toLocaleString();
            
            // Show/hide rejection reason
            const rejectionRow = document.getElementById('rejection-reason-row');
            if (submission.status === 'rejected' && submission.rejection_reason) {
                rejectionRow.style.display = 'table-row';
                document.getElementById('detail-rejection').textContent = submission.rejection_reason;
            } else {
                rejectionRow.style.display = 'none';
            }
            
            // Set document images
            document.getElementById('detail-front').src = `../uploads/kyc/${submission.document_front}`;
            document.getElementById('detail-back').src = `../uploads/kyc/${submission.document_back}`;
            document.getElementById('detail-bank').src = `../uploads/kyc/${submission.selfie}`;
            
            // Add action buttons if pending
            const actionsContainer = document.getElementById('detail-actions');
            if (submission.status === 'pending') {
                actionsContainer.innerHTML = `
                    <button class="btn btn-success me-2" onclick="updateKYCStatus(${submission.id}, 'approved')">
                        <i class="fas fa-check"></i> Approve
                    </button>
                    <button class="btn btn-danger" onclick="rejectKYC(${submission.id})">
                        <i class="fas fa-times"></i> Reject
                    </button>
                `;
            } else {
                actionsContainer.innerHTML = '';
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('kycDetailsModal'));
            modal.show();
        }

        // Filter KYC submissions
        function filterKYC(status) {
            const rows = document.querySelectorAll('.kyc-row');
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Update KYC Status
        function updateKYCStatus(kycId, status) {
            fetch('../controllers/admin/kyc_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    kyc_id: kycId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error updating KYC status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating KYC status');
            });
        }

        // Show Rejection Modal
        function rejectKYC(kycId) {
            document.getElementById('rejectKYCId').value = kycId;
            const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
            modal.show();
        }

        // Submit Rejection
        function submitRejection() {
            const kycId = document.getElementById('rejectKYCId').value;
            const reason = document.getElementById('rejectionReason').value;

            if (!reason) {
                alert('Please provide a rejection reason');
                return;
            }

            fetch('../controllers/admin/kyc_controller.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    kyc_id: kycId,
                    status: 'rejected',
                    rejection_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error rejecting KYC');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error rejecting KYC');
            });
        }
    </script>
</body>
</html> 