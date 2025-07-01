<?php
session_start();
include('../connect.php');

// Get the record ID and student ID from the URL
$record_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);

// Validate essential data
if (empty($record_id) || empty($student_id)) {
    die("Error: Missing required ID for deletion.");
}

try {
    // Prepare SQL DELETE statement
    $stmt = $db->prepare("DELETE FROM repeat_records WHERE id = :id");
    $stmt->bindParam(':id', $record_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Redirect back to the student view page
    header("location: viewstudent.php?id=" . $student_id);
    exit();
} catch (PDOException $e) {
    // For debugging: error_log("DB Error on delete_repeat_record.php: " . $e->getMessage());
    die("Database deletion failed. Please try again.");
}
