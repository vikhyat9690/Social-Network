<?php
session_start();
require_once "../db.php";


class PostController
{
    public static function createPost($userId, $content, $imagePath)
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $userId, $content, $imagePath);
        return $stmt->execute();
    }

    public static function fetchPosts()
    {
        global $conn;
        $query = "SELECT posts.*, users.username, 
                  (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND like_status = 1) AS likes,
                  (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id AND like_status = 0) AS dislikes
                  FROM posts 
                  JOIN users ON posts.user_id = users.id 
                  ORDER BY posts.created_at DESC";

        $result = $conn->query($query);
        $posts = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($posts as $post) {
            // Show delete button only for the post owner
            $deleteButton = ($post['user_id'] == $_SESSION['user_id']) ?
                "<button class='delete-btn' data-post-id='{$post['id']}'>Delete</button>" : "";

            echo "<div class='post'>
                        <p><strong>{$post['username']}</strong></p><br>
                        <p>{$post['content']}</p><br>";
            if ($post['image_path']) {
                echo "<img src='../uploads/{$post['image_path']}' alt='Post Image' style='max-width: 100%;'>";
            }
            echo "<button class='like-btn' data-post-id='{$post['id']}' data-like-status='1'>Like ({$post['likes']})</button>
                      <button class='like-btn' data-post-id='{$post['id']}' data-like-status='0'>Dislike ({$post['dislikes']})</button>
                      {$deleteButton}
                      </div><hr>";
        }
    }

    public static function deletePost($postId, $userId)
{
    global $conn;

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Verify ownership of the post
        $stmt = $conn->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            // Rollback transaction if the post doesn't belong to the user
            $conn->rollback();
            return ['success' => false, 'message' => 'Unauthorized or post not found'];
        }

        // Delete associated likes
        $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ?");
        $stmt->bind_param('i', $postId);
        $stmt->execute();

        // Delete the post
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param('i', $postId);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        return ['success' => true, 'message' => 'Post deleted successfully'];
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}

}

//Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $userId = $_SESSION['user_id'];
    $content = $_POST['content'];
    $imagePath = NULL;

    if (!empty($_FILES['image']['name'])) {
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $fileName = time() . '_post' . $fileExt;
        $target = '../uploads/' . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = $fileName;
        }
    }

    if (PostController::createPost($userId, $content, $imagePath)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    session_start();
    parse_str(file_get_contents("php://input"), $_DELETE); // Parse DELETE request data
    $postId = $_DELETE['postId'];
    $userId = $_SESSION['user_id'];

    if (PostController::deletePost($postId, $userId)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to delete the post.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    PostController::fetchPosts();
}
