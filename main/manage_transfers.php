<?php
require_once('auth.php');
include('../connect.php');

// Fetch all transfer students for the list
$transfer_students = [];
try {
    $stmt = $db->prepare("SELECT * FROM transfer_students ORDER BY transfer_date DESC");
    $stmt->execute();
    $transfer_students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Function to map status to a Bootstrap class for row highlighting
function get_status_class($status)
{
    switch (strtolower($status)) {
        case 'approved':
            return 'success'; // Green background
        case 'pending':
            return 'warning'; // Yellow background
        case 'rejected':
            return 'error'; // Red background (using 'error' which often maps to red in Bootstrap)
        default:
            return '';
    }
}
?>
<html>

<head>
    <title>Manage Transfer Students</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body {
            padding-top: 60px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
            margin: 20px auto;
            width: 500px;
        }

        .card-body span {
            display: inline-block;
            width: 160px;
            text-align: right;
            margin-right: 10px;
            vertical-align: middle;
        }

        /* Style for clickable rows, similar to students.php */
        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f5f5f5 !important;
            /* Use !important to override other styles */
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
            <div class="contentheader"><i class="icon-exchange"></i> Manage Transfer Students</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li class="active">Manage Transfers</li>
            </ul>

            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#manageTransfers" data-toggle="tab">Manage Transfers</a></li>
                <li><a href="#addTransfer" data-toggle="tab">Add Transfer Student</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="manageTransfers">
                    <table id="transfersTable" class="table table-bordered table-hover" style="background-color: #fff;">
                        <thead style="background-color: #343a40; color: white;">
                            <tr>
                                <th>New Student ID</th>
                                <th>Full Name</th>
                                <th>Previous University</th>
                                <th>Transfer Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transfer_students)) : ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">No transfer students found.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($transfer_students as $student) : ?>
                                    <?php
                                    // Set the class and data-href for the clickable row
                                    $row_class = get_status_class($student['status']);
                                    $view_url = "view_transfer_student.php?id=" . $student['id'];
                                    ?>
                                    <tr class="clickable-row <?php echo $row_class; ?>" data-href="<?php echo $view_url; ?>">
                                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($student['name'] . ' ' . $student['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['previous_university']); ?></td>
                                        <td><?php echo htmlspecialchars($student['transfer_date']); ?></td>
                                        <td><span class="label label-info"><?php echo htmlspecialchars($student['status']); ?></span></td>
                                        <td style="text-align:center;">
                                            <a href="edit_transfer_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-mini"><i class="icon-edit"></i> Edit</a>
                                            <a href="functions/delete_transfer_student.php?id=<?php echo $student['id']; ?>" class="delbutton btn btn-danger btn-mini" onclick="return confirm('Are you sure you want to delete this transfer record?');"><i class="icon-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane" id="addTransfer">
                    <div class="card">
                        <form action="functions/save_transfer_student.php" method="post" enctype="multipart/form-data">
                            <center>
                                <h4><i class="icon-plus-sign icon-large"></i> Add New Transfer Student</h4>
                            </center>
                            <hr>
                            <div class="card-body">
                                <div style="margin-bottom: 10px;"><span>New Student ID: </span><input type="text" style="width:280px; height:40px;" name="student_id" required></div>
                                <div style="margin-bottom: 10px;"><span>First Name: </span><input type="text" style="width:280px; height:40px;" name="name" required></div>
                                <div style="margin-bottom: 10px;"><span>Last Name: </span><input type="text" style="width:280px; height:40px;" name="last_name" required></div>
                                <div style="margin-bottom: 10px;"><span>Gender: </span><select name="gender" style="width:295px; height:40px;">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select></div>
                                <div style="margin-bottom: 10px;"><span>Date of Birth: </span><input type="date" style="width:280px; height:40px;" name="bdate"></div>
                                <div style="margin-bottom: 10px;"><span>Address: </span><input type="text" style="width:280px; height:40px;" name="address"></div>
                                <div style="margin-bottom: 10px;"><span>Contact: </span><input type="text" style="width:280px; height:40px;" name="contact"></div>
                                <div style="margin-bottom: 10px;"><span>Previous University: </span><input type="text" style="width:280px; height:40px;" name="previous_university" required></div>
                                <div style="margin-bottom: 10px;"><span>Previous Major: </span><input type="text" style="width:280px; height:40px;" name="previous_major" required></div>
                                <div style="margin-bottom: 10px;"><span>Transfer Date: </span><input type="date" style="width:280px; height:40px;" name="transfer_date" required></div>
                                <div style="margin-bottom: 10px;"><span>Status: </span>
                                    <select name="status" style="width:295px; height:40px;">
                                        <option>Pending</option>
                                        <option>Approved</option>
                                        <option>Rejected</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 10px;"><span>Student Photo: </span><input type="file" name="photo" style="vertical-align: middle;"></div>

                                <div style="margin-bottom: 10px;">
                                    <span>Transcripts/Documents: </span>
                                    <input type="file" name="documents[]" style="vertical-align: middle;" multiple>
                                </div>

                                <div style="margin-bottom: 10px;"><span>Notes: </span><textarea name="notes" style="width:280px; height:60px; vertical-align: top;"></textarea></div>
                                <br>
                                <div style="text-align:center;"><button class="btn btn-success btn-large" style="width:280px;">Save Transfer Student</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables for search, sort, and pagination
            $('#transfersTable').DataTable();

            // Handle tab switching
            $('#myTab a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Handle clickable rows, similar to students.php
            // Using .on() for dynamically added rows by DataTables
            $('#transfersTable tbody').on('click', 'tr.clickable-row', function(event) {
                // Prevent redirection if a button or link inside the row is clicked
                if ($(event.target).is('a, button, .btn')) {
                    return;
                }
                window.location.href = $(this).data('href');
            });
        });
    </script>

</body>

</html>