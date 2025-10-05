<?php
$config = include __DIR__ . '../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

$success_message = '';
$error_message = '';

$title = "";
$rules = "";
$rewards = "";

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


    $names = [];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("SELECT Name FROM Players");
        if ($stmt) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $names[] = $row['Name']; 
                    }
                }
            } 
            $stmt->close();
        } 
        $conn->close();
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokeland Tourneys</title>
    <link rel="stylesheet" href="./index/assets/main.css?v=22">
    <link rel="stylesheet" href="./index/assets/nav.css?v=4">
    <link rel="stylesheet" href="./index/assets/style.css?v=25">
    <link rel="stylesheet" href="./index/assets/tournament.css?v=8">
    <link rel="stylesheet" href="./index/assets/footer.css?v=67">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body>
    <div class="particles" id="particles"></div>
    <header>
        <div class="header">
            <div class="title">
                <a href=""><img src="./index/icons/Logo.png" alt="logo" class="logo"></a>
                <div class="group">
                    <a href="#info" class="title-pokeland title">Pokeland</a>
                    <a href="#info" class="title-pokeland hub">Tournament Hub</a>
                </div>
            </div>
            <div class="nav">
                <a href="#info" class="tournament">Tournament</a>
                <a href="tournament/html/index.html" class="brackets">Brackets</a>
                <a href="#rules" class="rules">Rules</a>
            </div>
            <a class="button" href="./login/php/login.php">Admin Login</a>        
        </div>
    </header>
    <main>
        <section id="info" class="sec-info">
            <div class="container">
                <p class="mainTitle"><strong>Pokeland Tournaments</strong></p>
                <p class="slug">Join the ultimate Pokemon competitive scene. Battle, compete,<br> and get free rewards!</p>
                <div class="card">
                    <div class="currentTournament">
                        <img src="index/icons/TrophyYellow.png" alt="" class="trophyYellow">
                        <p class="current">Current Tournament</p>
                    </div>
                    <p class="currentTitle">
                        <?php 
                            echo $title; 
                        ?>
                    </p>
                    <div class="dateContainer">
                        <p class="date"><img src="./index/icons/Date.png" alt="" class="img">
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
                    echo $nextTournament->format('l, d.m H:i');
                    ?>
                        </p>
                        <p class="players"><img src="./index/icons/Bolt.png" alt="" class="img"> 
                            <?php
                                echo count($names);
                            ?>
                            Players
                        </p>
                    </div>
                </div>
                <div class="buttonContainer">
                    <a class="viewBrackets links" href="">View Brackets</a>
                    <a class="viewRules links" href="#rules">Tournament Rules</a>
                </div>
            </div>
        </section>
        <section id="rules" class="sec-rules" style="display: flex; align-items: center; flex-direction: column;">
            <p class="mainTitle" style="color: white; margin-bottom: 20px;"><strong>Rules & Rewards</strong></p>
            <div class="tournament-container">
                <div class="tournament-tabs">
                    <!-- replace with eventlistener -->
                    <button class="tournament-tab active left-tab" onclick="showTournamentTab('tournament-rules')">
                        <!-- add icon here-->
                        <span class="tournament-tab-icon ">📋</span>
                        Rules & Guidelines
                    </button>
                    <!-- replace with eventlistener -->
                    <button class="tournament-tab right-tab" onclick="showTournamentTab('tournament-prizes')">
                        <!-- add icon here-->
                        <span class="tournament-tab-icon">🏆</span>
                        Prizes & Rewards
                    </button>
                </div>
                
                <div class="tournament-content">
                    <div id="tournament-rules" class="tournament-tab-content active">
                        <div class="tournament-rules-grid">
                            <div class="tournament-rule-card">
                                <div class="tournament-rule-header">
                                    <!-- add icon here -->
                                    <div class="tournament-rule-icon">🛡️</div>
                                    <h3 class="tournament-rule-title">
                                        <?php 
                                            echo $title;
                                        ?>
                                    </h3>
                                </div>
                                <div class="tournament-rule-item">
                                    <!-- Get rules from DB -->
                                    <div class="tournament-rule-bullet"></div>
                                    <span>
                                        <?php 
                                            echo nl2br(htmlspecialchars($rules));
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tournament-prizes" class="tournament-tab-content">
                        <div class="tournament-prizes-grid">
                            <div class="tournament-prize-card first">
                                <!-- add icons here -->
                                <div class="tournament-prize-icon">👑</div>
                                <h3 class="tournament-prize-place">1st Place</h3>
                                <div class="tournament-prize-package">Prize:</div>
                                <div class="tournament-prize-description"> 5 event keys + shiny swapper</div>
                            </div>
                            
                            <div class="tournament-prize-card second">
                                <!-- add icons here -->
                                <div class="tournament-prize-icon">🥈</div>
                                <h3 class="tournament-prize-place">2nd Place</h3>
                                <div class="tournament-prize-package">Prize:</div>
                                <div class="tournament-prize-description">3 event keys</div>
                            </div>
                            
                            <div class="tournament-prize-card third">
                                <!-- add icons here -->
                                <div class="tournament-prize-icon">🥉</div>
                                <h3 class="tournament-prize-place">3rd Place</h3>
                                <div class="tournament-prize-package">Prize:</div>
                                <div class="tournament-prize-description">2 event keys</div>
                            </div>
                        </div>
                        
                        <div class="tournament-total-prize">
                            <h3>Reward for participants</h3>
                            <div class="tournament-total-amount">1 event key</div>
                            <div class="tournament-total-subtitle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<footer>
    <div class="footer-content">
        <!-- Left Section: Brand -->
        <div class="footer-brand">
           <a href="https://pokeland.cz/" class="footer-icon"><img src="./index/icons/Logo.png" alt="Pokeland Logo" style="width: 60px; height: 60px;"></a>
            <div class="footer-brand-text">
                <a class="footer-brand-name" href="https://pokeland.cz/">Pokeland.cz</a>
                <a href="https://pokeland.cz/" class="footer-brand-subtitle">Pokemon Server</a>
            </div>
        </div>

        <div class="footer-social">
            <a href="https://discord.gg/Ma3r3yFB4J" class="social-link icon-discord"><img src="./index/icons/Discord.png" alt="Discord"></a>
            <a href="https://www.youtube.com/@PokelandCZ" class="social-link icon-youtube" ><img src="./index/icons/Youtube.png" alt="Youtube"></a>
            <a href="https://www.twitch.tv/captainvaniron" class="social-link icon-twitch" ><img src="./index/icons/Twitch.png" alt="Twitch"></a>
            <a href="https://www.instagram.com/pokeland.cz/" class="social-link icon-instagram" ><img src="./index/icons/Instagram.png" alt="Instagram"></a>
            <a href="https://github.com/Paklicecek" class="social-link icon-github" ><img src="./index/icons/Github.png" alt="Github"></a>
        </div>

        <div class="footer-links">
            <a href="#rules">Rules</a>
            <a href="https://store.rajce.pro/categories/Pokeland/klice">Store</a>
        </div>
    </div>
</footer>
    <script>
            function createParticles() {
            const particlesContainer = document.getElementById('particles');
            
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 10) + 's';
                particlesContainer.appendChild(particle);
                }, 300); 
            }
        }
        // explain this code below 
        // Smooth scrolling for internal links
        // What does that mean?
        // It means that when you click on a link that points to a section within the same page (like #info or #rules),
        // the page will scroll smoothly to that section instead of jumping abruptly.
        // This enhances user experience by providing a visually appealing transition.
        // The code selects all anchor tags with href attributes that start with '#'
        // and adds a click event listener to each of them.
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        createParticles();
        function showTournamentTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tournament-tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tournament-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}
    </script>
</body>
</html>