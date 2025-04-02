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
        $title = "Manage Deposit";
        require_once __DIR__."/includes/header.php"; ?>

        <div class="content-container">

        </div>
    </div>

</div>

</body>
</html>

