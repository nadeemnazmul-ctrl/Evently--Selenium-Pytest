<?php

require_once '../includes/config.php';

role_required('Admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name !== '') {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO categories (name, description) VALUES (?, ?)'
            );

            $stmt->execute([
                $name,
                $description
            ]);
        } catch (Exception $e) {
            // Ignore database error
        }
    }
}


if (isset($_GET['delete'])) {

    $categoryId = (int) $_GET['delete'];

    try {
        $stmt = $pdo->prepare(
            'DELETE FROM categories WHERE id = ?'
        );

        $stmt->execute([$categoryId]);
    } catch (Exception $e) {
        // Ignore database error
    }
}


$sql = "
    SELECT
        c.*,
        COUNT(e.id) AS n
    FROM categories c
    LEFT JOIN events e
        ON e.category_id = c.id
    GROUP BY c.id
    ORDER BY c.name
";

$rows = $pdo->query($sql)->fetchAll();


header_page('Categories');

?>

<div class="page-title">
    <h1>Categories</h1>
</div>

<div class="two">

    <!-- Add Category -->
    <div class="panel">

        <h2>Add Category</h2>

        <form method="post">

            <label>
                Name
                <input
                    type="text"
                    name="name"
                    required>
            </label>
                <br>
            <label>
                Description
                <input
                    type="text"
                    name="description">
            </label>
                <br>
            <button type="submit" class="btn">
                Add
            </button>

        </form>

    </div>

    <!-- Category List -->
    <div class="panel">

        <table>

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Events</th>
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
                            <?= (int) $r['n'] ?>
                        </td>

                        <td>
                            <a
                                class="small"
                                href="?delete=<?= (int) $r['id'] ?>"
                                onclick="return confirm('Delete category?')">
                                Delete
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

footer_page();

?>