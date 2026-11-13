<?php
require_once '../includes/config.php';

role_required('Admin');

if (isset($_GET['toggle'])) {

    $userId = (int)$_GET['toggle'];

    $s = $pdo->prepare(
        'UPDATE users
         SET is_active = 1 - is_active
         WHERE id = ?'
    );

    $s->execute([$userId]);

    go('users.php');
}

if (isset($_GET['delete'])) {

    $userId = (int)$_GET['delete'];

    $s = $pdo->prepare(
        'DELETE FROM users
         WHERE id = ? AND role != \'Admin\''
    );

    $s->execute([$userId]);

    go('users.php');
}

if (isset($_GET['make_admin'])) {

    $userId = (int)$_GET['make_admin'];

    $s = $pdo->prepare(
        'UPDATE users
         SET role = \'Admin\'
         WHERE id = ?'
    );

    $s->execute([$userId]);

    go('users.php');
}


if (isset($_GET['make_attendee'])) {

    $userId = (int)$_GET['make_attendee'];

    $s = $pdo->prepare(
        'UPDATE users
         SET role = \'Attendee\'
         WHERE id = ?'
    );

    $s->execute([$userId]);

    go('users.php');
}

$rows = $pdo->query(
    'SELECT * FROM users
     ORDER BY created_at DESC'
)->fetchAll();

?>

<?php header_page('Users'); ?>

<div class="page-title">
    <h1>Manage Users</h1>
</div>

<div class="panel">

    <table>

        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($rows as $r): ?>

            <tr>

                <td>
                    <?= e($r['name']) ?>
                </td>

                <td>
                    <?= e($r['email']) ?>
                </td>

                <td>
                    <?= e($r['role']) ?>
                </td>

                <td>
                    <?= $r['is_active'] ? 'Active' : 'Restricted' ?>
                </td>

                <td>

                    <?php if ($r['role'] == 'Admin'): ?>

                        <!-- Change Admin to Attendee -->
                        <a
                            class="small"
                            href="?make_attendee=<?= $r['id'] ?>"
                            onclick="return confirm('Are you sure you want to change this Admin to Attendee?')"
                        >
                            Make Attendee
                        </a>

                    <?php else: ?>

                        <!-- Restrict / Activate -->
                        <a
                            class="small"
                            href="?toggle=<?= $r['id'] ?>"
                        >
                            <?= $r['is_active'] ? 'Restrict' : 'Activate' ?>
                        </a>

                        <!-- Make Admin -->
                        <a
                            class="small"
                            href="?make_admin=<?= $r['id'] ?>"
                            onclick="return confirm('Are you sure you want to make this user an Admin?')"
                        >
                            Make Admin
                        </a>

                        <!-- Delete -->
                        <a
                            class="small"
                            href="?delete=<?= $r['id'] ?>"
                            onclick="return confirm('Delete user?')"
                        >
                            Delete
                        </a>

                    <?php endif; ?>

                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</div>

<?php footer_page(); ?>

