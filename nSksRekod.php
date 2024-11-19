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

// Query to retrieve data from car_rentals table
$sql = "SELECT * FROM car_rentals";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stesen Kereta Sewa (Rekod)</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <style>
        /* General Styles */
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #000000;
			color: #FFD700;
			margin: 0;
			padding: 20px;
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		header {
			display: flex;
			align-items: center;
			margin-bottom: 20px;
			width: 100%;
			justify-content: center;
			border-bottom: 2px solid #FFD700; /* Border for header */
			padding-bottom: 10px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
		}

		header img {
			height: 150px;
			margin-right: 15px;
		}

		header h1 {
			font-size: 50px;
			color: #FFD700;
			text-shadow: 1px 1px 2px #000;
		}

		.buttons {
			margin-bottom: 20px;
			margin-top: 20px;
			display: flex;
			justify-content: center;
			gap: 15px; /* Increased gap for modern look */
		}

		.buttons button {
			padding: 12px 20px;
			font-size: 16px;
			border: none;
			border-radius: 5px;
			background-color: #FFD700;
			color: #000;
			cursor: pointer;
			transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
		}

		.buttons button:hover {
			background-color: #FFC107;
			transform: scale(1.05);
			box-shadow: 0 6px 8px rgba(0, 0, 0, 0.4);
		}

		.treeview {
			border: 2px solid #FFD700;
			border-radius: 5px;
			padding: 10px;
			width: 100%;
			max-width: 1200px;
			overflow-x: auto;
			margin-top: 20px; /* Added margin to create space between buttons and treeview */
		}

		table {
			width: 100%;
			border-collapse: collapse;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
		}

		table th, table td {
			border: 1px solid #FFD700;
			padding: 10px;
			text-align: left;
		}

		table th {
			background-color: #FFD700;
			color: #000;
			position: sticky;
			top: 0;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
		}

		table tr:nth-child(even) {
			background-color: #333;
		}

		table tr:nth-child(odd) {
			background-color: #222;
		}

		/* Popup Form Styles */
			.popup {
				display: none;
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				background-color: #222;
				border: 2px solid #FFD700;
				padding: 20px;
				z-index: 1000;
				width: 500px; /* Adjusted width to prevent horizontal scrolling */
				max-height: 80vh; /* Set maximum height */
				border-radius: 8px;
				color: #FFD700;
				box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
				overflow-y: auto; /* Enable vertical scrolling */
			}

			.popup h2 {
				margin-top: 0;
				text-align: center;
				color: #FFD700;
				text-shadow: 1px 1px 2px #000;
			}

			.form-container {
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
				gap: 20px; /* Added gap for better spacing */
				margin-bottom: 20px; /* Added margin for spacing */
			}

			.form-section {
				width: calc(100% - 20px); /* Full width minus padding */
				margin-bottom: 10px;
				padding: 10px; /* Padding around each section */
				background-color: #333; /* Background color for better visibility */
				border-radius: 4px; /* Rounded corners for sections */
			}

			.form-section label {
				display: block;
				margin-bottom: 5px;
				font-weight: bold; /* Make labels bold */
			}

			.form-section input, .form-section select {
				width: 100%;
				padding: 10px;
				margin-bottom: 10px;
				background-color: #222; /* Match background color */
				color: #FFD700;
				border: 1px solid #FFD700;
				border-radius: 4px;
				transition: border-color 0.3s;
			}

			.form-section input:focus, .form-section select:focus {
				border-color: #FFC107; /* Change border color on focus */
				outline: none; /* Remove default outline */
			}

			.popup-buttons {
				display: flex;
				justify-content: space-between;
				margin-top: 20px; /* Added margin for spacing */
			}

			.popup-buttons button {
				padding: 10px 15px;
				font-size: 14px;
				border: none;
				border-radius: 5px;
				background-color: #FFD700;
				color: #000;
				cursor: pointer;
				transition: background-color 0.3s, transform 0.2s;
			}

			.popup-buttons button:hover {
				background-color: #FFC107;
				transform: scale(1.05);
			}

		/* Overlay */
		.overlay {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.8);
			z-index: 500;
		}
    </style>
</head>
<body>
    <header>
        <img src="logo2.png" alt="SKS Logo"> <!-- Replace with your logo file -->
        <h1>STESEN KERETA SEWA (REKOD)</h1>
    </header>

    <div class="buttons">
		<button onclick="showPopup()"><i class="fas fa-user"></i> Regular</button>
		<button onclick="handleNew()"><i class="fas fa-plus"></i> New</button>
		<button onclick="handleViewFile()"><i class="fas fa-file-alt"></i> View File</button>
	</div>

    <div class="treeview">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Agent Name</th>
                    <th>Agent P.Num</th>
                    <th>Renter Name</th>
                    <th>Renter P.Num</th>
                    <th>I/C Num</th>
                    <th>Car Model</th>
                    <th>No. Plate</th>
                    <th>Given Price</th>
                    <th>Start Date</th>
                    <th>Start time</th>
                    <th>End Date</th>
                    <th>End time</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody id="data-table">
                <?php
                // Check if there are rows to display
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr onclick='selectRow(this)'>";
                        echo "<td><input type='checkbox' class='row-checkbox' onclick='event.stopPropagation();' data-id='" . $row["id"] . "'></td>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["agent_name"] . "</td>";
                        echo "<td>" . $row["agent_tel"] . "</td>";
                        echo "<td>" . $row["renter_name"] . "</td>";
                        echo "<td>" . $row["renter_tel"] . "</td>";
                        echo "<td>" . $row["ic_num"] . "</td>";
                        echo "<td>" . $row["car_model"] . "</td>";
                        echo "<td>" . $row["no_plate"] . "</td>";
                        echo "<td>" . $row["price"] . "</td>";
                        echo "<td>" . $row["start_date"] . "</td>";
                        echo "<td>" . $row["start_time"] . "</td>";
                        echo "<td>" . $row["end_date"] . "</td>";
                        echo "<td>" . $row["end_time"] . "</td>";
                        echo "<td>" . $row["duration"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='15'>No records found</td></tr>";
                }
                // Close connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
	
	<!-- Regular Popup Form -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popupForm">
        <h2>Regular Form</h2>
        <div class="form-container">
            <div class="form-section">
                <label for="agentName">Name Agent:</label>
                <input type="text" id="agentName" maxlength="250">
                <label for="agentTel">Agent Tel. Num:</label>
                <input type="number" id="agentTel" max="999999999999999">
                <div class="divider"></div>
                <label for="renterName">Name Renter:</label>
                <input type="text" id="renterName" maxlength="250">
                <label for="renterTel">Tel. Num:</label>
                <input type="number" id="renterTel" max="999999999999999">
                <label for="icNum">IC. Num:</label>
                <input type="text" id="icNum" maxlength="14" placeholder="010203-01-0203">
            </div>
            <div class="form-section">
                <label for="carModel">Model:</label>
                <select id="carModel">
                    <option value="Bezza">Bezza</option>
                    <option value="Alza">Alza</option>
                    <option value="Vellfire">Vellfire</option>
                </select>
                <label for="noPlate">No. Plate:</label>
                <input type="text" id="noPlate" maxlength="10">
                <label for="price">Given Price:</label>
                <input type="number" id="price" max="9999999999">
                <label for="startDate">Date Start:</label>
                <input type="date" id="startDate">
                <label for="startTime">Time:</label>
                <input type="time" id="startTime">
            </div>
        </div>

        <div class="popup-buttons">
            <button onclick="saveForm()">Save</button>
            <button onclick="closePopup()">Cancel</button>
        </div>
    </div>

		<!-- New Popup Form -->
	<div class="overlay" id="newOverlay"></div>
	<div class="popup" id="newPopupForm">
		<h2>New Rental Form</h2>
		<div class="form-container">
			<div class="form-section">
				<label for="newAgentName">Agent Name:</label>
				<input type="text" id="newAgentName" maxlength="250" placeholder="Enter agent name" required>
				<label for="newAgentTel">Agent Tel. Num:</label>
				<input type="tel" id="newAgentTel" maxlength="15" placeholder="Enter agent telephone number" required>
				<label for="newRenterName">Renter Name:</label>
				<input type="text" id="newRenterName" maxlength="250" placeholder="Enter renter name" required>
				<label for="newRenterTel">Renter Tel. Num:</label>
				<input type="tel" id="newRenterTel" maxlength="15" placeholder="Enter renter telephone number" required>
				<label for="newIcNum">IC Number:</label>
				<input type="text" id="newIcNum" maxlength="14" placeholder="010203-01-0203" required>
				<label for="newAddress">Address:</label>
				<input type="text" id="newAddress" maxlength="500" placeholder="Enter address" required>
			</div>
			
			<div class="form-section">
				<h3>Reference Contacts</h3>
				<label for="newPerson1Name">Person 1 - Name:</label>
				<input type="text" id="newPerson1Name" maxlength="250" placeholder="Enter name of reference" required>
				<label for="newPerson1Tel">Tel. Num:</label>
				<input type="tel" id="newPerson1Tel" maxlength="15" placeholder="Enter reference telephone number" required>
				<label for="newPerson2Name">Person 2 - Name:</label>
				<input type="text" id="newPerson2Name" maxlength="250" placeholder="Enter name of reference" required>
				<label for="newPerson2Tel">Tel. Num:</label>
				<input type="tel" id="newPerson2Tel" maxlength="15" placeholder="Enter reference telephone number" required>
					
				<label for="newCarModel">Car Model:</label>
				<select id="newCarModel" required>
					<option value="Bezza">Bezza</option>
					<option value="Alza">Alza</option>
					<option value="Vellfire">Vellfire</option>
				</select>
				<label for="newNoPlate">No. Plate:</label>
				<input type="text" id="newNoPlate" maxlength="10" placeholder="Enter car number plate" required>
				<label for="newPrice">Given Price:</label>
				<input type="number" id="newPrice" max="9999999999" placeholder="Enter rental price" required>
				<label for="newStartDate">Start Date:</label>
				<input type="date" id="newStartDate" required>
				<label for="newStartTime">Start Time:</label>
				<input type="time" id="newStartTime" required>
			</div>
		</div>
		<div class="popup-buttons">
			<button onclick="saveNewForm()">Save</button>
			<button onclick="closeNewPopup()">Cancel</button>
		</div>
	</div>
	
	<div class="buttons">
		<button onclick="updateSelected()"><i class="fas fa-edit"></i> Update</button>
		<button onclick="deleteSelected()"><i class="fas fa-trash"></i> Delete</button>
		<button onclick="convertSelected()"><i class="fas fa-file-pdf"></i> Convert</button>
	</div>

    <!-- Update Form Popup -->
    <div class="overlay update-overlay" id="updateOverlay"></div>
    <div class="popup update-popup" id="updateForm">
        <h2>Update Record</h2>
        <div class="form-section">
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate">
            <label for="endTime">End Time:</label>
            <input type="time" id="endTime">
        </div>
        <div class="popup-buttons">
            <button onclick="saveUpdate()">Save</button>
            <button onclick="closeUpdatePopup()">Cancel</button>
        </div>
    </div>

    <script>
        // Show the regular popup form
        function showPopup() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("popupForm").style.display = "block";
        }

        // Close the regular popup form
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("popupForm").style.display = "none";
        }

        // Show the new popup form
        function handleNew() {
            document.getElementById("newOverlay").style.display = "block";
            document.getElementById("newPopupForm").style.display = "block";
        }

        // Close the new popup form
        function closeNewPopup() {
            document.getElementById("newOverlay").style.display = "none";
            document.getElementById("newPopupForm").style.display = "none";
        }

        // Save form data using AJAX
        function saveForm() {
            const agentName = document.getElementById("agentName").value;
            const agentTel = document.getElementById("agentTel").value;
            const renterName = document.getElementById("renterName").value;
            const renterTel = document.getElementById("renterTel").value;
            const icNum = document.getElementById("icNum").value;
            const carModel = document.getElementById("carModel").value;
            const noPlate = document.getElementById("noPlate").value;
            const price = document.getElementById("price").value;
            const startDate = document.getElementById("startDate").value;
            const startTime = document.getElementById("startTime").value;

            // Create a FormData object
            const formData = new FormData();
            formData.append("agentName", agentName);
            formData.append("agentTel", agentTel);
            formData.append("renterName", renterName);
            formData.append("renterTel", renterTel);
            formData.append("icNum", icNum);
            formData.append("carModel", carModel);
            formData.append("noPlate", noPlate);
            formData.append("price", price);
            formData.append("startDate", startDate);
            formData.append("startTime", startTime);

            // Make AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_rental.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Data saved successfully!");
                    closePopup();  // Close popup after saving
                    location.reload();  // Reload the page to show the new record
                } else {
                    alert("Error saving data.");
                }
            };
            xhr.send(formData);
        }

        // Save new form data using AJAX
        function saveNewForm() {
            const newAgentName = document.getElementById("newAgentName").value;
            const newAgentTel = document.getElementById("newAgentTel").value;
            const newRenterName = document.getElementById("newRenterName").value;
            const newRenterTel = document.getElementById("newRenterTel").value;
            const newIcNum = document.getElementById("newIcNum").value;
            const newAddress = document.getElementById("newAddress").value;
            const newPerson1Name = document.getElementById("newPerson1Name").value;
            const newPerson1Tel = document.getElementById("newPerson1Tel").value;
            const newPerson2Name = document.getElementById("newPerson2Name").value;
            const newPerson2Tel = document.getElementById("newPerson2Tel").value;
            const newCarModel = document.getElementById("newCarModel").value;
            const newNoPlate = document.getElementById("newNoPlate").value;
            const newPrice = document.getElementById("newPrice").value;
            const newStartDate = document.getElementById("newStartDate").value;
            const newStartTime = document.getElementById("newStartTime").value;

            // Create a FormData object
            const formData = new FormData();
            formData.append("newAgentName", newAgentName);
            formData.append("newAgentTel", newAgentTel);
            formData.append("newRenterName", newRenterName);
            formData.append("newRenterTel", newRenterTel);
            formData.append("newIcNum", newIcNum);
            formData.append("newAddress", newAddress);
            formData.append("newPerson1Name", newPerson1Name);
            formData.append("newPerson1Tel", newPerson1Tel);
            formData.append("newPerson2Name", newPerson2Name);
            formData.append("newPerson2Tel", newPerson2Tel);
            formData.append("newCarModel", newCarModel);
            formData.append("newNoPlate", newNoPlate);
            formData.append("newPrice", newPrice);
            formData.append("newStartDate", newStartDate);
            formData.append("newStartTime", newStartTime);

            // Send the data using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_rental2.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Rental data saved successfully!");
                    closeNewPopup();
                    // Optionally reload data from the database here
                } else {
                    alert("Failed to save data");
                }
            };
            xhr.send(formData);
        }
		
        // Show the update popup form
        function updateSelected() {
            const selectedCheckbox = document.querySelector('.row-checkbox:checked');
            if (selectedCheckbox) {
                selectedRowId = selectedCheckbox.getAttribute('data-id');
                document.getElementById("updateOverlay").style.display = "block";
                document.getElementById("updateForm").style.display = "block";
            } else {
                alert("Please select a row to update.");
            }
        }

        // Close the update popup form
        function closeUpdatePopup() {
            document.getElementById("updateOverlay").style.display = "none";
            document.getElementById("updateForm").style.display = "none";
        }

        // Save the updated data
        function saveUpdate() {
            const endDate = document.getElementById("endDate").value;
            const endTime = document.getElementById ("endTime").value;

            if (endDate && endTime) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "update_rental.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Record updated successfully!");
                        closeUpdatePopup();
                        location.reload();
                    } else {
                        alert("Failed to update record.");
                    }
                };
                xhr.send(`id=${selectedRowId}&endDate=${endDate}&endTime=${endTime}`);
            } else {
                alert("Please fill in all fields.");
            }
        }
		
        function deleteSelected() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert("Please select at least one row to delete.");
                return;
            }

            if (!confirm("Are you sure you want to delete the selected record(s)?")) {
                return;
            }

            const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-id'));

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_rental.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Selected record(s) deleted successfully!");
                    location.reload();
                } else {
                    alert("Failed to delete the selected record(s).");
                }
            };
            xhr.send(`ids=${JSON.stringify(selectedIds)}`);
        }

        async function convertSelected() {
            const { jsPDF } = window.jspdf;

            // Get the table element
            const table = document.querySelector(".treeview table");

            // Check if table exists
            if (!table) {
                alert("Table not found!");
                return;
            }

            // Extract headers
            const headers = Array.from(table.querySelectorAll("thead th"))
                .map(header => header.innerText);

            // Extract table data
            const rows = Array.from(table.querySelectorAll("tbody tr"))
                .map(row => Array.from(row.querySelectorAll("td"))
                    .map(cell => cell.innerText.trim())
                );

            // Create a new PDF document
            const pdf = new jsPDF({
                orientation: 'landscape', // Table fits better in landscape mode
                unit: 'mm',
                format: 'a4',
            });

            // Use autotable to render the table in the PDF
            pdf.autoTable({
                head: [headers], // Add table headers
                body: rows,      // Add table data
                startY: 20,      // Start below the default title area
                styles: {
                    fontSize: 10,  // Font size for table content
                    overflow: 'linebreak', // Handle long text
                },
                headStyles: {
                    fillColor: [0, 102, 204], // Header background color
                    textColor: 255,           // Header text color
                }
            });

            // Save the PDF
            pdf.save("treeview_table.pdf");
        }

        // Handle 'View File' button
        function handleViewFile() {
            window.location.href = 'view_files.php';  // Direct the user to view files
        }
    </script>
</body>
</html>