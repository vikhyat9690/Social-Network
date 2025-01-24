<?php


session_start();
require "./db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$bio = !empty($users['bio']) ? $users['bio'] : "This user has not updated their bio yet.";

$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// $profile_picture = $user['profile_picture'] ? "./uploads/" . $user['profile_picture'] : "default_pic.png";
$profile_picture = !empty($user['profile_picture']) ? "./uploads/" . $user['profile_picture'] : "./assests/default_pic.png";

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./assets/dashboard.css">
</head>

<body>
    <nav>
        <div>
            <img class="logo-dashboard" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqnL3QZSmErCRvtBDMv1pBq14E76QipOotaA&s" alt="">
        </div>
        <div class="links">
            <a href="dashboard.php">Home</a>
            <a href="profile.php">Profile</a>
        </div>
        <div class="img-logout">
            <a href="auth/logout.php" class="logout">Logout</a>
            <a href="profile.php"><img src="<?php echo $profile_picture; ?>" alt="" style="width: 30px; height: 30px; border-radius: 50%;"></a>
        </div>
    </nav>

    <div class="greet-container">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    </div>

    <div class="post-container-main">
        <div class="post-form">
            <div class="profile-block">
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture">
                <h3><?php echo $_SESSION['username']; ?></h3>
                <h4><?php echo $bio; ?></h4>
                <p>View your profile to update details</p>
            </div>
            <form id="postForm" enctype="multipart/form-data">
                <textarea name="content" placeholder="What's on your mind?"></textarea><br>
                <input type="file" name="image" accept="image/*"><br>
                <button type="submit">Post</button>
            </form>
            <div id="postMessage"></div>

        </div>

        <div class="display-post-main">
            <div id="postContainer">
                Post will be displayed here..
            </div>
        </div>

    </div>
    <script src="./scripts/post.js"></script>
</body>

</html>