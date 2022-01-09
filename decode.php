<?php
include_once "lib/stegano.php";
$result = true;
$stegano = new Stegano();
if(isset($_POST['submit'])) {
	$imgfile = $_FILES['imgfile'];
	$key = $_POST['key'];
	if(empty($_FILES['imgfile']['tmp_name'])) {
		$err[] = "Where is the image to be decoded?";
	}
	$imgext = strtolower(substr($imgfile['name'],-3));
	if(!empty($_FILES['imgfile']['tmp_name']) && $imgext !="png")
	{
		$err[] = "Only PNG Image is possible";
	}
	if(strlen($key) == 0) {
		$err[] = "Please enter the password of the message.";
	}
	if(!isset($err)) {
	$result = $stegano->recover($imgfile,$key);
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
<title>Stegno-Cryptography :: Decode</title>
<link rel="stylesheet" type="text/css" href="css/style.css" media="screen">
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<div id="header">
<div id="logo">Stegno-Cryptography</div>
	<ul>
		<a href="index.php"><li>Home</li></a>
		<a href="encode.php"><li>Encode</li></a>
		<a href="decode.php"><li class="active">Decode</li></a>
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
		<h1>Decode Message From Image</h1>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="encode_file">
		<table>
		<tr><td width="180">Image to decode message</td><td><input type="file" id="file" name="imgfile"></td></tr>
		<tr><td>Secret Key for decoding </td><td><input type="password" id="key" placeholder="Password" name="key"></td></tr>
		<tr><td></td><td><input type="submit" id="submit" name="submit" value="Decode"></td></tr>
		</table>
		</form>
	</div>
</div>
</body>
</html>
