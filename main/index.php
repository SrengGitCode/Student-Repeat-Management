<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('auth.php');
include('../connect.php');

// Initialize counts
$student_count = 0;
$students_with_fails_count = 0;
$courses_count = 0;
$transfers_count = 0;

try {
	if (isset($db)) {
		// Query to count total students
		$result_students = $db->prepare("SELECT count(*) FROM student");
		$result_students->execute();
		$student_count = $result_students->fetchColumn();

		// Query to count unique students with at least one failed record
		$result_fails = $db->prepare("SELECT COUNT(DISTINCT student_id_fk) FROM repeat_records WHERE passed = 0");
		$result_fails->execute();
		$students_with_fails_count = $result_fails->fetchColumn();

		// Query to count total courses
		$result_courses = $db->prepare("SELECT count(*) FROM courses");
		$result_courses->execute();
		$courses_count = $result_courses->fetchColumn();

		// Query to count total transfer students
		$result_transfers = $db->prepare("SELECT count(*) FROM transfer_students");
		$result_transfers->execute();
		$transfers_count = $result_transfers->fetchColumn();
	}
} catch (PDOException $e) {
	die("Database Error: " . $e->getMessage());
}
?>

<html>

<head>
	<title>Student Repeat Management System</title>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="css/sidebar.css" rel="stylesheet">
	<style type="text/css">
		/* Redesigned Stat Box Styles */
		.stat-box {
			display: block;
			background-color: #f9f9f9;
			border: 1px solid #ddd;
			border-radius: 5px;
			padding: 20px;
			margin-bottom: 20px;
			text-align: center;
			color: #333;
			text-decoration: none;
			transition: all 0.3s ease-in-out;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
		}

		.stat-box:hover {
			color: #fff;
			transform: translateY(-5px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.stat-box.students:hover {
			background-color: #51A351;
		}

		.stat-box.at-risk:hover {
			background-color: #f89406;
		}

		.stat-box.add-new:hover {
			background-color: #2f96b4;
		}

		.stat-box.academics:hover {
			background-color: #8e44ad;
		}

		.stat-box.transfers:hover {
			background-color: #e74c3c;
		}

		.stat-box i {
			display: block;
			margin-bottom: 15px;
			transition: transform 0.3s ease-in-out;
		}

		.stat-box:hover i {
			transform: scale(1.1);
		}

		.stat-info .stat-count {
			display: block;
			font-size: 28px;
			font-weight: bold;
			line-height: 1;
		}

		.stat-info .stat-label {
			display: block;
			font-size: 14px;
			text-transform: uppercase;
			letter-spacing: 1px;
		}

		.section-header {
			border-bottom: 1px solid #eee;
			padding-bottom: 10px;
			margin-bottom: 20px;
			margin-top: 20px;
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
			<div class="row-fluid">
				<div class="span12">
					<div class="contentheader">
						<i class="icon-dashboard"></i> Dashboard
					</div>
					<ul class="breadcrumb">
						<li class="active">Dashboard</li>
					</ul>

					<div id="main-stats">

						<!-- Student Repeat System Section -->
						<h3 class="section-header">Student Repeat System</h3>
						<div class="row-fluid">
							<div class="span4">
								<a class="stat-box students" href="students.php">
									<i class="icon-group icon-4x"></i>
									<div class="stat-info">
										<span class="stat-count"><?php echo $student_count; ?></span>
										<span class="stat-label">Total Students</span>
									</div>
								</a>
							</div>
							<div class="span4">
								<a class="stat-box at-risk" href="students.php?filter=at_risk">
									<i class="icon-warning-sign icon-4x"></i>
									<div class="stat-info">
										<span class="stat-count"><?php echo $students_with_fails_count; ?></span>
										<span class="stat-label">Students with Fails</span>
									</div>
								</a>
							</div>
							<div class="span4">
								<a class="stat-box add-new" href="addstudent.php">
									<i class="icon-plus-sign icon-4x"></i>
									<div class="stat-info">
										<span class="stat-count" style="font-size: 22px; padding-top: 6px;">Add New</span>
										<span class="stat-label">Student or Record</span>
									</div>
								</a>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span4">
								<a class="stat-box academics" href="manage_academics.php">
									<i class="icon-book icon-4x"></i>
									<div class="stat-info">
										<span class="stat-count" style="font-size: 22px; padding-top: 6px;">Add New</span>
										<span class="stat-label">Manage Academics</span>
									</div>
								</a>
							</div>
						</div>

						<!-- Student Transfer System Section -->
						<h3 class="section-header">Student Transfer System</h3>
						<div class="row-fluid">
							<div class="span4">
								<a class="stat-box transfers" href="manage_transfers.php">
									<i class="icon-exchange icon-4x"></i>
									<div class="stat-info">
										<span class="stat-count"><?php echo $transfers_count; ?></span>
										<span class="stat-label">Manage Transfers</span>
									</div>
								</a>
							</div>
						</div>

					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<?php include('footer.php'); ?>
</body>

</html>