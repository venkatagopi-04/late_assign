<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard</title>
<link rel="stylesheet" href="styles.css">
<style>
    /* Your CSS styles here */
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
        color: #555;
    }

    .sidebar ul li a:hover {
        color: #333;
    }

    .content {
        margin-left: 220px;
        padding: 20px;
    }

    .header {
        margin-bottom: 20px;
    }

    .header h1 {
        color: #333;
    }

    .header form {
        margin-top: 10px;
    }

    .header input[type="text"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .header button {
        padding: 8px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .assignments {
        margin-bottom: 20px;
    }

    .assignments h2 {
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
    }

    .student-submissions {
        margin-top: 20px;
    }

    .submission {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .submission h3 {
        color: #333;
        margin-top: 0;
    }
    /* Normal state styling */
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
                $sql = "SELECT name FROM teachers WHERE email = '$user_email'";
                $result = $conn->query($sql);

                // If result is found, display roll number
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tname = $row['name'];
                    
                   
                }
            }
           
            if (isset($user_email)) {
                echo "<li><a href='t_profile.php?email=" . urlencode($user_email) . "' class='shining'>Profile</a></li>";

            }
            
            echo "<br>";

            if (isset($user_email)) {
                echo "<li><a href='marks.php' class='shining'>Grades</a></li>";
            }
            echo "<br><li><a href='login.php' class='shining'>logout</a></li>";
            ?>
            
        </ul>
    </div>
</div>
<div class="content">
    <div class="header">
        <h1>Welcome, Teacher</h1>
        <form action="#" method="get">
            <input type="text" name="search" placeholder="Search assignments...">
            <button type="submit">Search</button>
        </form>
        <br><a href="assignment.php" class='shining'>Add Assignments</a>

    </div>
    <div class="assignments">
        <h2>All Assignments</h2>
        <!-- Display all assignments here -->
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

        // Fetch and display assignments from the database
        $sql = "SELECT * FROM assignments ORDER BY id DESC"; // Order by ID to show newer assignments first
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='assignment' onclick='loadSubmissions(" . $row["id"] . ")'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p>Branch: " . $row["branch"] . "</p>";
                echo "<p>Year: " . $row["year"] . "</p>";
                echo "<p>Subject: " . $row["subject"] . "</p>";
                echo "<p>Due Date: " . $row["due_date"] . "</p>";
                // Display button to view attachment
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
         onclick=\"window.open('" . $row['attachment'] . "')\">View Attachment</button>";

                // Placeholder for student submissions
                echo "<div class='student-submissions' id='submissions-" . $row["id"] . "'></div>";

                echo "</div>"; // Close assignment div
            }
        } else {
            echo "No assignments found.";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</div>

<script>
    function loadSubmissions(assignmentId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("submissions-" + assignmentId).innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "student_submissions.php?assignment_id=" + assignmentId, true);
        xhttp.send();
    }


    

function submitMarks(submissionId) {
    
 
    var marks = document.querySelector("#marks_form_" + submissionId + " input[name='marks']").value;
    var submissionIdField = document.querySelector("#marks_form_" + submissionId + " input[name='submission_id']").value;
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "markssubmission.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
            
            // Optionally update the page here
        }
    };
    xhr.send("submit_marks=1&submission_id=" + encodeURIComponent(submissionIdField) + "&marks=" + encodeURIComponent(marks));
}

</script>

</body>
</html>
