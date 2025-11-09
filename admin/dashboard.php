<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .dashboard-container {
            padding: 20px;
        }
        .dashboard-nav {
            margin-bottom: 20px;
        }
        .dashboard-nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main class="dashboard-container">
        <nav class="dashboard-nav">
            <a href="manage_flashcards.php">Manage Flashcards</a>
            <a href="manage_drag_and_drop.php">Manage Drag and Drop</a>
            <a href="manage_math_games.php">Manage Math Games</a>
            <a href="logout.php">Logout</a>
        </nav>
        <h2>Welcome, Admin!</h2>
        <p>Select a section to manage.</p>
    </main>
</body>
</html>