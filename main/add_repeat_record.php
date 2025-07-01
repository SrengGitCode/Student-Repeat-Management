<?php
session_start();
include('../connect.php');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: addstudent.php");
    exit();
}

// Sanitize and retrieve form data
$student_id_fk = filter_input(INPUT_POST, 'student_id_fk', FILTER_SANITIZE_NUMBER_INT);
$subject_name = filter_input(INPUT_POST, 'subject_name', FILTER_SANITIZE_STRING);
$failed_year = filter_input(INPUT_POST, 'failed_year', FILTER_SANITIZE_STRING);
$academic_year = filter_input(INPUT_POST, 'academic_year', FILTER_SANITIZE_STRING);
$semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_STRING);
$notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

// Handle the checkbox value for 'passed'
$passed = (isset($_POST['passed']) && $_POST['passed'] == '1') ? 1 : 0;

// Basic validation
if (empty($student_id_fk) || empty($subject_name) || empty($failed_year) || empty($academic_year)) {
    header("location: addstudent.php?error=emptyfields_repeat&tab=addRepeat");
    exit();
}

try {
    // Prepare SQL query to insert data into the repeat_records table
    $sql = "INSERT INTO repeat_records (student_id_fk, subject_name, failed_year, academic_year, semester, passed, notes) VALUES (:student_id_fk, :subject_name, :failed_year, :academic_year, :semester, :passed, :notes)";
    $stmt = $db->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':student_id_fk', $student_id_fk, PDO::PARAM_INT);
    $stmt->bindParam(':subject_name', $subject_name, PDO::PARAM_STR);
    $stmt->bindParam(':failed_year', $failed_year, PDO::PARAM_STR);
    $stmt->bindParam(':academic_year', $academic_year, PDO::PARAM_STR);
    $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
    $stmt->bindParam(':passed', $passed, PDO::PARAM_INT);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Redirect with a success message
    header("location: addstudent.php?success=recordadded");
    exit();
} catch (PDOException $e) {
    // For debugging, you can log the error.
    error_log("DB Error on add_repeat_record.php: " . $e->getMessage());
    header("location: addstudent.php?error=dberror&tab=addRepeat");
    exit();
}
