<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../src/database.php';

$category_id = $_GET['category_id'] ?? 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_item'])) {
        $name = $_POST['name'];
        $category_id = $_POST['category_id'];

        $image = $_FILES['image'];
        $image_path = 'assets/numbers/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], '../' . $image_path);

        $audio_path = '';
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
            $audio = $_FILES['audio'];
            $audio_path = 'assets/numbers/' . basename($audio['name']);
            move_uploaded_file($audio['tmp_name'], '../' . $audio_path);
        }

        $stmt = $conn->prepare("INSERT INTO items (category_id, name, image, audio) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $category_id, $name, $image_path, $audio_path);
        $stmt->execute();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // First, get the file paths to delete the files
    $stmt = $conn->prepare("SELECT image, audio FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    if ($item) {
        if (file_exists('../' . $item['image'])) {
            unlink('../' . $item['image']);
        }
        if ($item['audio'] && file_exists('../' . $item['audio'])) {
            unlink('../' . $item['audio']);
        }
    }

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: manage_items.php?category_id=' . $category_id);
    exit;
}

// Fetch items for the category
$stmt = $conn->prepare("SELECT * FROM items WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Flashcard Items</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .manage-container { padding: 20px; }
        .item-list { list-style: none; padding: 0; }
        .item-list li { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .item-list img { width: 50px; margin-right: 15px; }
        .form-container { background-color: #f2f2f2; padding: 20px; border-radius: 5px; margin-top: 20px; }
        .form-container input[type=text], .form-container input[type=file] { width: 100%; padding: 12px; margin: 8px 0; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-container button { width: 100%; background-color: #4CAF50; color: white; padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <header>
        <h1>Manage Flashcard Items</h1>
    </header>
    <main class="manage-container">
        <a href="manage_flashcards.php">Back to Categories</a>
        <h2>Items in Category</h2>
        <ul class="item-list">
            <?php foreach ($items as $item): ?>
                <li>
                    <span><img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></span>
                    <span>
                        <a href="?category_id=<?= $category_id ?>&delete=<?= $item['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="form-container">
            <h3>Add New Item</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="category_id" value="<?= $category_id ?>">
                <label for="name">Item Name</label>
                <input type="text" id="name" name="name" required>
                <label for="image">Item Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <label for="audio">Item Audio</label>
                <input type="file" id="audio" name="audio" accept="audio/*">
                <button type="submit" name="add_item">Add Item</button>
            </form>
        </div>
    </main>
</body>
</html>
