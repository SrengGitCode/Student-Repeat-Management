<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('auth.php');
include('../connect.php');
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

		/* Style for highlighting rows */
		.table-hover tbody tr.highlight-row:hover,
		.table-hover tbody tr.highlight-row {
			background-color: #f2dede;
			/* Light red background for emphasis */
		}
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="lib/jquery.js" type="text/javascript"></script>
	<script src="src/facebox.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('a[rel*=facebox]').facebox({
				loadingImage: 'src/loading.gif',
				closeImage: 'src/closelabel.png'
			})
		})
	</script>
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
					<i class="icon-group"></i> Manage Students
				</div>
				<ul class="breadcrumb">
					<li><a href="index.php">Dashboard</a></li> /
					<li class="active">Manage Students</li>
				</ul>

				<div style="margin-top: -19px; margin-bottom: 21px;">
					<a href="students.php"><button class="btn btn-default btn-large" style="float: left;"><i class="icon icon-circle-arrow-left icon-large"></i> Back</button></a>
					<?php
					$result = $db->prepare("SELECT count(*) as total FROM student");
					$result->execute();
					$row = $result->fetch();
					$total_students = $row['total'];
					?>
					<div style="text-align:center;">
						Total Number of Students: <font color="green" style="font:bold 22px 'Aleo';">[<?php echo $total_students; ?>]</font>
					</div>
				</div>

				<input type="text" style="height:35px; color:#222;" name="filter" value="" id="filter" placeholder="Search Student..." autocomplete="off" />
				<a href="addstudent.php"><button class="btn btn-primary btn-large" style="float: right;"><i class="icon icon-plus-sign icon-large"></i> Add Student</button></a>

				<table class="table table-bordered table-striped table-hover" id="resultTable" data-responsive="table" style="text-align: left;">
					<thead>
						<tr>
							<th width="15%"> Student ID</th>
							<th width="20%"> Full Name </th>
							<th width="15%"> Bachelor Of </th>
							<th width="15%"> Contact </th>
							<th width="10%" style="text-align:center;"> Repeat Count </th>
							<th width="20%" style="text-align:center;"> Actions </th>
						</tr>
					</thead>
					<tbody>
						<?php
						// The SQL query now joins the tables to get the count of failed subjects for each student.
						// It only counts records where 'passed' is 0.
						$stmt = $db->prepare("
                            SELECT 
                                s.*, 
                                COUNT(r.id) as repeat_count 
                            FROM 
                                student s 
                            LEFT JOIN 
                                repeat_records r ON s.id = r.student_id_fk AND r.passed = 0 
                            GROUP BY 
                                s.id 
                            ORDER BY 
                                s.id DESC
                        ");
						$stmt->execute();
						for ($i = 0; $row = $stmt->fetch(); $i++) {
							// Check if the repeat count is greater than 3 to apply the highlight class
							$highlight_class = $row['repeat_count'] > 3 ? 'class="highlight-row"' : '';
						?>
							<tr <?php echo $highlight_class; ?>>
								<td><?php echo htmlspecialchars($row['student_id']); ?></td>
								<td><?php echo htmlspecialchars($row['name'] . ' ' . $row['last_name']); ?></td>
								<td><?php echo htmlspecialchars($row['course']); ?></td>
								<td><?php echo "0";
									echo htmlspecialchars($row['contact']); ?></td>
								<td style="text-align:center;"><span class="label <?php echo $row['repeat_count'] > 0 ? 'label-warning' : 'label-success'; ?>"><?php echo $row['repeat_count']; ?></span></td>
								<td style="text-align:center;">
									<a title="View Student and Repeat Records" href="viewstudent.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-mini"><i class="icon-search"></i> View</a>
									<a title="Edit Student Information" href="editstudent.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
									<a href="#" id="<?php echo $row['id']; ?>" class="delbutton btn btn-danger btn-mini" title="Delete Student"><i class="icon-trash"></i> Delete</a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div><!--/span-->
		</div><!--/row-->
	</div><!--/.fluid-container-->

	<?php include('footer.php'); ?>

	<script src="js/jquery.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
	<script type="text/javascript">
		$(function() {
			// Initialize DataTable for sorting and filtering
			$("#resultTable").dataTable();

			$(".delbutton").click(function() {
				var element = $(this);
				var del_id = element.attr("id");
				var info = 'id=' + del_id;
				if (confirm("Are you sure you want to delete this student and all their records? This cannot be undone.")) {
					$.ajax({
						type: "GET",
						url: "deletestudent.php",
						data: info,
						success: function() {
							// Optional: Refresh page or provide feedback
						}
					});
					$(this).closest("tr").fadeOut('slow', function() {
						$(this).remove();
					});
				}
				return false;
			});
		});
	</script>
</body>

</html>