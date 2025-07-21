<?php
require_once('auth.php');
include('../connect.php');
$courses = $db->query("
    SELECT c.id, c.course_name, d.degree_name 
    FROM courses c 
    JOIN degrees d ON c.degree_id = d.id 
    ORDER BY d.degree_name, c.course_name ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<html>

<head>
    <title>Add New Subject(s)</title>
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

        .card-body>div {
            margin-bottom: 10px;
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
            <div class="contentheader"><i class="icon-plus-sign"></i> Add New Subjects</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_academics.php">Manage Academics</a></li> /
                <li class="active">Add Subjects</li>
            </ul>
            <div class="card">
                <form action="functions/save_subject.php" method="post">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Subject Details</h4>
                    </center>
                    <hr>
                    <div class="card-body">
                        <div>
                            <span>Course/Program: </span>
                            <select name="course_id" style="width:300px; height:40px;" required>
                                <option value="">Select a Program</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['degree_name'] . ' of ' . $course['course_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <span>Year: </span>
                            <select name="year" style="width:300px; height:40px;" required>
                                <option value="">Select Year</option>
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <span>Semester: </span>
                            <select name="semester" style="width:300px; height:40px;" required>
                                <option value="">Select Semester</option>
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>

                        <hr>

                        <div>
                            <span>Subjects: </span>
                            <div id="subject_fields_container" style="display: inline-block; vertical-align: top;">
                                <!-- Initial Subject Field -->
                                <div class="input-append" style="margin-bottom: 5px;">
                                    <input type="text" style="width:250px; height:30px;" name="subjects[]" placeholder="Subject Name" required />
                                    <button id="add_subject_button" class="btn btn-success" type="button"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div style="text-align: center;">
                            <button class="btn btn-success btn-large" style="width:280px;"><i class="icon-save"></i> Save Subjects</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CORRECTED: Using a modern version of jQuery from a CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add new subject field
            $('#add_subject_button').click(function() {
                var newField = `
                    <div class="input-append" style="margin-bottom: 5px;">
                        <input type="text" style="width:250px; height:30px;" name="subjects[]" placeholder="Subject Name" required />
                        <button class="btn btn-danger remove_field" type="button"><i class="icon-minus"></i></button>
                    </div>`;
                $('#subject_fields_container').append(newField);
            });

            // Remove subject field
            $('#subject_fields_container').on('click', '.remove_field', function() {
                $(this).parent('div.input-append').remove();
            });
        });
    </script>
</body>

</html>