<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';
require_once '../../controllers/ContactController.php';

$auth = new AuthController($conn);
$contact = new ContactController($conn);

// Check if user is logged in and is admin
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Handle message status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $messageId = $_POST['message_id'];
    $newStatus = $_POST['status'];
    
    $contact->updateMessageStatus($messageId, $newStatus);
    header('Location: messages.php' . (isset($_GET['status']) ? '?status=' . $_GET['status'] : ''));
    exit();
}

// Get messages with pagination and status filter
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$status = isset($_GET['status']) ? $_GET['status'] : null;
$messages = $contact->getMessages($status, $page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Messages - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AxisBot Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="messages.php">Messages</a>
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

    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Manage Messages</h5>
                <div>
                    <a href="?status=new" class="btn btn-sm btn-primary <?php echo $status === 'new' ? 'active' : ''; ?>">New</a>
                    <a href="?status=read" class="btn btn-sm btn-warning <?php echo $status === 'read' ? 'active' : ''; ?>">Read</a>
                    <a href="?status=replied" class="btn btn-sm btn-success <?php echo $status === 'replied' ? 'active' : ''; ?>">Replied</a>
                    <a href="?" class="btn btn-sm btn-secondary <?php echo !$status ? 'active' : ''; ?>">All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>From</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages['data']['messages'] as $message): ?>
                            <tr>
                                <td><?php echo $message['id']; ?></td>
                                <td><?php echo htmlspecialchars($message['name']); ?></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewMessage(<?php echo htmlspecialchars(json_encode($message)); ?>)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="new" <?php echo $message['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                            <option value="read" <?php echo $message['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                            <option value="replied" <?php echo $message['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="replyMessage(<?php echo htmlspecialchars(json_encode($message)); ?>)">
                                        <i class="bi bi-reply"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($messages['data']['pages'] > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $messages['data']['pages']; $i++): ?>
                        <li class="page-item <?php echo $i === $messages['data']['current_page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status ? '&status=' . $status : ''; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Message View Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="messageContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function viewMessage(message) {
        const modal = new bootstrap.Modal(document.getElementById('messageModal'));
        const content = document.getElementById('messageContent');
        
        content.innerHTML = `
            <p><strong>From:</strong> ${message.name}</p>
            <p><strong>Email:</strong> ${message.email}</p>
            <p><strong>Subject:</strong> ${message.subject}</p>
            <p><strong>Message:</strong></p>
            <p>${message.message}</p>
        `;
        
        modal.show();
    }
    
    function replyMessage(message) {
        // Add reply functionality here
        alert('Reply functionality will be implemented here');
    }
    </script>
</body>
</html> 