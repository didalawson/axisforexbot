<?php
// Get username from session if not already set
if (!isset($username) && isset($_SESSION['user_id'])) {
    // If User model is available, try to get username from database
    if (class_exists('User')) {
        $user = new User();
        $userData = $user->getUserById($_SESSION['user_id']);
        if ($userData) {
            $username = $userData['username'];
        } else {
            $username = 'User';
        }
    } else {
        // Fallback to a default value
        $username = 'User';
    }
}
?>
<div class="header">
    <h4><?= $title ?? "Dashboard" ?></h4>
    <div class="user-info">
        <img src="<?= $userImg ?? ASSET_URL.'/assets/avatar-1.png'?>" alt="User Avatar" style="width: 28px; height: 28px; border-radius: 50%; margin-right: 8px; object-fit: cover;">
        Hi, <?php echo htmlspecialchars($username ?? 'User'); ?>
    </div>
</div>