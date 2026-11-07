<?php
session_start();

if (!isset($_SESSION['player'])) {
    header("Location: ../../ready.php");
    exit();
}

$score = $_SESSION['last_score'] ?? 0;
$total = 5;
$percent = ($score / $total) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <style>
            @font-face {
        font-family: 'PixelFont';
        src: url('../../assets/img/PressStart2P-Regular.ttf') format('truetype');
    }

    body {
        background-color: #2f2f7f;
        color: white;
        font-family: 'PixelFont', monospace;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .result-box {
        background-color: #a267dc;
        border: 8px solid white;
        border-radius: 25px;
        box-shadow: 10px 10px #34145c;
        padding: 40px;
        text-align: center;
        width: 400px;
    }

    .result-box h2 {
        font-size: 18px;
        margin-bottom: 25px;
    }

    .circle {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: conic-gradient(#ff00ff <?= $percent ?>%, #333 <?= $percent ?>%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        position: relative;
        box-shadow:  6px #34145c;
    }

    .circle span {
        position: absolute;
        font-size: 18px;
        font-weight: bold;
        color: white;
    }

    .score-text {
        font-size: 12px;
        margin-bottom: 25px;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .buttons a {
        font-family: 'PixelFont', monospace;
        font-size: 12px;
        padding: 12px 20px;
        text-decoration: none;
        border-radius: 15px;
        display: inline-block;
    }

    .try-again {
        background: (#ffd93d,rgb(101, 86, 102));
        color:rgb(255, 255, 255);
        border: 3px solid white;
        box-shadow: 4px 4px #34145c;
    }

    .view-score {
        background-color:rgb(248, 122, 198);
        color: white;
        border: none;
        box-shadow: 4px 4px #34145c;
    }

    .try-again:hover,
    .view-score:hover {
        transform: scale(1.05);
    }
    </style>
</head>
<body>
    
    <div class="result-box">
        <h2>Quiz Result!</h2>
        <div class="circle">
            <span><?= round($percent) ?>%</span>
        </div>
        <div class="score-text">
            Your Score <?= $score ?> out of <?= $total ?>
        </div>
        <div class="buttons">
            <a class="try-again" href="../category/Sci&Tech.php">Try Again</a>
            <a class="view-score" href="../player/viewScore.php">View Score</a>
        </div>
    </div>
</body>
</html>
