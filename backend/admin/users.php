<?php require_once __DIR__."/includes/manage_users.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--    <title>User Management - AxisBot Admin</title>-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <?php require_once __DIR__."/includes/styles.php";?>
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <?php require_once __DIR__."/includes/admin_sidebar.php" ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        $title = "User Account Management";
        require_once __DIR__."/includes/header.php"; ?>

        <div class="content-container">


            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger mb-3">
                    <?php echo $error; ?>
                    <?php if (defined('DEBUG_MODE') && DEBUG_MODE): ?>
                        <div class="mt-2 small">
                            <strong>Debug Info:</strong> Check the server error logs for more details.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($result) || $result === null): ?>

                <div class="card mb-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <h4>Database Error</h4>
                        <p>There was a problem retrieving user data. Please try refreshing the page or contact the administrator.</p>
                        <a href="users.php" class="btn btn-primary mt-3">
                            <i class="fas fa-sync-alt"></i> Refresh Page
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Show</span>
                            <select class="form-select form-select-sm" style="width: 80px;" id="entriesSelect">
                                <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo $perPage == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo $perPage == 50 ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?php echo $perPage == 100 ? 'selected' : ''; ?>>100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive" style="width: 100%">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
<!--                            <th>ID</th>-->
<!--                            <th>Username</th>-->
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Balance</th>
                            <th>Active Deposit</th>
                            <th>Profit</th>
                            <th>Bonus</th>
                            <th>Package</th>
                            <th>Investment Date</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            $count = $offset + 1;
                            while ($user = $result->fetch_assoc()) {
                                // Define default values if investment data doesn't exist
                                $investmentStatus = isset($user['investment_status']) ? $user['investment_status'] : 'not yet funded';
                                $investmentDate = isset($user['investment_date']) && !empty($user['investment_date']) ?
                                    date('Y-m-d H:i:s', strtotime($user['investment_date'])) :
                                    'No investment';
                                $investmentPlan = isset($user['investment_plan']) && !empty($user['investment_plan']) ?
                                    htmlspecialchars($user['investment_plan']) :
                                    'None';
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
<!--                                    <td>--><?php //echo htmlspecialchars($user['id']); ?><!--</td>-->
<!--                                    <td>--><?php //echo isset($user['username']) ? htmlspecialchars($user['username']) : null; ?><!--</td>-->
                                    <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['country'] ?? 'Not set'); ?></td>
                                    <td class="currency editable" data-column="balance" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['balance'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['balance']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="active_deposit" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['active_deposit'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['active_deposit']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="profit" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['profit'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['profit']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="bonus" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['bonus'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['bonus']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td><?php echo $investmentPlan; ?></td>
                                    <td><?php echo $investmentDate; ?></td>
                                    <td>
                                        <?php if (isset($user['investment_status'])): ?>
                                            <span class="badge <?php echo strtolower($investmentStatus) == 'active' ? 'bg-success' : (strtolower($investmentStatus) == 'pending' ? 'bg-warning' : 'bg-secondary'); ?>">
                                            <?php echo $investmentStatus; ?>
                                        </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not available</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary add-investment" data-user-id="<?php echo $user['id']; ?>" data-username="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>">
                                            <i class="fas fa-plus"></i> Add Investment
                                        </button>
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
            <?php endif; ?>

            <?php if (isset($result) && $result !== null): ?>
                <div class="pagination-container">
                    <div>
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalUsers); ?> of <?php echo $totalUsers; ?> entries
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                            </li>

                            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Add Investment Modal -->
<div class="modal fade" id="addInvestmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Investment for <span id="modal-username"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addInvestmentForm">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="investment-user-id">

                    <div class="form-group mb-3">
                        <label class="form-label">Investment Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="amount" min="1" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Investment Plan</label>
                        <select class="form-select" name="plan" required>
                            <option value="">Select Plan</option>
                            <option value="Starter Pack">Starter Pack</option>
                            <option value="Silver Plan">Silver Plan</option>
                            <option value="Gold Plan">Gold Plan</option>
                            <option value="Premium Plan">Premium Plan</option>
                            <option value="VIP Plan">VIP Plan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Profit Rate (%)</label>
                        <input type="number" class="form-control" name="profit_rate" min="0.1" step="0.1" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Duration (days)</label>
                        <input type="number" class="form-control" name="duration" min="1" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="active" selected>Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="investment-form-feedback" class="d-none alert w-100"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Investment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Show global success/error notification
        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alert = $(`<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);

            $('.alert-container').append(alert);

            // Auto-dismiss after 3 seconds
            setTimeout(function() {
                alert.alert('close');
            }, 3000);
        }

        // Make cell editable on click
        $('.editable').click(function() {
            const cell = $(this);

            // Only make editable if not already editing
            if (!cell.hasClass('editing')) {
                // Add editing class to cell
                cell.addClass('editing');

                // Hide display value and show input
                cell.find('.display-value').hide();
                cell.find('.edit-input').show().focus();
            }
        });

        // Save on blur
        $('.edit-input').blur(function() {
            const input = $(this);
            const cell = input.closest('.editable');
            const value = input.val();
            const column = cell.data('column');
            const userId = cell.data('id');

            saveData(cell, userId, column, value);
        });

        // Save on enter key
        $('.edit-input').keypress(function(e) {
            if (e.which === 13) {
                const input = $(this);
                const cell = input.closest('.editable');
                const value = input.val();
                const column = cell.data('column');
                const userId = cell.data('id');

                saveData(cell, userId, column, value);
            }
        });

        // Function to save data to server
        function saveData(cell, userId, column, value) {
            // Show loading indicator
            const displayValue = cell.find('.display-value');
            const input = cell.find('.edit-input');

            $.ajax({
                url: window.location.href,
                method: 'POST',
                data: {
                    action: 'update_user',
                    user_id: userId,
                    column: column,
                    value: value
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            // Show success indicator
                            displayValue.text(data.value);

                            // Show temporary success message
                            const feedback = $('<span class="save-feedback bg-success text-white">Saved</span>');
                            cell.append(feedback);
                            setTimeout(function() {
                                feedback.fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 1000);
                        } else {
                            // Show error indicator
                            const feedback = $('<span class="save-feedback bg-danger text-white">Error</span>');
                            cell.append(feedback);
                            setTimeout(function() {
                                feedback.fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 1000);
                            showNotification(data.message, 'error');
                        }
                    } catch (e) {
                        console.error('Invalid JSON response', e);
                        showNotification('Server returned an invalid response', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('AJAX error', xhr);
                    // Show error indicator
                    const feedback = $('<span class="save-feedback bg-danger text-white">Error</span>');
                    cell.append(feedback);
                    setTimeout(function() {
                        feedback.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 1000);
                    showNotification('Connection error occurred', 'error');
                },
                complete: function() {
                    // End edit mode and reset display
                    cell.removeClass('editing');
                    displayValue.show();
                    input.hide();
                }
            });
        }

        // Entries select change
        $('#entriesSelect').change(function() {
            const entries = $(this).val();
            window.location.href = '?page=1&entries=' + entries + '&search=<?php echo urlencode($search); ?>';
        });

        // Show add investment modal
        $('.add-investment').click(function() {
            const userId = $(this).data('user-id');
            const username = $(this).data('username');

            $('#investment-user-id').val(userId);
            $('#modal-username').text(username);

            new bootstrap.Modal(document.getElementById('addInvestmentModal')).show();
        });

        // Handle add investment form submission
        $('#addInvestmentForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: 'add_investment.php',
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#investment-form-feedback').addClass('d-none').removeClass('alert-success alert-danger');
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            $('#investment-form-feedback')
                                .removeClass('d-none alert-danger')
                                .addClass('alert-success')
                                .text(data.message);

                            // Reset form
                            setTimeout(function() {
                                $('#addInvestmentModal').modal('hide');
                                window.location.reload();
                            }, 1500);
                        } else {
                            $('#investment-form-feedback')
                                .removeClass('d-none alert-success')
                                .addClass('alert-danger')
                                .text(data.message);
                        }
                    } catch (e) {
                        $('#investment-form-feedback')
                            .removeClass('d-none alert-success')
                            .addClass('alert-danger')
                            .text('Invalid server response');
                    }
                },
                error: function() {
                    $('#investment-form-feedback')
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger')
                        .text('Server error occurred');
                }
            });
        });
    });
</script>
</body>
</html>