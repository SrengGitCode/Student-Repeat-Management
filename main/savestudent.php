<?php
session_start();
include('../connect.php');

// Ensure the request is a POST request to prevent direct script access
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("location: addstudent.php");
  exit();
}

// Sanitize and retrieve form data using filter_input for security
$student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_STRING);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$course = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_STRING);
$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
$bdate = filter_input(INPUT_POST, 'bdate', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);

// Basic validation to ensure required fields are not empty
if (empty($student_id) || empty($name) || empty($last_name)) {
  header("location: addstudent.php?error=emptyfields");
  exit();
}

try {
  // Prepare SQL query to insert data into the student table using prepared statements
  $sql = "INSERT INTO student (student_id, name, last_name, course, gender, bdate, address, contact) VALUES (:student_id, :name, :last_name, :course, :gender, :bdate, :address, :contact)";
  $stmt = $db->prepare($sql);

  // Bind the parameters to the prepared statement
  $stmt->bindParam(':student_id', $student_id);
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':last_name', $last_name);
  $stmt->bindParam(':course', $course);
  $stmt->bindParam(':gender', $gender);
  $stmt->bindParam(':bdate', $bdate);
  $stmt->bindParam(':address', $address);
  $stmt->bindParam(':contact', $contact);

  // Execute the query
  $stmt->execute();

  // Redirect with a success message to show the user it worked
  header("location: addstudent.php?success=studentadded");
  exit();
} catch (PDOException $e) {
  // In case of a database error, log it and redirect with an error message.
  // For debugging, you can use: die("DB Error: " . $e->getMessage());
  error_log("DB Error on savestudent.php: " . $e->getMessage());
  header("location: addstudent.php?error=dberror");
  exit();
}
