<?php
require_once('auth.php');
include('../connect.php');

// Get parameters from URL
$record_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);

if (!$record_id || !$student_id) {
    die("Error: Missing required parameters.");
}

// Fetch course details and the specific repeat record
$course_info = null;
$record = null;
try {
    $stmt_course = $db->prepare("SELECT c.course_name, d.degree_name FROM student st JOIN courses c ON st.course_id = c.id JOIN degrees d ON c.degree_id = d.id WHERE st.id = :student_id");
    $stmt_course->bindParam(':student_id', $student_id);
    $stmt_course->execute();
    $course_info = $stmt_course->fetch(PDO::FETCH_ASSOC);

    $stmt_record = $db->prepare("SELECT * FROM repeat_records WHERE id = :id");
    $stmt_record->bindParam(':id', $record_id);
    $stmt_record->execute();
    $record = $stmt_record->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

if (!$course_info || !$record) {
    die("Error: Course or Record not found.");
}

// Extract the start year from the 'YYYY-YYYY' academic_year string
$start_year_from_db = substr($record['academic_year'], 0, 4);
?>

<html>

<head>
    <title>Edit Repeat Record</title>
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
            width: 140px;
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
            <div class="contentheader"><i class="icon-edit"></i> Edit Repeat Record</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="students.php">Manage Students</a></li> /
                <li><a href="viewstudent.php?id=<?php echo htmlspecialchars($student_id); ?>">View Student</a></li> /
                <li class="active">Edit Record</li>
            </ul>

            <a href="viewstudent.php?id=<?php echo htmlspecialchars($student_id); ?>" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
            <div style="clear:both;"></div>

            <div class="card">
                <form action="functions/save_edit_repeat_record.php" method="post">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Edit Failed Subject Details</h4>
                        <h5><?php echo htmlspecialchars($course_info['degree_name'] . ' of ' . $course_info['course_name']); ?></h5>
                    </center>
                    <hr>
                    <div class="card-body">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>" />
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" />

                        <span>Subject Name: </span><input type="text" style="width:265px; height:40px;" name="subject_name" value="<?php echo htmlspecialchars($record['subject_name']); ?>" required /><br>

                        <span>Academic Year: </span>
                        <select name="start_year" style="width:280px; height:40px;" required>
                            <?php
                            $current_year = date('Y');
                            // Generate a list of years from 10 years ago to 1 year in the future
                            for ($y = $current_year + 1; $y >= $current_year - 10; $y--):
                                $is_selected = ($y == $start_year_from_db) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $y; ?>" <?php echo $is_selected; ?>>
                                    <?php echo $y . '-' . ($y + 1); ?>
                                </option>
                            <?php endfor; ?>
                        </select><br>

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
        </div>
    </div>
</body>

</html>