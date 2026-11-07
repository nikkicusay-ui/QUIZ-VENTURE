<?php
session_start();
require "../../assets/db/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['player'])) {
    echo json_encode(["success" => false, "error" => "No player session."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$score = $input['score'] ?? 0;
$player = $_SESSION['player'];

$perfect = $score == 5;

$stmt = $conn->prepare("SELECT score, streak FROM player WHERE player = ?");
$stmt->bind_param("s", $player);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newScore = $row['score'] + $score;

    if ($perfect) {
        $newStreak = $row['streak'] + 1;
    } else {
        $newStreak = $row['streak'];
    }

    $update = $conn->prepare("UPDATE player SET score = ?, streak = ? WHERE player = ?");
    $update->bind_param("iis", $newScore, $newStreak, $player);
    $update->execute();
} else {
    $streak = $perfect ? 1 : 0;
    $insert = $conn->prepare("INSERT INTO player (player, score, streak) VALUES (?, ?, ?)");
    $insert->bind_param("sii", $player, $score, $streak);
    $insert->execute();
}

unset($_SESSION['science_questions']);

$_SESSION['last_score'] = $score;

echo json_encode(["success" => true]);