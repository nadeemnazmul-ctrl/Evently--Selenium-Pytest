<?php require_once '../includes/config.php';
role_required('Organizer');
$id = (int) ($_GET['id'] ?? $_GET['delete'] ?? 0);
if (isset($_GET['delete'])) {
    $s = $pdo->prepare('DELETE FROM events WHERE id=? AND organizer_id=?');
    $s->execute([$id, uid()]);
    go('dashboard.php');
}
$s = $pdo->prepare('SELECT * FROM events WHERE id=? AND organizer_id=?');
$s->execute([$id, uid()]);
$e = $s->fetch();
if (!$e)
    go('dashboard.php');
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new = (int) $_POST['seats'];
    $booked = $e['total_seats'] - $e['available_seats'];
    $available = max(0, $new - $booked);
    $s = $pdo->prepare('UPDATE events SET category_id=?,name=?,event_date=?,event_time=?,location=?,description=?,ticket_type=?,ticket_price=?,total_seats=?,available_seats=? WHERE id=? AND organizer_id=?');
    $s->execute([(int) $_POST['category'], trim($_POST['name']), $_POST['date'], $_POST['time'], trim($_POST['location']), trim($_POST['description']), trim($_POST['type']), (float) $_POST['price'], $new, $available, $id, uid()]);
    go('dashboard.php');
} ?><?php header_page('Edit Event'); ?>
<div class="page-title">
    <h1>Edit Event</h1>
</div>
<div class="panel">
    <form method="post">
        <div class="two"><label>Name<input name="name" value="<?= e($e['name']) ?>"></label><label>Category<select
                    name="category"><?php foreach ($cats as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $e['category_id'] ? 'selected' : '' ?>><?= e($c['name']) ?>
                        </option><?php endforeach; ?>
                </select></label></div>
        <div class="two"><label>Date<input type="date" name="date"
                    value="<?= $e['event_date'] ?>"></label><label>Time<input type="time" name="time"
                    value="<?= substr($e['event_time'], 0, 5) ?>"></label></div><label>Location<input name="location"
                value="<?= e($e['location']) ?>"></label>
        <div class="three"><label>Ticket Type<input name="type"
                    value="<?= e($e['ticket_type']) ?>"></label><label>Price<input name="price"
                    value="<?= $e['ticket_price'] ?>"></label><label>Total Tickets<input name="seats"
                    value="<?= $e['total_seats'] ?>"></label></div><label>Description<textarea name="description"
                rows="6"><?= e($e['description']) ?></textarea><button class="btn">Save Changes</button>
    </form>
</div><?php footer_page(); ?>