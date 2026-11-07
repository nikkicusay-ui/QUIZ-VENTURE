<?php
session_start();
require "../../assets/db/db.php";

if (!isset($_SESSION['player'])) {
    header("Location: ../../ready.php");
    exit();
}

$player = $_SESSION['player'];

$questions = [
    ["Who was the first President of the United States?", ["George Washington", "Abraham Lincoln", "Thomas Jefferson", "John Adams"], "George Washington"],
    ["In what year did World War II end?", ["1940", "1945", "1939", "1950"], "1945"],
    ["What ancient civilization built the pyramids?", ["Romans", "Greeks", "Egyptians", "Babylonians"], "Egyptians"],
    ["Who discovered America in 1492?", ["Leif Erikson", "Christopher Columbus", "Amerigo Vespucci", "Ferdinand Magellan"], "Christopher Columbus"],
    ["What wall fell in 1989 symbolizing the end of the Cold War?", ["Great Wall of China", "Berlin Wall", "Hadrian’s Wall", "Western Wall"], "Berlin Wall"],
    ["Which country is known as the Land of the Rising Sun?", ["China", "Japan", "Thailand", "South Korea"], "Japan"],
    ["What is the longest river in the world?", ["Amazon", "Yangtze", "Mississippi", "Nile"], "Nile"],
    ["Which continent is the Sahara Desert located in?", ["Asia", "Africa", "Australia", "South America"], "Africa"],
    ["What is the capital of France?", ["Paris", "Madrid", "Rome", "Berlin"], "Paris"],
    ["Mount Everest is located in which mountain range?", ["Alps", "Rockies", "Himalayas", "Andes"], "Himalayas"]
];

if (!isset($_SESSION['history_questions'])) {
    shuffle($questions);
    $_SESSION['history_questions'] = array_slice($questions, 0, 5);
}

$selected = $_SESSION['history_questions'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History & Geography Quiz</title>
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
        <h2>History & Geography</h2>
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

