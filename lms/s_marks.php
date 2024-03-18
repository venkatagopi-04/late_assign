<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container styles */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid blue;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid blue;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: lightblue;
        }
    </style>
</head>
<body>
    <div class="container">
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

// Check if roll number is provided in the URL
if(isset($_GET['rnum'])) {
    // Get roll number from the URL parameter
    $rollNumber = $_GET['rnum'];

    // Fetch marks from the marks table for the given roll number
    $sql = "SELECT * FROM marks WHERE roll_number = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $rollNumber);

        // Execute the statement
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Check if there are any marks
            if ($result->num_rows > 0) {
                // Start table with CSS styling
                echo "<table style='border-collapse: collapse; border: 2px solid blue;'>";
                echo "<tr style='background-color: lightblue;'><th>Assignment ID</th><th>Assignment Name</th><th>Marks</th></tr>";
                
                // Loop through the result set and output data in table rows
                while ($row = $result->fetch_assoc()) {
                    // Fetch assignment name based on assignment ID
                    $assignmentID = $row["assignment_id"];
                    $assignmentNameQuery = "SELECT name FROM assignments WHERE id = '$assignmentID'";
                    $assignmentNameResult = $conn->query($assignmentNameQuery);
                    $assignmentNameRow = $assignmentNameResult->fetch_assoc();
                    $assignmentName = $assignmentNameRow["name"];
                    
                    echo "<tr>";
                    echo "<td style='border: 1px solid blue;'>" . $row["assignment_id"] . "</td>";
                    echo "<td style='border: 1px solid blue;'>" . $assignmentName . "</td>";
                    echo "<td style='border: 1px solid blue;'>" . $row["marks"] . "</td>";
                    echo "</tr>";
                }

                // End table
                echo "</table>";
            } else {
                echo "No marks found for roll number: $rollNumber";
            }
        } else {
            echo "Error: Unable to execute SQL statement.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: Unable to prepare SQL statement.";
    }
} else {
    echo "Roll number not provided.";
}

// Close connection
$conn->close();
?>
        <!-- Your PHP code for displaying the table goes here -->
    </div>
</body>
</html>
