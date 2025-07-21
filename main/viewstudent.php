<?php
// Enable error reporting to debug blank page issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Authenticate and connect to the database
require_once('auth.php');
include('../connect.php');

// Initialize arrays
$student = null;
$grouped_records = [];

// Get the student ID from the URL and validate it
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($student_id) {
	try {
		if (isset($db)) {
			// UPDATED QUERY: Joins student, courses, and degrees to get names
			$stmt_student = $db->prepare("
                SELECT s.*, c.course_name, d.degree_name 
                FROM student s
                JOIN courses c ON s.course_id = c.id
                JOIN degrees d ON c.degree_id = d.id
                WHERE s.id = :userid
            ");
			$stmt_student->bindParam(':userid', $student_id);
			$stmt_student->execute();
			$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

			// Fetch all associated repeat records for that student
			$stmt_repeats = $db->prepare("SELECT * FROM repeat_records WHERE student_id_fk = :userid ORDER BY failed_year ASC, semester ASC");
			$stmt_repeats->bindParam(':userid', $student_id);
			$stmt_repeats->execute();
			$repeat_records = $stmt_repeats->fetchAll(PDO::FETCH_ASSOC);

			// Group the records by year and then by semester
			foreach ($repeat_records as $record) {
				$grouped_records[$record['failed_year']][$record['semester']][] = $record;
			}
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
	<title>View Student Details</title>
	<link href="css/bootstrap.css" rel="stylesheet">
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

		/* General Card Styles */
		.info-card {
			padding: 20px, 20px, 20px 0;
			border-radius: 8px;
			margin: 20px auto;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
		}

		.year-card,
		.semester-card {
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			margin: 20px auto;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
		}

		/* Student Info Card Specific Styles */
		.student-info-card {
			padding: 25px;
			width: 500px;
			/* Smaller width */
			background-color: #b4dcec;
			/* Custom background color */
			border-color: #9ac7d9;
		}

		.year-card {

			padding: 25px;
			background-color: #ffffff;
		}

		.semester-card {
			padding: 20px;
			margin-top: 15px;
			border-color: #f0f0f0;
			background-color: #f9f9f9;
		}

		.card-header {
			overflow: hidden;
			border-bottom: 1px solid #eee;
			padding-bottom: 10px;
			margin-bottom: 15px;
		}

		.card-header h4,
		.card-header h5 {
			margin: 0;
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
						<i class="icon-user"></i> Student Details
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php">Dashboard</a></li> /
						<li><a href="students.php">Manage Students</a></li> /
						<li class="active">View Student</li>
					</ul>

					<a href="students.php" style="float:left; margin-right:10px;"><button class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
					<div style="clear:both;"></div>

					<div class="info-card student-info-card" style="border-style: solid;">
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
								<td>Program:</td>
								<!-- CORRECTED: Displays full degree and course name -->
								<td><?php echo htmlspecialchars($student['degree_name'] . ' of ' . $student['course_name']); ?></td>
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
								<td><?php echo htmlspecialchars($student['contact']); ?></td>
							</tr>
						</table>
					</div>

					<div class="info-card" style="padding: 0% 3% ">
						<div class="card-header" style="padding: 6px;">
							<h4><i class="icon-list-alt icon-large"></i> Failed / Repeat Subject Records</h4>
							<a href="addstudent.php?student_id=<?php echo $student_id; ?>&tab=addRepeat" style="float: right; font-size: large;" class="btn btn-primary"><i class="icon-plus"></i> Add Subject Record</a>
						</div>

						<?php if (empty($grouped_records)) : ?>
							<div class="alert alert-info" style="text-align: center;">
								No Repeat Records Found for this student.
							</div>
						<?php else : ?>
							<?php foreach ($grouped_records as $year => $semesters) : ?>
								<div class="year-card" style="">
									<h4 style="color: #0056b3;">Year <?php echo htmlspecialchars(string: $year); ?></h4>

									<?php foreach ($semesters as $semester => $records) : ?>
										<div class="semester-card">
											<h5><i class="icon-book"></i> Semester <?php echo htmlspecialchars($semester); ?></h5>
											<table class="table table-bordered table-striped" style="margin-top: 10px;">
												<thead>
													<tr>
														<th>Subject Name</th>
														<th>Academic Year</th>
														<th>Notes</th>
														<th>Status</th>
														<th style="text-align:center;">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($records as $record) : ?>
														<tr>
															<td><?php echo htmlspecialchars($record['subject_name']); ?></td>
															<td><?php echo htmlspecialchars($record['academic_year']); ?></td>
															<td><?php echo htmlspecialchars($record['notes']); ?></td>
															<td style="text-align:center;" title="Clickable">
																<a href="update_status.php?id=<?php echo $record['id']; ?>&status=<?php echo $record['passed'] ? '0' : '1'; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-mini <?php echo $record['passed'] ? 'btn-success' : 'btn-danger'; ?>">
																	<?php echo $record['passed'] ? '<i class="icon-ok"></i> Passed' : '<i class="icon-remove"></i> Failed'; ?>
																</a>
															</td>
															<td style="text-align:center;">
																<a href="edit_repeat_record.php?id=<?php echo $record['id']; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
																<a href="delete_repeat_record.php?id=<?php echo $record['id']; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure you want to delete this record?');"><i class="icon-trash"></i> Delete</a>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>

</html>