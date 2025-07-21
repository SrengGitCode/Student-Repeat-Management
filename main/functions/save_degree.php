<?php
session_start();
include('../../connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $degree_name = filter_input(INPUT_POST, 'degree_name', FILTER_SANITIZE_STRING);
    if (!empty($degree_name)) {
        try {
            $sql = "INSERT INTO degrees (degree_name) VALUES (:degree_name)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':degree_name', $degree_name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Error saving degree: " . $e->getMessage());
        }
    }
}
header("location: ../manage_academics.php");
exit();
