<?php
session_start();
require '../../functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the subject code and name from POST data
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);

    // Check if subject code or name is empty
    if (empty($subject_code)) {
        $message = "Subject code is required.";
    } elseif (empty($subject_name)) {
        $message = "Subject name is required.";
    } else {
        // Check for duplicates
        $duplicate_error = is_subject_name_duplicate($subject_code, $subject_name);
        
        if ($duplicate_error) {
            $message = $duplicate_error;
        } else {
            // Insert the new subject
            if (insert_subject($subject_code, $subject_name)) {
                header('Location: add_subjects.php'); // Redirect to subjects page after success
                exit();
            } else {
                $message = "An error occurred while adding the subject.";
            }
        }
    }
}

// Get all subjects for display
$subjects = get_all_subjects();

// Set the current page and page title
$currentPage = 'subjects';
$pageTitle = "Add a New Subject";

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
                <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
            </ol>
        </nav>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
        <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Add Subject Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="add_subjects.php" method="POST">
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required
                            placeholder="Enter Subject Code"
                            value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required
                            placeholder="Enter Subject Name"
                            value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>
            </div>
        </div>

        <!-- Display List of Subjects -->
        <div class="card">
            <div class="card-header">
                <h4>Subject List</h4>
            </div>
            <div class="card-body">
                <?php if (count($subjects) > 0): ?>
                <!-- Subjects Table -->
                <table class="table table-light table-hover mb-4">
                    <thead class="table-primary">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td>
                                <a href="edit_subjects.php?code=<?php echo urlencode($subject['subject_code']); ?>"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_subjects.php?code=<?php echo urlencode($subject['subject_code']); ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this subject?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No subjects found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>