<?php
session_start();
include('../connect.php');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: students.php"); // Redirect if accessed directly
    exit();
}

// Sanitize and retrieve form data
$record_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_NUMBER_INT);
$subject_name = filter_input(INPUT_POST, 'subject_name', FILTER_SANITIZE_STRING);
$failed_year = filter_input(INPUT_POST, 'failed_year', FILTER_SANITIZE_STRING);
$academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
$semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_STRING);
$notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
$passed = (isset($_POST['passed']) && $_POST['passed'] == '1') ? 1 : 0;

// Validate essential data
if (empty($record_id) || empty($student_id)) {
    die("Error: Missing required ID.");
}

try {
    // Prepare SQL UPDATE statement
    $sql = "UPDATE repeat_records 
            SET subject_name = :subject_name,
                failed_year = :failed_year,
                academic_year = :academic_year,
                semester = :semester,
                passed = :passed,
                notes = :notes
            WHERE id = :id";
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':subject_name', $subject_name);
    $stmt->bindParam(':failed_year', $failed_year);
    $stmt->bindParam(':academic_year', $academic_year);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':passed', $passed, PDO::PARAM_INT);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':id', $record_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Redirect back to the student view page
    header("location: viewstudent.php?id=" . $student_id);
    exit();
} catch (PDOException $e) {
    // For debugging: error_log("DB Error on save_edit_repeat_record.php: " . $e->getMessage());
    die("Database update failed. Please try again.");
}
