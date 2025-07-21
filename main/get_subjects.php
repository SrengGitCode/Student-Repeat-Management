<?php
// Set the content type to JSON so the browser understands the response
header('Content-Type: application/json');

// Include the database connection
include('../connect.php');

// Sanitize the input parameters from the URL
$course_id = filter_input(INPUT_GET, 'course_id', FILTER_SANITIZE_NUMBER_INT);
$year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT);
$semester = filter_input(INPUT_GET, 'semester', FILTER_SANITIZE_NUMBER_INT);

$subjects = [];

// Proceed only if all parameters are valid and the database connection exists
if ($course_id && $year && $semester && isset($db)) {
    try {
        // Prepare and execute the SQL query to find matching subjects
        $stmt = $db->prepare("SELECT subject_name FROM subjects WHERE course_id = :course_id AND year = :year AND semester = :semester ORDER BY subject_name ASC");
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':semester', $semester, PDO::PARAM_INT);
        $stmt->execute();
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // In a real application, you would log this error instead of outputting it.
        // For now, we'll return an empty array on error.
        $subjects = ['error' => $e->getMessage()];
    }
}

// Return the subjects array encoded as JSON
echo json_encode($subjects);
