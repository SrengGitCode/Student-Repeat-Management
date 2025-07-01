<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// Initialize record variable
$record = null;

// Get the record ID and student ID from the URL
$record_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);

if ($record_id) {
    try {
        if (isset($db)) {
            // Fetch the repeat record's data
            $stmt = $db->prepare("SELECT * FROM repeat_records WHERE id = :id");
            $stmt->bindParam(':id', $record_id);
            $stmt->execute();
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

// If no record is found, handle it gracefully
if (!$record) {
    die("Error: Repeat record not found.");
}
?>
<html>

<head>
    <title>Edit Repeat Record</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .sidebar-nav {
            padding: 9px 0;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            width: 450px;
        }

        .card-body span {
            display: inline-block;
            width: 140px;
            text-align: right;
            margin-right: 10px;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include('navfixed.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
                        <li class="active"><a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a></li>
                        <li><a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a></li>
                    </ul>
                </div><!--/.well -->
            </div><!--/span-->
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-edit"></i> Edit Repeat Record
                </div>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li> /
                    <li><a href="students.php">Manage Students</a></li> /
                    <li><a href="viewstudent.php?id=<?php echo htmlspecialchars($student_id); ?>">View Student</a></li> /
                    <li class="active">Edit Record</li>
                </ul>

                <a href="viewstudent.php?id=<?php echo htmlspecialchars($student_id); ?>" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
                <div style="clear:both;"></div>

                <div class="card">
                    <form action="save_edit_repeat_record.php" method="post">
                        <center>
                            <h4><i class="icon-edit icon-large"></i> Edit Failed Subject Details</h4>
                        </center>
                        <hr>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>" />
                            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" />

                            <span>Subject Name: </span><input type="text" style="width:265px; height:30px;" name="subject_name" value="<?php echo htmlspecialchars($record['subject_name']); ?>" required /><br>

                            <span>Academic Year: </span><input type="text" style="width:265px; height:30px;" name="academic_year" value="<?php echo htmlspecialchars($record['academic_year']); ?>" required /><br>

                            <span>Year of Failure: </span>
                            <select name="failed_year" style="width:280px; height:40px;" required>
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option <?php echo ($record['failed_year'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select><br>

                            <span>Semester: </span>
                            <select name="semester" style="width:280px; height:40px;" required>
                                <option <?php echo ($record['semester'] == '1') ? 'selected' : ''; ?>>1</option>
                                <option <?php echo ($record['semester'] == '2') ? 'selected' : ''; ?>>2</option>
                            </select><br>

                            <span>Subject Passed: </span>
                            <input type="hidden" name="passed" value="0">
                            <input type="checkbox" name="passed" value="1" style="width:30px; height:30px;" <?php echo ($record['passed'] == 1) ? 'checked' : ''; ?>><br><br>

                            <span>Notes (Optional): </span><textarea style="width:265px; height:50px;" name="notes"><?php echo htmlspecialchars($record['notes']); ?></textarea><br><br>

                            <div style="text-align: center;">
                                <button class="btn btn-success btn-large" style="width:280px;"><i class="icon icon-save icon-large"></i> Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--/span-->
        </div><!--/row-->
    </div><!--/.fluid-container-->

    <?php include('footer.php'); ?>
</body>

</html>