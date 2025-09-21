<?php     
session_start(); 



$config = include __DIR__ . '/../config/db_credentials.php';

$servername = $config['host'];
$dbname = $config['db'];
$db_user = $config['user'];
$db_pass = $config['pass'];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        $conn = new mysqli($servername, $db_user, $db_pass, $dbname);
        
        if ($conn->connect_error) {
            $error = "Connection failed: " . $conn->connect_error;
        } else {
            $stmt = $conn->prepare("SELECT username, password_hash, perms FROM users WHERE username = ?");
            
            if ($stmt) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $row = $result->fetch_assoc()) {
                    $dbusername = $row['username'];
                    $password_hash = $row['password_hash'];
                    $perms = $row['perms'];

                    if (password_verify($password, $password_hash)) {
                        if($perms === 1){
                            $_SESSION['permission'] = true;
                        }
                        else{
                            $_SESSION['permission'] = false;

                        }
                        header('Location: settings.php');
                        exit;
                    } 
                    else {
                        $error = 'Invalid username or password.';
                    }
                } else {
                    $error = 'Invalid username or password.';
                }
                $stmt->close();
            } else {
                $error = 'Query prepare failed: ' . $conn->error;
            }
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PokeLand Tournament</title>
    <link rel="stylesheet" href="login.css?v=9">
    <script src="animation.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">PokeLand Tournament Management</p>
            </div>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Enter your username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Login
                </button>
            </form>

            <div class="back-link">
                <a href="index.php">← Back to Tournament</a>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>