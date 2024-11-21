<?php
session_start();
require '../functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Get the counts for subjects, students, failed, and passed students
$subjects_count = get_subject_count();
$students_count = get_student_count();
$failed_count = get_failed_students_count();
$passed_count = get_passed_students_count();

// Set the current page and page title
$currentPage = 'subjects';
$pageTitle = "Subjects";

require 'partials/header.php';
require 'partials/side-bar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Subjects</title>
</head>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h2>Add a New Subject</h2>

    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>



</main>

</html>