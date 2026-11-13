<?php require_once '../includes/config.php';
role_required('Organizer');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s = $pdo->prepare('INSERT INTO schedules(event_id,title,schedule_time,description) SELECT ?,?,?,? FROM events WHERE id=? AND organizer_id=?');
    $s->execute([(int) $_POST['event_id'], trim($_POST['title']), $_POST['time'], trim($_POST['description']), (int) $_POST['event_id'], uid()]);
}
if (isset($_GET['delete'])) {
    $s = $pdo->prepare('DELETE s FROM schedules s JOIN events e ON e.id=s.event_id WHERE s.id=? AND e.organizer_id=?');
    $s->execute([(int) $_GET['delete'], uid()]);
}
$s = $pdo->prepare('SELECT * FROM events WHERE organizer_id=?');
$s->execute([uid()]);
$events = $s->fetchAll();
$s = $pdo->prepare('SELECT s.*,e.name event_name FROM schedules s JOIN events e ON e.id=s.event_id WHERE e.organizer_id=? ORDER BY e.event_date,s.schedule_time');
$s->execute([uid()]);
$rows = $s->fetchAll(); ?><?php header_page('Schedule'); ?>
<div class="page-title">
    <h1>Event Schedule</h1>
</div>
<div class="two">
    <div class="panel">
        <form method="post"><label>Event<select name="event_id"><?php foreach ($events as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= e($e['name']) ?></option><?php endforeach; ?>
                </select></label><label>Title<input name="title" required></label><label>Time<input type="time"
                    name="time" required></label><label>Description<input name="description"></label><button
                class="btn">Add Item</button></form>
    </div>
    <div class="panel">
        <table>
            <tr>
                <th>Event</th>
                <th>Time</th>
                <th>Title</th>
                <th>Action</th>
            </tr><?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= e($r['event_name']) ?></td>
                    <td><?= substr($r['schedule_time'], 0, 5) ?></td>
                    <td><?= e($r['title']) ?></td>
                    <td><a class="small" href="?delete=<?= $r['id'] ?>">Delete</a></td>
                </tr><?php endforeach; ?>
        </table>
    </div>
</div><?php footer_page(); ?>