<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_NUMBER_INT);
    $subjects = $_POST['subjects'] ?? [];

    if ($course_id && $year && $semester) {
        try {
            $db->beginTransaction();

            // Step 1: Delete all existing subjects for this specific semester
            $stmt_delete = $db->prepare("DELETE FROM subjects WHERE course_id = :course_id AND year = :year AND semester = :semester");
            $stmt_delete->bindParam(':course_id', $course_id);
            $stmt_delete->bindParam(':year', $year);
            $stmt_delete->bindParam(':semester', $semester);
            $stmt_delete->execute();

            // Step 2: Insert the new (and existing) subjects from the form
            $stmt_insert = $db->prepare("INSERT INTO subjects (course_id, year, semester, subject_name) VALUES (:course_id, :year, :semester, :subject_name)");

            foreach ($subjects as $subject_name) {
                $subject_name = trim(filter_var($subject_name, FILTER_SANITIZE_STRING));
                if (!empty($subject_name)) {
                    $stmt_insert->bindParam(':course_id', $course_id);
                    $stmt_insert->bindParam(':year', $year);
                    $stmt_insert->bindParam(':semester', $semester);
                    $stmt_insert->bindParam(':subject_name', $subject_name);
                    $stmt_insert->execute();
                }
            }

            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            die("Error updating subjects: " . $e->getMessage());
        }
    }
}
header("location: ../manage_academics.php");
exit();
