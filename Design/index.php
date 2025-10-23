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
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokeland - Turnaje Cobblemon | <?= htmlspecialchars($title ?? "Aktuální turnaj") ?> </title>

    <link rel="icon" type="image/png" href="/index/icons/Logo.png" sizes="48x48">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Description -->
    <meta name="description" content="Oficiální Pokeland web na hostování turnajů." />

    <!-- Canonical -->
    <link rel="canonical" href="https://turnaje.fun/" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Pokeland - Turnaje Cobblemon" />
    <meta property="og:description" content="Oficiální Pokeland web na hostování turnajů." />
    <meta property="og:url" content="https://turnaje.fun/" />
    <meta property="og:image" content="https://turnaje.fun/index/icons/pokeland-turnaje.png" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Pokeland - Turnaje Cobblemon" />
    <meta name="twitter:description" content="Oficiální Pokeland web na hostování turnajů." />
    <meta name="twitter:image" content="https://turnaje.fun/index/icons/pokeland-turnaje.png" />

    <!-- Styles -->
    <link rel="stylesheet" href="./index/assets/main.css?v=23">
    <link rel="stylesheet" href="./index/assets/nav.css?v=6">
    <link rel="stylesheet" href="./index/assets/style.css?v=25">
    <link rel="stylesheet" href="./index/assets/tournament.css?v=12">
    <link rel="stylesheet" href="./index/assets/footer.css?v=68">
    <link rel="stylesheet" href="./index/assets/tooltip.css?v=24">
    <script src="./index/js/tooltip.js?v=2" defer></script>

</head>
<body>
    <div class="particles" id="particles"></div>
    <header>
        <div class="header">
            <div class="title">
                <a href=""><img src="./index/icons/Logo.png" alt="logo" class="logo"></a>
                <div class="group">
                    <a href="#info" class="title-pokeland title">Pokeland</a>
                    <a href="#info" class="title-pokeland hub">Turnajový Web</a>
                </div>
            </div>
            <div class="nav">
                <a href="#info" class="tournament">Turnaje</a>
                <a href="tournament/html/index.html" class="brackets">Pavouk</a>
                <a href="#rules" class="rules">Pravidla</a>
                <a href="https://navody.pokeland.cz/cs/cobblemon/turnaje" class="breakdown">Rozpis</a>
            </div>
            <a class="button" href="./login/php/login.php">Admin Login</a>        
        </div>
    </header>
    <main>
        <section id="info" class="sec-info">
            <div class="container">
                <p class="mainTitle"><strong>Pokeland Turnaje</strong></p>
                <p class="slug">Připoj se k hráčům na Pokelandu a užij si nezapomenutelné<br>turnaje plné skvělých odměn!</p>
                <div class="card">
                    <div class="currentTournament">
                        <img src="index/icons/TrophyYellow.png" alt="" class="trophyYellow">
                        <p class="current">Další turnaj</p>
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
                                
                                $czechDays = [
                                    'Monday' => 'Pondělí',
                                    'Tuesday' => 'Úterý',
                                    'Wednesday' => 'Středa',
                                    'Thursday' => 'Čtvrtek',
                                    'Friday' => 'Pátek',
                                    'Saturday' => 'Sobota',
                                    'Sunday' => 'Neděle'
                                ];
                                
                                $englishDay = $nextTournament->format('l');
                                $czechDay = $czechDays[$englishDay];
                                
                                echo $czechDay . $nextTournament->format(', d.m H:i');
                            ?>
                        </p>
                        <p class="players">
                            <img src="./index/icons/Bolt.png" alt="" class="img"> 
                            <?php
                                echo count($names);
                                if(count($names) == 0){
                                    echo " Hráčů";
                                }
                                else if(count($names) == 1){
                                    echo " Hráč";
                                }
                                else if(count($names) > 1 && count($names) <= 4){
                                    echo " Hráči";
                                }
                                else{
                                    echo " Hráčů";
                                }
                            ?>
                            <span class="tooltip-container">
                                    <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    width="1.2em"
                                    height="1.2em"
                                    fill="#8C8B8C"
                                    >
                                        <path
                                            d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22c-5.518 0-10-4.482-10-10s4.482-10 10-10 10 4.482 10 10-4.482 10-10 10zm-1-16h2v6h-2zm0 8h2v2h-2z"
                                        >
                                        </path>
                                    </svg>
                                    <span class="tooltip">
                                        Přihlášení hráči:
                                        <br>
                                        <?php
                                        foreach($names as $name){
                                            echo "$name <br>"; 
                                        }
                                        ?>
                                    </span>
                            </span>
                        </p>
                    </div>
                </div>
                <div class="buttonContainer">
                    <a class="viewBrackets links" href="./tournament/html/index.html">Koukni na pavouka</a>
                    <a class="viewRules links" href="#rules">Pravidla turnaje</a>
                </div>
            </div>
        </section>
        <section id="rules" class="sec-rules" style="display: flex; align-items: center; flex-direction: column;">
            <p class="mainTitle" style="color: white; margin-bottom: 20px;"><strong>Pravidla & Odměny</strong></p>
            <div class="tournament-container">
                <div class="tournament-tabs">
                    <button class="tournament-tab active left-tab" onclick="showTournamentTab('tournament-rules')">

                        <img class="tournament-tab-icon special-icon" src="index/icons/Rule.png?v=1" alt="Rules Icon">
                        <p>Pravidla</p>
                    </button>
                    <button class="tournament-tab right-tab" onclick="showTournamentTab('tournament-prizes')">

                        <img class="tournament-tab-icon" src="index/icons/TrophyYellow.png" alt="Prize Icon">
                        <p>Odměny</p>
                    </button>
                </div>
                
                <div class="tournament-content">
                    <div id="tournament-rules" class="tournament-tab-content active">
                        <div class="tournament-rules-grid">
                            <div class="tournament-rule-card">
                                <div class="tournament-rule-header">
                                    <div class="tournament-rule-icon">🛡️</div>
                                    <h3 class="tournament-rule-title">
                                        <?php 
                                            echo $title;
                                        ?>
                                    </h3>
                                </div>
                                <div class="tournament-rule-item">
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
                                <img class="tournament-prize-icon" src="./index/icons/crown.png?v=3" alt="Gold Trophy">
                                <h3 class="tournament-prize-place">První místo</h3>
                                <div class="tournament-prize-package">Cena:</div>
                                <div class="tournament-prize-description"> 5 event klíčů</div>
                            </div>
                            
                            <div class="tournament-prize-card second">
                                <img class="tournament-prize-icon" src="./index/icons/second.png?v=3" alt="Second Palce Trophy">
                                <h3 class="tournament-prize-place">Druhé místo</h3>
                                <div class="tournament-prize-package">Cena:</div>
                                <div class="tournament-prize-description">3 event klíče</div>
                            </div>
                            
                            <div class="tournament-prize-card third">
                                <img class="tournament-prize-icon" src="./index/icons/third.png?v=3" alt="Third Palce Trophy">
                                <h3 class="tournament-prize-place">Třetí místo</h3>
                                <div class="tournament-prize-package">Cena:</div>
                                <div class="tournament-prize-description">2 event klíče</div>
                            </div>
                        </div>
                        
                        <div class="tournament-total-prize">
                            <h3>Odměna za účast</h3>
                            <div class="tournament-total-amount">1 event klíč</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<footer>
    <div class="footer-content">
        <div class="footer-brand">
           <a href="https://pokeland.cz/" class="footer-icon"><img src="./index/icons/Logo.png" alt="Pokeland Logo" style="width: 60px; height: 60px;"></a>
            <div class="footer-brand-text">
                <a class="footer-brand-name" href="https://pokeland.cz/">Pokeland.cz</a>
                <a href="https://pokeland.cz/" class="footer-brand-subtitle">Pokemoní Server</a>
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
            <a href="#rules">Pravidla</a>
            <a href="https://store.rajce.pro/categories/Pokeland/klice">Obchod</a>
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
        const tabContents = document.querySelectorAll('.tournament-tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        
        const tabs = document.querySelectorAll('.tournament-tab');
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        
        document.getElementById(tabName).classList.add('active');
        
        const clickedButton = tabName === 'tournament-rules' 
            ? document.querySelector('.left-tab')
            : document.querySelector('.right-tab');
        
        if (clickedButton) {
            clickedButton.classList.add('active');
        }
    }
    </script>
</body>
</html>