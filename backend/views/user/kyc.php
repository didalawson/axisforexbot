<?php 
require_once __DIR__ . '/includes/kyc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYC Verification - AxisforexBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php require_once __DIR__ . '/includes/styles.php'; ?>
    <style>
        .kyc-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .document-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .upload-area {
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #007bff;
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
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?php require_once __DIR__ . "/sidebar.php"; ?>

    <div class="main-content">
        <?php
        $title = "KYC Verification";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="kyc-container">
                        <?php if (isset($kyc_status) && is_string($kyc_status)): ?>
                            <div class="alert alert-info">
                                <h5>Your KYC Status: <span class="status-badge status-<?php echo htmlspecialchars($kyc_status); ?>"><?php echo ucfirst(htmlspecialchars($kyc_status)); ?></span></h5>
                                <?php if ($kyc_status === 'rejected' && isset($rejection_reason)): ?>
                                    <p class="mt-2">Rejection Reason: <?php echo htmlspecialchars($rejection_reason); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!isset($kyc_status) || !is_string($kyc_status) || $kyc_status === 'rejected'): ?>
                            <form id="kycForm" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Document Type</label>
                                    <select class="form-select" name="document_type" required>
                                        <option value="">Select Document Type</option>
                                        <option value="passport">Passport</option>
                                        <option value="drivers_license">Driver's License</option>
                                        <option value="national_id">National ID</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Document Number</label>
                                    <input type="text" class="form-control" name="document_number" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Document Front</label>
                                        <div class="upload-area" onclick="document.getElementById('document_front').click()">
                                            <i class="fas fa-upload fa-2x mb-2"></i>
                                            <p>Click to upload</p>
                                            <input type="file" id="document_front" name="document_front" accept="image/*,.pdf" class="d-none" required>
                                            <img id="frontPreview" class="document-preview">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Document Back</label>
                                        <div class="upload-area" onclick="document.getElementById('document_back').click()">
                                            <i class="fas fa-upload fa-2x mb-2"></i>
                                            <p>Click to upload</p>
                                            <input type="file" id="document_back" name="document_back" accept="image/*,.pdf" class="d-none" required>
                                            <img id="backPreview" class="document-preview">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Upload bank statement</label>
                                        <div class="upload-area" onclick="document.getElementById('selfie').click()">
                                            <i class="fas fa-file-invoice fa-2x mb-2"></i>
                                            <p>Click to upload</p>
                                            <input type="file" id="selfie" name="selfie" accept="image/*,.pdf" class="d-none" required>
                                            <img id="selfiePreview" class="document-preview">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="submit-btn">Submit KYC</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center text-muted py-4">
            <small>Copyright © 2015-2025. All Rights Reserved</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

        // Document Preview
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // If it's an image, display preview
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    // If it's a PDF, show a PDF icon
                    preview.src = 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
                    preview.style.display = 'block';
                }
            }
        }

        document.getElementById('document_front').addEventListener('change', function() {
            previewImage(this, 'frontPreview');
        });

        document.getElementById('document_back').addEventListener('change', function() {
            previewImage(this, 'backPreview');
        });

        document.getElementById('selfie').addEventListener('change', function() {
            previewImage(this, 'selfiePreview');
        });

        // Form Submission
        document.addEventListener('DOMContentLoaded', function() {
            const kycForm = document.getElementById('kycForm');
            if (kycForm) {
                kycForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    
                    fetch('<?php echo BASE_URL; ?>/backend/controllers/kyc_controller.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('KYC submitted successfully!');
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error submitting KYC');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error submitting KYC');
                    });
                });
            }
        });
    </script>
</body>
</html> 