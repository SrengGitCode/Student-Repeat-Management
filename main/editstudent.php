<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// --- Initialize variables ---
$student = null;
$degrees = [];
$courses = [];

// Get the student ID from the URL and validate it
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($student_id) {
	try {
		if (isset($db)) {
			// Fetch the specific student's information, joining to get degree and course IDs
			$stmt_student = $db->prepare("
                SELECT s.*, c.degree_id 
                FROM student s
                JOIN courses c ON s.course_id = c.id
                WHERE s.id = :userid
            ");
			$stmt_student->bindParam(':userid', $student_id);
			$stmt_student->execute();
			$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

			// Fetch all degrees for the dropdown
			$stmt_degrees = $db->prepare("SELECT * FROM degrees ORDER BY degree_name ASC");
			$stmt_degrees->execute();
			$degrees = $stmt_degrees->fetchAll(PDO::FETCH_ASSOC);

			// Fetch all courses for the dynamic dropdown logic
			$stmt_courses = $db->prepare("SELECT id, course_name, degree_id FROM courses ORDER BY course_name ASC");
			$stmt_courses->execute();
			$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);
		}
	} catch (PDOException $e) {
		die("Database Error: " . $e->getMessage());
	}
}

// If no student is found with that ID, handle it gracefully
if (!$student) {
	die("Error: Student not found.");
}
?>
<html>

<head>
	<title>Edit Student</title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link href="css/sidebar.css" rel="stylesheet">
	<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
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
		<?php include('sidebar.php'); ?>
	</div>
	<div class="content-main">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="contentheader">
						<i class="icon-edit"></i> Edit Student Information
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php">Dashboard</a></li> /
						<li><a href="students.php">Manage Students</a></li> /
						<li class="active">Edit Student</li>
					</ul>

					<a href="students.php" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
					<div style="clear:both;"></div>

					<div class="card">
						<form action="saveeditstudent.php" method="post">
							<center>
								<h4><i class="icon-edit icon-large"></i> Edit Details</h4>
							</center>
							<hr>
							<div class="card-body">
								<input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>" />

								<span>Student ID: </span><input type="text" style="width:265px; height:40px;" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" readonly /><br>

								<span>First Name: </span><input type="text" style="width:265px; height:40px;" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required /><br>

								<span>Last Name: </span><input type="text" style="width:265px; height:40px;" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required /><br>

								<span>Degree Level: </span>
								<select id="degree_selector" style="width:280px; height:40px;" required>
									<option value="">Select a Degree</option>
									<?php foreach ($degrees as $degree): ?>
										<option value="<?php echo $degree['id']; ?>" <?php if ($degree['id'] == $student['degree_id']) echo 'selected'; ?>>
											<?php echo htmlspecialchars($degree['degree_name']); ?>
										</option>
									<?php endforeach; ?>
								</select><br>

								<span>Course/Program: </span>
								<select name="course_id" id="course_selector" style="width:280px; height:40px;" required>
									<option value="">Select a Degree First</option>
								</select><br>

								<span>Gender: </span>
								<select name="gender" style="width:280px; height:40px;" required>
									<option <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
									<option <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
								</select><br>

								<span>Birth Date: </span><input type="date" style="width:265px; height:40px;" name="bdate" value="<?php echo htmlspecialchars($student['bdate']); ?>" /><br>

								<span>Address: </span><input type="text" style="width:265px; height:40px;" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" /><br>

								<span>Contact: </span><input type="text" style="width:265px; height:40px;" name="contact" value="<?php echo htmlspecialchars($student['contact']); ?>" /><br><br>

								<div style="text-align: center;">
									<button class="btn btn-success btn-large" style="width:280px;"><i class="icon icon-save icon-large"></i> Save Changes</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>

	<script src="lib/jquery-3.7.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			const allCourses = <?php echo json_encode($courses); ?>;
			const studentCourseId = <?php echo $student['course_id']; ?>;

			function populateCourses(degreeId) {
				const courseSelector = $('#course_selector');
				courseSelector.html('<option value="">Select a Course</option>').prop('disabled', true);

				if (degreeId) {
					const filteredCourses = allCourses.filter(course => course.degree_id == degreeId);
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
			}

			// When the degree selector changes, update the course list
			$('#degree_selector').on('change', function() {
				const selectedDegreeId = $(this).val();
				populateCourses(selectedDegreeId);
			});

			// On page load, populate the courses for the student's current degree
			const initialDegreeId = $('#degree_selector').val();
			if (initialDegreeId) {
				populateCourses(initialDegreeId);
				// Pre-select the student's current course
				$('#course_selector').val(studentCourseId);
			}
		});
	</script>
</body>

</html>