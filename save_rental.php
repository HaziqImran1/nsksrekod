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

// Get data from the AJAX request
$agentName = $_POST['agentName'];
$agentTel = $_POST['agentTel'];
$renterName = $_POST['renterName'];
$renterTel = $_POST['renterTel'];
$icNum = $_POST['icNum'];
$carModel = $_POST['carModel'];
$noPlate = $_POST['noPlate'];
$price = $_POST['price'];
$startDate = $_POST['startDate'];
$startTime = $_POST['startTime'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO car_rentals (agent_name, agent_tel, renter_name, renter_tel, ic_num, car_model, no_plate, price, start_date, start_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $agentName, $agentTel, $renterName, $renterTel, $icNum, $carModel, $noPlate, $price, $startDate, $startTime);

// Execute the query
if ($stmt->execute()) {
    echo "Data saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
