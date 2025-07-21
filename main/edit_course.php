<?php
require_once('auth.php');
include('../connect.php');

$course_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$course = null;
$degrees = [];

if ($course_id) {
    $stmt_course = $db->prepare("SELECT * FROM courses WHERE id = :id");
    $stmt_course->bindParam(':id', $course_id);
    $stmt_course->execute();
    $course = $stmt_course->fetch(PDO::FETCH_ASSOC);

    $degrees = $db->query("SELECT * FROM degrees ORDER BY degree_name ASC")->fetchAll(PDO::FETCH_ASSOC);
}
if (!$course) die("Course not found.");
?>
<html>

<head>
    <title>Edit Course</title>
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
            width: 450px;
        }

        .card-body span {
            display: inline-block;
            width: 120px;
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
            <div class="contentheader"><i class="icon-edit"></i> Edit Course</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_academics.php">Manage Academics</a></li> /
                <li class="active">Edit Course</li>
            </ul>
            <div class="card">
                <form action="functions/save_edit_course.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($course['id']); ?>">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Course Details</h4>
                    </center>
                    <hr>
                    <div class="card-body">
                        <span>Degree Level: </span>
                        <select name="degree_id" style="width:280px; height:40px;" required>
                            <?php foreach ($degrees as $degree): ?>
                                <option value="<?php echo $degree['id']; ?>" <?php if ($degree['id'] == $course['degree_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($degree['degree_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br>
                        <span>Course Name: </span>
                        <input type="text" style="width:265px; height:40px;" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required /><br><br>
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