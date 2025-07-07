<?php
// Include database connection and auth
require_once('auth.php');
include('../connect.php');

// Get the record ID and student ID from the URL
$record_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$new_status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_NUMBER_INT);
$student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);

// Validate essential data
if ($record_id === null || $new_status === null || $student_id === null || ($new_status != 0 && $new_status != 1)) {
    die("Error: Invalid parameters provided.");
}

try {
    // Prepare the SQL UPDATE statement
    $sql = "UPDATE repeat_records SET passed = :status WHERE id = :id";
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':status', $new_status, PDO::PARAM_INT);
    $stmt->bindParam(':id', $record_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Redirect back to the student view page to show the update
    header("location: viewstudent.php?id=" . $student_id);
    exit();
} catch (PDOException $e) {
    // For debugging: error_log("DB Update Error: " . $e->getMessage());
    die("Database update failed. Please try again.");
}
