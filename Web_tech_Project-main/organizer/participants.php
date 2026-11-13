<?php require_once '../includes/config.php';
role_required('Organizer');
$id = (int) ($_GET['event_id'] ?? 0);
$s = $pdo->prepare('SELECT * FROM events WHERE id=? AND organizer_id=?');
$s->execute([$id, uid()]);
$e = $s->fetch();
if (!$e)
    go('dashboard.php');
$s = $pdo->prepare('SELECT b.*,u.name,u.email FROM bookings b JOIN users u ON u.id=b.attendee_id WHERE b.event_id=? ORDER BY b.booked_at DESC');
$s->execute([$id]);
$rows = $s->fetchAll(); ?><?php header_page('Participants'); ?>
<div class="page-title">
    <h1><?= e($e['name']) ?> - Participants</h1>
</div>
<div class="panel">
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Tickets</th>
            <th>Total</th>
            <th>Code</th>
        </tr><?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['name']) ?></td>
                <td><?= e($r['email']) ?></td>
                <td><?= $r['ticket_quantity'] ?></td>
                <td>৳<?= $r['total_amount'] ?></td>
                <td><?= $r['booking_code'] ?></td>
            </tr><?php endforeach; ?>
    </table>
</div><?php footer_page(); ?>