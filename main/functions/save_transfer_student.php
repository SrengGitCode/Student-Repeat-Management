<?php
session_start();
include('../../connect.php');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: ../manage_transfers.php");
    exit();
}

// --- File Upload Handling ---
function handle_upload($file_key, $upload_dir = '../uploads/')
{
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
        $filename = time() . '_' . basename($_FILES[$file_key]['name']);
        $target_path = $upload_dir . $filename;

        // Create uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $target_path)) {
            return $filename; // Return the new filename if upload is successful
        }
    }
    return null; // Return null if no file or an error occurred
}

$photo_filename = handle_upload('photo');
$document_filename = handle_upload('document');

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
    $stmt->bindParam(':photo', $photo_filename);
    $stmt->bindParam(':previous_university', $previous_university);
    $stmt->bindParam(':previous_major', $previous_major);
    $stmt->bindParam(':transfer_date', $transfer_date);
    $stmt->bindParam(':document', $document_filename);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':notes', $notes);

    $stmt->execute();

    header("location: ../manage_transfers.php?success=1");
    exit();
} catch (PDOException $e) {
    // For debugging: error_log("DB Error: " . $e->getMessage());
    header("location: ../manage_transfers.php?error=1");
    exit();
}
