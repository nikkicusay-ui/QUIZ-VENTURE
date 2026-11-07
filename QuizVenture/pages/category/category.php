<?php
session_start();

if (!isset($_SESSION['player'])) {
    header("Location: ../player/ready.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();  
    session_destroy(); 
    header("Location: ../player/ready.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Category</title>
    <link rel="stylesheet" href="../../assets/css/category.css">
</head>
<body>
    <div class="game-container">
        <div class="background">
            <img src="../../assets/img/building3.png" class="building" alt="Building">
        </div>

        <div class="category-area">
            <h1>CHOOSE A CATEGORY</h1>
            <div class="category-box">
                <div class="category-row">
                    <a href="Sci&Tech.php" class="category-button">Science and Technology</a>
                    <a href="PopCul&Entertain.php" class="category-button">Pop Culture and Entertainment</a>
                </div>
                <div class="category-row">
                    <a href="Math&Logic.php" class="category-button">Math and Logic</a>
                    <a href="His&Geo.php" class="category-button">History and Geography</a>
                    </div>

            </div>
        </div>

        <form method="POST" class="exit-form">
            <input type="submit" name="logout" value="EXIT" class="exit-button">
        </form>

        <div class="foreground">
            <img src="../../assets/img/car.png" class="car" alt="Car">
            <img src="../../assets/img/highway.png" class="road" alt="Road">
        </div>
    </div>
</body>
</html>