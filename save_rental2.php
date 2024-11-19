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

// Collecting POST data from the form
$newAgentName = $_POST['newAgentName'];
$newAgentTel = $_POST['newAgentTel'];
$newRenterName = $_POST['newRenterName'];
$newRenterTel = $_POST['newRenterTel'];
$newIcNum = $_POST['newIcNum'];
$newAddress = $_POST['newAddress'];
$newPerson1Name = $_POST['newPerson1Name'];
$newPerson1Tel = $_POST['newPerson1Tel'];
$newPerson2Name = $_POST['newPerson2Name'];
$newPerson2Tel = $_POST['newPerson2Tel'];
$newCarModel = $_POST['newCarModel'];
$newNoPlate = $_POST['newNoPlate'];
$newPrice = $_POST['newPrice'];
$newStartDate = $_POST['newStartDate'];
$newStartTime = $_POST['newStartTime'];

// SQL query to insert data into the database
$stmt = $conn->prepare("INSERT INTO car_rentals (agent_name, agent_tel, renter_name, renter_tel, ic_num, car_model, no_plate, price, start_date, start_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $newAgentName, $newAgentTel, $newRenterName, $newRenterTel, $newIcNum, $newCarModel, $newNoPlate, $newPrice, $newStartDate, $newStartTime);

// Execute the query
if ($stmt->execute()) {
    echo "Data saved successfully!";
    
    // Create a file with the naming convention (name_icnum.txt)
    $directory = "NewUser"; // Define the folder name
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true); // Create the folder if it doesn't exist
    }
    
    $filename = "{$directory}/{$newRenterName}_{$newIcNum}.txt";
    $fileContent = "Agent Name: $newAgentName\nAgent Tel: $newAgentTel\nRenter Name: $newRenterName\nRenter Tel: $newRenterTel\n";
    $fileContent .= "IC Number: $newIcNum\nAddress: $newAddress\nContact Person 1 Name: $newPerson1Name\nContact Person 1 Tel: $newPerson1Tel\n";
    $fileContent .= "Contact Person 2 Name: $newPerson2Name\nContact Person 2 Tel: $newPerson2Tel\n";

    // Write to the file
    file_put_contents($filename, $fileContent);
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
