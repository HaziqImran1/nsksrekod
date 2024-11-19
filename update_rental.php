<?php
// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "sksconn"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the AJAX request
    $id = $_POST['id'];
    $endDate = $_POST['endDate'];
    $endTime = $_POST['endTime'];

    // Validate inputs
    if (empty($id) || empty($endDate) || empty($endTime)) {
        echo "Error: Missing required fields.";
        exit;
    }

    // Fetch the start_date and start_time from the database
    $query = "SELECT start_date, start_time FROM car_rentals WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "Error: Rental record not found.";
        exit;
    }

    $startDateTime = new DateTime($row['start_date'] . ' ' . $row['start_time']);
    $endDateTime = new DateTime($endDate . ' ' . $endTime);

    // Calculate the difference
    $interval = $startDateTime->diff($endDateTime);

    // Extract days, hours, and minutes
    $days = $interval->d;
    $hours = $interval->h;
    $minutes = $interval->i;

    $duration = "{$days} day(s), {$hours} hour(s), {$minutes} minute(s)";

    // Update the database record
    $query = "UPDATE car_rentals SET end_date = ?, end_time = ?, duration = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $endDate, $endTime, $duration, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: Invalid request method.";
}

// Close the connection
$conn->close();
?>
