<?php require_once '../includes/config.php';
role_required('Attendee');
$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['category'] ?? 0);
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$sql = "SELECT e.*,c.name cat FROM events e JOIN categories c ON c.id=e.category_id WHERE e.event_date>=CURDATE() AND e.status='Upcoming'";
$p = [];
if ($q) {
    $sql .= ' AND (e.name LIKE ? OR e.location LIKE ?)';
    $p = ["%$q%", "%$q%"];
}
if ($cat) {
    $sql .= ' AND e.category_id=?';
    $p[] = $cat;
}
$sql .= ' ORDER BY e.event_date';
$s = $pdo->prepare($sql);
$s->execute($p);
$rows = $s->fetchAll(); ?><?php header_page('Events'); ?><div class="page-title">
    <h1>Find Events</h1>
</div>
<form class="tools"><input name="q" value="<?= e($q) ?>" placeholder="Search name or location"><select name="category">
        <option value="0">All Categories</option><?php foreach ($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
    </select><button class="btn">Search</button></form>
<div class="grid"><?php foreach ($rows as $e): ?><div class="card">
            <div class="cover"><?= e($e['cat']) ?></div>
            <h3><?= e($e['name']) ?></h3>
            <p><?= $e['event_date'] ?> · <?= $e['event_time'] ?></p>
            <p><?= e($e['location']) ?></p>
            <p>Seats: <b><?= $e['available_seats'] ?></b></p><b>৳<?= $e['ticket_price'] ?></b><br><a class="small" href="booking.php?id=<?= $e['id'] ?>">View & Book</a>
        </div><?php endforeach; ?></div><?php footer_page(); ?>