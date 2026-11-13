<?php require_once 'includes/config.php';
$errors = [];
$ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'Attendee';
    $gender = $_POST['gender'] ?? 'Other';
    if (!$name)
        $errors[] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Valid email is required.';
    if (strlen($pass) < 6)
        $errors[] = 'Password must be at least 6 characters.';
    if ($pass !== $confirm)
        $errors[] = 'Passwords do not match.';
    if (!in_array($role, ['Organizer', 'Attendee'], true))
        $errors[] = 'Invalid role.';
    $s = $pdo->prepare('SELECT id FROM users WHERE email=?');
    $s->execute([$email]);
    if ($s->fetch())
        $errors[] = 'Email already registered.';
    if (!$errors) {
        $s = $pdo->prepare('INSERT INTO users(name,email,password,role,gender,is_verified,is_active) VALUES(?,?,?,?,?,1,1)');
        $s->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), $role, $gender]);
        $ok = 'Registration successful. You can now login.';
    }
} ?>
<?php header_page('Register', ''); ?>
<div class="auth">
    <div class="authbox wide"><small>JOIN EVENTLY</small>
        <h1>Create an account</h1><?php if ($errors): ?>
            <div class="alert error">
                <ul><?php foreach ($errors as $x): ?>
                        <li><?= e($x) ?></li><?php endforeach; ?>
                </ul>
            </div><?php endif; ?><?php if ($ok): ?>
            <div class="alert success"><?= e($ok) ?> <a href="login.php">Login</a></div><?php endif; ?>
        <form method="post" onsubmit="return validateRegister()">
            <div class="two"><label>Name<input id="registerName" name="name" required></label><label>Email<input
                        type="email" name="email" required></label></div>
            <div class="two"><label>Password<input id="registerPassword" type="password" name="password"
                        required></label><label>Confirm Password<input id="confirmPassword" type="password"
                        name="confirm_password" required></label></div>
            <div class="two"><label>Gender<select name="gender">
                        <option>Other</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select></label><label>Role<select name="role">
                        <option>Attendee</option>
                        <option>Organizer</option>
                    </select></label></div><label class="check"><input id="terms" type="checkbox" required> I agree to
                the terms.</label><button class="btn full">Create Account</button>
        </form>
        <p class="center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div><?php footer_page(''); ?>