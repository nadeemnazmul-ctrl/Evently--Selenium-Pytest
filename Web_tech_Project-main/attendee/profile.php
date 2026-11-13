<?php require_once '../includes/config.php';
role_required('Attendee');
$s = $pdo->prepare('SELECT * FROM users WHERE id=?');
$s->execute([uid()]);
$u = $s->fetch();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $pass = $_POST['password'];
    if ($pass && strlen($pass) < 6) $msg = 'Password must be at least 6 characters.';
    else {
        $sql = 'UPDATE users SET name=?,gender=?';
        $p = [$name, $gender];
        if ($pass) {
            $sql .= ',password=?';
            $p[] = password_hash($pass, PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id=?';
        $p[] = uid();
        $pdo->prepare($sql)->execute($p);
        $_SESSION['user']['name'] = $name;
        $u['name'] = $name;
        $u['gender'] = $gender;
        $msg = 'Profile updated successfully.';
    }
} ?><?php header_page('Profile'); ?><div class="page-title">
    <h1>My Profile</h1>
</div>
<div class="panel">
    <form method="post"><label>Name<input name="name" value="<?= e($u['name']) ?>" required></label><label>Email<input value="<?= e($u['email']) ?>" disabled></label><label>Gender<select name="gender">
                <option <?= $u['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                <option <?= $u['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $u['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            </select></label><label>New Password<input type="password" name="password"></label><button class="btn">Update Profile</button></form><?php if ($msg): ?><div class="alert success"><?= e($msg) ?></div><?php endif; ?>
</div><?php footer_page(); ?>