<?php

session_start();
require '../helpers/jwt_helper.php';

if (isset($_SESSION['jwt']) && decodeJWT($_SESSION['jwt'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="w-full max-w-md bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-semibold text-center mb-6">Login</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'access'): ?>
        <p style="color: red;">No access</p>
    <?php endif; ?>

    <form action="../handlers/login_handler.php" method="POST">
        <div class="mb-4">
            <label for="username" class="block text-sm font-semibold">Username</label>
            <input type="text" name="username" id="username" class="w-full p-2 mt-1 border rounded" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 mt-1 border rounded" required>
        </div>
        <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded">Login</button>
        <p class="mt-4 text-sm text-center">
            No account? <a href="register.php" class="text-blue-500">Register here</a>
        </p>
    </form>
</div>
</body>
</html>