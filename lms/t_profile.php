<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
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

        // Check if email is provided in the URL
        if(isset($_GET['email'])) {
            // Get email from the URL parameter
            $email = $_GET['email'];

            // Fetch teacher details from the database based on email
            $sql = "SELECT * FROM teachers WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Display teacher details
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='student-card'>";
                    echo "<h2>" . $row['name'] . "</h2>";
                    echo "<p><strong>ID:</strong> " . $row['id'] . "</p>";
                    echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
                    echo "<li><a href='marks.php?email=" . urlencode($email) . "'>GET MARKS</a></li>";
                    echo "</div>";
                }
            } else {
                echo "No teacher found with email: $email";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Email not provided.";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
