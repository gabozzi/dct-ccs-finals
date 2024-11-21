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

// Function to get the count of failed students
function get_failed_students_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS failed_count 
            FROM students s 
            JOIN students_subjects ss ON s.id = ss.student_id
            WHERE ss.grade < 75";
    $result = $conn->query($sql);
    $failed_count = $result->fetch_assoc()['failed_count'];
    $conn->close();
    return $failed_count;
}

// Function to get the count of passed students
function get_passed_students_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS passed_count 
            FROM students s 
            JOIN students_subjects ss ON s.id = ss.student_id
            WHERE ss.grade >= 75";
    $result = $conn->query($sql);
    $passed_count = $result->fetch_assoc()['passed_count'];
    $conn->close();
    return $passed_count;
}

// Function to get all subjects
function get_all_subjects() {
    $conn = db_connect();
    $query = "SELECT subject_code, subject_name FROM subjects ORDER BY id ASC"; // Fetch all subjects in order
    $result = $conn->query($query);

    $subjects = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    }
    $conn->close();
    return $subjects;
}

// Function to get a single subject by ID
function get_subject_by_id($subject_id) {
    $conn = db_connect();
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $subject;
}

function is_subject_name_duplicate($subject_code, $subject_name) {
    $conn = db_connect();

    // Query to check for duplicates
    $sql = "SELECT * FROM subjects WHERE subject_code = ? OR subject_name = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $subject_code, $subject_name);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    $existing_subject = $result->fetch_assoc();

    // Check if a duplicate exists
    if ($existing_subject) {
        return "Invalid Input: Duplicate Subject Found";
    }

    return null;
}


// Function to insert a new subject
function insert_subject($subject_code, $subject_name) {
    $conn = db_connect(); // Use the existing DB connection function
    
    // Prepare the query to prevent SQL injection
    $query = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
    $stmt = $conn
    ->prepare($query);
    
    // Bind parameters and execute the query
    $stmt->bind_param("ss", $subject_code, $subject_name);  // "ss" for two strings
    
    if ($stmt->execute()) {
        return true;  // Return true if successful
    } else {
        return false;  // Return false if there was an error
    }
}







?>
