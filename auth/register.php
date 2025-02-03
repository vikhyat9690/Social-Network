<?php
require "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $bio = htmlspecialchars(trim($_POST['bio']));
    
    if (!$username || !$email || !$password) {
        die("All fields are required.");
    }

    // Handle file upload
    $profile_picture = "../assets/1737661208_profile.jpg";
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
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_picture, bio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $username, $email, $password, $profile_picture, $bio);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
