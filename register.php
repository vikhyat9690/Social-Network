<!DOCTYPE html>
<html lang="en">
<head>
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./assets/styles.css">
</head>
<body>
    <div class="main-container">
    <h2>Register form</h2>
    <form action="./auth/register.php" method="post" id="registerForm">
        <input type="text" name="username" placeholder="USERNAME" required id=""> <br><br>
        <input type="email" name="email" placeholder="EMAIL" required id=""><br><br>
        <input type="password" name="password" placeholder="PASSWORD" required id=""><br><br>
        <input type="text" name="bio" placeholder="BIO" id="">
        <input class="profile_pic" type="file" name="profile_picture" accept="images/*" id="">
        <button type="submit">Register</button>
    <p>Already registered <a href="login.php">Login</a></p>
    </form>
    <div id="registerResponse"></div>
    </div>
    <script src="./scripts/auth.js"></script>
</body>
</html>