<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .form-group input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .marks-table {
            margin-top: 20px;
        }

        .marks-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .marks-table table th, .marks-table table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .marks-table table th {
            background-color: #f2f2f2;
        }

        .marks-table table tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<div class="container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-group">
        <label for="assignment_id">Enter Assignment ID:</label>
        <input type="text" name="assignment_id" id="assignment_id">
        <input type="submit" value="Submit">
    </form>

    <?php
    if(isset($_POST['assignment_id'])) {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";         
        $dbname = "lms";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $assignment_id = $_POST['assignment_id'];
        $sql_marks = "SELECT * FROM marks WHERE assignment_id = ?";
        $stmt = $conn->prepare($sql_marks);
        $stmt->bind_param("i", $assignment_id);
        $stmt->execute();
        $result_marks = $stmt->get_result();

        if ($result_marks->num_rows > 0) {
            echo "<div class='marks-table'>";
            echo "<h2>Student Marks</h2>";
            echo "<table>";
            echo "<tr><th>Student ID</th><th>Marks</th></tr>";
            while ($row_marks = $result_marks->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_marks["roll_number"] . "</td>";
                echo "<td>" . $row_marks["marks"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</div>";
        } else {
            echo "No submissions found for this assignment.";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

</body>
</html>
