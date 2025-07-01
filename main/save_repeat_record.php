<?php
// Include necessary files for database connection and authentication
include('../connect.php'); // Assuming connect.php is in the parent directory
include('auth.php'); // Assuming auth.php is in the same directory

// Check if the user is logged in
if (!isset($_SESSION['SESS_MEMBER_ID'])) {
    header("location: ../index.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $student_id_fk = filter_var($_POST['student_id_fk'], FILTER_SANITIZE_NUMBER_INT);
    $academic_year = filter_var($_POST['academic_year'], FILTER_SANITIZE_STRING);
    $subject_failed = filter_var($_POST['subject_failed'], FILTER_SANITIZE_STRING);
    $semester = filter_var($_POST['semester'], FILTER_SANITIZE_STRING);
    $grade_received = filter_var($_POST['grade_received'], FILTER_SANITIZE_STRING);
    $notes = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);

    // Basic validation
    if (empty($student_id_fk) || empty($academic_year) || empty($subject_failed)) {
        echo "Error: Required fields are missing. Please go back and fill them.";
        exit();
    }

    try {
        // Prepare SQL INSERT statement
        $sql = "INSERT INTO student_repeat_records (student_id_fk, academic_year, subject_failed, semester, grade_received, notes) VALUES (:student_id_fk, :academic_year, :subject_failed, :semester, :grade_received, :notes)";
        $q = $db->prepare($sql);

        // Bind parameters
        $q->bindParam(':student_id_fk', $student_id_fk);
        $q->bindParam(':academic_year', $academic_year);
        $q->bindParam(':subject_failed', $subject_failed);
        $q->bindParam(':semester', $semester);
        $q->bindParam(':grade_received', $grade_received);
        $q->bindParam(':notes', $notes);

        // Execute the query
        $q->execute();

        // Redirect back to the students page or a confirmation page
        header("location: students.php?success=record_added");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        // Log the error for debugging, but don't show sensitive info to user
        // file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
    }
} else {
    // If accessed directly without POST request
    header("location: add_repeat_record.php");
    exit();
}
