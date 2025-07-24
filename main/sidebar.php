<?php
// Get the filename of the currently executing script to determine the active page
$current_page = basename($_SERVER['PHP_SELF']);

// Define which pages belong to which group for highlighting
$student_management_pages = [
    'students.php',
    'viewstudent.php',
    'editstudent.php'
];
$course_management_pages = [
    'manage_academics.php',
    'add_course.php',
    'add_degree.php',
    'add_subject.php',
    'edit_repeat_record.php'
];
$transfer_management_pages = [
    'manage_transfers.php'
];
?>

<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="nav-header">Student Repeat System</li>
        <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard</a>
        </li>
        <li class="<?php echo (in_array($current_page, $student_management_pages)) ? 'active' : ''; ?>">
            <a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a>
        </li>
        <li class="<?php echo ($current_page == 'addstudent.php') ? 'active' : ''; ?>">
            <a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a>
        </li>
        <li class="<?php echo (in_array($current_page, $course_management_pages)) ? 'active' : ''; ?>">
            <a href="manage_academics.php"><i class="icon-book icon-2x"></i>Manage Academics</a>
        </li>

        <li class="nav-header">Student Transfer System</li>
        <li class="<?php echo (in_array($current_page, $transfer_management_pages)) ? 'active' : ''; ?>">
            <a href="manage_transfers.php"><i class="icon-exchange icon-2x"></i> Manage Transfers</a>
        </li>
    </ul>
</div>