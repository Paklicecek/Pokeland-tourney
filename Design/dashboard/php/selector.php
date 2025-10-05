<?php

$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

$success_message = '';
$error_message = '';

$titles = [];

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
} else {
    $tournament_options = array('Default Tournament' => 'Default Tournament');
}
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
    //CALL TO PRESET ON API SIDE
    include "../API/Preset.php";
    $_SESSION['Response'] = true;
    
}
else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['Response'] = false;
    }
}
?>