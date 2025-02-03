<?php
session_start();
require "./db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

$conn->begin_transaction();

//For profile_picture
$stmt = $conn->prepare("SELECT profile_picture, bio, username FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// $profile_picture = $user['profile_picture'] ? "./uploads/" . $user['profile_picture'] : "default_pic.png";
$profile_picture = !empty($user['profile_picture']) ? "./uploads/" . $user['profile_picture'] : "./assests/default_pic.png";

$username = !empty($user['username']) ? $user['username'] : $_SESSION['username'];

$bio = !empty($user['bio']) ? $user['bio'] : "User has not updated bio yet."

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./assets/dashboard.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-logo">
            <img class="logo" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqnL3QZSmErCRvtBDMv1pBq14E76QipOotaA&s" alt="Logo">
        </div>
        <div class="navbar-links">
            <a href="dashboard.php">Home</a>
            <a href="profile.php">Profile</a>
        </div>
        <div class="navbar-profile">
            <a href="profile.php">
                <img src="<?php echo $profile_picture; ?>" alt="logo">
            </a>
            <a href="auth/logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <header class="welcome-header">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <p>Your personalized dashboard</p>
    </header>

    <main class="dashboard-content">
        <section class="profile-section">
            <div class="profile-card">
                <img src="<?php echo $profile_picture; ?>" alt="logo">
                <h2><?php echo $username; ?></h2>
                <p class="bio"><?php echo $bio; ?></p>
                <a href="profile.php" class="update-profile">Update Profile</a>
            </div>
        </section>

        <section class="post-section">
            <div class="post-form">
                <h2>Create a Post</h2>
                <form id="postForm" enctype="multipart/form-data">
                    <textarea name="content" placeholder="What's on your mind?"></textarea>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit">Post</button>
                </form>
                <div id="postMessage"></div>
            </div>
        </section>

        <section class="posts-display">
            <h2>Recent Posts</h2><hr><br><br>
            <div id="postContainer">
                Post will be displayed here..
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Your Company. All Rights Reserved.</p>
    </footer>

    <script src="./scripts/post.js"></script>
</body>

</html>
