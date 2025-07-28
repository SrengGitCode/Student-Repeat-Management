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
            // Fetch the transfer student's information
            $stmt = $db->prepare("SELECT * FROM transfer_students WHERE id = :id");
            $stmt->bindParam(':id', $student_id);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}

// If no student is found with that ID, handle it gracefully
if (!$student) {
    die("Error: Transfer student not found.");
}
?>
<html>

<head>
    <title>View Transfer Student Details</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/sidebar.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
        }

        .info-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin: 20px auto;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .student-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            margin-bottom: 15px;
        }

        .details-table td {
            padding: 8px;
            border-top: 1px solid #f0f0f0;
        }

        .details-table tr td:first-child {
            font-weight: bold;
            width: 200px;

        }

        .profile-card {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin: 30px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            padding: 25px;
            max-width: 950px;
            /* Adjust width as needed */
        }

        /* Left sidebar for photo and name */
        .profile-sidebar {
            text-align: center;
            padding-right: 25px;
            border-right: 1px solid #eee;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            display: block;
        }

        .profile-sidebar h2 {
            margin-bottom: 5px;
            font-size: 22px;
        }

        .profile-sidebar .label {
            margin-bottom: 20px;
            display: inline-block;
            font-size: 13px;
            padding: 4px 8px;
        }

        /* Table for details on the right */
        .details-table td:first-child {
            font-weight: bold;
            width: 180px;
        }

        /* List for multiple documents */
        .document-list {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .document-list li {
            margin-bottom: 5px;
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
            <div class="contentheader">
                <i class="icon-user"></i> Transfer Student Details
            </div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_transfers.php">Manage Transfers</a></li> /
                <li class="active">View Details</li>
            </ul>
            <div style="clear:both;"></div>

            <div class="profile-card">
                <div class="row-fluid ce">
                    <div class="span4 profile-sidebar">
                        <?php if (!empty($student['photo'])): ?>
                            <img src="./uploads/<?php echo htmlspecialchars($student['photo']); ?>" alt="Student Photo" class="profile-photo">
                        <?php else: ?>
                            <img src="./img/default-logo.png" alt="Default Avatar" class="profile-photo">
                        <?php endif; ?>

                        <h2><?php echo htmlspecialchars($student['name'] . ' ' . $student['last_name']); ?></h2>
                        <span class="label label-success"><?php echo htmlspecialchars($student['status']); ?></span>

                        <?php if (!empty($student['photo'])): ?>
                            <a href="uploads/<?php echo htmlspecialchars($student['photo']); ?>" download target="_blank" class="btn btn-primary btn-block"><i class="icon-download-alt icon-white"></i> Download Photo</a>
                        <?php endif; ?>
                    </div>

                    <div class="span8">
                        <h4><i class="icon-edit"></i> Student Information</h4>
                        <table class="table details-table">
                            <tbody>
                                <tr>
                                    <td>New Student ID:</td>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
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
                                    <td>Contact:</td>
                                    <td><?php echo htmlspecialchars($student['contact']); ?></td>
                                </tr>
                                <tr>
                                    <td>Address:</td>
                                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <h4 style="margin-top: 20px;"><i class="icon-exchange"></i> Transfer Details</h4>
                        <table class="table details-table">
                            <tbody>
                                <tr>
                                    <td>Previous University:</td>
                                    <td><?php echo htmlspecialchars($student['previous_university']); ?></td>
                                </tr>
                                <tr>
                                    <td>Previous Major:</td>
                                    <td><?php echo htmlspecialchars($student['previous_major']); ?></td>
                                </tr>
                                <tr>
                                    <td>Transfer Date:</td>
                                    <td><?php echo htmlspecialchars($student['transfer_date']); ?></td>
                                </tr>
                                <tr>
                                    <td>Transcript/Document:</td>
                                    <td>
                                        <?php
                                        if (!empty($student['document'])) {

                                            $documents = json_decode($student['document'], true);

                                            if (is_array($documents) && !empty($documents)) {
                                                foreach ($documents as $doc_path) {
                                                    echo '<a href="uploads/' . htmlspecialchars($doc_path) . '" target="_blank" class="btn btn-primary btn-mini"  style="margin-right: 5px; margin-bottom: 5px;">';
                                                    echo '<i class="icon-download icon-white"></i> ';
                                                    echo htmlspecialchars(basename($doc_path));
                                                    echo '</a>';
                                                }
                                            } else {
                                                echo '<a href="uploads/' . htmlspecialchars($student['document']) . '" target="_blank" class="btn btn-primary btn-mini"><i class="icon-download"></i> Download Document</a>';
                                            }
                                        } else {

                                            echo '<span class="text-muted">No document uploaded.</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Notes:</td>
                                    <td><?php echo nl2br(htmlspecialchars($student['notes'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>