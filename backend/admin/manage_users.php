਍ഀഀ
<!DOCTYPE html>਍㰀栀琀洀氀 氀愀渀最㴀∀攀渀∀㸀ഀഀ
<head>਍    㰀洀攀琀愀 挀栀愀爀猀攀琀㴀∀唀吀䘀ⴀ㠀∀㸀ഀഀ
    <meta name="viewport" content="width=device-width, initial-scale=1.0">਍㰀℀ⴀⴀ    㰀琀椀琀氀攀㸀唀猀攀爀 䴀愀渀愀最攀洀攀渀琀 ⴀ 䄀砀椀猀䈀漀琀 䄀搀洀椀渀㰀⼀琀椀琀氀攀㸀ⴀⴀ㸀ഀഀ
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">਍    㰀氀椀渀欀 爀攀氀㴀∀猀琀礀氀攀猀栀攀攀琀∀ 栀爀攀昀㴀∀栀琀琀瀀猀㨀⼀⼀挀搀渀樀猀⸀挀氀漀甀搀昀氀愀爀攀⸀挀漀洀⼀愀樀愀砀⼀氀椀戀猀⼀昀漀渀琀ⴀ愀眀攀猀漀洀攀⼀㘀⸀　⸀　⼀挀猀猀⼀愀氀氀⸀洀椀渀⸀挀猀猀∀㸀ഀഀ
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">਍ഀഀ
    <?php require_once __DIR__."/includes/styles.php";?>਍㰀⼀栀攀愀搀㸀ഀഀ
<body>਍    㰀搀椀瘀 挀氀愀猀猀㴀∀愀搀洀椀渀ⴀ眀爀愀瀀瀀攀爀∀㸀ഀഀ
        <!-- Sidebar -->਍        㰀㼀瀀栀瀀 爀攀焀甀椀爀攀开漀渀挀攀 开开䐀䤀刀开开⸀∀⼀椀渀挀氀甀搀攀猀⼀愀搀洀椀渀开猀椀搀攀戀愀爀⸀瀀栀瀀∀ 㼀㸀ഀഀ
਍        㰀℀ⴀⴀ 䴀愀椀渀 䌀漀渀琀攀渀琀 ⴀⴀ㸀ഀഀ
        <div class="main-content">਍            㰀搀椀瘀 挀氀愀猀猀㴀∀栀攀愀搀攀爀∀㸀ഀഀ
                <h1>User Account Management</h1>਍                㰀搀椀瘀 挀氀愀猀猀㴀∀甀猀攀爀ⴀ椀渀昀漀∀㸀ഀഀ
                    <div class="dropdown">਍                        㰀愀 栀爀攀昀㴀∀⌀∀ 挀氀愀猀猀㴀∀搀爀漀瀀搀漀眀渀ⴀ琀漀最最氀攀∀ 搀愀琀愀ⴀ戀猀ⴀ琀漀最最氀攀㴀∀搀爀漀瀀搀漀眀渀∀㸀ഀഀ
                            <img src="../assets/images/admin-avatar.png" alt="Admin Avatar">਍                            㰀猀瀀愀渀㸀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀愀搀洀椀渀唀猀攀爀渀愀洀攀⤀㬀 㼀㸀㰀⼀猀瀀愀渀㸀ഀഀ
                        </a>਍                        㰀甀氀 挀氀愀猀猀㴀∀搀爀漀瀀搀漀眀渀ⴀ洀攀渀甀 搀爀漀瀀搀漀眀渀ⴀ洀攀渀甀ⴀ攀渀搀∀㸀ഀഀ
                            <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-key me-2"></i> Change Password</a></li>਍                            㰀氀椀㸀㰀栀爀 挀氀愀猀猀㴀∀搀爀漀瀀搀漀眀渀ⴀ搀椀瘀椀搀攀爀∀㸀㰀⼀氀椀㸀ഀഀ
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>਍                        㰀⼀甀氀㸀ഀഀ
                    </div>਍                㰀⼀搀椀瘀㸀ഀഀ
            </div>਍ഀഀ
            <div class="content-container">਍                㰀搀椀瘀 挀氀愀猀猀㴀∀搀ⴀ昀氀攀砀 樀甀猀琀椀昀礀ⴀ挀漀渀琀攀渀琀ⴀ戀攀琀眀攀攀渀 愀氀椀最渀ⴀ椀琀攀洀猀ⴀ挀攀渀琀攀爀 洀戀ⴀ㐀∀㸀ഀഀ
                    <h3 class="mb-0">All Users</h3>਍                    㰀搀椀瘀 挀氀愀猀猀㴀∀愀氀攀爀琀ⴀ挀漀渀琀愀椀渀攀爀∀㸀㰀⼀搀椀瘀㸀ഀഀ
                </div>਍ഀഀ
                <?php if (isset($error) && !empty($error)): ?>਍                㰀搀椀瘀 挀氀愀猀猀㴀∀愀氀攀爀琀 愀氀攀爀琀ⴀ搀愀渀最攀爀 洀戀ⴀ㌀∀㸀ഀഀ
                    <?php echo $error; ?>਍                    㰀㼀瀀栀瀀 椀昀 ⠀搀攀昀椀渀攀搀⠀✀䐀䔀䈀唀䜀开䴀伀䐀䔀✀⤀ ☀☀ 䐀䔀䈀唀䜀开䴀伀䐀䔀⤀㨀 㼀㸀ഀഀ
                    <div class="mt-2 small">਍                        㰀猀琀爀漀渀最㸀䐀攀戀甀最 䤀渀昀漀㨀㰀⼀猀琀爀漀渀最㸀 䌀栀攀挀欀 琀栀攀 猀攀爀瘀攀爀 攀爀爀漀爀 氀漀最猀 昀漀爀 洀漀爀攀 搀攀琀愀椀氀猀⸀ഀഀ
                    </div>਍                    㰀㼀瀀栀瀀 攀渀搀椀昀㬀 㼀㸀ഀഀ
                </div>਍                㰀㼀瀀栀瀀 攀渀搀椀昀㬀 㼀㸀ഀഀ
਍                㰀㼀瀀栀瀀 椀昀 ⠀␀爀攀猀甀氀琀 㴀㴀㴀 渀甀氀氀⤀㨀 㼀㸀ഀഀ
                <div class="card mb-4">਍                    㰀搀椀瘀 挀氀愀猀猀㴀∀挀愀爀搀ⴀ戀漀搀礀 琀攀砀琀ⴀ挀攀渀琀攀爀 瀀礀ⴀ㔀∀㸀ഀഀ
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>਍                        㰀栀㐀㸀䐀愀琀愀戀愀猀攀 䔀爀爀漀爀㰀⼀栀㐀㸀ഀഀ
                        <p>There was a problem retrieving user data. Please try refreshing the page or contact the administrator.</p>਍                        㰀愀 栀爀攀昀㴀∀洀愀渀愀最攀开甀猀攀爀猀⸀瀀栀瀀∀ 挀氀愀猀猀㴀∀戀琀渀 戀琀渀ⴀ瀀爀椀洀愀爀礀 洀琀ⴀ㌀∀㸀ഀഀ
                            <i class="fas fa-sync-alt"></i> Refresh Page਍                        㰀⼀愀㸀ഀഀ
                    </div>਍                㰀⼀搀椀瘀㸀ഀഀ
                <?php else: ?>਍                㰀搀椀瘀 挀氀愀猀猀㴀∀爀漀眀 洀戀ⴀ㌀∀㸀ഀഀ
                    <div class="col-md-6">਍                        㰀搀椀瘀 挀氀愀猀猀㴀∀搀ⴀ昀氀攀砀 愀氀椀最渀ⴀ椀琀攀洀猀ⴀ挀攀渀琀攀爀∀㸀ഀഀ
                            <span class="me-2">Show</span>਍                            㰀猀攀氀攀挀琀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ猀攀氀攀挀琀 昀漀爀洀ⴀ猀攀氀攀挀琀ⴀ猀洀∀ 猀琀礀氀攀㴀∀眀椀搀琀栀㨀 㠀　瀀砀㬀∀ 椀搀㴀∀攀渀琀爀椀攀猀匀攀氀攀挀琀∀㸀ഀഀ
                                <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10</option>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀㈀㔀∀ 㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀攀爀倀愀最攀 㴀㴀 ㈀㔀 㼀 ✀猀攀氀攀挀琀攀搀✀ 㨀 ✀✀㬀 㼀㸀㸀㈀㔀㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="50" <?php echo $perPage == 50 ? 'selected' : ''; ?>>50</option>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀㄀　　∀ 㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀攀爀倀愀最攀 㴀㴀 ㄀　　 㼀 ✀猀攀氀攀挀琀攀搀✀ 㨀 ✀✀㬀 㼀㸀㸀㄀　　㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                            </select>਍                            㰀猀瀀愀渀 挀氀愀猀猀㴀∀洀猀ⴀ㈀∀㸀攀渀琀爀椀攀猀㰀⼀猀瀀愀渀㸀ഀഀ
                        </div>਍                    㰀⼀搀椀瘀㸀ഀഀ
                    <div class="col-md-6">਍                        㰀昀漀爀洀 洀攀琀栀漀搀㴀∀䜀䔀吀∀ 愀挀琀椀漀渀㴀∀∀㸀ഀഀ
                            <div class="input-group">਍                                㰀椀渀瀀甀琀 琀礀瀀攀㴀∀琀攀砀琀∀ 挀氀愀猀猀㴀∀昀漀爀洀ⴀ挀漀渀琀爀漀氀∀ 瀀氀愀挀攀栀漀氀搀攀爀㴀∀匀攀愀爀挀栀⸀⸀⸀∀ 渀愀洀攀㴀∀猀攀愀爀挀栀∀ 瘀愀氀甀攀㴀∀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀猀攀愀爀挀栀⤀㬀 㼀㸀∀㸀ഀഀ
                                <button class="btn btn-outline-secondary" type="submit">਍                                    㰀椀 挀氀愀猀猀㴀∀昀愀猀 昀愀ⴀ猀攀愀爀挀栀∀㸀㰀⼀椀㸀ഀഀ
                                </button>਍                            㰀⼀搀椀瘀㸀ഀഀ
                        </form>਍                    㰀⼀搀椀瘀㸀ഀഀ
                </div>਍ഀഀ
                <div class="table-responsive">਍                    㰀琀愀戀氀攀 挀氀愀猀猀㴀∀琀愀戀氀攀 琀愀戀氀攀ⴀ戀漀爀搀攀爀攀搀 琀愀戀氀攀ⴀ栀漀瘀攀爀∀㸀ഀഀ
                        <thead>਍                            㰀琀爀㸀ഀഀ
                                <th>#</th>਍                                㰀琀栀㸀䤀䐀㰀⼀琀栀㸀ഀഀ
                                <th>Username</th>਍                                㰀琀栀㸀一愀洀攀㰀⼀琀栀㸀ഀഀ
                                <th>Email</th>਍                                㰀琀栀㸀䌀漀甀渀琀爀礀㰀⼀琀栀㸀ഀഀ
                                <th>Balance</th>਍                                㰀琀栀㸀䄀挀琀椀瘀攀 䐀攀瀀漀猀椀琀㰀⼀琀栀㸀ഀഀ
                                <th>Profit</th>਍                                㰀琀栀㸀䈀漀渀甀猀㰀⼀琀栀㸀ഀഀ
                                <th>Package</th>਍                                㰀琀栀㸀䤀渀瘀攀猀琀洀攀渀琀 䐀愀琀攀㰀⼀琀栀㸀ഀഀ
                                <th>Status</th>਍                                㰀琀栀㸀䨀漀椀渀攀搀㰀⼀琀栀㸀ഀഀ
                                <th>Actions</th>਍                            㰀⼀琀爀㸀ഀഀ
                        </thead>਍                        㰀琀戀漀搀礀㸀ഀഀ
                            <?php਍                            椀昀 ⠀␀爀攀猀甀氀琀 ☀☀ ␀爀攀猀甀氀琀ⴀ㸀渀甀洀开爀漀眀猀 㸀 　⤀ 笀ഀഀ
                                $count = $offset + 1;਍                                眀栀椀氀攀 ⠀␀甀猀攀爀 㴀 ␀爀攀猀甀氀琀ⴀ㸀昀攀琀挀栀开愀猀猀漀挀⠀⤀⤀ 笀ഀഀ
                                    // Define default values if investment data doesn't exist਍                                    ␀椀渀瘀攀猀琀洀攀渀琀匀琀愀琀甀猀 㴀 椀猀猀攀琀⠀␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开猀琀愀琀甀猀✀崀⤀ 㼀 ␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开猀琀愀琀甀猀✀崀 㨀 ✀渀漀琀 礀攀琀 昀甀渀搀攀搀✀㬀ഀഀ
                                    $investmentDate = isset($user['investment_date']) && !empty($user['investment_date']) ?਍                                        搀愀琀攀⠀✀夀ⴀ洀ⴀ搀 䠀㨀椀㨀猀✀Ⰰ 猀琀爀琀漀琀椀洀攀⠀␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开搀愀琀攀✀崀⤀⤀ 㨀ഀഀ
                                        'No investment';਍                                    ␀椀渀瘀攀猀琀洀攀渀琀倀氀愀渀 㴀 椀猀猀攀琀⠀␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开瀀氀愀渀✀崀⤀ ☀☀ ℀攀洀瀀琀礀⠀␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开瀀氀愀渀✀崀⤀ 㼀ഀഀ
                                        htmlspecialchars($user['investment_plan']) :਍                                        ✀一漀渀攀✀㬀ഀഀ
                            ?>਍                                㰀琀爀㸀ഀഀ
                                    <td><?php echo $count++; ?></td>਍                                    㰀琀搀㸀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀甀猀攀爀嬀✀椀搀✀崀⤀㬀 㼀㸀㰀⼀琀搀㸀ഀഀ
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>਍                                    㰀琀搀㸀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀甀猀攀爀嬀✀昀椀爀猀琀开渀愀洀攀✀崀 ⸀ ✀ ✀ ⸀ ␀甀猀攀爀嬀✀氀愀猀琀开渀愀洀攀✀崀⤀㬀 㼀㸀㰀⼀琀搀㸀ഀഀ
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>਍                                    㰀琀搀㸀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀甀猀攀爀嬀✀挀漀甀渀琀爀礀✀崀 㼀㼀 ✀一漀琀 猀攀琀✀⤀㬀 㼀㸀㰀⼀琀搀㸀ഀഀ
                                    <td class="currency editable" data-column="balance" data-id="<?php echo $user['id']; ?>">਍                                        ␀㰀猀瀀愀渀 挀氀愀猀猀㴀∀搀椀猀瀀氀愀礀ⴀ瘀愀氀甀攀∀㸀㰀㼀瀀栀瀀 攀挀栀漀 渀甀洀戀攀爀开昀漀爀洀愀琀⠀␀甀猀攀爀嬀✀戀愀氀愀渀挀攀✀崀Ⰰ ㈀⤀㬀 㼀㸀㰀⼀猀瀀愀渀㸀ഀഀ
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['balance']; ?>" style="display: none;" min="0" step="0.01">਍                                    㰀⼀琀搀㸀ഀഀ
                                    <td class="currency editable" data-column="active_deposit" data-id="<?php echo $user['id']; ?>">਍                                        ␀㰀猀瀀愀渀 挀氀愀猀猀㴀∀搀椀猀瀀氀愀礀ⴀ瘀愀氀甀攀∀㸀㰀㼀瀀栀瀀 攀挀栀漀 渀甀洀戀攀爀开昀漀爀洀愀琀⠀␀甀猀攀爀嬀✀愀挀琀椀瘀攀开搀攀瀀漀猀椀琀✀崀Ⰰ ㈀⤀㬀 㼀㸀㰀⼀猀瀀愀渀㸀ഀഀ
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['active_deposit']; ?>" style="display: none;" min="0" step="0.01">਍                                    㰀⼀琀搀㸀ഀഀ
                                    <td class="currency editable" data-column="profit" data-id="<?php echo $user['id']; ?>">਍                                        ␀㰀猀瀀愀渀 挀氀愀猀猀㴀∀搀椀猀瀀氀愀礀ⴀ瘀愀氀甀攀∀㸀㰀㼀瀀栀瀀 攀挀栀漀 渀甀洀戀攀爀开昀漀爀洀愀琀⠀␀甀猀攀爀嬀✀瀀爀漀昀椀琀✀崀Ⰰ ㈀⤀㬀 㼀㸀㰀⼀猀瀀愀渀㸀ഀഀ
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['profit']; ?>" style="display: none;" min="0" step="0.01">਍                                    㰀⼀琀搀㸀ഀഀ
                                    <td class="currency editable" data-column="bonus" data-id="<?php echo $user['id']; ?>">਍                                        ␀㰀猀瀀愀渀 挀氀愀猀猀㴀∀搀椀猀瀀氀愀礀ⴀ瘀愀氀甀攀∀㸀㰀㼀瀀栀瀀 攀挀栀漀 渀甀洀戀攀爀开昀漀爀洀愀琀⠀␀甀猀攀爀嬀✀戀漀渀甀猀✀崀Ⰰ ㈀⤀㬀 㼀㸀㰀⼀猀瀀愀渀㸀ഀഀ
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['bonus']; ?>" style="display: none;" min="0" step="0.01">਍                                    㰀⼀琀搀㸀ഀഀ
                                    <td><?php echo $investmentPlan; ?></td>਍                                    㰀琀搀㸀㰀㼀瀀栀瀀 攀挀栀漀 ␀椀渀瘀攀猀琀洀攀渀琀䐀愀琀攀㬀 㼀㸀㰀⼀琀搀㸀ഀഀ
                                    <td>਍                                        㰀㼀瀀栀瀀 椀昀 ⠀椀猀猀攀琀⠀␀甀猀攀爀嬀✀椀渀瘀攀猀琀洀攀渀琀开猀琀愀琀甀猀✀崀⤀⤀㨀 㼀㸀ഀഀ
                                        <span class="badge <?php echo strtolower($investmentStatus) == 'active' ? 'bg-success' : (strtolower($investmentStatus) == 'pending' ? 'bg-warning' : 'bg-secondary'); ?>">਍                                            㰀㼀瀀栀瀀 攀挀栀漀 ␀椀渀瘀攀猀琀洀攀渀琀匀琀愀琀甀猀㬀 㼀㸀ഀഀ
                                        </span>਍                                        㰀㼀瀀栀瀀 攀氀猀攀㨀 㼀㸀ഀഀ
                                        <span class="badge bg-secondary">Not available</span>਍                                        㰀㼀瀀栀瀀 攀渀搀椀昀㬀 㼀㸀ഀഀ
                                    </td>਍                                    㰀琀搀㸀㰀㼀瀀栀瀀 攀挀栀漀 搀愀琀攀⠀✀夀ⴀ洀ⴀ搀✀Ⰰ 猀琀爀琀漀琀椀洀攀⠀␀甀猀攀爀嬀✀挀爀攀愀琀攀搀开愀琀✀崀⤀⤀㬀 㼀㸀㰀⼀琀搀㸀ഀഀ
                                    <td>਍                                        㰀戀甀琀琀漀渀 琀礀瀀攀㴀∀戀甀琀琀漀渀∀ 挀氀愀猀猀㴀∀戀琀渀 戀琀渀ⴀ猀洀 戀琀渀ⴀ瀀爀椀洀愀爀礀 愀搀搀ⴀ椀渀瘀攀猀琀洀攀渀琀∀ 搀愀琀愀ⴀ甀猀攀爀ⴀ椀搀㴀∀㰀㼀瀀栀瀀 攀挀栀漀 ␀甀猀攀爀嬀✀椀搀✀崀㬀 㼀㸀∀ 搀愀琀愀ⴀ甀猀攀爀渀愀洀攀㴀∀㰀㼀瀀栀瀀 攀挀栀漀 栀琀洀氀猀瀀攀挀椀愀氀挀栀愀爀猀⠀␀甀猀攀爀嬀✀甀猀攀爀渀愀洀攀✀崀⤀㬀 㼀㸀∀㸀ഀഀ
                                            <i class="fas fa-plus"></i> Add Investment਍                                        㰀⼀戀甀琀琀漀渀㸀ഀഀ
                                    </td>਍                                㰀⼀琀爀㸀ഀഀ
                            <?php਍                                紀ഀഀ
                            } else {਍                            㼀㸀ഀഀ
                                <tr>਍                                    㰀琀搀 挀漀氀猀瀀愀渀㴀∀㄀㔀∀ 挀氀愀猀猀㴀∀琀攀砀琀ⴀ挀攀渀琀攀爀∀㸀ഀഀ
                                        <?php if ($result === null): ?>਍                                            㰀搀椀瘀 挀氀愀猀猀㴀∀愀氀攀爀琀 愀氀攀爀琀ⴀ搀愀渀最攀爀 洀ⴀ㌀∀㸀ഀഀ
                                                <i class="fas fa-exclamation-circle me-2"></i>਍                                                䔀爀爀漀爀 爀攀琀爀椀攀瘀椀渀最 甀猀攀爀 搀愀琀愀⸀ 倀氀攀愀猀攀 挀栀攀挀欀 搀愀琀愀戀愀猀攀 挀漀渀渀攀挀琀椀漀渀⸀ഀഀ
                                            </div>਍                                        㰀㼀瀀栀瀀 攀氀猀攀㨀 㼀㸀ഀഀ
                                            <div class="alert alert-info m-3">਍                                                㰀椀 挀氀愀猀猀㴀∀昀愀猀 昀愀ⴀ椀渀昀漀ⴀ挀椀爀挀氀攀 洀攀ⴀ㈀∀㸀㰀⼀椀㸀ഀഀ
                                                No users found matching your criteria. Try adjusting your search.਍                                            㰀⼀搀椀瘀㸀ഀഀ
                                        <?php endif; ?>਍                                    㰀⼀琀搀㸀ഀഀ
                                </tr>਍                            㰀㼀瀀栀瀀ഀഀ
                            }਍                            㼀㸀ഀഀ
                        </tbody>਍                    㰀⼀琀愀戀氀攀㸀ഀഀ
                </div>਍                㰀㼀瀀栀瀀 攀渀搀椀昀㬀 㼀㸀ഀഀ
਍                㰀㼀瀀栀瀀 椀昀 ⠀椀猀猀攀琀⠀␀爀攀猀甀氀琀⤀ ☀☀ ␀爀攀猀甀氀琀 ℀㴀㴀 渀甀氀氀⤀㨀 㼀㸀ഀഀ
                <div class="pagination-container">਍                    㰀搀椀瘀㸀ഀഀ
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalUsers); ?> of <?php echo $totalUsers; ?> entries਍                    㰀⼀搀椀瘀㸀ഀഀ
                    <nav>਍                        㰀甀氀 挀氀愀猀猀㴀∀瀀愀最椀渀愀琀椀漀渀∀㸀ഀഀ
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">਍                                㰀愀 挀氀愀猀猀㴀∀瀀愀最攀ⴀ氀椀渀欀∀ 栀爀攀昀㴀∀㼀瀀愀最攀㴀㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀愀最攀 ⴀ ㄀㬀 㼀㸀☀攀渀琀爀椀攀猀㴀㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀攀爀倀愀最攀㬀 㼀㸀☀猀攀愀爀挀栀㴀㰀㼀瀀栀瀀 攀挀栀漀 甀爀氀攀渀挀漀搀攀⠀␀猀攀愀爀挀栀⤀㬀 㼀㸀∀㸀倀爀攀瘀椀漀甀猀㰀⼀愀㸀ഀഀ
                            </li>਍ഀഀ
                            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>਍                                㰀氀椀 挀氀愀猀猀㴀∀瀀愀最攀ⴀ椀琀攀洀 㰀㼀瀀栀瀀 攀挀栀漀 ␀椀 㴀㴀 ␀瀀愀最攀 㼀 ✀愀挀琀椀瘀攀✀ 㨀 ✀✀㬀 㼀㸀∀㸀ഀഀ
                                    <a class="page-link" href="?page=<?php echo $i; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>਍                                㰀⼀氀椀㸀ഀഀ
                            <?php endfor; ?>਍ഀഀ
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">਍                                㰀愀 挀氀愀猀猀㴀∀瀀愀最攀ⴀ氀椀渀欀∀ 栀爀攀昀㴀∀㼀瀀愀最攀㴀㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀愀最攀 ⬀ ㄀㬀 㼀㸀☀攀渀琀爀椀攀猀㴀㰀㼀瀀栀瀀 攀挀栀漀 ␀瀀攀爀倀愀最攀㬀 㼀㸀☀猀攀愀爀挀栀㴀㰀㼀瀀栀瀀 攀挀栀漀 甀爀氀攀渀挀漀搀攀⠀␀猀攀愀爀挀栀⤀㬀 㼀㸀∀㸀一攀砀琀㰀⼀愀㸀ഀഀ
                            </li>਍                        㰀⼀甀氀㸀ഀഀ
                    </nav>਍                㰀⼀搀椀瘀㸀ഀഀ
                <?php endif; ?>਍ഀഀ
            </div>਍        㰀⼀搀椀瘀㸀ഀഀ
    </div>਍ഀഀ
    <!-- Add Investment Modal -->਍    㰀搀椀瘀 挀氀愀猀猀㴀∀洀漀搀愀氀 昀愀搀攀∀ 椀搀㴀∀愀搀搀䤀渀瘀攀猀琀洀攀渀琀䴀漀搀愀氀∀ 琀愀戀椀渀搀攀砀㴀∀ⴀ㄀∀㸀ഀഀ
        <div class="modal-dialog">਍            㰀搀椀瘀 挀氀愀猀猀㴀∀洀漀搀愀氀ⴀ挀漀渀琀攀渀琀∀㸀ഀഀ
                <div class="modal-header">਍                    㰀栀㔀 挀氀愀猀猀㴀∀洀漀搀愀氀ⴀ琀椀琀氀攀∀㸀䄀搀搀 䤀渀瘀攀猀琀洀攀渀琀 昀漀爀 㰀猀瀀愀渀 椀搀㴀∀洀漀搀愀氀ⴀ甀猀攀爀渀愀洀攀∀㸀㰀⼀猀瀀愀渀㸀㰀⼀栀㔀㸀ഀഀ
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>਍                㰀⼀搀椀瘀㸀ഀഀ
                <form id="addInvestmentForm">਍                    㰀搀椀瘀 挀氀愀猀猀㴀∀洀漀搀愀氀ⴀ戀漀搀礀∀㸀ഀഀ
                        <input type="hidden" name="user_id" id="investment-user-id">਍ഀഀ
                        <div class="form-group mb-3">਍                            㰀氀愀戀攀氀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ氀愀戀攀氀∀㸀䤀渀瘀攀猀琀洀攀渀琀 䄀洀漀甀渀琀㰀⼀氀愀戀攀氀㸀ഀഀ
                            <div class="input-group">਍                                㰀猀瀀愀渀 挀氀愀猀猀㴀∀椀渀瀀甀琀ⴀ最爀漀甀瀀ⴀ琀攀砀琀∀㸀␀㰀⼀猀瀀愀渀㸀ഀഀ
                                <input type="number" class="form-control" name="amount" min="1" step="0.01" required>਍                            㰀⼀搀椀瘀㸀ഀഀ
                        </div>਍ഀഀ
                        <div class="form-group mb-3">਍                            㰀氀愀戀攀氀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ氀愀戀攀氀∀㸀䤀渀瘀攀猀琀洀攀渀琀 倀氀愀渀㰀⼀氀愀戀攀氀㸀ഀഀ
                            <select class="form-select" name="plan" required>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀∀㸀匀攀氀攀挀琀 倀氀愀渀㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="Starter Pack">Starter Pack</option>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀匀椀氀瘀攀爀 倀氀愀渀∀㸀匀椀氀瘀攀爀 倀氀愀渀㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="Gold Plan">Gold Plan</option>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀倀爀攀洀椀甀洀 倀氀愀渀∀㸀倀爀攀洀椀甀洀 倀氀愀渀㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="VIP Plan">VIP Plan</option>਍                            㰀⼀猀攀氀攀挀琀㸀ഀഀ
                        </div>਍ഀഀ
                        <div class="form-group mb-3">਍                            㰀氀愀戀攀氀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ氀愀戀攀氀∀㸀倀爀漀昀椀琀 刀愀琀攀 ⠀─⤀㰀⼀氀愀戀攀氀㸀ഀഀ
                            <input type="number" class="form-control" name="profit_rate" min="0.1" step="0.1" required>਍                        㰀⼀搀椀瘀㸀ഀഀ
਍                        㰀搀椀瘀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ最爀漀甀瀀 洀戀ⴀ㌀∀㸀ഀഀ
                            <label class="form-label">Duration (days)</label>਍                            㰀椀渀瀀甀琀 琀礀瀀攀㴀∀渀甀洀戀攀爀∀ 挀氀愀猀猀㴀∀昀漀爀洀ⴀ挀漀渀琀爀漀氀∀ 渀愀洀攀㴀∀搀甀爀愀琀椀漀渀∀ 洀椀渀㴀∀㄀∀ 爀攀焀甀椀爀攀搀㸀ഀഀ
                        </div>਍ഀഀ
                        <div class="form-group mb-3">਍                            㰀氀愀戀攀氀 挀氀愀猀猀㴀∀昀漀爀洀ⴀ氀愀戀攀氀∀㸀匀琀愀琀甀猀㰀⼀氀愀戀攀氀㸀ഀഀ
                            <select class="form-select" name="status" required>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀瀀攀渀搀椀渀最∀㸀倀攀渀搀椀渀最㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="active" selected>Active</option>਍                                㰀漀瀀琀椀漀渀 瘀愀氀甀攀㴀∀挀漀洀瀀氀攀琀攀搀∀㸀䌀漀洀瀀氀攀琀攀搀㰀⼀漀瀀琀椀漀渀㸀ഀഀ
                                <option value="cancelled">Cancelled</option>਍                            㰀⼀猀攀氀攀挀琀㸀ഀഀ
                        </div>਍                    㰀⼀搀椀瘀㸀ഀഀ
                    <div class="modal-footer">਍                        㰀搀椀瘀 椀搀㴀∀椀渀瘀攀猀琀洀攀渀琀ⴀ昀漀爀洀ⴀ昀攀攀搀戀愀挀欀∀ 挀氀愀猀猀㴀∀搀ⴀ渀漀渀攀 愀氀攀爀琀 眀ⴀ㄀　　∀㸀㰀⼀搀椀瘀㸀ഀഀ
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>਍                        㰀戀甀琀琀漀渀 琀礀瀀攀㴀∀猀甀戀洀椀琀∀ 挀氀愀猀猀㴀∀戀琀渀 戀琀渀ⴀ瀀爀椀洀愀爀礀∀㸀䄀搀搀 䤀渀瘀攀猀琀洀攀渀琀㰀⼀戀甀琀琀漀渀㸀ഀഀ
                    </div>਍                㰀⼀昀漀爀洀㸀ഀഀ
            </div>਍        㰀⼀搀椀瘀㸀ഀഀ
    </div>਍ഀഀ
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>਍    㰀猀挀爀椀瀀琀 猀爀挀㴀∀栀琀琀瀀猀㨀⼀⼀挀搀渀⸀樀猀搀攀氀椀瘀爀⸀渀攀琀⼀渀瀀洀⼀戀漀漀琀猀琀爀愀瀀䀀㔀⸀㄀⸀㌀⼀搀椀猀琀⼀樀猀⼀戀漀漀琀猀琀爀愀瀀⸀戀甀渀搀氀攀⸀洀椀渀⸀樀猀∀㸀㰀⼀猀挀爀椀瀀琀㸀ഀഀ
    <script>਍        ␀⠀搀漀挀甀洀攀渀琀⤀⸀爀攀愀搀礀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
            // Show global success/error notification਍            昀甀渀挀琀椀漀渀 猀栀漀眀一漀琀椀昀椀挀愀琀椀漀渀⠀洀攀猀猀愀最攀Ⰰ 琀礀瀀攀⤀ 笀ഀഀ
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';਍                挀漀渀猀琀 愀氀攀爀琀 㴀 ␀⠀怀㰀搀椀瘀 挀氀愀猀猀㴀∀愀氀攀爀琀 ␀笀愀氀攀爀琀䌀氀愀猀猀紀 愀氀攀爀琀ⴀ搀椀猀洀椀猀猀椀戀氀攀 昀愀搀攀 猀栀漀眀∀ 爀漀氀攀㴀∀愀氀攀爀琀∀㸀ഀഀ
                    ${message}਍                    㰀戀甀琀琀漀渀 琀礀瀀攀㴀∀戀甀琀琀漀渀∀ 挀氀愀猀猀㴀∀戀琀渀ⴀ挀氀漀猀攀∀ 搀愀琀愀ⴀ戀猀ⴀ搀椀猀洀椀猀猀㴀∀愀氀攀爀琀∀ 愀爀椀愀ⴀ氀愀戀攀氀㴀∀䌀氀漀猀攀∀㸀㰀⼀戀甀琀琀漀渀㸀ഀഀ
                </div>`);਍ഀഀ
                $('.alert-container').append(alert);਍ഀഀ
                // Auto-dismiss after 3 seconds਍                猀攀琀吀椀洀攀漀甀琀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                    alert.alert('close');਍                紀Ⰰ ㌀　　　⤀㬀ഀഀ
            }਍ഀഀ
            // Make cell editable on click਍            ␀⠀✀⸀攀搀椀琀愀戀氀攀✀⤀⸀挀氀椀挀欀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                const cell = $(this);਍ഀഀ
                // Only make editable if not already editing਍                椀昀 ⠀℀挀攀氀氀⸀栀愀猀䌀氀愀猀猀⠀✀攀搀椀琀椀渀最✀⤀⤀ 笀ഀഀ
                    // Add editing class to cell਍                    挀攀氀氀⸀愀搀搀䌀氀愀猀猀⠀✀攀搀椀琀椀渀最✀⤀㬀ഀഀ
਍                    ⼀⼀ 䠀椀搀攀 搀椀猀瀀氀愀礀 瘀愀氀甀攀 愀渀搀 猀栀漀眀 椀渀瀀甀琀ഀഀ
                    cell.find('.display-value').hide();਍                    挀攀氀氀⸀昀椀渀搀⠀✀⸀攀搀椀琀ⴀ椀渀瀀甀琀✀⤀⸀猀栀漀眀⠀⤀⸀昀漀挀甀猀⠀⤀㬀ഀഀ
                }਍            紀⤀㬀ഀഀ
਍            ⼀⼀ 匀愀瘀攀 漀渀 戀氀甀爀ഀഀ
            $('.edit-input').blur(function() {਍                挀漀渀猀琀 椀渀瀀甀琀 㴀 ␀⠀琀栀椀猀⤀㬀ഀഀ
                const cell = input.closest('.editable');਍                挀漀渀猀琀 瘀愀氀甀攀 㴀 椀渀瀀甀琀⸀瘀愀氀⠀⤀㬀ഀഀ
                const column = cell.data('column');਍                挀漀渀猀琀 甀猀攀爀䤀搀 㴀 挀攀氀氀⸀搀愀琀愀⠀✀椀搀✀⤀㬀ഀഀ
਍                猀愀瘀攀䐀愀琀愀⠀挀攀氀氀Ⰰ 甀猀攀爀䤀搀Ⰰ 挀漀氀甀洀渀Ⰰ 瘀愀氀甀攀⤀㬀ഀഀ
            });਍ഀഀ
            // Save on enter key਍            ␀⠀✀⸀攀搀椀琀ⴀ椀渀瀀甀琀✀⤀⸀欀攀礀瀀爀攀猀猀⠀昀甀渀挀琀椀漀渀⠀攀⤀ 笀ഀഀ
                if (e.which === 13) {਍                    挀漀渀猀琀 椀渀瀀甀琀 㴀 ␀⠀琀栀椀猀⤀㬀ഀഀ
                    const cell = input.closest('.editable');਍                    挀漀渀猀琀 瘀愀氀甀攀 㴀 椀渀瀀甀琀⸀瘀愀氀⠀⤀㬀ഀഀ
                    const column = cell.data('column');਍                    挀漀渀猀琀 甀猀攀爀䤀搀 㴀 挀攀氀氀⸀搀愀琀愀⠀✀椀搀✀⤀㬀ഀഀ
਍                    猀愀瘀攀䐀愀琀愀⠀挀攀氀氀Ⰰ 甀猀攀爀䤀搀Ⰰ 挀漀氀甀洀渀Ⰰ 瘀愀氀甀攀⤀㬀ഀഀ
                }਍            紀⤀㬀ഀഀ
਍            ⼀⼀ 䘀甀渀挀琀椀漀渀 琀漀 猀愀瘀攀 搀愀琀愀 琀漀 猀攀爀瘀攀爀ഀഀ
            function saveData(cell, userId, column, value) {਍                ⼀⼀ 匀栀漀眀 氀漀愀搀椀渀最 椀渀搀椀挀愀琀漀爀ഀഀ
                const displayValue = cell.find('.display-value');਍                挀漀渀猀琀 椀渀瀀甀琀 㴀 挀攀氀氀⸀昀椀渀搀⠀✀⸀攀搀椀琀ⴀ椀渀瀀甀琀✀⤀㬀ഀഀ
਍                ␀⸀愀樀愀砀⠀笀ഀഀ
                    url: window.location.href,਍                    洀攀琀栀漀搀㨀 ✀倀伀匀吀✀Ⰰഀഀ
                    data: {਍                        愀挀琀椀漀渀㨀 ✀甀瀀搀愀琀攀开甀猀攀爀✀Ⰰഀഀ
                        user_id: userId,਍                        挀漀氀甀洀渀㨀 挀漀氀甀洀渀Ⰰഀഀ
                        value: value਍                    紀Ⰰഀഀ
                    success: function(response) {਍                        琀爀礀 笀ഀഀ
                            const data = JSON.parse(response);਍                            椀昀 ⠀搀愀琀愀⸀猀琀愀琀甀猀 㴀㴀㴀 ✀猀甀挀挀攀猀猀✀⤀ 笀ഀഀ
                                // Show success indicator਍                                搀椀猀瀀氀愀礀嘀愀氀甀攀⸀琀攀砀琀⠀搀愀琀愀⸀瘀愀氀甀攀⤀㬀ഀഀ
਍                                ⼀⼀ 匀栀漀眀 琀攀洀瀀漀爀愀爀礀 猀甀挀挀攀猀猀 洀攀猀猀愀最攀ഀഀ
                                const feedback = $('<span class="save-feedback bg-success text-white">Saved</span>');਍                                挀攀氀氀⸀愀瀀瀀攀渀搀⠀昀攀攀搀戀愀挀欀⤀㬀ഀഀ
                                setTimeout(function() {਍                                    昀攀攀搀戀愀挀欀⸀昀愀搀攀伀甀琀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                                        $(this).remove();਍                                    紀⤀㬀ഀഀ
                                }, 1000);਍                            紀 攀氀猀攀 笀ഀഀ
                                // Show error indicator਍                                挀漀渀猀琀 昀攀攀搀戀愀挀欀 㴀 ␀⠀✀㰀猀瀀愀渀 挀氀愀猀猀㴀∀猀愀瘀攀ⴀ昀攀攀搀戀愀挀欀 戀最ⴀ搀愀渀最攀爀 琀攀砀琀ⴀ眀栀椀琀攀∀㸀䔀爀爀漀爀㰀⼀猀瀀愀渀㸀✀⤀㬀ഀഀ
                                cell.append(feedback);਍                                猀攀琀吀椀洀攀漀甀琀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                                    feedback.fadeOut(function() {਍                                        ␀⠀琀栀椀猀⤀⸀爀攀洀漀瘀攀⠀⤀㬀ഀഀ
                                    });਍                                紀Ⰰ ㄀　　　⤀㬀ഀഀ
                                showNotification(data.message, 'error');਍                            紀ഀഀ
                        } catch (e) {਍                            挀漀渀猀漀氀攀⸀攀爀爀漀爀⠀✀䤀渀瘀愀氀椀搀 䨀匀伀一 爀攀猀瀀漀渀猀攀✀Ⰰ 攀⤀㬀ഀഀ
                            showNotification('Server returned an invalid response', 'error');਍                        紀ഀഀ
                    },਍                    攀爀爀漀爀㨀 昀甀渀挀琀椀漀渀⠀砀栀爀⤀ 笀ഀഀ
                        console.error('AJAX error', xhr);਍                        ⼀⼀ 匀栀漀眀 攀爀爀漀爀 椀渀搀椀挀愀琀漀爀ഀഀ
                        const feedback = $('<span class="save-feedback bg-danger text-white">Error</span>');਍                        挀攀氀氀⸀愀瀀瀀攀渀搀⠀昀攀攀搀戀愀挀欀⤀㬀ഀഀ
                        setTimeout(function() {਍                            昀攀攀搀戀愀挀欀⸀昀愀搀攀伀甀琀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                                $(this).remove();਍                            紀⤀㬀ഀഀ
                        }, 1000);਍                        猀栀漀眀一漀琀椀昀椀挀愀琀椀漀渀⠀✀䌀漀渀渀攀挀琀椀漀渀 攀爀爀漀爀 漀挀挀甀爀爀攀搀✀Ⰰ ✀攀爀爀漀爀✀⤀㬀ഀഀ
                    },਍                    挀漀洀瀀氀攀琀攀㨀 昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                        // End edit mode and reset display਍                        挀攀氀氀⸀爀攀洀漀瘀攀䌀氀愀猀猀⠀✀攀搀椀琀椀渀最✀⤀㬀ഀഀ
                        displayValue.show();਍                        椀渀瀀甀琀⸀栀椀搀攀⠀⤀㬀ഀഀ
                    }਍                紀⤀㬀ഀഀ
            }਍ഀഀ
            // Entries select change਍            ␀⠀✀⌀攀渀琀爀椀攀猀匀攀氀攀挀琀✀⤀⸀挀栀愀渀最攀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                const entries = $(this).val();਍                眀椀渀搀漀眀⸀氀漀挀愀琀椀漀渀⸀栀爀攀昀 㴀 ✀㼀瀀愀最攀㴀㄀☀攀渀琀爀椀攀猀㴀✀ ⬀ 攀渀琀爀椀攀猀 ⬀ ✀☀猀攀愀爀挀栀㴀㰀㼀瀀栀瀀 攀挀栀漀 甀爀氀攀渀挀漀搀攀⠀␀猀攀愀爀挀栀⤀㬀 㼀㸀✀㬀ഀഀ
            });਍ഀഀ
            // Show add investment modal਍            ␀⠀✀⸀愀搀搀ⴀ椀渀瘀攀猀琀洀攀渀琀✀⤀⸀挀氀椀挀欀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                const userId = $(this).data('user-id');਍                挀漀渀猀琀 甀猀攀爀渀愀洀攀 㴀 ␀⠀琀栀椀猀⤀⸀搀愀琀愀⠀✀甀猀攀爀渀愀洀攀✀⤀㬀ഀഀ
਍                ␀⠀✀⌀椀渀瘀攀猀琀洀攀渀琀ⴀ甀猀攀爀ⴀ椀搀✀⤀⸀瘀愀氀⠀甀猀攀爀䤀搀⤀㬀ഀഀ
                $('#modal-username').text(username);਍ഀഀ
                new bootstrap.Modal(document.getElementById('addInvestmentModal')).show();਍            紀⤀㬀ഀഀ
਍            ⼀⼀ 䠀愀渀搀氀攀 愀搀搀 椀渀瘀攀猀琀洀攀渀琀 昀漀爀洀 猀甀戀洀椀猀猀椀漀渀ഀഀ
            $('#addInvestmentForm').submit(function(e) {਍                攀⸀瀀爀攀瘀攀渀琀䐀攀昀愀甀氀琀⠀⤀㬀ഀഀ
                const formData = $(this).serialize();਍ഀഀ
                $.ajax({਍                    甀爀氀㨀 ✀愀搀搀开椀渀瘀攀猀琀洀攀渀琀⸀瀀栀瀀✀Ⰰഀഀ
                    method: 'POST',਍                    搀愀琀愀㨀 昀漀爀洀䐀愀琀愀Ⰰഀഀ
                    beforeSend: function() {਍                        ␀⠀✀⌀椀渀瘀攀猀琀洀攀渀琀ⴀ昀漀爀洀ⴀ昀攀攀搀戀愀挀欀✀⤀⸀愀搀搀䌀氀愀猀猀⠀✀搀ⴀ渀漀渀攀✀⤀⸀爀攀洀漀瘀攀䌀氀愀猀猀⠀✀愀氀攀爀琀ⴀ猀甀挀挀攀猀猀 愀氀攀爀琀ⴀ搀愀渀最攀爀✀⤀㬀ഀഀ
                    },਍                    猀甀挀挀攀猀猀㨀 昀甀渀挀琀椀漀渀⠀爀攀猀瀀漀渀猀攀⤀ 笀ഀഀ
                        try {਍                            挀漀渀猀琀 搀愀琀愀 㴀 䨀匀伀一⸀瀀愀爀猀攀⠀爀攀猀瀀漀渀猀攀⤀㬀ഀഀ
                            if (data.status === 'success') {਍                                ␀⠀✀⌀椀渀瘀攀猀琀洀攀渀琀ⴀ昀漀爀洀ⴀ昀攀攀搀戀愀挀欀✀⤀ഀഀ
                                    .removeClass('d-none alert-danger')਍                                    ⸀愀搀搀䌀氀愀猀猀⠀✀愀氀攀爀琀ⴀ猀甀挀挀攀猀猀✀⤀ഀഀ
                                    .text(data.message);਍ഀഀ
                                // Reset form਍                                猀攀琀吀椀洀攀漀甀琀⠀昀甀渀挀琀椀漀渀⠀⤀ 笀ഀഀ
                                    $('#addInvestmentModal').modal('hide');਍                                    眀椀渀搀漀眀⸀氀漀挀愀琀椀漀渀⸀爀攀氀漀愀搀⠀⤀㬀ഀഀ
                                }, 1500);਍                            紀 攀氀猀攀 笀ഀഀ
                                $('#investment-form-feedback')਍                                    ⸀爀攀洀漀瘀攀䌀氀愀猀猀⠀✀搀ⴀ渀漀渀攀 愀氀攀爀琀ⴀ猀甀挀挀攀猀猀✀⤀ഀഀ
                                    .addClass('alert-danger')਍                                    ⸀琀攀砀琀⠀搀愀琀愀⸀洀攀猀猀愀最攀⤀㬀ഀഀ
                            }਍                        紀 挀愀琀挀栀 ⠀攀⤀ 笀ഀഀ
                            $('#investment-form-feedback')਍                                ⸀爀攀洀漀瘀攀䌀氀愀猀猀⠀✀搀ⴀ渀漀渀攀 愀氀攀爀琀ⴀ猀甀挀挀攀猀猀✀⤀ഀഀ
                                .addClass('alert-danger')਍                                ⸀琀攀砀琀⠀✀䤀渀瘀愀氀椀搀 猀攀爀瘀攀爀 爀攀猀瀀漀渀猀攀✀⤀㬀ഀഀ
                        }਍                    紀Ⰰഀഀ
                    error: function() {਍                        ␀⠀✀⌀椀渀瘀攀猀琀洀攀渀琀ⴀ昀漀爀洀ⴀ昀攀攀搀戀愀挀欀✀⤀ഀഀ
                            .removeClass('d-none alert-success')਍                            ⸀愀搀搀䌀氀愀猀猀⠀✀愀氀攀爀琀ⴀ搀愀渀最攀爀✀⤀ഀഀ
                            .text('Server error occurred');਍                    紀ഀഀ
                });਍            紀⤀㬀ഀഀ
        });਍    㰀⼀猀挀爀椀瀀琀㸀ഀഀ
</body>਍㰀⼀栀琀洀氀㸀ഀഀ
