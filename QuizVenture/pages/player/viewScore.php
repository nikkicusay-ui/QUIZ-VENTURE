<?php
require "../../assets/db/db.php";

// Fetch all players, sorted by score descending
$result = $conn->query("SELECT player, score, streak FROM player ORDER BY score DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Scores</title>
    <link rel="stylesheet" href="../../assets/css/viewScore.css">
</head>
<body>

<div class="game-container">
    <div class="background">
        <img src="../../assets/img/building6.png" class="building" alt="Building">
    </div>

    <div class="score-title">SCOREBOARD</div>
    <div class="category-box">
    <div class="scoreboard">
        <div class="score-row header">
            <div>Players</div>
            <div>Score</div>
            <div>Streak</div>
        </div>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="score-row">
                <div><?= htmlspecialchars($row['player']) ?></div>
                <div><?= $row['score'] ?></div>
                <div><?= $row['streak'] ?></div>
            </div>
        <?php endwhile; ?>
        <div class="pixel-cat"></div>
    </div>
        <div class="foreground">
            <img src="../../assets/img/highway.png" class="road" alt="Road">            
    </div>
   <div class="outer_wrapper">
    <div class="wrapper">

      <div class="cat_wrapper">
        <div class="cat first_pose">
          <div class="cat_head">
            <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 76.4 61.2" >
              <polygon class="eyes" points="63.8,54.1 50.7,54.1 50.7,59.6 27.1,59.6 27.1,54.1 12.4,54.1 12.4,31.8 63.8,31.8 "/>
              <path d="M15.3,45.9h5.1V35.7h-5.1C15.3,35.7,15.3,45.9,15.3,45.9z M45.8,56.1V51H30.6v5.1H45.8z M61.1,35.7H56v10.2h5.1
                V35.7z M10.2,61.2v-5.1H5.1V51H0V25.5h5.1V15.3h5.1V5.1h5.1V0h5.1v5.1h5.1v5.1h5.1v5.1c0,0,15.2,0,15.2,0v-5.1h5.1V5.1H56V0h5.1v5.1
                h5.1v10.2h5.1v10.2h5.1l0,25.5h-5.1v5.1h-5.1v5.1H10.2z"/>
            </svg>

          </div>
          <div class="body">
            <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 91.7 40.8" >
              <path class="st0" d="M91.7,40.8H0V10.2h5.1V5.1h5.1V0h66.2v5.1h10.2v5.1h5.1L91.7,40.8z"/>
            </svg>

            <div class="tail">
              <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 25.5 61.1" >
                <polygon class="st0" points="10.2,56 10.2,50.9 5.1,50.9 5.1,40.7 0,40.7 0,20.4 5.1,20.4 5.1,10.2 10.2,10.2 10.2,5.1 15.3,5.1 
                  15.3,0 25.5,0 25.5,10.2 20.4,10.2 20.4,15.3 15.3,15.3 15.3,20.4 10.2,20.4 10.2,40.7 15.3,40.7 15.3,45.8 20.4,45.8 20.4,50.9 
                  25.5,50.9 25.5,61.1 15.3,61.1 15.3,56 "/>
              </svg>
            </div>
          </div>
          
          <div class="front_legs">
            <div class="leg one">
              <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 14 30.5" >
                <polygon points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "/>
              </svg>
            </div>
            <div class="leg two">
              <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 14 30.5" >
                <polygon points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "/>
              </svg>
            </div>  
          </div>
          
          <div class="back_legs">
            <div class="leg three">
              <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 14 30.5" >
                <polygon points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "/>
              </svg>
            </div>
            <div class="leg four">
              <svg x="0px" y="0px" width="100%" height="100%" viewBox="0 0 14 30.5" >
                <polygon points="15.3,30.5 5.1,30.5 5.1,25.4 0,25.4 0,0 15.3,0 "/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="ground"></div>

  </div>


    </div>
    <a class="back-button" href="../category/category.php">HOME</a>

</div>
 <script>

function init() { 
const catWrapper = document.querySelector('.cat_wrapper')
  const wrapper = document.querySelector('.wrapper')
  const cat = document.querySelector('.cat')
  const head = document.querySelector('.cat_head')
  const legs = document.querySelectorAll('.leg')
  const pos = {
    x: null,
    y: null
  }

  const walk = () =>{
    cat.classList.remove('first_pose')
    legs.forEach(leg=>leg.classList.add('walk'))
  }

  const handleMouseMotion = e =>{
    pos.x = e.clientX
    pos.y = e.clientY
    walk()
  }

  const handleTouchMotion = e =>{
    if (!e.targetTouches) return
    pos.x = e.targetTouches[0].offsetX
    pos.y = e.targetTouches[0].offsetY
    walk()
  }

  const turnRight = () =>{
    cat.style.left = `${pos.x - 90}px`
    cat.classList.remove('face_left')
    cat.classList.add('face_right')
  }

  const turnLeft = () =>{
    cat.style.left = `${pos.x + 10}px`
    cat.classList.remove('face_right')
    cat.classList.add('face_left')
  }

  const decideTurnDirection = () =>{
    cat.getBoundingClientRect().x < pos.x ?
      turnRight()
      :
      turnLeft()
  }

  const headMotion = () =>{
    pos.y > (wrapper.clientHeight - 100) ?
      head.style.top = '-15px'
      :
      head.style.top = '-30px'
  }

const jump = () => {
  const catRect = catWrapper.getBoundingClientRect(); // get position of cat
  const catBottomY = catRect.bottom; // where the cat ends on screen

  // Check if mouse is at least 100px higher than the cat's bottom
  if (mouseY < catBottomY - 250) {
    if (!catWrapper.classList.contains('jump')) {
      catWrapper.classList.add('jump');
      setTimeout(() => {
        catWrapper.classList.remove('jump');
      }, 1000);
    }
  }
};
catWrapper.addEventListener('mouseenter', () => {
  catWrapper.classList.add('sit');
});

catWrapper.addEventListener('mouseleave', () => {
  catWrapper.classList.remove('sit');
});

  const decideStop = ()=>{
    if (cat.classList.contains('face_right') && pos.x - 90 === cat.offsetLeft ||
        cat.classList.contains('face_left') && pos.x + 10 === cat.offsetLeft) {
      legs.forEach(leg=>leg.classList.remove('walk'))    
    }
  }
  
  setInterval(()=>{
    if (!pos.x || !pos.y) return
    decideTurnDirection()
    headMotion()
    decideStop()
  },100)

  setInterval(()=>{
    if (!pos.x || !pos.y) return
    jump()
  },1000)
  

  document.addEventListener('mousemove', handleMouseMotion)
  document.addEventListener('mousemove', handleTouchMotion)
}

window.addEventListener('DOMContentLoaded', init)

let mouseY = 0;

document.addEventListener('mousemove', (e) => {
  mouseY = e.clientY;
});

</script>
</body>
</html>

