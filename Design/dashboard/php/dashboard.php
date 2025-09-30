<?php
session_start();

$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];
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
$_SESSION['Names'] = $names;

include 'selector.php';

$selectedName = isset($_GET['winner']) ? $_GET['winner'] : '';
if (!empty($selectedName)) {
    $_SESSION['winner'] = $selectedName;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="../js/script.js?v=5" defer></script>
    <link rel="stylesheet" href="../assets/css/Controls.css">
    <script src="../js/phpfunctions.js?v=16" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-text">Admin Panel</div>
        </div>

        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <img src="../assets/icons/grid.png" class="dashboard-icon nav-icon">
                        <span class="nav-icon"></span>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <img src="../assets/icons/trophygray.png" class="tournaments-icon nav-icon">
                        <span class="nav-icon"></span>
                        Tournaments
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <img src="../assets/icons/clock.png" class="clock-icon nav-icon">
                        <span class="nav-icon"></span>
                        Coming soon
                    </a>
                </li>
            </ul>
        </nav>
        <div class="logout">
            <a href="#" class="nav-link">
                <img src="../assets/icons/logout.png" alt="" class="nav-icon">
                <span class="nav-icon"></span>
                Logout
            </a>
        </div>
    </aside>

    <header>
        <h1 class="header-title">Admin Dashboard</h1>
    </header>

    <main>

        <div class="dashboard-content">
            <p class="welcome-text">
                Welcome back, Admin! Manage your tournaments and settings here.
            </p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="people-logo">
                        <img src="../assets/icons/people.png" class="people-icon">
                    </div>
                    <div class="player-container">
                        <div class="stat-label">Total Players</div>
                        <div class="stat-number">1,284</div>
                    </div>
                </div>
                <div class="stat-card trophy-card">
                    <div class="trophy-logo">
                        <img src="../assets/icons/trophy.png" class="trophy-icon">
                    </div>
                    <div class="counter-container">
                        <div class="stat-label">Tournament Counter</div>
                        <div class="stat-number">1</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="wifi-logo">
                        <img src="../assets/icons/wifi.png" class="wifi-icon">
                    </div>
                    <div class="response-container">
                        <div class="stat-label">Request response</div>
                        <?php
                        if($_SESSION['Response']){
                            echo '<p class="stat-number" "correct">Executed</p>';
                        }
                        else{
                            echo '<p class="stat-number" "false">Error</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="preset-section">
            <div class="preset-grid">
                <form action="dashboard.php" method="POST">
                    <div class="preset-card">
                        <h2 class="card-title">Tournament Preset</h2>
                        <div class="preset-controls">
                            <div class="custom-select">
                                <div class="form-group">
                                    <select name="selected_tournament" class="tournament-selector" >
                                    <option value="">-- Select Tournament --</option>
                                    <?php foreach ($tournament_options as $value => $display_name): ?>
                                    <option value="<?php echo htmlspecialchars($value); ?>" 
                                            <?php echo ($current_tournament === $display_name) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($display_name); ?>
                                    </option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <button class="preset-confirm-btn preset submit-preset" id="presetConfirmBtn" type="submit">
                                <img src="../assets/icons/check.png" alt="" class="preset-img"> Apply Preset
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Player Selector -->
                <form method="GET" action="dashboard.php">
                    <div class="preset-card">
                        <h2 class="card-title">Player Selection</h2>
                        <div class="preset-controls">
                            <div class="custom-select">
                                <div class="form-group">
                                    <select name="winner" class="tournament-selector">
                                        <option value="">-- Select Player --</option>
                                        <?php if (!empty($names) && is_array($names)): ?>
                                            <?php foreach ($names as $name): ?>
                                                <option value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                                                    <?php echo ($selectedName === $name) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No players available</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <button class="preset-confirm-btn submit-player" id="playerConfirmBtn" type="submit">
                                <img src="../assets/icons/check.png" alt="" class="preset-img"> Apply Player
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="control-section">
            <div class="control-card">
                <h2 class="card-title control-title">Tournament Control</h2>
                <div id="result"></div>
                <div class="control-grid">
                    <button class="control-btn start">
                        <div class="control-icon"><img src="../assets/icons/start.png" alt="" class="control-logo"></div>
                        <div class="control-text">Start<br>Tournament</div>
                    </button>
                    <button class="control-btn end">
                        <div class="control-icon"><img src="../assets/icons/end.png" alt="" class="control-logo"></div>
                        <div class="control-text">End<br>Tournament</div>
                    </button>
                    <button class="control-btn matches">
                        <div class="control-icon"><img src="../assets/icons/generate.png" alt="" class="control-logo"></div>
                        <div class="control-text">Generate<br>Matches</div>
                    </button>
                    <button class="control-btn reset">
                        <div class="control-icon"><img src="../assets/icons/restart.png" alt="" class="control-logo"></div>
                        <div class="control-text">Reset<br>Brackets</div>
                    </button>
                    <button class="control-btn finish remove">
                        <div class="control-icon"><img src="../assets/icons/trophy.png" alt="" class="control-logo"></div>
                        <div class="control-text">Remove<br>Players</div>
                    </button>
                    <button class="control-btn soon shuffle">
                        <div class="control-icon"><img src="../assets/icons/soon.png" alt="" class="control-logo"></div>
                        <div class="control-text">Shuffle<br>Seeds</div>
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>