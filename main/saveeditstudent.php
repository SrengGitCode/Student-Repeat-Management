<?php
session_start();
include('../connect.php');

// Ensure the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: students.php");
    exit();
}

// Sanitize and retrieve form data
$student_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$course_id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT);
$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
$bdate = filter_input(INPUT_POST, 'bdate', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);

// Basic validation
if (empty($student_id) || empty($name) || empty($last_name) || empty($course_id)) {
    // Redirect back with an error if required fields are missing
    header("location: editstudent.php?id=" . $student_id . "&error=emptyfields");
    exit();
}

try {
    // Prepare SQL UPDATE statement
    $sql = "UPDATE student 
            SET name = :name,
                last_name = :last_name,
                course_id = :course_id,
                gender = :gender,
                bdate = :bdate,
                address = :address,
                contact = :contact
            WHERE id = :id";
    $stmt = $db->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':bdate', $bdate);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':id', $student_id, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Redirect back to the students list page
    header("location: students.php");
    exit();
} catch (PDOException $e) {
    // For debugging: error_log("DB Error on saveeditstudent.php: " . $e->getMessage());
    header("location: editstudent.php?id=" . $student_id . "&error=dberror");
    exit();
}
