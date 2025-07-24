<?php
require_once('auth.php');
include('../connect.php');

// Fetch and group all academic data
$academic_structure = [];
try {
    $stmt = $db->prepare("
        SELECT 
            d.id AS degree_id, d.degree_name,
            c.id AS course_id, c.course_name,
            s.id AS subject_id, s.subject_name, s.year, s.semester
        FROM degrees d
        LEFT JOIN courses c ON d.id = c.degree_id
        LEFT JOIN subjects s ON c.id = s.course_id
        ORDER BY d.degree_name, c.course_name, s.year, s.semester, s.subject_name
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Process results into a structured, multi-level array
    foreach ($results as $row) {
        $degree_name = $row['degree_name'];
        $course_name = $row['course_name'];
        $year = $row['year'];
        $semester = $row['semester'];

        $academic_structure[$degree_name]['id'] = $row['degree_id'];
        if ($row['course_id']) {
            $academic_structure[$degree_name]['courses'][$course_name]['id'] = $row['course_id'];
            if ($row['subject_id']) {
                $academic_structure[$degree_name]['courses'][$course_name]['subjects'][$year][$semester][] = $row;
            }
        }
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<html>

<head>
    <title>Manage Academics</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/sidebar.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }

        .info-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 0 3%;
        }

        .card-header {
            overflow: hidden;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            margin-bottom: 15px;
        }

        .card-header h4 {
            margin: 0;
            float: left;
        }

        .card-header .action-buttons {
            float: right;
        }

        .accordion-group {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .accordion-heading {
            background-color: #f7f7f9;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
            position: relative;
        }

        .accordion-heading .accordion-toggle {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            color: #333;
        }

        .accordion-heading .actions {
            position: absolute;
            right: 15px;
            top: 8px;
        }

        .accordion-inner {
            padding: 15px;
        }

        .course-accordion .accordion-heading {
            background-color: #fff;
        }

        .course-accordion .accordion-heading .accordion-toggle {
            font-size: 14px;
        }

        .course-accordion .accordion-inner {
            border-top: 1px solid #e9e9e9;
            padding: 15px;
        }

        .year-divider {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            line-height: 0.1em;
            margin: 30px 0 20px;
        }

        .year-divider span {
            background: #fff;
            padding: 0 10px;
            color: #0056b3;
            font-size: 18px;
            font-weight: bold;
        }

        .semester-block {
            padding: 10px;
        }

        .semester-header {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .semester-header h6 {
            float: left;
            margin: 0;
        }

        .subject-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .subject-list li {
            padding: 5px 0;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include('navfixed.php'); ?>
    <div class="sidebar-fixed">
        <?php include('sidebar.php'); ?>
    </div>
    <div class="content-main">
        <div class="container-fluid">
            <div class="contentheader">
                <i class="icon-book"></i> Manage Academics
            </div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li class="active">Manage Academics</li>
            </ul>

            <div class="info-card">
                <div class="card-header">
                    <h4><i class="icon-list-alt icon-large"></i> Academic Structure</h4>
                    <div class="action-buttons">
                        <a href="add_degree.php" class="btn btn-primary"><i class="icon-plus"></i> Add Degree</a>
                        <a href="add_course.php" class="btn btn-success"><i class="icon-plus"></i> Add Course</a>
                        <a href="add_subject.php" class="btn btn-info"><i class="icon-plus"></i> Add Subject</a>
                    </div>
                </div>

                <div class="accordion" id="degreeAccordion">
                    <?php if (empty($academic_structure)): ?>
                        <div class="alert alert-info" style="margin-top: 15px;">No academic structure found. Start by adding a degree.</div>
                    <?php else: ?>
                        <?php foreach ($academic_structure as $degree_name => $degree_data): ?>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#degreeAccordion" href="#degree-<?php echo $degree_data['id']; ?>">
                                        <i class="icon-folder-close"></i> <strong><?php echo htmlspecialchars($degree_name); ?></strong>
                                    </a>
                                    <div class="actions">
                                        <a href="edit_degree.php?id=<?php echo $degree_data['id']; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
                                        <?php
                                        $has_courses = isset($degree_data['courses']);
                                        $disabled_class = $has_courses ? 'disabled' : '';
                                        $tooltip_title = $has_courses ? 'Cannot delete: Degree has courses' : 'Delete this degree';
                                        ?>
                                        <a href="functions/delete_degree.php?id=<?php echo $degree_data['id']; ?>" class="btn btn-danger btn-mini <?php echo $disabled_class; ?>" title="<?php echo $tooltip_title; ?>" onclick="if(this.classList.contains('disabled')) return false; return confirm('Are you sure?');"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                <div id="degree-<?php echo $degree_data['id']; ?>" class="accordion-body collapse">
                                    <div class="accordion-inner">
                                        <?php if (isset($degree_data['courses'])): ?>
                                            <div class="accordion course-accordion" id="courseAccordion-<?php echo $degree_data['id']; ?>">
                                                <?php foreach ($degree_data['courses'] as $course_name => $course_data): ?>
                                                    <div class="accordion-group">
                                                        <div class="accordion-heading">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#courseAccordion-<?php echo $degree_data['id']; ?>" href="#course-<?php echo $course_data['id']; ?>">
                                                                <i class="icon-briefcase"></i> <?php echo htmlspecialchars($course_name); ?>
                                                            </a>
                                                            <div class="actions">
                                                                <a href="edit_course.php?id=<?php echo $course_data['id']; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
                                                                <?php
                                                                $has_subjects = isset($course_data['subjects']);
                                                                $disabled_class_course = $has_subjects ? 'disabled' : '';
                                                                $tooltip_title_course = $has_subjects ? 'Cannot delete: Course has subjects' : 'Delete this course';
                                                                ?>
                                                                <a href="functions/delete_course.php?id=<?php echo $course_data['id']; ?>" class="btn btn-danger btn-mini <?php echo $disabled_class_course; ?>" title="<?php echo $tooltip_title_course; ?>" onclick="if(this.classList.contains('disabled')) return false; return confirm('Are you sure?');"><i class="icon-trash"></i></a>
                                                            </div>
                                                        </div>
                                                        <div id="course-<?php echo $course_data['id']; ?>" class="accordion-body collapse">
                                                            <div class="accordion-inner">
                                                                <?php if (isset($course_data['subjects'])): ?>
                                                                    <?php ksort($course_data['subjects']); ?>
                                                                    <?php foreach ($course_data['subjects'] as $year => $semesters): ?>
                                                                        <div class="year-group">
                                                                            <h4 class="year-divider"><span>Year <?php echo htmlspecialchars($year); ?></span></h4>
                                                                            <div class="row-fluid">
                                                                                <div class="span6 semester-block">
                                                                                    <div class="semester-header">
                                                                                        <h6>Semester 1</h6>
                                                                                        <a href="edit_subjects.php?course_id=<?php echo $course_data['id']; ?>&year=<?php echo $year; ?>&semester=1" class="btn btn-mini" style="float:right;"><i class="icon-edit"></i> Edit Subjects</a>
                                                                                    </div>
                                                                                    <ul class="subject-list">
                                                                                        <?php if (isset($semesters[1])): ?>
                                                                                            <?php foreach ($semesters[1] as $subject): ?>
                                                                                                <li><i class="icon-book"></i> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
                                                                                            <?php endforeach; ?>
                                                                                        <?php else: ?>
                                                                                            <li><span class="text-muted">No subjects defined.</span></li>
                                                                                        <?php endif; ?>
                                                                                    </ul>
                                                                                </div>
                                                                                <div class="span6 semester-block" style="border-left: 1px solid #eee;">
                                                                                    <div class="semester-header">
                                                                                        <h6>Semester 2</h6>
                                                                                        <a href="edit_subjects.php?course_id=<?php echo $course_data['id']; ?>&year=<?php echo $year; ?>&semester=2" class="btn btn-mini" style="float:right;"><i class="icon-edit"></i> Edit Subjects</a>
                                                                                    </div>
                                                                                    <ul class="subject-list">
                                                                                        <?php if (isset($semesters[2])): ?>
                                                                                            <?php foreach ($semesters[2] as $subject): ?>
                                                                                                <li><i class="icon-book"></i> <?php echo htmlspecialchars($subject['subject_name']); ?></li>
                                                                                            <?php endforeach; ?>
                                                                                        <?php else: ?>
                                                                                            <li><span class="text-muted">No subjects defined.</span></li>
                                                                                        <?php endif; ?>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                <?php else: echo "<h5>Please Add Subject</h5>" ?>

                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        <?php else: echo "<h5>Please Add Course</h5>" ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
</body>

</html>