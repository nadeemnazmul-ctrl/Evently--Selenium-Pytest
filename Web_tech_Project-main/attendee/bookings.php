<?php

require_once '../includes/config.php';

role_required('Attendee');

/*
|--------------------------------------------------------------------------
| Get Attendee Bookings
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT
        b.*,
        e.name AS event_name,
        e.event_date,
        e.location,
        p.transaction_id
    FROM bookings b
    JOIN events e
        ON e.id = b.event_id
    LEFT JOIN payments p
        ON p.booking_id = b.id
    WHERE b.attendee_id = ?
    ORDER BY b.booked_at DESC
");

$stmt->execute([uid()]);

$rows = $stmt->fetchAll();

/*
|--------------------------------------------------------------------------
| Mark Notifications as Read
|--------------------------------------------------------------------------
*/
if (isset($_GET['read'])) {

    $stmt = $pdo->prepare("
        UPDATE notifications
        SET is_read = 1
        WHERE user_id = ?
    ");

    $stmt->execute([uid()]);
}

$stmt = $pdo->prepare("
    SELECT *
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 10
");

$stmt->execute([uid()]);

$notes = $stmt->fetchAll();

header_page('Bookings');

?>

<div class="page-title">
    <h1>Booking History</h1>
</div>

<!-- Booking Success Message -->
<?php if (isset($_GET['success'])): ?>

    <div class="alert success">
        Booking successful!
        Code:
        <b><?= e($_GET['code']) ?></b>
    </div>

<?php endif; ?>


<!-- Booking History -->
<div class="panel">

    <table>

        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Tickets</th>
                <th>Total</th>
                <th>Code</th>
                <th>Transaction</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>

                    <td>
                        <?= e($r['event_name']) ?>
                    </td>

                    <td>
                        <?= e($r['event_date']) ?>
                    </td>

                    <td>
                        <?= (int) $r['ticket_quantity'] ?>
                    </td>

                    <td>
                        ৳<?= e($r['total_amount']) ?>
                    </td>

                    <td>
                        <?= e($r['booking_code']) ?>
                    </td>

                    <td>
                        <?= e($r['transaction_id'] ?? '') ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<div class="panel">

    <h2>Notifications</h2>

    <a
        class="small"
        href="?read=1"
    >
        Mark all read
    </a>

    <?php foreach ($notes as $n): ?>

        <div
            class="notification <?= $n['is_read'] ? '' : 'unread' ?>"
        >

            <b>
                <?= e($n['title']) ?>
            </b>

            <p>
                <?= e($n['message']) ?>
            </p>

        </div>

    <?php endforeach; ?>

</div>


<?php

footer_page();

?>

