<?php
require_once('auth.php');
include('../connect.php');
$degrees = $db->query("SELECT * FROM degrees ORDER BY degree_name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<html>

<head>
    <title>Add New Course</title>
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
            <div class="contentheader"><i class="icon-plus-sign"></i> Add New Course</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_academics.php">Manage Academics</a></li> /
                <li class="active">Add Course</li>
            </ul>
            <div class="card">
                <form action="functions/save_course.php" method="post">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Course Details</h4>
                    </center>
                    <hr>
                    <div class="card-body">
                        <span>Degree Level: </span>
                        <select name="degree_id" style="width:280px; height:40px;" required>
                            <option value="">Select a Degree</option>
                            <?php foreach ($degrees as $degree): ?>
                                <option value="<?php echo $degree['id']; ?>"><?php echo htmlspecialchars($degree['degree_name']); ?></option>
                            <?php endforeach; ?>
                        </select><br>
                        <span>Course Name: </span>
                        <input type="text" style="width:265px; height:40px;" name="course_name" placeholder="e.g., Data Science" required /><br><br>
                        <div style="text-align: center;">
                            <button class="btn btn-success btn-large" style="width:280px;"><i class="icon-save"></i> Save Course</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>