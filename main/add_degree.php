<?php
require_once('auth.php');
include('../connect.php');
?>
<html>

<head>
    <title>Add New Degree</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="css/sidebar.css" rel="stylesheet">
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
            width: 450px;
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
            <div class="contentheader"><i class="icon-plus-sign"></i> Add New Degree</div>
            <ul class="breadcrumb">
                <li><a href="index.php">Dashboard</a></li> /
                <li><a href="manage_academics.php">Manage Academics</a></li> /
                <li class="active">Add Degree</li>
            </ul>
            <div class="card">
                <form action="functions/save_degree.php" method="post">
                    <center>
                        <h4><i class="icon-edit icon-large"></i> Degree Details</h4>
                    </center>
                    <hr>
                    <span>Degree Name: </span><input type="text" style="width:265px; height:40px;" name="degree_name" placeholder="e.g., Diploma" required /><br><br>
                    <div style="text-align: center;">
                        <button class="btn btn-success btn-large" style="width:280px;"><i class="icon-save"></i> Save Degree</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>