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
    $email = trim($email);
    $password = md5(trim($password)); // Use MD5 hashing to match stored password

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['password'] === $password) {
        $stmt->close();
        $conn->close();
        return $user; // Return user data if valid
    }

    $stmt->close();
    $conn->close();
    return "Invalid email or password.";
}


function logout($indexPage) {
    // Unset the 'email' session variable
    unset($_SESSION['email']);

    // Destroy the session
    session_destroy();

    // Redirect to the login page (index.php)
    header("Location: $indexPage");
    exit;
}

function guard_login(){
    
    $dashboardPage = 'dashboard.php';

    if(isset($_SESSION['email'])){
        header("Location: $dashboardPage");
    } 
}




// FUNCTION TO GET SUBJECTS COUNT
function get_subject_count() {
    $conn = db_connect();
    $sql = "SELECT COUNT(*) AS subject_count FROM subjects";
    $result = $conn->query($sql);
    $subject_count = $result->fetch_assoc()['subject_count'];
    $conn->close();
    return $subject_count;
}

// FUNCTION TO GET COUNTS OF STUDENTS
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
function insert_subject($conn, $subject_code, $subject_name) {
    $query = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subject_code, $subject_name);

    if ($stmt->execute()) {
        $stmt->close();
        return "Subject added successfully.";
    } else {
        return "Error: " . $stmt->error; // Return detailed error message
    }
}


// Get subject by code
function get_subject_by_code($conn, $subject_code) {
    $query = "SELECT * FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $subject_code);  // Only bind $subject_code
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to update a subject
function update_subject($conn, $subject_code, $subject_name, $original_subject_code) {
    // SQL to update the subject record
    $query = "UPDATE subjects SET subject_code = ?, subject_name = ? WHERE subject_code = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters
        $stmt->bind_param("sss", $subject_code, $subject_name, $original_subject_code);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Successfully updated
        } else {
            return false; // Failed to update
        }
    } else {
        return false; // Query preparation failed
    }
}





// Delete subject
// Function to delete a subject
function delete_subject($conn, $subject_code) {
    $query = "DELETE FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $subject_code);  // Bind the subject code parameter
    return $stmt->execute();  // Return true if the delete is successful
}





?>