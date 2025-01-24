<?php
    $host = 'localhost';
    $username = 'root';
    $password = 'Vikhyat@9690';
    $dbname = 'social_network';

    $conn = new mysqli($host, $username, $password, $dbname);

    if($conn->connect_error) {
        die("Error in connecting to database : ". $conn->connect_error);
    }
    