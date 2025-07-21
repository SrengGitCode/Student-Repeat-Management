<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $degree_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $degree_name = filter_input(INPUT_POST, 'degree_name', FILTER_SANITIZE_STRING);

    if ($degree_id && !empty($degree_name)) {
        try {
            $sql = "UPDATE degrees SET degree_name = :degree_name WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':degree_name', $degree_name);
            $stmt->bindParam(':id', $degree_id);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error updating degree: " . $e->getMessage());
        }
    }
}
header("location: ../manage_academics.php");
exit();
