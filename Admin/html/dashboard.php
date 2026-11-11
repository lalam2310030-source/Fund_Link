<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FundLink | Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="AdminLayout" id="overview">
        <aside class="Sidebar">
            <a class="Brand" href="index.php"><i class="fa-solid fa-handshake"></i> FundLink</a>
            <p class="SidebarLabel">ADMIN MENU</p>
            <nav>
                <a class="NavLink active" href="#overview"><i class="fa-solid fa-chart-pie"></i> Overview</a>
                <a class="NavLink" href="#users"><i class="fa-solid fa-users"></i> Users</a>
                <a class="NavLink" href="#requests"><i class="fa-solid fa-file-lines"></i> Loan requests</a>
                <a class="NavLink" href="../Dashboard/index.php"><i class="fa-solid fa-arrow-left"></i> User dashboard</a>
            </nav>
            <a class="LogoutLink" href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
        </aside>

        <main class="MainContent">
            <header class="PageHeader">
                <div>
                    <p class="Eyebrow">FUNDLINK / ADMIN</p>
                    <h1>Overview</h1>
                </div>
                <div class="AdminProfile">
                    <div class="ProfileIcon"><i class="fa-solid fa-user-shield"></i></div>
                    <div>
                        <strong><?php echo admin_escape($admin['full_name']); ?></strong>
                        <span>Administrator</span>
                    </div>
                </div>
            </header>

            <section class="StatsGrid" aria-label="Platform statistics">
                <article class="StatCard">
                    <div class="StatIcon green"><i class="fa-solid fa-users"></i></div>
                    <p>Registered users</p>
                    <h2><?php echo number_format((int) $user_count); ?></h2>
                </article>
                <article class="StatCard">
                    <div class="StatIcon blue"><i class="fa-solid fa-file-circle-check"></i></div>
                    <p>Open loan requests</p>
                    <h2><?php echo number_format((int) $request_count); ?></h2>
                </article>
                <article class="StatCard">
                    <div class="StatIcon orange"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <p>Active loans</p>
                    <h2><?php echo number_format((int) $loan_count); ?></h2>
                </article>
            </section>

            <section class="DataSection" id="requests">
                <div class="SectionHeading">
                    <div>
                        <p class="Eyebrow">PLATFORM ACTIVITY</p>
                        <h2>Recent loan requests</h2>
                    </div>
                    <a href="../Loan Marketplace/index.php">View marketplace <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                </div>
                <div class="TableWrap">
                    <table>
                        <thead>
                            <tr><th>Request</th><th>Borrower</th><th>Amount</th><th>Status</th><th>Created</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_requests): ?>
                                <?php foreach ($recent_requests as $request): ?>
                                    <tr>
                                        <td class="PrimaryCell"><?php echo admin_escape($request['title']); ?></td>
                                        <td><?php echo admin_escape($request['full_name']); ?></td>
                                        <td>৳<?php echo number_format((float) $request['amount']); ?></td>
                                        <td><span class="Status <?php echo $request['status'] === 'open' ? 'Open' : 'Other'; ?>"><?php echo admin_escape(ucfirst($request['status'])); ?></span></td>
                                        <td><?php echo admin_escape(date('M j, Y', strtotime($request['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td class="EmptyState" colspan="5">No loan requests yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="DataSection" id="users">
                <div class="SectionHeading">
                    <div>
                        <p class="Eyebrow">COMMUNITY</p>
                        <h2>Recently joined users</h2>
                    </div>
                    <span class="SectionNote">Latest 6 accounts</span>
                </div>
                <div class="TableWrap">
                    <table>
                        <thead>
                            <tr><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Joined</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_users): ?>
                                <?php foreach ($recent_users as $user): ?>
                                    <tr>
                                        <td class="PrimaryCell"><?php echo admin_escape($user['full_name']); ?></td>
                                        <td><?php echo admin_escape($user['username']); ?></td>
                                        <td><?php echo admin_escape($user['email']); ?></td>
                                        <td><span class="RoleTag"><?php echo admin_escape(ucfirst($user['role'])); ?></span></td>
                                        <td><?php echo admin_escape(date('M j, Y', strtotime($user['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td class="EmptyState" colspan="5">No accounts found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <footer class="PageFooter">FundLink Admin</footer>
        </main>
    </div>
</body>
</html>