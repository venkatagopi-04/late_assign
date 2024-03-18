<?php
// Check if assignment_id parameter is set
if(isset($_GET['assignment_id'])) {
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

    // Prepare SQL statement to fetch student submissions for the specified assignment
    $sql = "SELECT s.*, m.marks FROM student_submission s LEFT JOIN marks m ON s.submission_id = m.submission_id WHERE s.assignment_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("i", $assignment_id);

        // Set the assignment_id parameter from the GET request
        $assignment_id = $_GET['assignment_id'];

        // Execute the statement
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Check if there are any submissions
            if ($result->num_rows > 0) {
                // Display student submissions
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='submission'>";
                    echo "<p>Submission Id: " . $row["submission_id"] . "</p>";
                    echo "<h3>Student ID: " . $row["roll_number"] . "</h3>";
                    echo "<p>Time: " . $row["submission_date"] . "</p>";
                    
                    // Generate a unique link for each file
                    $file_link = 'uploads/' . $row["file"];
                    echo "<a href='$file_link' target='_blank'><button>View File</button></a>";
                    
                    // Display marks if available
                    if (!is_null($row["marks"])) {
                        echo "<p>Marks: " . $row["marks"] . "</p>";
                    }
                    
                    // Add a form for entering marks
                    echo "<form id='marks_form_" . $row["submission_id"] . "' class='marks-form' method='post' action=''>";
                    echo "<input type='hidden' name='submission_id' value='" . $row["submission_id"] . "'>";
                    echo "<input type='number' name='marks' placeholder='Enter Marks'>";
                    echo "<input type='button' onclick='submitMarks(" . $row["submission_id"] . ")' value='Submit Marks'>";
                    echo "</form>";
                    
                    // Display submission file, text box for marks, etc.
                    echo "</div>";
                }
            } else {
                echo "No submissions found for this assignment.";
            }
        } else {
            echo "Error: Unable to execute SQL statement.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Unable to prepare SQL statement.";
    }

    // Close connection
    $conn->close();
} else {
    echo "Assignment ID parameter is missing.";
}
?>

<style>
   .submission {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 20px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: box-shadow 0.3s ease; /* Smooth transition for box-shadow */
}

.submission:hover {
    box-shadow: 0 0 50px rgba(88, 89, 166, 0.7); /* Add shadow on hover */
}

    .submission p,
    .submission h3 {
        margin: 0;
    }

    .submission button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submission button:hover {
        background-color: #45a049;
    }

    .marks-form {
        margin-top: 10px;
        display: flex;
        align-items: center;
    }

    .marks-form input[type='number'] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 10px;
    }

    .marks-form input[type='button'] {
        background-color: #008CBA;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .marks-form input[type='button']:hover {
        background-color: #005f6b;
    }
</style>
