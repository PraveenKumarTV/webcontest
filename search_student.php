<?php
// Database connection details
$servername = "localhost";
$username = "root";    // Default MySQL username for XAMPP
$password = "";        // Default MySQL password for XAMPP
$dbname = "studentdb"; // The database to query

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the query variable and result array
$query = "SELECT * FROM students WHERE 1=1"; // Start with a valid condition
$conditions = [];
$results = [];

// Check if the search form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data (student name, year, tutor name)
    $student_name = trim($_POST['student_name']);
    $year_of_study = trim($_POST['year_of_study']);
    $tutor_name = trim($_POST['tutor_name']);

    // Build query based on user input
    if (!empty($student_name)) {
        $conditions[] = "student_name LIKE '%$student_name%'";
    }
    if (!empty($year_of_study)) {
        $conditions[] = "year_of_study = '$year_of_study'";
    }
    if (!empty($tutor_name)) {
        $conditions[] = "tutor_name LIKE '%$tutor_name%'";
    }

    // If there are conditions, append them to the query
    if (count($conditions) > 0) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    // Execute the query
    $result = $conn->query($query);

    // Check if there are any results and store them
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $results = null; // No results found
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .container input, .container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #45a049;
        }
        .table-container {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-results {
            color: red;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Search Student Details</h2>

        <!-- Form to select search criteria -->
        <form action="search_student.php" method="POST">
            <input type="text" name="student_name" placeholder="Enter Student Name">
            <input type="text" name="year_of_study" placeholder="Enter Year of Study">
            <input type="text" name="tutor_name" placeholder="Enter Tutor Name">
            <button type="submit">Search</button>
        </form>

        <!-- Display search results in a table if available -->
        <div class="table-container">
            <?php if (isset($results)): ?>
                <?php if ($results): ?>
                    <table>
                        <tr>
                            <th>Student Name</th>
                            <th>Year of Study</th>
                            <th>Student ID</th>
                            <th>Phone Number</th>
                            <th>Tutor Name</th>
                        </tr>
                        <?php foreach ($results as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['year_of_study']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($student['tutor_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <!-- Display "No data found" message -->
                    <p class="no-results">No data found for your search criteria.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
