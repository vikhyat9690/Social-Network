<?php
session_start();
require "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$bio = $_POST['bio'];

// Handle file upload
$profile_picture = NULL;
if (!empty($_FILES['profile_picture']['name'])) {
    // Extract file extension
    $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
    
    // Generate unique file name with extension
    $file_name = time() . '_profile.' . $file_ext;
    $target = '../uploads/' . $file_name;

    // Move uploaded file to the target directory
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
        $profile_picture = $file_name;
    }
} else {
    // If no new file is uploaded, keep the existing profile picture
    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $profile_picture = $user['profile_picture'];
}

// Update user details
if ($profile_picture) {
    // Update with profile picture
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, bio = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $username, $email, $bio, $profile_picture, $user_id);
} else {
    // Update without profile picture
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?");
    $stmt->bind_param('sssi', $username, $email, $bio, $user_id);
}

if ($stmt->execute()) {
    // Redirect back to the dashboard after successful update
    header("Location: ../dashboard.php");
    exit();
} else {
    echo "Error updating user profile: " . $stmt->error;
}
