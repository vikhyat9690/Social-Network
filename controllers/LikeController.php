<?php

require_once "../db.php";

class LikeController
{
    public static function toggleLike($userId, $postId, $likeStatus)
    {
        global $conn;

        // Check if user has already liked/disliked the post
        $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->bind_param('ii', $userId, $postId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing like/dislike
            $stmt = $conn->prepare("UPDATE likes SET like_status = ? WHERE user_id = ? AND post_id = ?");
            $stmt->bind_param('iii', $likeStatus, $userId, $postId);
        } else {
            // Insert a new like/dislike
            $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id, like_status) VALUES (?, ?, ?)");
            $stmt->bind_param('iii', $userId, $postId, $likeStatus);
        }

        return $stmt->execute();
    }
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $userId = $_SESSION['user_id'];
    $postId = $_POST['postId']; // Fixed: Use POST data from AJAX
    $likeStatus = $_POST['likeStatus']; // Fixed: Use POST data from AJAX

    if (LikeController::toggleLike($userId, $postId, $likeStatus)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
