<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./assets/login.css">
</head>
<body>
    <div class="main-container">
    <h2>Login Form</h2>
    <form action="./auth/login.php" id="loginForm" method="post">
        <input type="email" name="email" placeholder="EMAIL" required><br><br>
        <input type="password" name="password" placeholder="PASSWORD" required id=""><br><br>
        <button type="submit">Login</button>
        <p>New here <a href="register.php">Register</a></p>
    </form>
    <div id="loginResponse"></div>
    </div>
    <script src="./scripts/auth.js"></script>
</body>
</html>