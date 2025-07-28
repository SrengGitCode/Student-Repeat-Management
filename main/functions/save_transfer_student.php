<?php
session_start();
include('../../connect.php');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: ../manage_transfers.php");
    exit();
}

// --- Sanitize and retrieve form data ---
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_UNSAFE_RAW);
$name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_UNSAFE_RAW);
$gender = filter_input(INPUT_POST, 'gender', FILTER_UNSAFE_RAW);
$bdate = filter_input(INPUT_POST, 'bdate', FILTER_UNSAFE_RAW);
$address = filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW);
$contact = filter_input(INPUT_POST, 'contact', FILTER_UNSAFE_RAW);
$previous_university = filter_input(INPUT_POST, 'previous_university', FILTER_UNSAFE_RAW);
$previous_major = filter_input(INPUT_POST, 'previous_major', FILTER_UNSAFE_RAW);
$transfer_date = filter_input(INPUT_POST, 'transfer_date', FILTER_UNSAFE_RAW);
$status = filter_input(INPUT_POST, 'status', FILTER_UNSAFE_RAW);
$notes = filter_input(INPUT_POST, 'notes', FILTER_UNSAFE_RAW);

// --- File Upload Logic ---
$upload_dir = "../uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 1. Handle the single photo upload
$photo_path_to_db = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo_name = time() . '_' . basename($_FILES["photo"]["name"]);
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $upload_dir . $photo_name)) {
        // We store the path relative to the uploads folder
        $photo_path_to_db = $photo_name;
    }
}

// 2. Handle the multiple document uploads
$document_paths = [];
if (isset($_FILES['documents']) && is_array($_FILES['documents']['name'])) {
    $file_count = count($_FILES['documents']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        // Check if a file was uploaded and there's no error
        if (isset($_FILES['documents']['error'][$i]) && $_FILES['documents']['error'][$i] === UPLOAD_ERR_OK) {
            $doc_name = time() . '_' . basename($_FILES["documents"]["name"][$i]);
            if (move_uploaded_file($_FILES["documents"]["tmp_name"][$i], $upload_dir . $doc_name)) {
                // Add the new filename to our array
                $document_paths[] = $doc_name;
            }
        }
    }
}

// 3. Convert the array of document paths to a JSON string for the database
$docs_json_to_db = json_encode($document_paths);

// --- Database Insertion ---
try {
    $sql = "INSERT INTO transfer_students (student_id, name, last_name, gender, bdate, address, contact, photo, previous_university, previous_major, transfer_date, document, status, notes) 
            VALUES (:student_id, :name, :last_name, :gender, :bdate, :address, :contact, :photo, :previous_university, :previous_major, :transfer_date, :document, :status, :notes)";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':bdate', $bdate);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':photo', $photo_path_to_db); // Bind photo filename
    $stmt->bindParam(':previous_university', $previous_university);
    $stmt->bindParam(':previous_major', $previous_major);
    $stmt->bindParam(':transfer_date', $transfer_date);
    $stmt->bindParam(':document', $docs_json_to_db); // Bind the JSON string of document filenames
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':notes', $notes);

    $stmt->execute();

    header("location: ../manage_transfers.php?success=1");
    exit();
} catch (PDOException $e) {
    // For debugging, you can use: error_log("DB Error: " . $e->getMessage());
    header("location: ../manage_transfers.php?error=1");
    exit();
}
