<?php
session_start();

$config = include __DIR__ . '/../config/db_credentials.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 75%; /* adjust ratio as needed */
            height: 0;
        }
        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

    </style>
    <script src="gpt.js?v=8" defer></script>
</head>
<body>
<button id="delete-players">Delete Players</button>
<button id="start-tournament">Start Tournament</button>
<button id="finish-tournament">Finish Tournament</button>
<button id="reset-tournament">Reset Tournament</button>
<button id="add-players">Add Players</button>
<button id="shuffle-seeds">Shuffle Seeds</button>
<button id="winner">Winner</button>
<button id="get-users">Get Users</button>
<button id="get-matches">Get Matches</button>


<div id="result"></div>

<div class="iframe-container">
    <iframe src="https://challonge.com/cs/myTest92521/module?theme=2" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>
</div>
</body>
</html>
