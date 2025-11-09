<?php
require_once '../src/database.php';

$category_id = $_GET['category_id'] ?? 0;

if ($category_id) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM items WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle case where no category is selected
    $category = ['name' => 'Unknown'];
    $items = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category['name']) ?> Flashcards</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .slider-container {
            width: 80%;
            max-width: 600px;
            margin: 50px auto;
            position: relative;
            text-align: center;
        }
        .flashcard {
            cursor: pointer;
            width: 100%;
        }
        .flashcard img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        .controls {
            margin-top: 20px;
        }
        .controls button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 10px;
        }
        .card-name {
            font-size: 2em;
            margin-top: 15px;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($category['name']) ?> Flashcards</h1>
    </header>
    <main>
        <div class="slider-container">
            <div class="flashcard" id="flashcard">
                <img id="flashcardImage" src="" alt="Flashcard">
            </div>
            <div class="card-name" id="cardName"></div>
            <div class="controls">
                <button id="prevBtn">Previous</button>
                <button id="nextBtn">Next</button>
            </div>
            <audio id="flashcardAudio" src=""></audio>
        </div>
    </main>

    <script>
        const flashcards = <?= json_encode($items) ?>;
        let currentCard = 0;

        const flashcardImage = document.getElementById('flashcardImage');
        const cardName = document.getElementById('cardName');
        const flashcardAudio = document.getElementById('flashcardAudio');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const flashcardDiv = document.getElementById('flashcard');

        function showCard() {
            if (flashcards.length > 0) {
                flashcardImage.src = '../' + flashcards[currentCard].image;
                cardName.textContent = flashcards[currentCard].name;
                flashcardAudio.src = '../' + flashcards[currentCard].audio;
                flashcardAudio.play();
            }
        }

        flashcardDiv.addEventListener('click', () => {
            if (flashcards.length > 0) {
                flashcardAudio.play();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (flashcards.length > 0) {
                currentCard = (currentCard - 1 + flashcards.length) % flashcards.length;
                showCard();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (flashcards.length > 0) {
                currentCard = (currentCard + 1) % flashcards.length;
                showCard();
            }
        });

        // Show the first card initially
        showCard();
    </script>
</body>
</html>
