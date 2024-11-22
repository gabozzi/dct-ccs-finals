<?php
session_start();
require '../../functions.php';

// Ensure the database connection is established
$conn = DB_CONNECT();

// guard_login();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Ensure subject code is provided
if (!isset($_GET['code']) || empty($_GET['code'])) {
    header('Location: add_subjects.php'); // Redirect if no subject code is provided
    exit();
}

$subject_code = $_GET['code'];

// Fetch subject details based on the subject code
$subject = get_subject_by_code($conn, $subject_code);  // Pass $conn to the function

// If the subject does not exist, redirect back
if (!$subject) {
    header('Location: add_subjects.php');
    exit();
}

// Handle form submission for deleting the subject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Call the delete function to remove the subject
    if (delete_subject($conn, $subject_code)) {
        header('Location: add_subjects.php'); // Redirect after success
        exit();
    } else {
        $message = "An error occurred while deleting the subject.";
    }
}

// Set the current page and title
$currentPage = 'delete subject';
$pageTitle = "Delete Subject";

$dashboardPage = '../dashboard.php';
$add_subPage = './add_subjects.php';
$studentPage = './students/';
$logoutPage = 'logout.php';

require '../partials/header.php';
require '../partials/side-bar.php';
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

<body>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
        <h2><?php echo $pageTitle; ?></h2>

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $dashboardPage ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $add_subPage ?>">Subjects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
            </ol>
        </nav>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
        <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Delete Subject Confirmation -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="delete_subject.php?code=<?php echo urlencode($subject_code); ?>" method="POST">
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" readonly
                            value="<?php echo htmlspecialchars($subject['subject_code']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" readonly
                            value="<?php echo htmlspecialchars($subject['subject_name']); ?>">
                    </div>
                    <p class="text-danger">Are you sure you want to delete this subject?</p>
                    <a href="<?= $add_subPage ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-danger">Delete Subject</button>
                </form>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
