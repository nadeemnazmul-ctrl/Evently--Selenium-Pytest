<?php
require_once '../includes/config.php';
role_required('Attendee');
$id = (int) ($_GET['id'] ?? 0);
$s = $pdo->prepare(
    'SELECT e.*, c.name cat
     FROM events e
     JOIN categories c ON c.id = e.category_id
     WHERE e.id = ?'
);

$s->execute([$id]);

$e = $s->fetch();

if (!$e) {
    go('events.php');
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $q = (int) $_POST['quantity'];


    if ($q < 1 || $q > $e['available_seats']) {

        $err = 'Invalid ticket quantity or not enough seats.';

    } else {

        try {

            $pdo->beginTransaction();

            $s = $pdo->prepare(
                'SELECT *
                 FROM events
                 WHERE id = ?
                 FOR UPDATE'
            );

            $s->execute([$id]);

            $fresh = $s->fetch();

            if ($q > $fresh['available_seats']) {
                throw new Exception('Seats just sold out.');
            }

            $total = $q * $fresh['ticket_price'];

            $code =
                'EVT' .
                date('ymd') .
                strtoupper(bin2hex(random_bytes(3)));

            $s = $pdo->prepare(
                "INSERT INTO bookings
                (
                    event_id,
                    attendee_id,
                    ticket_quantity,
                    total_amount,
                    booking_code,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'Confirmed'
                )"
            );

            $s->execute([
                $id,
                uid(),
                $q,
                $total,
                $code
            ]);

            $bid = $pdo->lastInsertId();

            $tx =
                'TXN' .
                date('YmdHis') .
                random_int(100, 999);

            $s = $pdo->prepare(
                "INSERT INTO payments
                (
                    booking_id,
                    amount,
                    method,
                    transaction_id,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    'Success'
                )"
            );

            $s->execute([
                $bid,
                $total,
                'Demo Payment',
                $tx
            ]);


            $s = $pdo->prepare(
                'UPDATE events
                 SET available_seats = available_seats - ?
                 WHERE id = ?'
            );

            $s->execute([
                $q,
                $id
            ]);


            notify(
                $pdo,
                uid(),
                'Booking Confirmed',
                'Your booking for ' .
                $e['name'] .
                ' is confirmed. Code: ' .
                $code
            );


            notify(
                $pdo,
                $e['organizer_id'],
                'New Booking',
                'A new booking was made for ' .
                $e['name'] .
                '.'
            );


            $pdo->commit();


            go(
                'bookings.php?success=1&code=' .
                urlencode($code)
            );

        } catch (Exception $x) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $err = $x->getMessage();
        }
    }
}

?>

<?php header_page('Booking'); ?>

<div class="page-title">
    <h1>Book Ticket</h1>
</div>

<div class="booking-layout">

    <div class="panel">

        <span class="badge">
            <?= e($e['cat']) ?>
        </span>

        <h2>
            <?= e($e['name']) ?>
        </h2>

        <p>
            <?= e($e['description']) ?>
        </p>

        <p>
            <b>Date:</b>
            <?= $e['event_date'] ?>

            <b>Time:</b>
            <?= $e['event_time'] ?>
        </p>

        <p>
            <b>Location:</b>
            <?= e($e['location']) ?>
        </p>

        <p>
            <b>Available:</b>
            <?= $e['available_seats'] ?>
        </p>

    </div>

    <div class="panel">

        <?php if ($err): ?>

            <div class="alert error">
                <?= e($err) ?>
            </div>

        <?php endif; ?>


        <form
            method="post"
            oninput="updateTotal()"
        >

            <label>
                Quantity

                <input
                    id="quantity"
                    name="quantity"
                    type="number"
                    min="1"
                    max="<?= $e['available_seats'] ?>"
                    value="1"
                >
            </label>

            <input
                id="unitPrice"
                type="hidden"
                value="<?= $e['ticket_price'] ?>"
            >

            <p>
                Total:
                <b id="totalPrice">
                    ৳<?= $e['ticket_price'] ?>
                </b>
            </p>
            <!-- Submit Button -->
            <button class="btn full">Confirm & Pay </button>
        </form>

    </div>

</div>

<?php footer_page(); ?>
