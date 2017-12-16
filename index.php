<?php

// Create new MySQLi Database Connection
$db = new mysqli("localhost","root","","linkie") or die("Error: Unable to connect to MySQL Database.");
// die messages are cool.

// Thanks for this function, StackOverflow
function generateRandomString($length = 10) {
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

if(isset($_GET['title'])) {
	// SELECT link FROM database
	$result = "";
	$result = $db->prepare("SELECT * FROM links WHERE title=?");
	$result->bind_param("s",$_GET['title']);
	$result->execute();

	$goto = $result->get_result()->fetch_array();
	$g = $goto['1'];
	header('Location: '.$g);
}

// OnClick of button
if(isset($_POST['shorten'])) {

	// Generate Title
	$title = generateRandomString();

	// Insert http://
	if(substr($_POST['url_to_shorten'],0,7) != "http://") {
		// Prepend http://
		$url = "http://".$_POST['url_to_shorten'];
	} else {
		$url = $_POST['url_to_shorten'];
	}

	// Insert link into database using magic
	$result = "";
	$result = $db->prepare("INSERT INTO links VALUES('',?,?)");
	$result->bind_param("ss",$url, $title);
	$result->execute();
	echo "<center>Your link has been shortened to: <br /> yourdomain.com/".$title."</center>";

}

?>
<!DOCTYPE html>
<html>
<head>
	<title>OwlURL - Open Source Link Shortener</title>

	<!-- ADD BOOTSTRAP FRAMEWORK -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">


</head>
<body>
	<!-- Centers the heading and form -->
	<center>
		<div class="panel panel-default" style="width: 600px; margin-top: 15%;">
			<div class="panel-heading">
				<h3 class="panel-title">Shorten a URL</h3>
			</div>
			<div class="panel-body">
				<form action="#" method="POST">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">http://</span>
						<input type="text" class="form-control" name="url_to_shorten" value="" placeholder="Paste the link to shorten.">
					</div>
					<br />
					<input type="submit" class="btn btn-default btn-primary" name="shorten" value="Shorten Link">
				</form>
			</div>
		</div>
		<!-- Generic Heading -->
		<!-- <h1>Shorten Your Links</h1> -->

		<!-- The slash means to submit the form to it's own file. The PHP in this file will handle the shortening. -->
<!-- <form action="/" method="POST">
	<input type="text" name="url_to_shorten" value="" placeholder="Paste the link to shorten.">
	<input type="submit" name="shorten" value="Shorten Link">
</form> -->

</center>
</body>
</html>
