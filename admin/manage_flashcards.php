<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../src/database.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = $_POST['name'];
        $icon = $_FILES['icon'];
        $icon_path = 'assets/icon/' . basename($icon['name']);
        move_uploaded_file($icon['tmp_name'], '../' . $icon_path);

        $stmt = $conn->prepare("INSERT INTO categories (name, icon, type) VALUES (?, ?, 'flashcard')");
        $stmt->bind_param("ss", $name, $icon_path);
        $stmt->execute();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // First, get the icon path to delete the file
    $stmt = $conn->prepare("SELECT icon FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    if ($category && file_exists('../' . $category['icon'])) {
        unlink('../' . $category['icon']);
    }

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: manage_flashcards.php');
    exit;
}

$result = $conn->query("SELECT * FROM categories WHERE type = 'flashcard'");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Flashcards</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .manage-container { padding: 20px; }
        .category-list { list-style: none; padding: 0; }
        .category-list li { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .category-list img { width: 50px; margin-right: 15px; }
        .form-container { background-color: #f2f2f2; padding: 20px; border-radius: 5px; margin-top: 20px; }
        .form-container input[type=text], .form-container input[type=file] { width: 100%; padding: 12px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-container button { width: 100%; background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <h1>Manage Flashcards</h1>
    </header>
    <main class="manage-container">
        <a href="dashboard.php">Back to Dashboard</a>
        <h2>Flashcard Categories</h2>
        <ul class="category-list">
            <?php foreach ($categories as $category): ?>
                <li>
                    <span><img src="../<?= htmlspecialchars($category['icon']) ?>" alt="<?= htmlspecialchars($category['name']) ?>"><?= htmlspecialchars($category['name']) ?></span>
                    <span>
                        <a href="manage_items.php?category_id=<?= $category['id'] ?>">Manage Items</a> |
                        <a href="?delete=<?= $category['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-container">
            <h3>Add New Category</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" required>
                <label for="icon">Category Icon</label>
                <input type="file" id="icon" name="icon" accept="image/*" required>
                <button type="submit" name="add_category">Add Category</button>
            </form>
        </div>
    </main>
</body>
</html>
