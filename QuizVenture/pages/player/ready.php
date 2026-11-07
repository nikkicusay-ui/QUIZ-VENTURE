<?php
session_start();
require "../../assets/db/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player = trim($_POST['player']);

    if (!empty($player)) {
        $_SESSION['player'] = $player;

        $checkStmt = $conn->prepare("SELECT * FROM player WHERE player = ?");
        $checkStmt->bind_param("s", $player);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            $insertStmt = $conn->prepare("INSERT INTO player (player, score, streak) VALUES (?, 0, 0)");
            $insertStmt->bind_param("s", $player);
            $insertStmt->execute();
        }

        header("Location: ../category/category.php");
        exit();
    } else {
        echo "Please enter a valid name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enter Player</title>
    <link rel="stylesheet" href="../../assets/css/ready.css">
</head>
<body>
    <div class="game-container">
        <div class="background">
            <img src="../../assets/img/building3.png" class="building" alt="Building">
        </div>

        <div class="ready-area">
            <h1>ENTER NAME</h1>
            <form method="POST" action="">
                <div class="name-box">
                    <label for="player">Player Name:</label><br>
                    <input type="text" name="player" id="player" required><br>
                    <input type="submit" value="ENTER">
                </div>
            </form>
        </div>

        <div class="foreground">
            <img src="../../assets/img/car.png" class="car" alt="Car">
            <img src="../../assets/img/highway.png" class="road" alt="Road">
        </div>
    </div>
</body>
</html>
