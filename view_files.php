<?php
$folderPath = "NewUser"; // Replace with the actual path to your "NewUser" folder

// Check if the directory exists
if (is_dir($folderPath)) {
    $files = scandir($folderPath); // Get files from the folder
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            echo "<li><a href='$folderPath/$file' target='_blank'>$file</a></li>"; // Create a link to open the file
        }
    }
    echo "</ul>";
} else {
    echo "Folder not found.";
}
?>
