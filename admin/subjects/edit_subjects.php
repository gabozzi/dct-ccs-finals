<?php
session_start();
require '../../functions.php';

guard();

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

// Fetch subject details based on the subject code
$subject = get_subject_by_code($subject_code);

// If the subject does not exist, redirect back
if (!$subject) {
    header('Location: add_subjects.php');
    exit();
}

// Handle form submission for updating the subject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get subject name from the POST data
    $subject_name = trim($_POST['subject_name']);

    // Validate input
    if (empty($subject_name)) {
        $message = "Subject name is required.";
    } else {
        // Check for duplicates
        $duplicate_error = is_subject_name_duplicate($subject_code, $subject_name);
        
        if ($duplicate_error) {
            $message = $duplicate_error;
        } else {
            // Update the subject in the database
            if (update_subject($subject_code, $subject_name)) {
                header('Location: add_subjects.php'); // Redirect after success
                exit();
            } else {
                $message = "An error occurred while updating the subject.";
            }
        }
    }
}

// Set the current page and title
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
                <li class="breadcrumb-item"><a href="<?= $add_subPage ?>">Subjects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
            </ol>
        </nav>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
        <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Edit Subject Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="edit_subjects.php?code=<?php echo urlencode($subject_code); ?>" method="POST">
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" readonly
                            value="<?php echo htmlspecialchars($subject['subject_code']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required
                            placeholder="Enter Subject Name"
                            value="<?php echo htmlspecialchars($subject['subject_name']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
