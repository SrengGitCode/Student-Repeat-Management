<?php
session_start();
include('../../connect.php');

$degree_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($degree_id) {
    try {
        // Check if any courses are linked to this degree
        $stmt_check = $db->prepare("SELECT COUNT(*) FROM courses WHERE degree_id = :degree_id");
        $stmt_check->bindParam(':degree_id', $degree_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $course_count = $stmt_check->fetchColumn();

        // Only delete if there are no courses
        if ($course_count == 0) {
            $stmt_delete = $db->prepare("DELETE FROM degrees WHERE id = :id");
            $stmt_delete->bindParam(':id', $degree_id, PDO::PARAM_INT);
            $stmt_delete->execute();
        }
    } catch (PDOException $e) {
        die("Error deleting degree: " . $e->getMessage());
    }
}
header("location: ../manage_academics.php");
exit();
