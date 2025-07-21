<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $degree_id = filter_input(INPUT_POST, 'degree_id', FILTER_SANITIZE_NUMBER_INT);
    $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);

    if (!empty($degree_id) && !empty($course_name)) {
        try {
            $sql = "INSERT INTO courses (degree_id, course_name) VALUES (:degree_id, :course_name)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':degree_id', $degree_id, PDO::PARAM_INT);
            $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error saving course: " . $e->getMessage());
        }
    }
}
header("location: ../manage_academics.php");
exit();
