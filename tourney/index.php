<?php
session_start();



$config = include __DIR__ . '/../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

$success_message = '';
$error_message = '';

$title = "";
$rules = "";
$rewards = "";
if(true){
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("SELECT title, rules, rewards FROM active_preset");
        if ($stmt) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {

                    $row = $result->fetch_assoc();

                    $title = $row['title'];
                    $rules = $row['rules'];
                    $rewards = $row['rewards'];
                }
            } 
            $stmt->close();
        } 
        $conn->close();
    }
}
$parts = preg_split('/(?=\d\.místo:|za účast:)/', $rewards, -1, PREG_SPLIT_NO_EMPTY);

?>
<!DOCTYPE html>
<html lang="cz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeLand Tournament</title>
    <link rel="stylesheet" href="style.css?v=20">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="title-section">
                <h1>PokeLand Tournament</h1>
                <p class="date">
                <?php
                    date_default_timezone_set('Europe/Prague'); 

                    $now = new DateTime();

                    $days = ['Thursday', 'Sunday'];
                    $nextTournament = null;

                    foreach ($days as $day) {
                        $candidate = new DateTime("next $day 19:00");
                        if ($now->format('l') === $day && $now < new DateTime("today 19:00")) {
                            $candidate = new DateTime("today 19:00");
                        }
                        if ($nextTournament === null || $candidate < $nextTournament) {
                            $nextTournament = $candidate;
                        }
                    }
                    echo "Next tournament: " . $nextTournament->format('l, d.m.Y H:i');
                    ?>
                </p>
            </div>
            <a class="admin-login" href="login.php">
                Admin Login
            </a>
        </header>

        <form method="POST" action="index.php">
        <section class="tournament-info">
            <div class="info-card">
                <div class="dropdown-header" onclick="toggleDropdown()">
                    <h2>Tournament Information</h2>
                    <span class="dropdown-arrow" id="dropdownArrow">▼</span>
                    </div>
                <div class="dropdown-content" id="dropdownContent">
                    <div class="info-placeholder">
                        <?php
                            echo "<p><strong>Název: </strong> <br>" . htmlspecialchars($title) . "</p>";
                            echo "<p><strong>Pravidla:</strong><br>" . nl2br(htmlspecialchars($rules)) . "</p>";
                            echo "<p><strong>Odměny:</strong><br>";
                            foreach ($parts as $reward) {
                                echo htmlspecialchars(trim($reward)) . "<br>";
                            }
                            echo "</p>";
                        ?>
                    </div>
                </div>
            </div>
        </section>
        </form>

        <main class="main-content">
        </main>
    </div>

    <script>
        function toggleDropdown() {
            const content = document.getElementById('dropdownContent');
            const arrow = document.getElementById('dropdownArrow');
            
            content.classList.toggle('open');
            arrow.classList.toggle('rotated');
        }

            
    </script>
</body>
</html>