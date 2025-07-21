<?php
session_start();
include('../connect.php');

// Ensure the request is a POST request to prevent direct script access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("location: addstudent.php");
  exit();
}

// Sanitize and retrieve form data using modern filters
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_UNSAFE_RAW);
$name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_UNSAFE_RAW);
$course_id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT); // Correctly get and sanitize course_id
$gender = filter_input(INPUT_POST, 'gender', FILTER_UNSAFE_RAW);
$bdate = filter_input(INPUT_POST, 'bdate', FILTER_UNSAFE_RAW);
$address = filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW);
$contact = filter_input(INPUT_POST, 'contact', FILTER_UNSAFE_RAW);

// Basic validation to ensure required fields are not empty
if (empty($student_id) || empty($name) || empty($last_name) || empty($course_id)) {
  header("location: addstudent.php?error=emptyfields");
  exit();
}

try {
  // Prepare SQL query to insert data into the student table
  $sql = "INSERT INTO student (student_id, name, last_name, course_id, gender, bdate, address, contact) VALUES (:student_id, :name, :last_name, :course_id, :gender, :bdate, :address, :contact)";
  $stmt = $db->prepare($sql);

  // Bind the parameters to the prepared statement
  $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
  $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT); // Bind the correct variable as an integer
  $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
  $stmt->bindParam(':bdate', $bdate, PDO::PARAM_STR);
  $stmt->bindParam(':address', $address, PDO::PARAM_STR);
  $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);

  // Execute the query
  $stmt->execute();

  // Redirect with a success message
  header("location: addstudent.php?success=studentadded");
  exit();
} catch (PDOException $e) {
  // In case of a database error, log it and redirect with an error message.
  error_log("DB Error on savestudent.php: " . $e->getMessage());
  header("location: addstudent.php?error=dberror");
  exit();
}
