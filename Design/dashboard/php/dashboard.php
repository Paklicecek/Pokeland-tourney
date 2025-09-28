<?php
session_start();

$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

include 'selector.php';

include 'API.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="../js/script.js?v=4" defer></script>
    <link rel="stylesheet" href="../assets/css/Controls.css">
    <script src="../js/phpfunctions.js?v=5" defer></script>

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
                        <div class="stat-number">Placeholder for php response</div>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST" action="dashboard.php">
            <div class="preset-section">
                <div class="preset-grid">
                    <div class="preset-card">
                        <h2 class="card-title">Tournament Preset</h2>
                        <div class="preset-controls">
                            <div class="custom-select">
                                <div class="form-group">
                                    <select name="selected_tournament" id="tournament_select" class="tournament-selector" required>
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
                            <button class="preset-confirm-btn preset" id="presetConfirmBtn" type="submit">
                                <img src="../assets/icons/check.png" alt="" class="preset-img"> Apply Preset
                            </button>
                        </div>
                    </div>
            </form>
                <!-- Player Selector -->
                <div class="preset-card">
                    <h2 class="card-title">Player Selection</h2>
                    <div class="preset-controls">
                        <div class="custom-select">
                            <div class="select-btn" id="playerSelector">
                                <span id="selectedPlayer">Select Player</span>
                                <span class="dropdown-arrow">▼</span>
                            </div>
                            <div class="dropdown-menu" id="playerDropdown">
                                <div class="dropdown-option" data-value="player1">
                                    <span>Paklicek</span>
                                </div>
                                <div class="dropdown-option" data-value="player2">
                                    <span>Cakeman</span>
                                </div>
                            </div>
                        </div>
                        <button class="preset-confirm-btn" id="playerConfirmBtn">
                            <img src="../assets/icons/check.png" alt="" class="preset-img"> Apply Player
                        </button>
                    </div>
                </div>
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