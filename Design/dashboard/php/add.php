<?php
session_start();
$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

$presetTitle = $_POST['title'];
$presetRules = $_POST['rules'];
$presetRewards = $_POST['rewards'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title']) && !empty($_POST['rules']) && !empty($_POST['rewards'])) {

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("INSERT INTO presets (title, rules, rewards) VALUES (?, ?, ?)");
        if($stmt){
            $stmt->bind_param('sss', $presetTitle, $presetRules, $presetRewards);
            if ($stmt->execute()) {
                $success_message = "Inserted new preset. Insert ID: " . $conn->insert_id;
                $_SESSION['Response'] = true;
                header('Location: dashboard.php');
                exit();
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>