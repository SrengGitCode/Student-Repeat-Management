<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// Initialize student variable
$student = null;

// Get the student ID from the URL and validate it
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($student_id) {
	try {
		if (isset($db)) {
			// Fetch the student's main information to pre-fill the form
			$stmt = $db->prepare("SELECT * FROM student WHERE id = :userid");
			$stmt->bindParam(':userid', $student_id);
			$stmt->execute();
			$student = $stmt->fetch(PDO::FETCH_ASSOC);
		}
	} catch (PDOException $e) {
		// Display a detailed error message if the query fails
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

		/* Card-like design for the edit form */
		.card {
			background-color: #ffffff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			padding: 25px;
			margin: 20px auto;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
			width: 450px;
			/* Adjust width as needed */
		}

		.card-body span {
			display: inline-block;
			width: 120px;
			/* Aligns the labels */
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
						<li class="active"><a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a></li>
						<li><a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a></li>
					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<div class="span10">
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

							<span>Student ID: </span><input type="text" style="width:265px; height:30px;" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" readonly /><br>

							<span>First Name: </span><input type="text" style="width:265px; height:30px;" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required /><br>

							<span>Last Name: </span><input type="text" style="width:265px; height:30px;" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required /><br>

							<span>Bachelor of: </span><input type="text" style="width:265px; height:30px;" name="course" value="<?php echo htmlspecialchars($student['course']); ?>" /><br>

							<span>Gender: </span>
							<select name="gender" style="width:280px; height:40px;" required>
								<option <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
								<option <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
							</select><br>

							<span>Birth Date: </span><input type="date" style="width:265px; height:30px;" name="bdate" value="<?php echo htmlspecialchars($student['bdate']); ?>" /><br>

							<span>Address: </span><input type="text" style="width:265px; height:30px;" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" /><br>

							<span>Contact: </span><input type="text" style="width:265px; height:30px;" name="contact" value="<?php echo htmlspecialchars($student['contact']); ?>" /><br><br>

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

	<script src="lib/jquery.js"></script>
	<script src="src/facebox.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>