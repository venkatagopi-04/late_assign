<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
        }

        .student-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .student-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .student-card h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .student-card p {
            color: #666;
            margin-bottom: 5px;
        }

        /* Animation */
        @keyframes slideInFromLeft {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .student-card {
            animation: slideInFromLeft 0.5s ease-in-out;
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

            // Fetch student details from the database based on roll number
            $sql = "SELECT * FROM students WHERE roll_number = ?";
            if ($stmt = $conn->prepare($sql)) {
                // Bind parameters
                $stmt->bind_param("s", $rollNumber);

                // Execute the statement
                if ($stmt->execute()) {
                    // Get result set
                    $result = $stmt->get_result();

                    // Check if there are any records
                    if ($result->num_rows > 0) {
                        // Display student details
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='student-card'>";
                            echo "<h2>" . $row['name'] . "</h2>";
                            echo "<p><strong>Roll Number:</strong> " . $row['roll_number'] . "</p>";
                            echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                            echo "<p><strong>Branch:</strong> " . $row['branch'] . "</p>";
                            echo "<p><strong>Year:</strong> " . $row['year'] . "</p>";
                            
                                echo "<li><a href='s_marks.php?rnum=" . urlencode($row['roll_number']) . "'>Grades</a></li>";
                            
                            echo "</div>";
                        }
                    } else {
                        echo "No student found with roll number: $rollNumber";
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
    </div>
</body>
</html>
