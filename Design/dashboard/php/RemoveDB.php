<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "removeDB") {
    echo "<p class="success">Please wait 2 seconds for response!</p>";
    $_SESSION["Response"] = true;
    $config = include __DIR__ . "../../../../config/db_credentials.php";

    $servername = $config["host"];
    $dbname = $config["db"];
    $username = $config["user"];
    $password = $config["pass"];
    $success_message = "";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        $error_message = "Connection failed: " . $conn->connect_error;
    } 
    else {
        $stmt = $conn->prepare("DELETE FROM Players");
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
} else {
    echo "Invalid request.";
}
?>
