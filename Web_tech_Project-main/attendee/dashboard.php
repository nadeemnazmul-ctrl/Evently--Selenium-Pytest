<?php

require_once '../includes/config.php';

role_required('Attendee');

$s = $pdo->prepare(
    'SELECT COUNT(*) c
     FROM bookings
     WHERE attendee_id = ?'
);

$s->execute([uid()]);

$b = $s->fetch()['c'];

$s = $pdo->prepare(
    'SELECT COUNT(*) c
     FROM notifications
     WHERE user_id = ?
     AND is_read = 0'
);

$s->execute([uid()]);

$n = $s->fetch()['c'];

$events = $pdo->query(
    "SELECT e.*, c.name cat
     FROM events e
     JOIN categories c ON c.id = e.category_id
     WHERE e.event_date >= CURDATE()
     AND e.status = 'Upcoming'
     ORDER BY e.event_date
     LIMIT 6"
)->fetchAll();

?>


<?php header_page('Attendee Dashboard'); ?>

<div class="page-title">

    <h1>
        Welcome, <?= e($_SESSION['user']['name']) ?>
    </h1>

    <a class="btn" href="events.php">
        Find Events
    </a>

</div>

<div class="stat-grid">

    <div class="stat">
      <a class="btn" href="bookings.php"> Bookings</a>
        <b><?= $b ?></b>
    </div>

    <div class="stat">
        <a class="btn" href="bookings.php"> Notifications</a>
        <b><?= $n ?></b>
    </div>

</div>

<div class="quick">

    <a href="events.php">
        Search Events
    </a>

    <a href="bookings.php">
        Booking History
    </a>

    <a href="profile.php">
        Profile
    </a>

</div>

<div class="panel">

    <h2>Upcoming Events</h2>

    <div class="grid">

        <?php foreach ($events as $e): ?>

            <div class="card">

                <span class="badge">
                    <?= e($e['cat']) ?>
                </span>

                <h3>
                    <?= e($e['name']) ?>
                </h3>

                <p>
                    <?= $e['event_date'] ?>
                    ·
                    <?= e($e['location']) ?>
                </p>

                <b>
                    ৳<?= $e['ticket_price'] ?>
                </b>

                <br>

                <a
                    class="small"
                    href="booking.php?id=<?= $e['id'] ?>"
                >
                    Book Ticket
                </a>

            </div>

        <?php endforeach; ?>

    </div>

</div>



<?php footer_page(); ?>
