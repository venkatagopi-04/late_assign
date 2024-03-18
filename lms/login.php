<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Add some basic styling for better presentation */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        form {
            max-width: 300px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4caf50;
            color: white;
            padding: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="id">Email:</label><br>
        <input type="text" id="id" name="id" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Retrieve user input
    $user_email = $_POST['id'];
    $user_password = $_POST['password'];

    // Prepare SQL statement to fetch user data based on role
    $sql_student = "SELECT * FROM students WHERE email = '$user_email' AND password = '$user_password'";
    $sql_teacher = "SELECT * FROM teachers WHERE email = '$user_email' AND password = '$user_password'";

    // Check if user exists and credentials match
    $result_student = $conn->query($sql_student);
    $result_teacher = $conn->query($sql_teacher);

    if ($result_student->num_rows == 1) {
        // Authentication successful for student, redirect to student dashboard with email as parameter
        header("Location: s_dashboard.php?email=" . urlencode($user_email));
        exit;
    } elseif ($result_teacher->num_rows == 1) {
        // Authentication successful for teacher, redirect to teacher dashboard with email as parameter
        header("Location: t_dashboard.php?email=" . urlencode($user_email));
        exit;
    } else {
        // Authentication failed, redirect back to login page with error message
        header("Location: login.php?error=1");
        exit;
    }

    // Close database connection
    $conn->close();
}
?>

</body>
</html>
