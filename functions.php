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
        return $user;
    }

    $stmt->close();
    $conn->close();
    return false;
}

//FUNCTION TO GET SUBJECTS COUNT
function get_subject_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS subject_count FROM subjects";
    $result = $conn->query($sql);
    $subject_count = $result->fetch_assoc()['subject_count'];
    $conn->close();
    return $subject_count;
}

//FNCTION TO GET COUNTS OF STUDENTS
function get_student_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS student_count FROM students";
    $result = $conn->query($sql);
    $student_count = $result->fetch_assoc()['student_count'];
    $conn->close();
    return $student_count;
}

//FUNCTION TO GET THE COUNT OF FAILED STUDENTS
function get_failed_students_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS failed_count 
            FROM students s 
            JOIN grades g ON s.id = g.student_id
            WHERE g.grade < 75";
    $result = $conn->query($sql);
    $failed_count = $result->fetch_assoc()['failed_count'];
    $conn->close();
    return $failed_count;
}
//FUNTCTION TO GET THE COUNT OF PASSED STUDENTS
function get_passed_students_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS passed_count 
            FROM students s 
            JOIN grades g ON s.id = g.student_id
            WHERE g.grade >= 75";
    $result = $conn->query($sql);
    $passed_count = $result->fetch_assoc()['passed_count'];
    $conn->close();
    return $passed_count;
}


?>
