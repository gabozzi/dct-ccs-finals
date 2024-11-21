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
$currentPage = 'dashboard';
$pageTitle = "Dashboard";

$add_subPage = './subjects/add_subjects.php';
$studentPage = './students/';
$logoutPage = '';

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
    <title><?php echo $pageTitle; ?></title>
</head>

        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
            <h1 class="h2"><?php echo $pageTitle; ?></h1>

            <div class="row mt-5">
                <div class="col-12 col-xl-3">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                        <div class="card-body text-primary">
                            <h5 class="card-title"><?php echo $subjects_count; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                        <div class="card-body text-success">
                            <h5 class="card-title"><?php echo $students_count; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="card border-danger mb-3">
                        <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                        <div class="card-body text-danger">
                            <h5 class="card-title"><?php echo $failed_count; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="card border-success mb-3">
                        <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                        <div class="card-body text-success">
                            <h5 class="card-title"><?php echo $passed_count; ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</main>

</html>