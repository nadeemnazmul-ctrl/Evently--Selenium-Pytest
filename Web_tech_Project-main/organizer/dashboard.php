<?php require_once '../includes/config.php';
role_required('Organizer');
$id = uid();
$s = $pdo->prepare('SELECT * FROM events WHERE organizer_id=? ORDER BY event_date');
$s->execute([$id]);
$rows = $s->fetchAll();
$s = $pdo->prepare('SELECT COUNT(*) c FROM bookings b JOIN events e ON e.id=b.event_id WHERE e.organizer_id=?');
$s->execute([$id]);
$b = $s->fetch()['c'];
$s = $pdo->prepare("SELECT COALESCE(SUM(p.amount),0)c FROM payments p JOIN bookings b ON b.id=p.booking_id JOIN events e ON e.id=b.event_id WHERE e.organizer_id=? AND p.status='Success'");
$s->execute([$id]);
$rev = $s->fetch()['c']; ?><?php header_page('Organizer Dashboard'); ?>
<div class="page-title">
    <h1>Organizer Dashboard</h1><a class="btn" href="create-event.php">+ Create Event</a>
</div>
<div class="stat-grid">
    <div class="stat">My Events<b><?= count($rows) ?></b></div>
    <div class="stat">Bookings<b><?= $b ?></b></div>
    <div class="stat">Revenue<b>৳<?= number_format($rev, 2) ?></b></div>
</div>
<div class="quick"><a href="schedule.php">Schedule</a><a href="reports.php">Reports</a></div>
<div class="panel">
    <table>
        <tr>
            <th>Event</th>
            <th>Date</th>
            <th>Seats</th>
            <th>Actions</th>
        </tr><?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['name']) ?></td>
                <td><?= $r['event_date'] ?></td>
                <td><?= $r['available_seats'] ?>/<?= $r['total_seats'] ?></td>
                <td><a class="small" href="edit-event.php?id=<?= $r['id'] ?>">Edit</a> <a class="small"
                        href="participants.php?event_id=<?= $r['id'] ?>">Participants</a> <a class="small"
                        href="edit-event.php?delete=<?= $r['id'] ?>" onclick="return confirm('Delete event?')">Delete</a></td>
            </tr><?php endforeach; ?>
    </table>
</div><?php footer_page(); ?>