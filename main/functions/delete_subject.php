<?php
session_start();
include('../../connect.php');

$subject_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($subject_id) {
    try {
        $stmt = $db->prepare("DELETE FROM subjects WHERE id = :id");
        $stmt->bindParam(':id', $subject_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error deleting subject: " . $e->getMessage());
    }
}
header("location: ../manage_academics.php");
exit();
