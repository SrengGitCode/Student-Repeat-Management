<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('auth.php');
include('../connect.php');

$filter_mode = $_GET['filter'] ?? '';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Student Repeat Management System</title>

	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/DT_bootstrap.css" rel="stylesheet">
	<link href="../style.css" rel="stylesheet">
	<link href="src/facebox.css" rel="stylesheet">
	<link href="css/sidebar.css" rel="stylesheet">

	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

	<style>
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}

		.sidebar-nav {
			padding: 9px 0;
		}

		.highlight-row {
			background-color: #f2dede !important;
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
						<i class="icon-group"></i> Manage Students
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php">Dashboard</a></li> /
						<li class="active">Manage Students</li>
					</ul>

					<div style="margin-top: -19px; margin-bottom: 21px;">
						<a href="students.php" class="btn btn-default btn-large"><i class="icon icon-circle-arrow-left icon-large"></i> Back</a>
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

					<div class="" <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">


						<div>
							<a href="addstudent.php" class="btn btn-primary btn-large"><i class="icon icon-plus-sign icon-large"></i> Add Student</a>
							<?php if ($filter_mode === 'at_risk') : ?>
								<a href="students.php" class="btn btn-info btn-large"><i class="icon-list"></i> Show All Students</a>
							<?php else : ?>
								<a href="students.php?filter=at_risk" class="btn btn-warning btn-large"><i class="icon-filter"></i> Show Students with Fails (≥ 1)</a>
							<?php endif; ?>
						</div>
					</div>

					<table id="resultTable" class="table table-bordered table-hover" style="text-align: left;">
						<thead>
							<tr>
								<th>Student ID</th>
								<th>Full Name</th>
								<th>Bachelor Of</th>
								<th style="text-align:center;">Total Subjects</th>
								<th style="text-align:center;">Failed Count</th>
								<th style="text-align:center;">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "
							SELECT 
								s.*, 
								COUNT(r.id) AS total_subjects,
								IFNULL(SUM(CASE WHEN r.passed = 0 THEN 1 ELSE 0 END), 0) AS failed_count
							FROM student s
							LEFT JOIN repeat_records r ON s.id = r.student_id_fk
							GROUP BY s.id
						";

							if ($filter_mode === 'at_risk') {
								$sql .= " HAVING failed_count >= 1";
							}

							$sql .= " ORDER BY s.id DESC";

							$stmt = $db->prepare($sql);
							$stmt->execute();

							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$failed_count = (int)($row['failed_count'] ?? 0);  // Explicitly cast
								$highlight = ($failed_count >= 1) ? 'class="highlight-row"' : '';

							?>
								<tr <?php echo $highlight; ?>>
									<td><?php echo htmlspecialchars($row['student_id']); ?></td>
									<td><?php echo htmlspecialchars($row['name'] . ' ' . $row['last_name']); ?></td>
									<td><?php echo htmlspecialchars($row['course']); ?></td>
									<td style="text-align:center;"><?php echo $row['total_subjects']; ?></td>
									<td style="text-align:center;">
										<span class="label <?php echo $failed_count > 0 ? 'label-important' : 'label-success'; ?>">
											<?php echo $failed_count; ?>
										</span>
									</td>
									<td style="text-align:center;">
										<a href="viewstudent.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-mini"><i class="icon-search"></i> Subject</a>
										<a href="editstudent.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
										<a href="#" class="delbutton btn btn-danger btn-mini" id="<?php echo $row['id']; ?>"><i class="icon-trash"></i> Delete</a>
									</td>
								</tr>
							<?php } ?>

						</tbody>
					</table>

				</div>
			</div>
		</div>

		<?php include('footer.php'); ?>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

		<script>
			$(document).ready(function() {
				var oTable = $('#resultTable').DataTable({
					paging: true,
					ordering: true,
					info: true,
					searching: true,
					createdRow: function(row, data, dataIndex) {
						// Assuming the "Failed Count" is in the 5th column (index 4)
						var failedCount = parseInt($(data[4]).text());

						if (failedCount >= 3) {
							$(row).addClass('highlight-row');
						}
					}
				});

				// Optional: link external search filter
				$('#filter').on('keyup', function() {
					oTable.search(this.value).draw();
				});

				// Delete functionality
				$(".delbutton").click(function() {
					var element = $(this);
					var del_id = element.attr("id");
					if (confirm("Are you sure you want to delete this student and all their records? This cannot be undone.")) {
						$.ajax({
							type: "GET",
							url: "deletestudent.php",
							data: {
								id: del_id
							},
							success: function() {
								element.closest("tr").fadeOut('slow', function() {
									$(this).remove();
								});
							}
						});
					}
					return false;
				});
			});
		</script>
</body>

</html>