<?php
// Get the filename of the currently executing script
$current_page = basename($_SERVER['PHP_SELF']);

// Define which pages belong to the "Manage Students" group for highlighting
$student_management_pages = [
    'students.php',
    'viewstudent.php',
    'editstudent.php',
    'edit_repeat_record.php'
];
?>

<div class="well sidebar-nav">
    <ul class="nav nav-list">
        <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard</a>
        </li>
        <li class="<?php echo (in_array($current_page, $student_management_pages)) ? 'active' : ''; ?>">
            <a href="students.php"><i class="icon-group icon-2x"></i>Manage Students</a>
        </li>
        <li class="<?php echo ($current_page == 'addstudent.php') ? 'active' : ''; ?>">
            <a href="addstudent.php"><i class="icon-user-md icon-2x"></i>Add Student & Repeats</a>
        </li>
    </ul>
</div>