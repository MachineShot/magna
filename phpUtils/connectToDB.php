<?php
	$server = "localhost";
	$user = "root";
	$password = "";
	$db = "isp";

	$dbc = mysqli_connect($server, $user, $password, $db);

	if(!$dbc) {
		die ("Nepavyko prisijungti prie MySQL:".mysqli_error($dbc));
	}

    # For correct date generation
	date_default_timezone_set("Europe/Vilnius");

    # For lithuanian letters
	$sql = ("SET CHARACTER SET utf8");
	$dbc->query($sql);

	// function for sending queries to the database
	function db_send_query($sql) {
		global $dbc;
		$result = mysqli_query($dbc, $sql);
		if(!$result) {
			die ('Nepavyko įvykdyti SQL užklausos:'.mysqli_error($dbc));
		}
		return $result;
	}
?>
