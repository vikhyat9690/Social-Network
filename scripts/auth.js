$(document).ready( function() {
    //Register function
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post("./auth/register.php", formData,
            function (response) {
                if(response.trim() === 'success') {
                    window.location = 'login.php';
                }
                $('#registerResponse').text(response);
            },
        );
    })

    //Login function
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post("./auth/login.php", formData,
            function (response) {
                if(response.trim() === 'success') {
                    window.location = 'dashboard.php';
                } else {
                    $('#loginResponse').text(response);
                }
            },
        );
    })
})