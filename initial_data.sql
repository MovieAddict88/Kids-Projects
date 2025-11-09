INSERT INTO `categories` (`id`, `name`, `icon`, `type`) VALUES
(1, 'Numbers', 'assets/icon/Numbers.png', 'flashcard'),
(2, 'Alphabets', 'assets/icon/Alphabets.png', 'flashcard'),
(3, 'Animals', 'assets/icon/Animals.png', 'flashcard');

INSERT INTO `items` (`id`, `category_id`, `name`, `image`, `audio`) VALUES
(1, 1, 'One', 'assets/numbers/Numbers_1.png', 'assets/numbers/Numbers_1.mp3'),
(2, 1, 'Two', 'assets/numbers/Numbers_2.png', 'assets/numbers/Numbers_2.mp3'),
(3, 1, 'Three', 'assets/numbers/Numbers_3.png', 'assets/numbers/Numbers_3.mp3'),
(4, 1, 'Four', 'assets/numbers/Numbers_4.png', 'assets/numbers/Numbers_4.mp3'),
(5, 1, 'Five', 'assets/numbers/Numbers_5.png', 'assets/numbers/Numbers_5.mp3'),
(6, 1, 'Six', 'assets/numbers/Numbers_6.png', 'assets/numbers/Numbers_6.mp3'),
(7, 1, 'Seven', 'assets/numbers/Numbers_7.png', 'assets/numbers/Numbers_7.mp3'),
(8, 1, 'Eight', 'assets/numbers/Numbers_8.png', 'assets/numbers/Numbers_8.mp3'),
(9, 1, 'Nine', 'assets/numbers/Numbers_9.png', 'assets/numbers/Numbers_9.mp3'),
(10, 1, 'Ten', 'assets/numbers/Numbers_10.png', 'assets/numbers/Numbers_10.mp3');
