<?php
session_start();
include('../connect.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    try {
        // Optional: First, get filenames to delete files from server
        $stmt_select = $db->prepare("SELECT photo, document FROM transfer_students WHERE id = :id");
        $stmt_select->bindParam(':id', $id);
        $stmt_select->execute();
        $files = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($files) {
            if (!empty($files['photo']) && file_exists('../uploads/' . $files['photo'])) {
                unlink('../uploads/' . $files['photo']);
            }
            if (!empty($files['document']) && file_exists('../uploads/' . $files['document'])) {
                unlink('../uploads/' . $files['document']);
            }
        }

        // Now, delete the record from the database
        $stmt_delete = $db->prepare("DELETE FROM transfer_students WHERE id = :id");
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();
    } catch (PDOException $e) {
        // In a real application, you would log this error.
        // For now, we just redirect.
    }
}

header("location: ../manage_transfers.php");
exit();
