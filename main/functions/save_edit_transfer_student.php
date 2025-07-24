<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("location: ../manage_transfers.php");
    exit();
}

// Sanitize and retrieve form data
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
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

if (empty($id)) {
    die("Error: ID is missing.");
}

try {
    $sql = "UPDATE transfer_students SET 
                student_id = :student_id,
                name = :name,
                last_name = :last_name,
                gender = :gender,
                bdate = :bdate,
                address = :address,
                contact = :contact,
                previous_university = :previous_university,
                previous_major = :previous_major,
                transfer_date = :transfer_date,
                status = :status,
                notes = :notes
            WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':bdate', $bdate);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':previous_university', $previous_university);
    $stmt->bindParam(':previous_major', $previous_major);
    $stmt->bindParam(':transfer_date', $transfer_date);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    header("location: ../manage_transfers.php");
    exit();
} catch (PDOException $e) {
    header("location: ../edit_transfer_student.php?id=" . $id . "&error=1");
    exit();
}
