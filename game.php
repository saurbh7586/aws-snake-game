<?php
session_start();
include('db_config.php');

// जर युजर लॉगिन नसेल तर त्याला लॉगिन पेजवर पाठवा
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Snake Game - AWS Project</title>
    <style>
        body { background: #1a1a1a; color: white; text-align: center; font-family: 'Arial', sans-serif; }
        canvas { border: 10px solid #333; background: #000; display: block; margin: 20px auto; box-shadow: 0 0 20px rgba(0,255,0,0.2); }
        .stats { font-size: 20px; margin-bottom: 10px; }
        .leaderboard { background: #222; padding: 15px; width: 300px; margin: 20px auto; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border-bottom: 1px solid #444; }
        .logout-btn { background: #ff4444; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

    <h1>Snake Game</h1>
    <div class="stats">Player: <strong><?php echo $user; ?></strong> | Score: <span id="scoreVal">0</span></div>
    
    <canvas id="snakeGame" width="400" height="400"></canvas>

    <br>
    <a href="login.php" class="logout-btn">Logout</a>

    <div class="leaderboard">
        <h3>Top 5 High Scores</h3>
        <table>
            <tr><th>User</th><th>Score</th></tr>
            <?php
            $query = "SELECT username, MAX(score) as high FROM scores GROUP BY username ORDER BY high DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>".$row['username']."</td><td>".$row['high']."</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        const canvas = document.getElementById("snakeGame");
        const ctx = canvas.getContext("2d");

        let score = 0;
        let box = 20;
        let snake = [{x: 200, y: 200}];
        let food = { x: Math.floor(Math.random() * 19 + 1) * box, y: Math.floor(Math.random() * 19 + 1) * box };
        let d = "RIGHT";

        document.addEventListener("keydown", direction);

        function direction(event) {
            if(event.keyCode == 37 && d != "RIGHT") d = "LEFT";
            else if(event.keyCode == 38 && d != "DOWN") d = "UP";
            else if(event.keyCode == 39 && d != "LEFT") d = "RIGHT";
            else if(event.keyCode == 40 && d != "UP") d = "DOWN";
        }

        function draw() {
            ctx.fillStyle = "black";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            for(let i = 0; i < snake.length; i++) {
                ctx.fillStyle = (i == 0) ? "lime" : "green";
                ctx.fillRect(snake[i].x, snake[i].y, box, box);
                ctx.strokeStyle = "black";
                ctx.strokeRect(snake[i].x, snake[i].y, box, box);
            }

            ctx.fillStyle = "red";
            ctx.fillRect(food.x, food.y, box, box);

            let snakeX = snake[0].x;
            let snakeY = snake[0].y;

            if( d == "LEFT") snakeX -= box;
            if( d == "UP") snakeY -= box;
            if( d == "RIGHT") snakeX += box;
            if( d == "DOWN") snakeY += box;

            if(snakeX == food.x && snakeY == food.y) {
                score += 10;
                document.getElementById("scoreVal").innerHTML = score;
                food = { x: Math.floor(Math.random() * 19 + 1) * box, y: Math.floor(Math.random() * 19 + 1) * box };
            } else {
                snake.pop();
            }

            let newHead = { x: snakeX, y: snakeY };

            if(snakeX < 0 || snakeY < 0 || snakeX >= canvas.width || snakeY >= canvas.height || collision(newHead, snake)) {
                clearInterval(game);
                submitScore(score);
            }

            snake.unshift(newHead);
        }

        function collision(head, array) {
            for(let i = 0; i < array.length; i++) {
                if(head.x == array[i].x && head.y == array[i].y) return true;
            }
            return false;
        }

        function submitScore(finalScore) {
            let formData = new FormData();
            formData.append('username', '<?php echo $user; ?>');
            formData.append('score', finalScore);

            fetch('save_score.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                alert("Game Over! Score: " + finalScore);
                location.reload();
            });
        }

        let game = setInterval(draw, 100);
    </script>
</body>
</html>
