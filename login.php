<?php
// Start session
session_start();

// Array to store validation errors
$errmsg_arr = array();

// Validation error flag
$errflag = false;

// Connect to MySQL server using MySQLi
$link = mysqli_connect('localhost', 'root', '', 'model');

if (!$link) {
	die('Failed to connect to server: ' . mysqli_connect_error());
}

// Function to sanitize values received from the form to prevent SQL injection
function clean($str, $link)
{
	$str = trim($str);
	return mysqli_real_escape_string($link, $str);
}

// Sanitize the POST values
$login = isset($_POST['username']) ? clean($_POST['username'], $link) : '';
$password = isset($_POST['password']) ? clean($_POST['password'], $link) : '';

// Input Validations
if ($login == '') {
	$errmsg_arr[] = 'Username missing';
	$errflag = true;
}
if ($password == '') {
	$errmsg_arr[] = 'Password missing';
	$errflag = true;
}

// If there are input validations, redirect back to the login form
if ($errflag) {
	$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close();
	header("location: index.php");
	exit();
}

// Create query (NOTE: You should hash passwords in a real app)
$qry = "SELECT * FROM user WHERE username='$login' AND password='$password'";
$result = mysqli_query($link, $qry);

// Check whether the query was successful
if ($result) {
	if (mysqli_num_rows($result) > 0) {
		// Login Successful
		session_regenerate_id(true);
		$member = mysqli_fetch_assoc($result);
		$_SESSION['SESS_MEMBER_ID'] = $member['id'];
		$_SESSION['SESS_FIRST_NAME'] = $member['name'];
		$_SESSION['SESS_LAST_NAME'] = $member['username'];
		session_write_close();
		header("location: main/index.php");
		exit();
	} else {
		// Login failed
		$_SESSION['ERRMSG_ARR'] = array('Invalid username or password');
		header("location: index.php");
		exit();
	}
} else {
	die("Query failed: " . mysqli_error($link));
}
