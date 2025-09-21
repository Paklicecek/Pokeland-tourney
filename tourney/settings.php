<?php
session_start();

$config = include __DIR__ . '/../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

$success_message = '';
$error_message = '';

$titles = [];

if(!isset($_SESSION['permission']) || $_SESSION['permission'] !== true)
{
    header('Location: login.php');
    exit;
}

$current_tournament = isset($_SESSION['current_tournament']) ? $_SESSION['current_tournament'] : 'Default Tournament';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    }
    else{
        $res = $conn->query("SELECT title FROM presets ORDER BY title");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $titles[] = $row['title'];
            }
            $res->free();
        }
    }
    $conn->close();



$tournament_options = array();
if (!empty($titles)) {
    $tournament_options = array_combine($titles, $titles);
} 

$preset_title = "";
$preset_rewards = "";
$preset_rules = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_tournament'])) {
        $selected_tournament = $_POST['selected_tournament'];
        $_SESSION['current_tournament'] = $selected_tournament;
        $current_tournament = $selected_tournament;
        

        $success_message = "Tournament changed to: " . htmlspecialchars($selected_tournament);

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            $error_message = "Connection failed: " . $conn->connect_error;
        } else {

            $stmt = $conn->prepare("SELECT title, rules, rewards FROM presets WHERE title = ?");
            if ($stmt) {
                $stmt->bind_param('s', $current_tournament);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result && $result->num_rows > 0) {

                        $row = $result->fetch_assoc();

                        $preset_title = $row['title'];
                        $preset_rules = $row['rules'];
                        $preset_rewards = $row['rewards'];
                        $success_message .= "<br>Loaded preset: " . htmlspecialchars($preset_title);
                    }
                } 
                $stmt->close();
            } 
            $conn->close();
        }
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("DELETE FROM active_preset");
        if($stmt){
            if ($stmt->execute()) {
                $affected_rows = $stmt->affected_rows;
                if ($affected_rows > 0) {
                    $success_message .= "<br>Cleared previous active preset.";
                }
            } 
            $stmt->close();
        }
    }
    $conn->close();

    if (!empty($preset_title) && !empty($preset_rules) && !empty($preset_rewards)) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            $error_message = "Connection failed: " . $conn->connect_error;
        } 
        else {
            $stmt = $conn->prepare("INSERT INTO active_preset (title, rules, rewards) VALUES (?, ?, ?)");
            if($stmt){
                $stmt->bind_param('sss', $preset_title, $preset_rules, $preset_rewards);
                if ($stmt->execute()) {
                    $success_message = "Inserted new preset. Insert ID: " . $conn->insert_id;
                } 
                $stmt->close();
            }
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - PokeLand Tournament</title>
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tournament Settings</h1>
            <p>Manage your active tournament</p>
        </div>

        <div class="settings-card">
            <h2 class="settings-title">Select Current Tournament</h2>

            <div class="current-tournament">
                <strong>Currently Active:</strong> <?php echo htmlspecialchars($current_tournament); ?>
            </div>

            <?php if ($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="settings.php">
                <div class="form-group">
                    <label for="tournament_select" class="form-label">Choose Tournament:</label>
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

                <button type="submit" class="submit-btn">
                    Change Tournament
                </button>
            </form>

            <div class="add-tournament-section">
                <h3 class="form-label">Want to add more tournaments?</h3>
                <p class="info-text">
                    Contact the administrator of this website.
                </p>
            </div>
        </div>

        <div class="navigation">
            <a href="index.php" class="nav-link">← Back to Dashboard</a>
            <a href="logout.php" class="nav-link">Logout →</a>
        </div>
    </div>
</body>
</html>