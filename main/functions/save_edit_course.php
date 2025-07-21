<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $degree_id = filter_input(INPUT_POST, 'degree_id', FILTER_SANITIZE_NUMBER_INT);
    $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);

    if ($course_id && $degree_id && !empty($course_name)) {
        try {
            $sql = "UPDATE courses SET degree_id = :degree_id, course_name = :course_name WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':degree_id', $degree_id);
            $stmt->bindParam(':course_name', $course_name);
            $stmt->bindParam(':id', $course_id);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error updating course: " . $e->getMessage());
        }
    }
}
header("location: ../manage_academics.php");
exit();
