<?php
require_once '../src/database.php';

$result = $conn->query("SELECT * FROM categories WHERE type = 'flashcard'");
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Flashcards</h1>
    </header>
    <main>
        <div class="grid-container">
            <?php foreach ($categories as $category): ?>
                <a href="flashcard_viewer.php?category_id=<?= $category['id'] ?>" class="grid-item">
                    <img src="<?= htmlspecialchars($category['icon']) ?>" alt="<?= htmlspecialchars($category['name']) ?>">
                    <h2><?= htmlspecialchars($category['name']) ?></h2>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
