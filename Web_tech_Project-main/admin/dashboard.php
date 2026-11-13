<?php require_once '../includes/config.php';
role_required('Admin');
$u = $pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'];
$e = $pdo->query('SELECT COUNT(*) c FROM events')->fetch()['c'];
$b = $pdo->query('SELECT COUNT(*) c FROM bookings')->fetch()['c'];
$r = $pdo->query("SELECT COALESCE(SUM(amount),0)c FROM payments WHERE status='Success'")->fetch()['c'];
$x = $pdo->query("SELECT e.*,c.name cat,u.name org FROM events e JOIN categories c ON c.id=e.category_id JOIN users u ON u.id=e.organizer_id ORDER BY e.id DESC LIMIT 8")->fetchAll(); ?><?php header_page('Admin Dashboard'); ?><div class="page-title">
    <h1>Admin Dashboard</h1>
</div>
<div class="stat-grid">
    <div class="stat">Users<b><?= $u ?></b></div>
    <div class="stat">Events<b><?= $e ?></b></div>
    <div class="stat">Bookings<b><?= $b ?></b></div>
    <div class="stat">Revenue<b>৳<?= number_format($r, 2) ?></b></div>
</div>
<div class="quick">
    <a href="users.php">Users</a>
    <a href="categories.php">Categories</a>
    <a href="events.php">Events</a>
    <!-- <a href="reports.php">Reports</a> -->
</div>
<div class="panel">
    <h2>Recent Events</h2>
    <table>
        <tr>
            <th>Event</th>
            <th>Organizer</th>
            <th>Category</th>
            <th>Seats</th>
        </tr><?php foreach ($x as $z): ?><tr>
                <td><?= e($z['name']) ?></td>
                <td><?= e($z['org']) ?></td>
                <td><?= e($z['cat']) ?></td>
                <td><?= $z['available_seats'] ?>/<?= $z['total_seats'] ?></td>
            </tr><?php endforeach; ?>
    </table>
</div><?php footer_page(); ?>