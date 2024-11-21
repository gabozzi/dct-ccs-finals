<?php

// Database connection
function db_connect() {
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'dct-ccs-finals';

    $conn = new mysqli($host, $user, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Validate login using email
function validate_login($email, $password) {
    $conn = db_connect();
    $email = trim($email);  // Trim email to remove extra spaces
    $password = trim($password);  // Trim password to remove extra spaces

    // Query to check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If user exists, compare plain text passwords
    if ($user && $user['password'] === $password) {
        $stmt->close();
        $conn->close();
        return $user;  // Login successful
    }

    $stmt->close();
    $conn->close();
    return false;  // Invalid login
}
?>
