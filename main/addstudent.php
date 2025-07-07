<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// Get student ID and tab from URL for pre-selection
$preselected_student_id = filter_input(INPUT_GET, 'student_id', FILTER_SANITIZE_NUMBER_INT);
$active_tab_param = filter_input(INPUT_GET, 'tab', FILTER_UNSAFE_RAW);

// Determine which tab and pane should be active on page load
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

// Initialize students array
$students = [];
try {
  if (isset($db)) {
    // Fetch all students AND their course to enable dynamic subject loading
    $stmt = $db->prepare("SELECT id, student_id, name, last_name, course FROM student ORDER BY name ASC");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
} catch (PDOException $e) {
  die("Database Error: " . $e->getMessage());
}

// Safely load course data from JSON files
$cgd_json = file_exists('courses/Computer Graphic Design.json') ? file_get_contents('courses/Computer Graphic Design.json') : '{}';
$cs_json = file_exists('courses/Cyber security.json') ? file_get_contents('courses/Cyber security.json') : '{}';
$se_json = file_exists('courses/Software engineering.json') ? file_get_contents('courses/Software engineering.json') : '{}';


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
  <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
  <?php include('navfixed.php'); ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span2">
        <div class="well sidebar-nav">
          <ul class="nav nav-list">
            <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
            <li><a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a> </li>
            <li class="active"><a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a></li>
          </ul>
        </div>
      </div>
      <div class="span10">
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
                  <span>Bachelor of: </span>
                  <select name="course" style="width:280px; height:40px;" required>
                    <option>Computer Graphic Design</option>
                    <option>Cyber Security</option>
                    <option>Software Engineering</option>
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
                  <select name="student_id_fk" id="student_selector" style="width:280px; height:40px;" required>
                    <option value="" data-course="">Select a student</option>
                    <?php foreach ($students as $student) : ?>
                      <option value="<?php echo htmlspecialchars($student['id']); ?>" data-course="<?php echo htmlspecialchars($student['course']); ?>" <?php if ($preselected_student_id == $student['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($student['name'] . ' ' . $student['last_name'] . ' (' . $student['student_id'] . ')'); ?>
                      </option>
                    <?php endforeach; ?>
                  </select><br>
                  <span>Year of Failure: </span>
                  <select name="failed_year" id="failed_year" style="width:280px; height:40px;" required>
                    <option value="">Select Year</option>
                    <?php for ($i = 1; $i <= 4; $i++) : ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
                  </select><br>
                  <span>Semester: </span>
                  <select name="semester" id="semester" style="width:280px; height:40px;" required>
                    <option value="">Select Semester</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select><br>
                  <span>Subject Name: </span>
                  <select name="subject_name" id="subject_name" style="width:280px; height:40px;" disabled required>
                    <option value="">Select Student, Year, and Semester first</option>
                  </select><br>
                  <span>Academic Year: </span><input type="text" name="academic_year" style="width:265px; height:40px;" placeholder="e.g., 2023-2024" required /><br>
                  <span>Subject Passed: </span>
                  <input type="hidden" name="passed" value="0">
                  <input type="checkbox" name="passed" value="1" title="Check if the student has passed this subject" style="width:30px; height:30px;"><br><br>
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
      const courseData = {
        "Computer Graphic Design": <?php echo $cgd_json; ?>,
        "Cyber Security": <?php echo $cs_json; ?>,
        "Software Engineering": <?php echo $se_json; ?>
      };

      function updateSubjects() {
        const studentSelector = $('#student_selector');
        const selectedStudent = studentSelector.find('option:selected');
        const courseName = selectedStudent.data('course');
        const year = $('#failed_year').val();
        const semester = $('#semester').val();
        const subjectSelector = $('#subject_name');


        // ---

        subjectSelector.html('<option value="">Select Student, Year, and Semester first</option>').prop('disabled', true);

        if (!courseName || !year || !semester) {
          return; // Exit if any of the required fields are not selected
        }

        // Find the matching key in courseData, ignoring case and trimming whitespace
        let matchedCourseKey = Object.keys(courseData).find(key => key.toLowerCase().trim() === courseName.toLowerCase().trim());


        if (matchedCourseKey && courseData[matchedCourseKey]) {
          const course = courseData[matchedCourseKey];
          const yearData = course.years.find(y => y.year == year);
          if (yearData) {
            const semesterData = yearData.semesters.find(s => s.semester == semester);
            if (semesterData && semesterData.courses.length > 0) {
              subjectSelector.html('<option value="">Select a subject</option>');
              semesterData.courses.forEach(function(subject) {
                subjectSelector.append($('<option>', {
                  value: subject,
                  text: subject
                }));
              });
              subjectSelector.prop('disabled', false); // Enable the dropdown

            } else {
              console.log("No subjects found for this semester.");
            }
          } else {
            console.log("No data found for this year.");
          }
        } else {
          console.log("Could not find course data for:", courseName);
        }
      }

      // Attach event listener to all three dropdowns
      $('#student_selector, #failed_year, #semester').on('change', updateSubjects);

      // Trigger the function on page load if a student is already selected
      if ($('#student_selector').val()) {
        updateSubjects();
      }

      // Facebox and Tab initialization
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