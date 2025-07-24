<?php
require_once('auth.php');
include('../connect.php');

$student = null;
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($student_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM transfer_students WHERE id = :id");
        $stmt->bindParam(':id', $student_id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

if (!$student) {
    die("Error: Transfer student not found.");
}
?>
<html>

<head>
    <title>Edit Transfer Student</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/sidebar.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin: 20px auto;
            width: 500px;
        }

        .card-body span {
            display: inline-block;
            width: 160px;
            text-align: right;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <?php include('navfixed.php'); ?>
    <div class="sidebar-fixed">
        <?php include('sidebar.php'); ?>
    </div>
    <div class="content-main">
        <div class="container-fluid">
            <div class="contentheader"><i class="icon-edit"></i> Edit Transfer Student</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_transfers.php">Manage Transfers</a></li> /
                <li class="active">Edit Transfer</li>
            </ul>

            <div class="card">
                <form action="functions/save_edit_transfer_student.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Edit Transfer Details</h4>
                    </center>
                    <hr>
                    <div class="card-body">
                        <span>New Student ID: </span><input type="text" style="width:280px; height:30px;" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" required><br>
                        <span>First Name: </span><input type="text" style="width:280px; height:30px;" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required><br>
                        <span>Last Name: </span><input type="text" style="width:280px; height:30px;" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required><br>
                        <span>Gender: </span><select name="gender" style="width:295px; height:40px;">
                            <option <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                        </select><br>
                        <span>Date of Birth: </span><input type="date" style="width:280px; height:30px;" name="bdate" value="<?php echo htmlspecialchars($student['bdate']); ?>"><br>
                        <span>Address: </span><input type="text" style="width:280px; height:30px;" name="address" value="<?php echo htmlspecialchars($student['address']); ?>"><br>
                        <span>Contact: </span><input type="text" style="width:280px; height:30px;" name="contact" value="<?php echo htmlspecialchars($student['contact']); ?>"><br>
                        <span>Previous University: </span><input type="text" style="width:280px; height:30px;" name="previous_university" value="<?php echo htmlspecialchars($student['previous_university']); ?>" required><br>
                        <span>Previous Major: </span><input type="text" style="width:280px; height:30px;" name="previous_major" value="<?php echo htmlspecialchars($student['previous_major']); ?>" required><br>
                        <span>Transfer Date: </span><input type="date" style="width:280px; height:30px;" name="transfer_date" value="<?php echo htmlspecialchars($student['transfer_date']); ?>" required><br>
                        <span>Status: </span>
                        <select name="status" style="width:295px; height:40px;">
                            <option <?php if ($student['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option <?php if ($student['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                            <option <?php if ($student['status'] == 'Rejected') echo 'selected'; ?>>Rejected</option>
                        </select><br>
                        <span>Notes: </span><textarea name="notes" style="width:280px; height:60px;"><?php echo htmlspecialchars($student['notes']); ?></textarea><br><br>
                        <div style="text-align:center;"><button class="btn btn-success btn-large" style="width:280px;">Save Changes</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>