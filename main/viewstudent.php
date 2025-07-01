<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// Initialize arrays for student data and their repeat records
$student = null;
$repeat_records = [];

// Get the student ID from the URL and validate it
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($student_id) {
	try {
		if (isset($db)) {
			// Fetch the student's main information
			$stmt_student = $db->prepare("SELECT * FROM student WHERE id = :userid");
			$stmt_student->bindParam(':userid', $student_id);
			$stmt_student->execute();
			$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

			// Fetch all associated repeat records for that student
			$stmt_repeats = $db->prepare("SELECT * FROM repeat_records WHERE student_id_fk = :userid ORDER BY academic_year DESC, semester DESC");
			$stmt_repeats->bindParam(':userid', $student_id);
			$stmt_repeats->execute();
			$repeat_records = $stmt_repeats->fetchAll(PDO::FETCH_ASSOC);
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
	<title>View Student Details</title>
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

		/* Card-like design for information blocks */
		.info-card {
			background-color: #ffffff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			padding: 25px;
			margin: 20px auto;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
		}

		.card-header {
			overflow: hidden;
			/* Clearfix for floated elements */
			border-bottom: 1px solid #eee;
			padding-bottom: 10px;
			margin-bottom: 15px;
		}

		.card-header h4 {
			margin-top: 0;
			margin-bottom: 0;
			float: left;
		}

		.student-details-table td {
			padding: 8px;
			border: none;
		}

		.student-details-table tr td:first-child {
			font-weight: bold;
			text-align: right;
			width: 150px;
		}

		.action-btn {
			width: 50px;
			margin-bottom: 5px;
		}
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php include('navfixed.php'); ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<div class="well sidebar-nav">
					<ul class="nav nav-list">
						<li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
						<li class="active"><a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a> </li>
						<li><a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a></li>
					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<div class="span10">
				<div class="contentheader">
					<i class="icon-user"></i> Student Details
				</div>
				<ul class="breadcrumb">
					<li><a href="index.php">Dashboard</a></li> /
					<li><a href="students.php">Manage Students</a></li> /
					<li class="active">View Student</li>
				</ul>

				<a href="students.php" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
				<div style="clear:both;"></div>

				<!-- Student Information Card -->
				<div class="info-card">
					<div class="card-header">
						<h4><i class="icon-edit icon-large"></i> Student Information</h4>
					</div>
					<table class="student-details-table">
						<tr>
							<td>Student ID:</td>
							<td><?php echo htmlspecialchars($student['student_id']); ?></td>
						</tr>
						<tr>
							<td>Full Name:</td>
							<td><?php echo htmlspecialchars($student['name'] . ' ' . $student['last_name']); ?></td>
						</tr>
						<tr>
							<td>Bachelor of:</td>
							<td><?php echo htmlspecialchars($student['course']); ?></td>
						</tr>
						<tr>
							<td>Gender:</td>
							<td><?php echo htmlspecialchars($student['gender']); ?></td>
						</tr>
						<tr>
							<td>Date of Birth:</td>
							<td><?php echo htmlspecialchars($student['bdate']); ?></td>
						</tr>
						<tr>
							<td>Address:</td>
							<td><?php echo htmlspecialchars($student['address']); ?></td>
						</tr>
						<tr>
							<td>Contact:</td>
							<td><?php echo "0";
								echo htmlspecialchars($student['contact']); ?></td>
						</tr>
					</table>
				</div>

				<!-- Repeat Records Card -->
				<div class="info-card">
					<div class="card-header">
						<h4><i class="icon-list-alt icon-large"></i> Failed / Repeat Subject Records</h4>
						<a href="addstudent.php?student_id=<?php echo $student_id; ?>&tab=addRepeat" style="float: right;" class="btn btn-primary"><i class="icon-plus"></i> Add Subject Record</a>
					</div>
					<?php if (count($repeat_records) > 0): ?>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Subject Name</th>
									<th>Academic Year</th>
									<th>Year of Failure</th>
									<th>Semester</th>
									<th>Status</th>
									<th>Notes</th>
									<th style="text-align:center;">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($repeat_records as $record): ?>
									<tr>
										<td><?php echo htmlspecialchars($record['subject_name']); ?></td>
										<td><?php echo htmlspecialchars($record['academic_year']); ?></td>
										<td><?php echo htmlspecialchars($record['failed_year']); ?></td>
										<td><?php echo htmlspecialchars($record['semester']); ?></td>
										<td><?php echo $record['passed'] ? '<span class="label label-success">Passed</span>' : '<span class="label label-important">Failed</span>'; ?></td>
										<td><?php echo htmlspecialchars($record['notes']); ?></td>
										<td style="text-align:center;">
											<a href="edit_repeat_record.php?id=<?php echo $record['id']; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-warning btn-mini action-btn"><i class="icon-edit"></i> Edit</a>
											<a href="delete_repeat_record.php?id=<?php echo $record['id']; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-danger btn-mini action-btn" onclick="return confirm('Are you sure you want to delete this record?');"><i class="icon-trash"></i> Delete</a>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<div class="alert alert-info" style="text-align: center;">
							No Repeat Records Found for this student.
						</div>
					<?php endif; ?>
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