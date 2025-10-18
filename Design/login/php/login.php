<?php     
session_start(); 

$config = include __DIR__ . '../../../../config/db_credentials.php';

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
                        header('Location: /dashboard/php/dashboard.php');
                        exit;
                    }
                    else {
                        $error = 'Invalid username or password.';
                        $correct = false;
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
<html lang="cz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Login</title>
    <link rel="stylesheet" href="./../assets/css/login.css?v=19">
    <link rel="icon" type="image/png" href="../../index/icons/Logo.png?v=3">
</head>
<body>
    <div class="particles" id="particles"></div>

    <div class="login-container">
        <div class="logo-container">
            <img src="./../assets/icons/shield.png" class="logo">
        </div>
        <div class="container">
            <h1>Dashboard Login</h1>
            <p class="subtitle">Vítej zpět! Prosím přihlaš se ke svému účtu.</p>
            <?php 
                if(!$correct){
                    echo '<p class="error subtitle">' . $error . '</p>';
                }
            ?>
        </div>
        <form action="./login.php" method="POST">
            <div class="form-group">
                <label for="user">Uživatelské jméno</label>
                <input type="text" id="user" name="username" placeholder="Enter your username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Heslo</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="login-btn">Přihlásit se</button>
            <a href="../../index.php" class="Go-back"><- Jít zpátky</a>
        </form>
    </div>

    <script>
            function createParticles() {
            const particlesContainer = document.getElementById('particles');
            
            for (let i = 0; i < 30; i++) {
                setTimeout(() => {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 10) + 's';
                particlesContainer.appendChild(particle);
                }, 300); 
            }
        }


        // Button click animation - FIXED VERSION
        function addButtonAnimation() {
            const button = document.querySelector('.login-btn');
            
            button.addEventListener('click', function(e) {
                // Don't prevent default - let the form submit naturally
                
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.3);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        }

        // Add ripple animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            addButtonAnimation();
        });
    </script>
</body>
</html>