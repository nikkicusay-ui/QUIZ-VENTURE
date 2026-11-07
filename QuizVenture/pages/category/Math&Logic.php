<?php
session_start();
require "../../assets/db/db.php";

if (!isset($_SESSION['player'])) {
    header("Location: ../../ready.php");
    exit();
}

if (!isset($_SESSION['streak'])) {
    $_SESSION['streak'] = 0;
}

$player = $_SESSION['player'];
$streak = $_SESSION['streak'];

$questions = [
    ["What is the value of 7 + 6 × 2?", ["26", "20", "19", "16"], "19"],
    ["Which number is a prime?", ["9", "15", "17", "21"], "17"],
    ["If x = 3, what is the value of 2x²?", ["18", "12", "9", "6"], "18"],
    ["What is the square root of 81?", ["7", "9", "8", "6"], "9"],
    ["What is the missing number in the sequence: 2, 4, 8, 16, __?", ["20", "24", "32", "30"], "32"],
    ["If 3 pencils cost 18 pesos, how much do 5 pencils cost?", ["30", "20", "25", "28"], "30"],
    ["What comes next in the pattern: A, C, E, G, __?", ["I", "H", "J", "K"], "I"],
    ["Which of these is equal to ⅔?", ["0.5", "0.75", "0.66", "0.25"], "0.66"],
    ["What is 15% of 200?", ["30", "20", "25", "35"], "30"],
    ["If a train leaves at 3:15 PM and arrives at 5:45 PM, how long is the trip?", ["2 hours", "2.5 hours", "3 hours", "1.5 hours"], "2.5 hours"]
];

if (!isset($_SESSION['math_questions'])) {
    shuffle($questions);
    $_SESSION['math_questions'] = array_slice($questions, 0, 5);
}

$selected = $_SESSION['math_questions'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Math & Logic Quiz</title>
    <link rel="stylesheet" href="../../assets/css/questions.css">
</head>
<body>

    <div class="game-container">
        <div class="background">
            <img src="../../assets/img/building4.png" class="building" alt="Building">
        </div>
        <div class="foreground">
            <img src="../../assets/img/car.png" class="car" id="car" alt="Car">
            <img src="../../assets/img/highway.png" class="road" alt="Road">
        </div>
    </div>

    <div class="quiz-box">
        <div class="top-bar">
            <div class="player">Player: <?= htmlspecialchars($player) ?></div>
            <div class="score">Score: <span id="score">0</span> / 5</div>
        </div>
        <h2>Math & Logic</h2>
        <div class="timer" id="timer">Time Left: 20</div>
        <div id="quiz-content">
            <div class="question" id="questionText"></div>
            <div id="options"></div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                <p id="questionCount" style="margin: 0;">1 of 5 Questions</p>
                <button id="nextBtn" disabled>Next</button>
            </div>
        </div>
    </div>

    <script>
        const questions = <?= json_encode($selected) ?>;
        let current = 0;
        let score = 0;
        let timer;
        let timeLeft = 10;

        const questionText = document.getElementById("questionText");
        const optionsDiv = document.getElementById("options");
        const nextBtn = document.getElementById("nextBtn");
        const scoreSpan = document.getElementById("score");
        const questionCount = document.getElementById("questionCount");
        const timerDisplay = document.getElementById("timer");
        const car = document.getElementById("car");

        function loadQuestion() {
            const q = questions[current];
            questionText.textContent = (current + 1) + ". " + q[0];
            optionsDiv.innerHTML = "";

            q[1].forEach(choice => {
                const div = document.createElement("div");
                div.className = "option";
                div.textContent = choice;
                div.onclick = () => {
                    clearInterval(timer);
                    stopCar();
                    selectOption(div, choice, q[2], true);
                };
                optionsDiv.appendChild(div);
            });

            nextBtn.disabled = true;
            questionCount.textContent = `${current + 1} of 5 Questions`;
            startTimer(q[2]);
            moveCar();
        }

        function moveCar() {
            car.classList.remove("move");
            void car.offsetWidth; 
            car.classList.add("move");
        }

        function stopCar() {
            car.classList.remove("move");
        }

        function startTimer(correctAnswer) {
            timeLeft = 20;
            timerDisplay.textContent = `Time Left: ${timeLeft}`;
            timer = setInterval(() => {
                timeLeft--;
                timerDisplay.textContent = `Time Left: ${timeLeft}`;
                if (timeLeft === 0) {
                    clearInterval(timer);
                    autoReveal(correctAnswer);
                }
            }, 1000);
        }

        function autoReveal(correctAnswer) {
            const options = document.querySelectorAll(".option");
            options.forEach(opt => {
                opt.onclick = null;
                if (opt.textContent === correctAnswer) {
                    opt.classList.add("correct");
                }
            });
            stopCar();
            nextBtn.disabled = false;
        }

        function selectOption(selectedDiv, selectedChoice, correctAnswer, givePoint) {
            const options = document.querySelectorAll(".option");
            options.forEach(opt => opt.onclick = null);

            if (selectedChoice === correctAnswer) {
                selectedDiv.classList.add("correct");
                if (givePoint) {
                    score++;
                    scoreSpan.textContent = score;
                }
            } else {
                selectedDiv.classList.add("wrong");
                options.forEach(opt => {
                    if (opt.textContent === correctAnswer) {
                        opt.classList.add("correct");
                    }
                });
            }
            nextBtn.disabled = false;
        }

        nextBtn.onclick = () => {
            current++;
            if (current < questions.length) {
                loadQuestion();
            } else {
                submitResults();
            }
        };

        function submitResults() {
            fetch("../player/submit.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ score: score })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "../player/congratulations.php";
                } else {
                    alert("Error saving your score.");
                }
            });
        }

        loadQuestion();
    </script>
</body>
</html>

