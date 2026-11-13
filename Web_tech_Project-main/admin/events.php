<?php

require_once '../includes/config.php';

role_required('Admin');

/*
|--------------------------------------------------------------------------
| Delete Event
|--------------------------------------------------------------------------
*/
if (isset($_GET['delete'])) {

    $eventId = (int) $_GET['delete'];

    $stmt = $pdo->prepare(
        'DELETE FROM events WHERE id = ?'
    );

    $stmt->execute([$eventId]);

    go('events.php');
}

/*
|--------------------------------------------------------------------------
| Get All Events
|--------------------------------------------------------------------------
|
| Fetch event information along with:
| - Category name
| - Organizer name
|
*/
$sql = "
    SELECT
        e.*,
        c.name AS cat,
        u.name AS org
    FROM events e
    JOIN categories c
        ON c.id = e.category_id
    JOIN users u
        ON u.id = e.organizer_id
    ORDER BY e.event_date
";

$rows = $pdo->query($sql)->fetchAll();

header_page('Events');

?>

<div class="page-title">
    <h1>All Events</h1>
</div>

<div class="panel">

    <table>

        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Category</th>
                <th>Organizer</th>
                <th>Seats</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>

                    <td>
                        <?= e($r['name']) ?>
                    </td>

                    <td>
                        <?= e($r['event_date']) ?>
                    </td>

                    <td>
                        <?= e($r['cat']) ?>
                    </td>

                    <td>
                        <?= e($r['org']) ?>
                    </td>

                    <td>
                        <?= (int) $r['available_seats'] ?>
                        /
                        <?= (int) $r['total_seats'] ?>
                    </td>

                    <td>
                        <a
                            class="small"
                            href="?delete=<?= (int) $r['id'] ?>"
                            onclick="return confirm('Delete event?')"
                        >
                            Delete
                        </a>
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php

footer_page();

?>

