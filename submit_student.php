<?php
// Database connection details
$servername = "localhost";
$username = "root";    // Default MySQL username for XAMPP
$password = "";        // Default MySQL password for XAMPP
$dbname = "studentdb"; // The database you want to create and use

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully (or already exists).<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database to use
$conn->select_db($dbname);

// Create the students table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    year_of_study INT NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    tutor_name VARCHAR(100) NOT NULL
)";

if ($conn->query($table_sql) === TRUE) {
    echo "Table 'students' is ready.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the form inputs
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $year_of_study = $_POST['year_of_study'];
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $tutor_name = $conn->real_escape_string($_POST['tutor_name']);

    // Prepare the SQL query to insert the data into the students table
    $insert_sql = "INSERT INTO students (student_name, year_of_study, student_id, phone_number, tutor_name) 
                   VALUES ('$student_name', '$year_of_study', '$student_id', '$phone_number', '$tutor_name')";

    // Execute the query and check if the insertion was successful
    if ($conn->query($insert_sql) === TRUE) {
        echo "New student registered successfully!";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
