<?php
// Set the directory where you want to save the uploaded files
$uploadDir = 'uploads/';

// Check if the directory exists, if not, create it
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

require(__DIR__ . "/token.php");
$headerToken = $_SERVER['HTTP_AUTHORIZATION'];

// Check if the token is valid
if ($headerToken !== $token) {
    $response = [
        "status" => "error",
        "message" => "Invalid token"
    ];

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if a file was uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    // Sanitize the file name to prevent any security issues
    $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $fileName);

    // Set the full path to save the file
    $destPath = $uploadDir . $fileName;

    // Move the file from the temporary location to the upload folder
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $response = [
            "status" => "success",
            "url" => 'https://' . $_SERVER['HTTP_HOST'] . '/' . $destPath
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "There was an error moving the uploaded file."
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "No file was uploaded or there was an upload error."
    ];
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);