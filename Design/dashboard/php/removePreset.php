<?php
session_start();
$config = include __DIR__ . '../../../../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$username = $config['user'];
$password = $config['pass'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['remove_tournament'])) {

    $presetToRemove = $_POST['remove_tournament'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("DELETE FROM presets WHERE title=?");
        if($stmt){
            $stmt->bind_param('s', $presetToRemove);
            if ($stmt->execute()) {
                $success_message = "Inserted new preset. Insert ID: " . $conn->insert_id;
                $_SESSION['Response'] = true;
                header('Location: dashboard.php');
                exit();
            }
            else {
                $_SESSION['Response'] = false;
            }
            $stmt->close();
        }
    }
    $conn->close();
    header('Location: dashboard.php');
    exit();
}
?>