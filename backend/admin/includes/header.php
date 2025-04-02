<div class="top-bar">
    <button class="btn-toggle-menu">
        <i class="fas fa-bars"></i>
    </button>
    <h1 class="h4 mb-0"><?= $title ?? "Dashboard Overview" ?></h1>
    <div class="user-info">
        <span class="me-2"><?php echo htmlspecialchars($adminUsername); ?></span>
        <i class="fas fa-user-circle"></i>
    </div>
</div>