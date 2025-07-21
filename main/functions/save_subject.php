<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
    $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_NUMBER_INT);
    $subjects = $_POST['subjects'] ?? []; // Retrieve the array of subjects

    // Proceed only if the main details are present
    if (!empty($course_id) && !empty($year) && !empty($semester) && !empty($subjects)) {
        try {
            // Start a transaction
            $db->beginTransaction();

            // Prepare the SQL statement once, outside the loop
            $sql = "INSERT INTO subjects (course_id, year, semester, subject_name) VALUES (:course_id, :year, :semester, :subject_name)";
            $stmt = $db->prepare($sql);

            // Loop through each submitted subject name
            foreach ($subjects as $subject_name) {
                // Sanitize and trim the subject name
                $clean_subject_name = trim(filter_var($subject_name, FILTER_UNSAFE_RAW));

                // Only insert if the subject name is not empty
                if (!empty($clean_subject_name)) {
                    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
                    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                    $stmt->bindParam(':semester', $semester, PDO::PARAM_INT);
                    $stmt->bindParam(':subject_name', $clean_subject_name, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            // If all insertions were successful, commit the transaction
            $db->commit();
        } catch (PDOException $e) {
            // If any error occurred, roll back the entire transaction
            $db->rollBack();
            die("Error saving subjects: " . $e->getMessage());
        }
    }
}

// Redirect back to the management page
header("location: ../manage_academics.php");
exit();
