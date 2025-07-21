<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// --- Fetch data for the forms ---
$degrees = [];
$courses = [];
$students = [];

try {
  if (isset($db)) {
    // Fetch all degrees
    $stmt_degrees = $db->prepare("SELECT * FROM degrees ORDER BY degree_name ASC");
    $stmt_degrees->execute();
    $degrees = $stmt_degrees->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all courses and join with degrees
    $stmt_courses = $db->prepare("SELECT c.id, c.course_name, c.degree_id, d.degree_name FROM courses c JOIN degrees d ON c.degree_id = d.id ORDER BY d.degree_name, c.course_name");
    $stmt_courses->execute();
    $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all students and join with courses and degrees to get all necessary info
    $stmt_students = $db->prepare("
            SELECT s.id, s.student_id, s.name, s.last_name, s.course_id, c.course_name, d.degree_name 
            FROM student s
            JOIN courses c ON s.course_id = c.id
            JOIN degrees d ON c.degree_id = d.id
            ORDER BY s.name ASC
        ");
    $stmt_students->execute();
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
  }
} catch (PDOException $e) {
  die("Database Error: " . $e->getMessage());
}

// --- Handle active tab logic ---
$preselected_student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);
$active_tab_param = filter_input(INPUT_GET, 'tab', FILTER_UNSAFE_RAW);

$addStudentTabClass = 'active';
$addRepeatTabClass = '';
$addStudentPaneClass = 'active in';
$addRepeatPaneClass = '';

if ($active_tab_param === 'addRepeat' || !empty($preselected_student_id)) {
  $addStudentTabClass = '';
  $addRepeatTabClass = 'active';
  $addStudentPaneClass = '';
  $addRepeatPaneClass = 'active in';
}

/**
 * Displays a success message if the corresponding URL parameter is set.
 */
function display_success_message($type, $message)
{
  if (isset($_GET['success']) && $_GET['success'] == $type) {
    echo '<div class="alert alert-success" style="width: 80%; margin: 10px auto;">
                  <center><strong><i class="icon-ok"></i> ' . htmlspecialchars($message) . '</strong></center>
              </div>';
  }
}
?>
<html>

<head>
  <title>Student Repeat Management System</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link href="css/sidebar.css" rel="stylesheet">
  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;
    }

    .sidebar-nav {
      padding: 9px 0;
    }

    .nav-tabs {
      margin-bottom: 20px;
    }

    .card {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 25px;
      margin: 15px auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      width: 450px;
    }

    .card-body span {
      display: inline-block;
      width: 120px;
      text-align: right;
      margin-right: 10px;
    }
  </style>
  <link href="css/bootstrap-responsive.css" rel="stylesheet">
  <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
  <?php include('navfixed.php'); ?>
  <div class="sidebar-fixed">
    <?php include('sidebar.php');  ?>
  </div>
  <div class="content-main">
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">
          <div class="contentheader">
            <i class="icon-table"></i> Student and Repeat Management
          </div>
          <ul class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li> /
            <li class="active">Add Student & Repeats</li>
          </ul>

          <a href="students.php" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
          <div style="clear:both; margin-bottom: 10px;"></div>

          <ul class="nav nav-tabs" id="myTab">
            <li class="<?php echo $addStudentTabClass; ?>"><a href="#addStudent" data-toggle="tab">Add Student</a></li>
            <li class="<?php echo $addRepeatTabClass; ?>"><a href="#addRepeat" data-toggle="tab">Add Repeat Record</a></li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane <?php echo $addStudentPaneClass; ?>" id="addStudent">
              <div class="card">
                <form action="savestudent.php" method="post">
                  <center>
                    <h4><i class="icon-edit icon-large"></i> Add New Student</h4>
                  </center>
                  <?php display_success_message('studentadded', 'Student saved successfully!'); ?>
                  <hr>
                  <div class="card-body">
                    <span>Student ID: </span><input type="text" style="width:265px; height:40px;" name="student_id" placeholder="Student ID" required /><br>
                    <span>First Name: </span><input type="text" style="width:265px; height:40px;" name="name" placeholder="First Name" required /><br>
                    <span>Last Name: </span><input type="text" style="width:265px; height:40px;" name="last_name" placeholder="Last Name" required /><br>

                    <span>Degree Level: </span>
                    <select id="degree_selector_student" style="width:280px; height:40px;" required>
                      <option value="">Select a Degree</option>
                      <?php foreach ($degrees as $degree): ?>
                        <option value="<?php echo $degree['id']; ?>"><?php echo htmlspecialchars($degree['degree_name']); ?></option>
                      <?php endforeach; ?>
                    </select><br>

                    <span>Course/Program: </span>
                    <select name="course_id" id="course_selector_student" style="width:280px; height:40px;" required disabled>
                      <option value="">Select a Degree First</option>
                    </select><br>

                    <span>Gender: </span><select name="gender" style="width:280px; height:40px;">
                      <option>Male</option>
                      <option>Female</option>
                    </select><br>
                    <span>Birth Date: </span><input type="date" style="width:265px; height:40px;" name="bdate" /><br>
                    <span>Address: </span><input type="text" style="width:265px; height:40px;" name="address" placeholder="Address" /><br>
                    <span>Contact: </span><input type="number" style="width:265px; height:40px;" name="contact" placeholder="Contact" /><br><br>
                    <div style="text-align: center;"><button class="btn btn-success btn-large" style="width:280px;"><i class="icon icon-save icon-large"></i> Save Student</button></div>
                  </div>
                </form>
              </div>
            </div>

            <div class="tab-pane <?php echo $addRepeatPaneClass; ?>" id="addRepeat">
              <div class="card">
                <form action="add_repeat_record.php" method="post">
                  <center>
                    <h4><i class="icon-plus-sign icon-large"></i> Add Failed Subject Record</h4>
                  </center>
                  <?php display_success_message('recordadded', 'Repeat record saved successfully!'); ?>
                  <hr>
                  <div class="card-body">
                    <span>Student: </span>
                    <select name="student_id_fk" id="student_selector_repeat" style="width:280px; height:40px;" required>
                      <option value="" data-courseid="">Select a student</option>
                      <?php foreach ($students as $student) : ?>
                        <option value="<?php echo htmlspecialchars($student['id']); ?>" data-courseid="<?php echo htmlspecialchars($student['course_id']); ?>" <?php if ($preselected_student_id == $student['id']) echo 'selected'; ?>>
                          <?php echo htmlspecialchars($student['name'] . ' ' . $student['last_name'] . ' (' . $student['degree_name'] . ' of ' . $student['course_name'] . ')'); ?>
                        </option>
                      <?php endforeach; ?>
                    </select><br>
                    <span>Year of Failure: </span>
                    <select name="failed_year" id="failed_year_repeat" style="width:280px; height:40px;" required>
                      <option value="">Select Year</option>
                      <?php for ($i = 1; $i <= 4; $i++) : ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
                    </select><br>
                    <span>Semester: </span>
                    <select name="semester" id="semester_repeat" style="width:280px; height:40px;" required>
                      <option value="">Select Semester</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select><br>
                    <span>Subject Name: </span>
                    <select name="subject_name" id="subject_name_repeat" style="width:280px; height:40px;" disabled required>
                      <option value="">Select Student, Year, and Semester first</option>
                    </select><br>
                    <span>Academic Year: </span><input type="text" name="academic_year" style="width:265px; height:40px;" placeholder="e.g., 2023-2024" required /><br>
                    <span>Subject Passed: </span>
                    <input type="hidden" name="passed" value="0">
                    <input type="checkbox" name="passed" value="1" title="subject is already failed - Greyed out" style="width:30px; height:30px;" disabled><br><br>
                    <span>Notes (Optional): </span><textarea style="width:265px; height:50px;" name="notes"></textarea><br><br>
                    <div style="text-align: center;"><button class="btn btn-success btn-large" style="width:280px;"><i class="icon icon-save icon-large"></i> Add Record</button></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include('footer.php'); ?>

    <script src="lib/jquery-3.7.1.min.js"></script>
    <script src="src/facebox.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
      jQuery(document).ready(function($) {
        // --- Data for dynamic dropdowns ---
        const courses = <?php echo json_encode($courses); ?>;
        const allSubjects = {}; // We will fetch this via AJAX

        // --- Logic for "Add Student" Tab ---
        $('#degree_selector_student').on('change', function() {
          const degreeId = $(this).val();
          const courseSelector = $('#course_selector_student');

          courseSelector.html('<option value="">Select a Course</option>').prop('disabled', true);

          if (degreeId) {
            const filteredCourses = courses.filter(course => course.degree_id == degreeId);
            if (filteredCourses.length > 0) {
              filteredCourses.forEach(function(course) {
                courseSelector.append($('<option>', {
                  value: course.id,
                  text: course.course_name
                }));
              });
              courseSelector.prop('disabled', false);
            }
          }
        });

        // --- Logic for "Add Repeat Record" Tab ---
        function updateSubjects() {
          const studentSelector = $('#student_selector_repeat');
          const selectedStudent = studentSelector.find('option:selected');
          const courseId = selectedStudent.data('courseid');
          const year = $('#failed_year_repeat').val();
          const semester = $('#semester_repeat').val();
          const subjectSelector = $('#subject_name_repeat');

          subjectSelector.html('<option value="">Select Student, Year, and Semester first</option>').prop('disabled', true);

          if (courseId && year && semester) {
            // Use AJAX to get subjects from the server
            $.ajax({
              url: 'get_subjects.php',
              type: 'GET',
              data: {
                course_id: courseId,
                year: year,
                semester: semester
              },
              dataType: 'json',
              success: function(subjects) {
                if (subjects && subjects.length > 0) {
                  subjectSelector.html('<option value="">Select a subject</option>');
                  subjects.forEach(function(subject) {
                    subjectSelector.append($('<option>', {
                      value: subject.subject_name,
                      text: subject.subject_name
                    }));
                  });
                  subjectSelector.prop('disabled', false);
                } else {
                  subjectSelector.html('<option value="">No subjects found</option>');
                }
              },
              error: function() {
                subjectSelector.html('<option value="">Error loading subjects</option>');
              }
            });
          }
        }

        $('#student_selector_repeat, #failed_year_repeat, #semester_repeat').on('change', updateSubjects);

        if ($('#student_selector_repeat').val()) {
          updateSubjects();
        }

        // --- General Page Logic ---
        $('a[rel*=facebox]').facebox({
          loadingImage: 'src/loading.gif',
          closeImage: 'src/closelabel.png'
        });

        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');
        if (successParam === 'recordadded') {
          $('#myTab a[href="#addRepeat"]').tab('show');
        }
      });
    </script>
</body>

</html>