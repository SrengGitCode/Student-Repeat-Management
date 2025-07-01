<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('auth.php');
include('../connect.php');

// Initialize counts
$student_count = 0;
$repeat_records_count = 0;

try {
	if (isset($db)) {
		// Query to count total students
		$result_students = $db->prepare("SELECT count(*) FROM student");
		$result_students->execute();
		$student_count = $result_students->fetchColumn();

		// Query to count total repeat records
		$result_repeats = $db->prepare("SELECT count(*) FROM repeat_records");
		$result_repeats->execute();
		$repeat_records_count = $result_repeats->fetchColumn();
	}
} catch (PDOException $e) {
	die("Database Error: " . $e->getMessage());
}
?>

<html>

<head>
	<title>Student Repeat Management System</title>
	<?php // include('header.php'); // Assuming you have a header file with CSS links 
	?>
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
			background-color: #51A351;
			/* Greenish hover color */
			color: #fff;
			transform: translateY(-5px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
						<li class="active"><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard </a></li>
						<li><a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a> </li>
						<li><a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a></li>
					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<div class="span10">
				<div class="contentheader">
					<i class="icon-dashboard"></i> Dashboard
				</div>
				<ul class="breadcrumb">
					<li class="active">Dashboard</li>
				</ul>

				<div id="main-stats">
					<div class="row-fluid">
						<div class="span4">
							<a class="stat-box" href="addstudent.php">
								<i class="icon-plus-sign icon-4x"></i>
								<div class="stat-info">
									<span class="stat-count" style="font-size: 22px; padding-top: 6px;">Add New</span>
									<span class="stat-label">Student or Record</span>
								</div>
							</a>
						</div>
						<div class="span4">
							<a class="stat-box" href="students.php">
								<i class="icon-group icon-4x"></i>
								<div class="stat-info">
									<span class="stat-count"><?php echo $student_count; ?></span>
									<span class="stat-label">Total Students</span>
								</div>
							</a>
						</div>
						<div class="span4">
							<a class="stat-box" href="#">
								<i class="icon-list-alt icon-4x"></i>
								<div class="stat-info">
									<span class="stat-count"><?php echo $repeat_records_count; ?></span>
									<span class="stat-label">Repeat Records</span>
								</div>
							</a>
						</div>

					</div>
				</div>

				<div class="clearfix"></div>
			</div><!--/span-->
		</div><!--/row-->
	</div><!--/.fluid-container-->

	<?php include('footer.php'); ?>

	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>