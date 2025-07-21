<?php
session_start();
include('../../connect.php');

$course_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($course_id) {
    try {
        // The foreign key constraint with ON DELETE CASCADE will automatically delete all subjects
        // associated with this course.
        $stmt = $db->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error deleting course: " . $e->getMessage());
    }
}
header("location: ../manage_academics.php");
exit();
