<?php require_once '../includes/config.php';
role_required('Organizer');
$cats = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$err = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $loc = trim($_POST['location']);
    $desc = trim($_POST['description']);
    $cat = (int) $_POST['category'];
    $type = trim($_POST['type']);
    $price = (float) $_POST['price'];
    $seats = (int) $_POST['seats'];
    if (!$name || !$date || !$time || !$loc || !$desc || $seats < 1)
        $err = 'Please complete all required fields.';
    else {
        $s = $pdo->prepare('INSERT INTO events(organizer_id,category_id,name,event_date,event_time,location,description,ticket_type,ticket_price,total_seats,available_seats) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $s->execute([uid(), $cat, $name, $date, $time, $loc, $desc, $type, $price, $seats, $seats]);
        go('dashboard.php');
    }
} ?><?php header_page('Create Event'); ?>
<div class="page-title">
    <h1>Create Event</h1>
</div>
<div class="panel"><?php if ($err): ?>
        <div class="alert error"><?= e($err) ?></div><?php endif; ?>
    <form method="post">
        <div class="two"><label>Event Name<input name="name" required></label><label>Category<select
                    name="category"><?php foreach ($cats as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?>
                </select></label></div>
        <div class="two"><label>Date<input type="date" name="date" required></label><label>Time<input type="time"
                    name="time" required></label></div><label>Location<input name="location" required></label>
        <div class="three"><label>Ticket Type<input name="type" value="General"></label><label>Price<input type="number"
                    step="0.01" name="price" value="0"></label><label>Tickets<input type="number" name="seats" min="1"
                    required></label></div><label>Description<textarea name="description" rows="6"
                required></textarea><button class="btn">Create Event</button>
    </form>
</div><?php footer_page(); ?>