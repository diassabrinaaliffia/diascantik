<?php
// login.php
require_once 'koneksi.php';
require_once 'helpers.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, fullname FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'] ?? $username;
            header('Location: index.php');
            exit;
        }
    }
    $error = "Username atau password salah.";
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Username</label><br>
            <input type="text" name="username" required><br>
            <label>Password</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>