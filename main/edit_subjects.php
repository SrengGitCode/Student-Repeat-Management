<?php
require_once('auth.php');
include('../connect.php');

// Get parameters from URL
$course_id = filter_input(INPUT_GET, 'course_id', FILTER_SANITIZE_NUMBER_INT);
$year = filter_input(INPUT_GET, 'year', FILTER_SANITIZE_NUMBER_INT);
$semester = filter_input(INPUT_GET, 'semester', FILTER_SANITIZE_NUMBER_INT);

if (!$course_id || !$year || !$semester) {
    die("Error: Missing required parameters.");
}

// Fetch course details and existing subjects
$course_info = null;
$subjects = [];
try {
    $stmt_course = $db->prepare("SELECT c.course_name, d.degree_name FROM courses c JOIN degrees d ON c.degree_id = d.id WHERE c.id = :course_id");
    $stmt_course->bindParam(':course_id', $course_id);
    $stmt_course->execute();
    $course_info = $stmt_course->fetch(PDO::FETCH_ASSOC);

    $stmt_subjects = $db->prepare("SELECT id, subject_name FROM subjects WHERE course_id = :course_id AND year = :year AND semester = :semester ORDER BY id ASC");
    $stmt_subjects->bindParam(':course_id', $course_id);
    $stmt_subjects->bindParam(':year', $year);
    $stmt_subjects->bindParam(':semester', $semester);
    $stmt_subjects->execute();
    $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

if (!$course_info) {
    die("Error: Course not found.");
}
?>

<html>

<head>
    <title>Edit Subjects</title>
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
            width: 100px;
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
            <div class="contentheader"><i class="icon-edit"></i> Edit Subjects</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_academics.php">Manage Academics</a></li> /
                <li class="active">Edit Subjects</li>
            </ul>

            <a href="manage_academics.php" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
            <div style="clear:both;"></div>

            <div class="card">
                <form action="functions/save_edited_subjects.php" method="post">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Edit Subjects for <?php echo htmlspecialchars($course_info['degree_name'] . ' of ' . $course_info['course_name']); ?></h4>
                    </center>
                    <h5>Year <?php echo htmlspecialchars($year); ?>, Semester <?php echo htmlspecialchars($semester); ?></h5>
                    <hr>
                    <div class="card-body">
                        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                        <input type="hidden" name="year" value="<?php echo $year; ?>">
                        <input type="hidden" name="semester" value="<?php echo $semester; ?>">

                        <?php for ($i = 0; $i < 6; $i++): // Allow editing up to 6 subjects 
                        ?>
                            <span>Subject <?php echo $i + 1; ?>: </span>
                            <input type="text" style="width:300px; height:30px;" name="subjects[]" value="<?php echo isset($subjects[$i]) ? htmlspecialchars($subjects[$i]['subject_name']) : ''; ?>" placeholder="Enter subject name..." /><br>
                        <?php endfor; ?>

                        <br>
                        <div style="text-align: center;">
                            <button class="btn btn-success btn-large" style="width:280px;"><i class="icon-save"></i> Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>