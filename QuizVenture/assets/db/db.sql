CREATE DATABASE quizventure;
USE quizventure;

CREATE TABLE player (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player VARCHAR(255),
    score INT,
    streak INT
);