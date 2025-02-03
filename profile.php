<?php
session_start();
require "./db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn->begin_transaction();

//For profile_picture
$stmt = $conn->prepare("SELECT profile_picture, email, bio, username FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// $profile_picture = $user['profile_picture'] ? "./uploads/" . $user['profile_picture'] : "default_pic.png";
$profile_picture = !empty($user['profile_picture']) ? "./uploads/" . $user['profile_picture'] : "./assests/default_pic.png";

$username = !empty($user['username']) ? $user['username'] : $_SESSION['username'];
$email = !empty($user['email']) ? $user['email'] : $_SESSION['email'];
$bio = !empty($user['bio']) ? $user['bio'] : "User has not updated bio yet."
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
            <div class="form-container">
                <!-- Display profile picture -->
            <div class="left">
            <img src="<?php echo $profile_picture; ?>" width="150">
            </div>
            <hr>
            <div class="right">
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"><br>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo $email; ?>"><br>
            <label>Bio:</label>
            <br>
            <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea><br>
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture"><br>
            <button type="submit">Update Profile</button>
            <a href="dashboard.php">Return to the dashboard</a>
            </div>
            </div>
    </div>
    </form>
</body>

</html>