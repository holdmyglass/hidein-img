<?php
include_once "lib/stegano.php";
$result = true;

$stegano = new Stegano();
if(isset($_POST['submit'])) {
	$imgfile = $_FILES['imgfile'];
	$textfile = $_FILES['textfile'];
	$key = $_POST['key'];
	if(empty($_FILES['imgfile']['tmp_name'])) {
		$err[] = "You must select an image.";
	}
	$imgext = strtolower(substr($imgfile['name'],-3));
	if(!empty($_FILES['imgfile']['tmp_name']) && $imgext !="jpg")
	{
		$err[] = "Only JPG Image Please";
	}
	if(empty($_FILES['textfile']['tmp_name'])) {
		$err[] = "You must select a text file to hide.";
	}
	$txtext = strtolower(substr($textfile['name'],-3));
	if(!empty($_FILES['textfile']['tmp_name']) && $txtext !="txt")
	{
		$err[] = "Only text (.txt) File can be hidden.";
	}
	if(strlen($key) == 0) {
		$err[] = "Please enter a password.";
	}
	if(!isset($err)) {
	$result = $stegano->hidefile($imgfile,$textfile,$key);
	}
}
if(isset($_POST['submit1'])) {
	$imgfile = $_FILES['imgfile'];
	$filename = $_POST['filename'];
	$key = $_POST['key'];
	$message = $_POST['message'];
	$imgext = strtolower(substr($imgfile['name'],-3));
	if(empty($_FILES['imgfile']['tmp_name'])) {
		$err[] = "You must select an image.";
	}
	$imgext = strtolower(substr($imgfile['name'],-3));
	if(!empty($_FILES['imgfile']['tmp_name']) && $imgext !="jpg")
	{
		$err[] = "Only JPG Image Please";
	}
	if(strlen($key) == 0) {
		$err[] = "Please enter a password.";
	}
	if(strlen($filename) == 0) {
		$err[] = "What filename would you like to use?";
	}
	if(strlen($message) == 0) {
		$err[] = "Where is the message?";
	}
	if(!isset($err)) {
	$result = $stegano->hidemsg($imgfile,$filename,$message,$key);
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Sushanta Pyakurel">
<title>Stegno-Cryptography :: Encode</title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="header">
<div id="logo">Stegno-Cryptography</div>
	<ul>
		<a href="index.php"><li>Home</li></a>
		<a href="encode.php"><li class="active">Encode</li></a>
		<a href="decode.php"><li>Decode</li></a>
	</ul>
</div>
<div id="wrapper">
<?php
if(isset($err)) {
	echo "<div id='error'>";
	echo "<h3>Alert! There were following errors</h3>";
	for($i=0;$i<sizeof($err);$i++) {
			echo "<li>".$err[$i]."<br></li>";
}
echo "</div>";
}
?>
<div id="encode_form">
		<h1>Encode Message From Text File</h1>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="encode_file">
		<table>
		<tr><td width="150">Image to Hide File</td><td><input type="file" id="file" name="imgfile"><span id="info">* Only JPG image can be used (.jpg)</span></td></tr>
		<tr><td>Text file to Hide</td><td><input type="file" id="file" name="textfile"><span id="info">* Only text file (.txt)</span></td></tr>
		<tr><td>Secret Key </td><td><input type="password" id="key" placeholder="Password" name="key"></td></tr>
		<tr><td></td><td><input type="submit" id="submit" name="submit" value="Encode"></td></tr>
		</table>
		</form>
</div>
<div id="encode_form">		
		<h1>Encode Message From Input Field</h1>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="encode_file">
		<table>
		<tr><td width="250">Image to Hide Message</td><td><input type="file" id="file" name="imgfile"><span id="info">* Only JPG image can be used (.jpg)</span></td></tr>
		<tr><td>Secret Key </td><td><input type="password" id="key" placeholder="Password" name="key"></td></tr>
		<tr><td>Filename to save the Message</td><td><input id="key" placeholder="Filename" type="text" id="file" name="filename"></td></tr>
		<tr><td>Message </td><td><textarea id="inputarea" name="message" Placeholder="Enter your message here"></textarea></td></tr>
		<tr><td></td><td><input type="submit" id="submit" name="submit1" value="Encode"></td></tr>
		</table>
		</form>
</div>
</div>
</body>
</html>
