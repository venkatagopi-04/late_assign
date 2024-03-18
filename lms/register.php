<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    form {
        max-width: 400px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    label {
        font-weight: bold;
        color: #555;
    }
    input[type="text"],
    input[type="password"],
    input[type="email"],
    select {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    #studentFields,
    #teacherFields {
        display: none;
    }
    .field-group {
        margin-bottom: 15px;
    }
</style>
</head>
<body>

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
$name = $role = $mail = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $mail = $_POST["mail"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Insert data into corresponding table based on role
    if ($role == "student") {
        $rollNumber = $_POST["rollNumber"];
        $domainMail = $_POST["domainMail"];
        $branch = $_POST["branch"];
        $year = $_POST["year"];

        $sql = "INSERT INTO students (name, roll_number, email, branch, year, d_mail, password) 
                VALUES ('$name', '$rollNumber', '$domainMail', '$branch', '$year', '$mail', '$password')";
    } elseif ($role == "teacher") {
        $id = $_POST["id"];
        
        $sql = "INSERT INTO teachers (name, id, email, password) 
                VALUES ('$name', '$id', '$mail', '$password')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<h2>Registration Form</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="role">Register as:</label>
    <select name="role" id="role" onchange="showFields(this.value)">
        <option value="">Select...</option>
        <option value="student">Student</option>
        <option value="teacher">Teacher</option>
    </select>
    <br><br>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>

    <label for="mail">Mail:</label>
    <input type="email" id="mail" name="mail"><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password"><br><br>

    <div id="studentFields" style="display: none;">
        <label for="rollNumber">Roll Number:</label>
        <input type="text" id="rollNumber" name="rollNumber"><br><br>
        
        <label for="domainMail">Domain Mail:</label>
        <input type="email" id="domainMail" name="domainMail"><br><br>
        
        <label for="branch">Branch:</label>
        <input type="text" id="branch" name="branch"><br><br>
        
        <label for="year">Year:</label>
        <select id="year" name="year">
            <option value="1">1st year</option>
            <option value="2">2nd year</option>
            <option value="3">3rd year</option>
            <option value="4">4th year</option>
        </select><br><br>

    </div>

    <div id="teacherFields" style="display: none;">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id"><br><br>
    </div>

    <input type="submit" value="Register">
</form>

<script>
function showFields(role) {
    var studentFields = document.getElementById("studentFields");
    var teacherFields = document.getElementById("teacherFields");

    if (role === "student") {
        studentFields.style.display = "block";
        teacherFields.style.display = "none";
    } else if (role === "teacher") {
        studentFields.style.display = "none";
        teacherFields.style.display = "block";
    }
}
</script>

</body>
</html>
