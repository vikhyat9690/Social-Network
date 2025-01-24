<?php
 require "../db.php";

 if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $bio = $_POST['bio'];
    $profile_picture = null;

    if(isset($_FILES['profile_picture']['name'])) {
        $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $file_name = time() . '_profile' . $file_ext;
        $target = '../uploads' . $file_name;

        //Move file to the target library
        if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
            $profile_picture = $file_name;
        } else {
            die("Error in uploading in the profile picture");
        }
    }

    $stmt = $conn->prepare("INSERT INTO USERS (USERNAME, EMAIL, PASSWORD, PROFILE_PICTURE, BIO) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $username, $email, $password, $profile_picture, $bio);

    if($stmt->execute()) {
        echo "success";
    } else {
        echo "Error in registering user : ". $stmt->error;
    }
 }