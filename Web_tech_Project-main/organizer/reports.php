<?php require_once '../includes/config.php';
role_required('Organizer');
$s = $pdo->prepare("SELECT e.name,e.available_seats,COUNT(b.id) bookings,COALESCE(SUM(b.ticket_quantity),0) tickets,COALESCE(SUM(p.amount),0) revenue FROM events e LEFT JOIN bookings b ON b.event_id=e.id LEFT JOIN payments p ON p.booking_id=b.id AND p.status='Success' WHERE e.organizer_id=? GROUP BY e.id");
$s->execute([uid()]);
$rows = $s->fetchAll(); ?><?php header_page('Reports'); ?>
<div class="page-title">
    <h1>Event Reports</h1>
</div>
<div class="panel">
    <table>
        <tr>
            <th>Event</th>
            <th>Bookings</th>
            <th>Tickets</th>
            <th>Available</th>
            <th>Revenue</th>
        </tr><?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['name']) ?></td>
                <td><?= $r['bookings'] ?></td>
                <td><?= $r['tickets'] ?></td>
                <td><?= $r['available_seats'] ?></td>
                <td>৳<?= number_format($r['revenue'], 2) ?></td>
            </tr><?php endforeach; ?>
    </table>
</div><?php footer_page(); ?>