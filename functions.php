<?php

// Database connection
function db_connect() {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'your_database';

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Validate login using email
function validate_login($email, $password) {
    $conn = db_connect();
    $hashed_password = md5($password); // Hashed the password
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    
    return $user; // Returns user data if successful, or null if not
}
?>
