<?php
session_start();
require "./db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

//Make user id the session id
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT USERNAME, EMAIL, BIO, PROFILE_PICTURE FROM USERS WHERE ID = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!--Web page part is started from here -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile</title>
    <link rel="stylesheet" href="./assets/profile.css">
</head>

<body>
    <div class="main">
        <h1>Profile</h1>
        <form action="./auth/update_profile.php" method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" name="username"><br>
            <label>Email:</label>
            <input type="email" name="email"><br>
            <label>Bio:</label>
            <textarea name="bio"></textarea> <br>
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture"><br>
            <button type="submit">Update Profile</button>
            <a href="dashboard.php">Return to the dashboard</a>
    </div>
    </form>
</body>

</html>