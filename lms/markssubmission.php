<?php
// Check if submit_marks parameter is set
if(isset($_POST['submit_marks'])) {
    // Check if marks and submission_id are set
    if(isset($_POST['marks']) && isset($_POST['submission_id'])) {
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

        // Sanitize input
        $marks = intval($_POST['marks']);
        $submission_id = intval($_POST['submission_id']);
        $roll_number = "some_roll_number"; // Add roll number

        // Check if marks already exist for this submission
        $existing_marks_query = "SELECT marks FROM marks WHERE submission_id = ?";
        $stmt_existing_marks = $conn->prepare($existing_marks_query);
        $stmt_existing_marks->bind_param("i", $submission_id);
        $stmt_existing_marks->execute();
        $stmt_existing_marks->store_result();
        $num_rows = $stmt_existing_marks->num_rows;

        if ($num_rows > 0) {
            // If marks already exist, update them
            $sql = "UPDATE marks SET marks = ? WHERE submission_id = ?";
        } else {
            // If marks do not exist, insert them
            $sql = "INSERT INTO marks (submission_id, assignment_id, marks, subject, roll_number, time) 
                    SELECT ?, assignment_id, ?, subject, ?, NOW() FROM student_submission WHERE submission_id = ?";
        }

        if ($stmt = $conn->prepare($sql)) {
            if ($num_rows > 0) {
                // Update existing marks
                $stmt->bind_param("ii", $marks, $submission_id);
            } else {
                // Insert new marks
                $stmt->bind_param("iisi", $submission_id, $marks, $roll_number, $submission_id);
            }

            if($stmt->execute()) {
                // Marks stored or updated successfully
                echo "Marks submitted successfully.";

                // Remove the file from uploads folder (Assuming you have the filename stored in the database)
                // Replace FILENAME_COLUMN with the actual column name that stores the filename
                $query = "SELECT file FROM student_submission WHERE submission_id = ?";
                $stmt3 = $conn->prepare($query);
                $stmt3->bind_param("i", $submissionId);
                $stmt3->execute();
                $stmt3->store_result();
                $stmt3->bind_result($filename);
                $stmt3->fetch();
                $file_path = 'uploads/' . $filename;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $stmt3->close();
            } else {
                echo "Error: Unable to store or update marks.";
            }
            $stmt->close();
        } else {
            echo "Error: Unable to prepare SQL statement to store or update marks.";
        }

        // Close connection
        $conn->close();
    } else {
        echo "Error: Marks or submission ID is missing.";
    }
} else {
    echo "Error: submit_marks parameter is missing.";
}
?>
