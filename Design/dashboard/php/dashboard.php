<?php
session_start();

if(!isset($_SESSION['permission']) || $_SESSION['permission'] !== true)
{
    header('Location: login.php');
    exit;
}

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
    <link rel="stylesheet" href="../assets/css/dashboard.css?v=34">
    <link rel="stylesheet" href="../assets/css/Controls.css?v=64">
    <link rel="stylesheet" href="../assets/css/Creation.css?v=31">

    <script src="../js/script.js?v=7" defer></script>
    <script src="../js/phpfunctions.js?v=17" defer></script>
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
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../tournament/html/index.html" class="nav-link">
                        <img src="../assets/icons/trophygray.png" class="tournaments-icon nav-icon">
                        <p>Coming soon</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <img src="../assets/icons/clock.png" class="clock-icon nav-icon">
                        <p>Coming soon</p>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="logout">
            <a href="#" class="nav-link">
                <img src="../assets/icons/logout.png" alt="" class="nav-icon">
                <p>Logout</p>
            </a>
        </div>
    </aside>

    <main>

        <div class="dashboard-content">
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="people-logo">
                        <img src="../assets/icons/people.png" class="people-icon">
                    </div>
                    <div class="player-container">
                        <div class="stat-label">Total Players</div>
                        <div class="stat-number">
                            <?php echo count($names); ?> 
                        </div>
                    </div>
                </div>
                <div class="stat-card trophy-card">
                    <div class="trophy-logo">
                        <img src="../assets/icons/trophy.png" class="trophy-icon">
                    </div>
                    <div class="counter-container">
                        <div class="stat-label">Tournament Counter</div>
                        <div class="stat-number"> 
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                $error_message = "Connection failed: " . $conn->connect_error;
                            } 
                            else {
                                $stmt = $conn->prepare("SELECT Tcount FROM Tcounter");
                                if ($stmt) {
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $counter = $row['Tcount']; 
                                            }
                                        }
                                    } 
                                    $stmt->close();
                                } 
                                $conn->close();
                            }
                            if(isset($_SESSION['counter'])){
                                $counter = $counter + $_SESSION['counter'];
                                unset($_SESSION['counter']);
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                if ($conn->connect_error) {
                                    $error_message = "Connection failed: " . $conn->connect_error;
                                } 
                                else {
                                    $stmt = $conn->prepare("UPDATE Tcounter SET Tcount = ?");
                                    if($stmt){
                                        $stmt->bind_param('i', $counter);
                                        $stmt->execute();
                                        $stmt->close();
                                    }
                                }
                                $conn->close();
                            }
                            echo $counter;
                            ?>      
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="wifi-logo">
                        <img src="../assets/icons/wifi.png" class="wifi-icon">
                    </div>
                    <div class="response-container">
                        <div class="stat-label">Request response</div>
                        <p class="stat-number">
                            <?php
                            if($_SESSION['Response'] === true){
                                echo "Executed successfully";
                            }
                            else if($_SESSION['Response'] === false){
                                echo "An error occurred";
                            }
                            else{
                                echo "No requests made yet";
                            }
                            unset($_SESSION['Response']);
                            ?>
                        </p>
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
                    <button class="control-btn reset">
                        <div class="control-icon"><img src="../assets/icons/restart.png" alt="" class="control-logo"></div>
                        <div class="control-text">Reset<br>Brackets</div>
                    </button>
                    <button class="control-btn matches">
                        <div class="control-icon"><img src="../assets/icons/generate.png" alt="" class="control-logo"></div>
                        <div class="control-text">Generate<br>Matches</div>
                    </button>
                    <button class="control-btn soon shuffle">
                        <div class="control-icon"><img src="../assets/icons/soon.png" alt="" class="control-logo"></div>
                        <div class="control-text">Shuffle<br>Seeds</div>
                    </button>
                    <button class="control-btn finish remove">
                        <div class="control-icon"><img src="../assets/icons/trophy.png" alt="" class="control-logo"></div>
                        <div class="control-text">Remove players<br>from brackets</div>
                    </button>
                    <button class="control-btn end removeDB">
                        <div class="control-icon"><img src="../assets/icons/end.png" alt="" class="control-logo"></div>
                        <div class="control-text">Remove players<br>from DB</div>
                    </button>
                </div>
            </div>
        </div>
    <div class="input-section">
        <div class="input-card">
            <form action="add.php" method="POST">
                <h2 class="input-title">Create a preset</h2>
                <div class="input-container">
                    <label for="title" class="label-title">Title</label>
                    <input type="text" class="input title" name="title" id="title" placeholder="Singles unlimited..." require>
        
                    <label for="rules" class="label-rules">Rules</label>
                    <input type="text"class="input rules" name="rules" id="rules" placeholder="Povolena 1 legenda/ultrabeast/paradox..." require>
        
                    <label for="rewards" class="label-rewards">Rewards</label>
                    <input type="text" class="input rewards" name="rewards" id="rewards" placeholder="1. 5 event klíčů navíc..." require>
                </div>
                <button type="submit" class="preset-confirm-btn create-button">Create</button>
            </form>
        </div>
        <div class="input-card remove-card">
                <h2 class="input-title">Add & Remove</h2>
                <div class="input-container">
                    <form action="addPlayer.php" method="POST">
                        <label for="add-player" class="label-title">Add a player</label>

                        <div class="input-section2">
                        <input type="text" class="remove-input" name="add-player" id="add-player" placeholder="Paklicek" require>
                        <button type="submit" class="preset-confirm-btn remove-button">Add player</button>
                        </div>
                    </form>
                    <form action="removePreset.php" method="POST">
                        <label for="remove-tournament" class="label-rules">Remove a preset</label>

                        <div class="input-section2">
                            <select name="remove_tournament" class="tournament-selector2" >
                            <option value="">-- Select Tournament --</option>
                            <?php foreach ($tournament_options as $value => $display_name): ?>
                            <option value="<?php echo htmlspecialchars($value); ?>">
                                <?php echo htmlspecialchars($display_name); ?>
                            </option>
                            <?php endforeach; ?>
                            </select>
                            <button type="submit" class="preset-confirm-btn remove-button">Remove preset</button>
                        </div>
                    </form>

                </div>
        </div>
    </div>
    </main>
</body>
</html>