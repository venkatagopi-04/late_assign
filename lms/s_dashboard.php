<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar styles */
        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 20px;
            float: left;
        }

        .sidebar h2 {
            color: #333;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            
        }

        .sidebar ul li a:hover {
            color: #333;
        }

        /* Content styles */
        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .header h1 {
            color: #333;
        }

        /* Assignment styles */
    .assignment {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 20px;
    margin-bottom: 20px;
    cursor: pointer;
    transition: box-shadow 0.3s ease; /* Smooth transition for box-shadow */
}

.assignment:hover {
    box-shadow: 0 0 50px rgba(88, 89, 166, 0.7); /* Add shadow on hover */
}


        .assignment h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .assignment p {
            margin: 0;
        }

        .assignment button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }

        /* Submission form styles */
        .submission-form {
            margin-top: 20px;
        }

        .submission-form h4 {
            margin-top: 0;
        }

        .submission-form input[type="file"] {
            margin-bottom: 10px;
        }

        .submission-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
        }
        /* Shining effect animation */
@keyframes shine {
  0% {
    background-position: -200px;
  }
  100% {
    background-position: calc(100% + 200px);
  }
}

/* Normal state styling */
a.shining {
  display: inline-block;
  padding: 10px 20px;
  background: linear-gradient(90deg, #2196F3, #64B5F6); /* Blue gradient background */
  color: #fff;
  text-decoration: none;
  border-radius: 20px;
  transition: background 0.3s ease;
  background-size: 200% auto;
  animation: shine 4s infinite linear;
}

/* Hover state styling */
a.shining:hover {
  background-position: calc(100% + 200px);
}
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            
            <?php
              if(isset($_GET['email'])) {
                // Get email from URL parameter
                $user_email = $_GET['email'];

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

                // Prepare SQL statement to fetch student's roll number based on email
                $sql = "SELECT roll_number FROM students WHERE email = '$user_email'";
                $result = $conn->query($sql);

                // If result is found, display roll number
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $student_roll_number = $row['roll_number'];
                    
                   
                }
            }
            if (isset($student_roll_number)) {
                echo "<li><a href='s_profile.php?rnum=" . urlencode($student_roll_number) . "' class='shining'>Profile</a></li>";
            }
            echo "<br>";
            if (isset($student_roll_number)) {
                echo "<li><a href='s_marks.php?rnum=" . urlencode($student_roll_number) . "'  class='shining'>Grades</a></li>";
            }
            echo "<br><li><a href='login.php' class='shining'>logout</a></li>";
            ?>
            
        </ul>
    </div>
    <div class="content">
        <div class="header">
            <h1>Welcome, Student</h1>
            <?php
            // Check if email is set
            if(isset($_GET['email'])) {
                // Get email from URL parameter
                $user_email = $_GET['email'];

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

                // Prepare SQL statement to fetch student's roll number based on email
                $sql = "SELECT roll_number FROM students WHERE email = '$user_email'";
                $result = $conn->query($sql);

                // If result is found, display roll number
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $student_roll_number = $row['roll_number'];
                    
                    echo "<p>Roll Number: " . $student_roll_number . "</p>";
                } else {
                    echo "Roll number not found.";
                }

                // Close connection
                $conn->close();
            } else {
                echo "Email not provided.";
            }
            ?>
        </div>
        <div class="assignments">
            <h2>Assignments</h2>
            <?php
            // Check if roll number is set
            if(isset($student_roll_number)) {
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
                $sql = "SELECT roll_number, year, branch FROM students WHERE email = '$user_email'";

                $result = $conn->query($sql);

                // If result is found, display roll number
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $student_roll_number = $row['roll_number'];
                    $student_year = $row['year'];
                    $student_branch = $row['branch'];
                }

                

                // Fetch assignments based on student's branch and year
                $sql = "SELECT * FROM assignments WHERE branch = '$student_branch' AND year = '$student_year' ORDER BY id DESC";
                $result = $conn->query($sql);

                // Display assignments
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='assignment'>";

                        echo "<h3>" . $row["name"] . "</h3> <br>";
                        echo "<p>Branch: " . $row["branch"] . "</p><br>";
                        echo "<p>Year: " . $row["year"] . "</p><br>";
                        echo "<p>Subject: " . $row["subject"] . "</p><br>";
                        echo "<p>Due Date: " . $row["due_date"] . "</p><br>";
                        
                        echo "<button style='display: inline-block;
                padding: 10px 20px;
                background: linear-gradient(90deg, red, tomato);
                color: #fff;
                text-decoration: none;
                border-radius: 20px;
                transition: background 0.3s ease;
                background-size: 200% auto;
                animation: shine 4s infinite linear;
                border: none;
                cursor: pointer;'
         onclick=\"window.open('" . $row['attachment'] . "')\">View Attachment</button><br><br>";

                        // Display button to view attachment
                        echo "<a href='submissionform.php?assignment_id=" . $row["id"] . "&student_rollnumber=" . $student_roll_number . "'  class='shining'>Submit Assignment</a>";

                        echo "</div>";
                    }
                } else {
                    echo "No assignments found.";
                }

                // Close connection
                $conn->close();
            } else {
                echo "Roll number not set.";
            }
            ?>
        </div>
    </div>

    
</body>
</html>
