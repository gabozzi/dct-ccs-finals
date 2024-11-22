<?php
session_start();
require '../../functions.php';

$conn = db_connect(); // Ensure the connection is made.

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

$message = '';

// Ensure subject code is provided
if (!isset($_GET['code']) || empty($_GET['code'])) {
    header('Location: add_subjects.php'); // Redirect if no subject code is provided
    exit();
}

$subject_code = $_GET['code'];

// Fetch subject details by subject_code
$subject = get_subject_by_code($conn, $subject_code);

if (!$subject) {
    $message = "Subject not found.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve subject code and name from POST data
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    
    // Check if subject code and name are not empty
    if (empty($subject_code) || empty($subject_name)) {
        $message = "Subject code and name are required.";
    } else {
        // Call the function to update the subject in the database
        $update_status = update_subject($conn, $subject_code, $subject_name, $subject['subject_code']);

        if ($update_status) {
            header('Location: add_subjects.php'); // Redirect to subjects page after success
            exit();
        } else {
            $message = "An error occurred while updating the subject.";
        }
    }
}

$currentPage = 'subjects';
$pageTitle = "Edit Subject";

$dashboardPage = '../dashboard.php';
$add_subPage = './add_subjects.php';
$studentPage = './students/';
$logoutPage = '';

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

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $dashboardPage ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= $add_subPage ?>">Add Subject</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
        <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Edit Subject Form -->
        <form action="edit_subjects.php?code=<?php echo urlencode($subject_code); ?>" method="POST">
            <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>" />
            
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo isset($subject['subject_code']) ? htmlspecialchars($subject['subject_code']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo isset($subject['subject_name']) ? htmlspecialchars($subject['subject_name']) : ''; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </main>
</body>
</html>
