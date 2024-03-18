<?php
// Database connection parameters
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and initialize with empty values
$name = $branch = $year = $description = $due_date = $attachment = $subject = "";
$name_err = $branch_err = $year_err = $description_err = $due_date_err = $attachment_err = $subject_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the assignment name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate branch
    if (empty(trim($_POST["branch"]))) {
        $branch_err = "Please enter the branch.";
    } else {
        $branch = trim($_POST["branch"]);
    }

    // Validate year
    if (empty(trim($_POST["year"]))) {
        $year_err = "Please enter the year.";
    } else {
        $year = trim($_POST["year"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter the assignment description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate due date
    if (empty(trim($_POST["due_date"]))) {
        $due_date_err = "Please enter the due date.";
    } else {
        $due_date = trim($_POST["due_date"]);
    }

    // Validate subject
    if (empty(trim($_POST["subject"]))) {
        $subject_err = "Please enter the subject.";
    } else {
        $subject = trim($_POST["subject"]);
    }

    // Check if file was uploaded without errors
    $file_name = $_FILES['attachment']['name'];
    $file_size = $_FILES['attachment']['size'];
    $file_tmp = $_FILES['attachment']['tmp_name'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = array("doc", "docx", "pdf", "png", "jpg");

    if (in_array($file_ext, $allowed_exts) && $file_size < 10 * 1024 * 1024) { // less than 10MB
        $file_dest = "uploads/" . $file_name;
        move_uploaded_file($file_tmp, $file_dest);
        // Insert file name into the database
        $attachment = $file_dest;
    } else {
        $attachment_err = "Invalid file format or size.";
    }

    // If no errors, insert data into database
    if (empty($name_err) && empty($branch_err) && empty($year_err) && empty($description_err) && empty($due_date_err) && empty($attachment_err) && empty($subject_err)) {
        // Prepare SQL statement to insert assignment into database
        $sql = "INSERT INTO assignments (name, branch, year, description, due_date, attachment, subject) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssssss", $param_name, $param_branch, $param_year, $param_description, $param_due_date, $param_attachment, $param_subject);

            // Set parameters
            $param_name = $name;
            $param_branch = $branch;
            $param_year = $year;
            $param_description = $description;
            $param_due_date = $due_date;
            $param_attachment = $attachment;
            $param_subject = $subject;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to dashboard or display success message
                header("location: t_dashboard.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard</title>
<link rel="stylesheet" href="styles.css">
<style>
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #555;
    }
    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    .form-group textarea {
        resize: vertical;
    }
    .form-group .error {
        color: #ff0000;
        font-size: 14px;
    }
    .form-group input[type="file"] {
        margin-top: 5px;
    }
    .form-group input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 12px 20px;
        cursor: pointer;
    }
    .form-group input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Teacher Dashboard - Post Assignment</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Assignment Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <span class="error"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group">
            <label for="branch">Branch:</label>
            <input type="text" id="branch" name="branch" value="<?php echo htmlspecialchars($branch); ?>">
            <span class="error"><?php echo $branch_err; ?></span>
        </div>

        <div class="form-group">
            <label for="year">Year:</label>
            <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>">
            <span class="error"><?php echo $year_err; ?></span>
        </div>
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
            <span class="error"><?php echo $subject_err; ?></span>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            <span class="error"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="date" id="due_date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>">
            <span class="error"><?php echo $due_date_err; ?></span>
        </div>
        <div class="form-group">
            <label for="attachment">Attachment:</label>
            <input type="file" id="attachment" name="attachment">
            <span class="error"><?php echo $attachment_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" value="Post Assignment">
        </div>
    </form>
</div>
</body>
</html>
