<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sksconn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the IDs from the request
if (isset($_POST['ids'])) {
    $ids = json_decode($_POST['ids'], true);

    if (!empty($ids)) {
        // Convert IDs array into a comma-separated string
        $idsString = implode(',', array_map('intval', $ids));

        // Delete rows from the database
        $sql = "DELETE FROM car_rentals WHERE id IN ($idsString)";
        if ($conn->query($sql) === TRUE) {
            echo "Records deleted successfully";
        } else {
            echo "Error deleting records: " . $conn->error;
        }
    } else {
        echo "No IDs provided.";
    }
} else {
    echo "Invalid request.";
}

// Close the connection
$conn->close();
?>
