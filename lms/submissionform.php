<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assignment_id']) && isset($_POST['student_rollnumber']) && isset($_FILES['file'])) {
        $assignment_id = $_POST['assignment_id'];
        $student_rollnumber = $_POST['student_rollnumber'];

        $sql = "SELECT * FROM assignments WHERE id = $assignment_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $subject = $row["subject"];

            $file_name = $_FILES['file']['name'];
            $file_size = $_FILES['file']['size'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = array("doc", "docx", "pdf", "png", "jpg");

            if (in_array($file_ext, $allowed_exts) && $file_size < 10 * 1024 * 1024) { // less than 10MB
                $file_dest = "uploads/" . $file_name;
                if (move_uploaded_file($file_tmp, $file_dest)) {
                    // Insert file name into the database
                    $sql = "INSERT INTO student_submission (subject, roll_number, assignment_id, submission_date, file) VALUES ('$subject', '$student_rollnumber', '$assignment_id', NOW(), '$file_name')";
                    if ($conn->query($sql) === TRUE) {
                        // Fetching student's email
                        $sql = "SELECT email FROM students WHERE roll_number = '$student_rollnumber'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $student_mail = $row['email'];
                            // Redirecting to the student dashboard with email parameter
                            header("Location: s_dashboard.php?email=$student_mail");
                            exit();
                        } else {
                            echo "Student email not found.";
                        }
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Failed to move uploaded file.";
                }
            } else {
                echo "Invalid file or file size exceeds 10MB limit.";
            }
        } else {
            echo "Assignment not found.";
        }
    } else {
        echo "Assignment ID, student roll number, or file not provided.";
    }
} else {
    if (isset($_GET['assignment_id']) && isset($_GET['student_rollnumber'])) {
        $assignment_id = $_GET['assignment_id'];
        $student_rollnumber = $_GET['student_rollnumber'];
        echo "<div class='container'>";
        echo "<h2>Upload Assignment</h2>";
        echo "<p>Assignment ID: $assignment_id</p>";
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post' enctype='multipart/form-data'>";
        echo "<label for='file' class='file-upload-btn'>Choose File</label>";
        echo "<input type='file' name='file' id='file' accept='.doc,.docx,.pdf,.png,.jpg' style='display: none;'><br>";
        echo "<input type='hidden' name='assignment_id' value='$assignment_id'>";
        echo "<input type='hidden' name='student_rollnumber' value='$student_rollnumber'>";
        echo "<input type='submit' value='Upload' class='submit-btn'>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "Assignment ID or Student Roll Number not provided.";
    }
}

// Close connection
$conn->close();
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        text-align: center;
        margin-top: 20px;
    }

    .file-upload-btn {
        display: inline-block;
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-btn:hover {
        background-color: #45a049;
    }

    input[type="file"] {
        display: none;
    }

    .submit-btn {
        display: block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #008CBA;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #005f6b;
    }
</style>
