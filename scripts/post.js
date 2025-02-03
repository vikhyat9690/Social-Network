$(document).ready(function() {

    //handle post submission
    $('#postForm').submit(function (e) {

        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '../controllers/PostController.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                const result = JSON.parse(response);
                if(result.status === 'success') {
                    $('#postMessage').html('Post created successfully..!');
                    $('#postForm')[0].reset();
                    fetchPosts(); //refresh posts
                } else {
                    $('#postMessage').html('An error occurred while creating this post.');
                }
            },
            error: function() {
                $('#postMessage').html('An error occurred');
            }
        });
    });



    //Fetch and display post
    function fetchPosts() {
        $.ajax({
            url: '../controllers/PostController.php',
            type: 'GET',
            success: function (response) {
                $('#postContainer').html(response);
            }
        });
    }

    fetchPosts();


    //Handle likes and dislikes
    $(document).on('click', '.like-btn', function (){
        const postId = $(this).data('postId');
        const likeStatus = $(this).data('likeStatus');

        $.ajax({
            url: '../controllers/LikeController.php',
            type: 'POST',
            data: {postId, likeStatus},
            success: function (response) {
                const result = JSON.parse(response);
                if(result.status === 'success') {
                    fetchPosts();  //Refresh all the posts
                } else {
                    alert('Error liking/disliking posts');
                }
            }
        });
    });

    // Handle deleting a post
$(document).on('click', '.delete-btn', function () {
    const postId = $(this).data('postId');

    if (confirm('Are you sure you want to delete this post?')) {
        $.ajax({
            url: '../controllers/PostController.php',
            type: 'DELETE',
            data: { postId: postId },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    $(`.post-${postId}`).remove(); // Remove the post from the DOM
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function () {
                alert('An error occurred while deleting the post.');
            }
        });
    }
});

    
});