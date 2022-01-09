<?php

class Stegano {

	function hidefile($imgfile, $textfile, $key) {
		$binstream = "";
		$recordstream = "";
		$make_odd = Array();
		$extension = strtolower(substr($imgfile['name'],-3));
		$nameofimage = substr($imgfile['name'], 0, -4);
		if($extension=="jpg")
		{
			$createFunc = "ImageCreateFromJPEG";
		} else {
			return "Only .jpg image files are supported";
		}
		$imgk = move_uploaded_file($imgfile["tmp_name"], 'tmp/'.$imgfile['name']);

		$pic = imagecreatefromjpeg('tmp/'.$imgfile['name']);
		$attributes = getimagesize('tmp/'.$imgfile['name']);
		$outpic = imagecreatefromjpeg('tmp/'.$imgfile['name']);

		if(!$pic || !$outpic || !$attributes)
		{
			return "cannot create images - maybe GDlib not installed?";
		}

		$message = file_get_contents($textfile['tmp_name']);
		$message = $this->encrypt($message,$key);

		do
		{
		$border = chr(rand(32,127)).chr(rand(32,127)).chr(rand(32,127));
		} while(strpos($message,$border)!==false && strpos($textfile['name'],$border)!==false);

		$message = $border.$textfile['name'].$border.$message.$border;

		if(strlen($message)*8 > ($attributes[0]*$attributes[1])*3)
		{
			imagedestroy($outpic);
			imagedestroy($pic);
			return "Cannot fit ".$textfile['name']." in ".$imgfile['name'].".<br />".$textfile['name']." requires mask to contain at least ".(intval((strlen($data)*8)/3)+1)." pixels.<br />Maximum filesize that ".$imgfile['name']." can hide is ".intval((($attributes[0]*$attributes[1])*3)/8)." bytes";
		}

		for($i=0; $i<strlen($message) ; $i++)
		{
			$char = $message[$i];

			$binary = $this->asc2bin($char);
			$binstream .= $binary;
			for($j=0 ; $j<strlen($binary) ; $j++)
			{
				$binpart = $binary[$j];
				if($binpart=="0")
				{
					$oddeven[] = "true";
				} else {
					$oddeven[] = "false";
				}
			}
		}

		$y=0;
		for($x=0,$i=0;$i<(sizeof($oddeven)-2);$x++,$i+=3) {
			$rgb = imagecolorat($pic,$x,$y);
			$rgbval = Array();
			$rgbval[] = ($rgb >> 16) & 0xFF;
			$rgbval[] = ($rgb >> 8) & 0xFF;
			$rgbval[] = ($rgb >> 0) & 0xFF;
			for($j=0 ; $j<3; $j++) {
				if($oddeven[$i+$j] == "true" && $this->is_even($rgbval[$j])) {
					$rgbval[$j]++;
				}
				else if($oddeven[$i+$j] == "false" && !$this->is_even($rgbval[$j])) {
					$rgbval[$j]--;
				}
			}
			$r = $rgbval[0];
			$g = $rgbval[1];
			$b = $rgbval[2];
			$temp_col = imagecolorallocate($outpic,$r, $g, $b);
			imagesetpixel($outpic,$x,$y,$temp_col);
			if($x==($attributes[0]-1))
			{
				$y++;
				$x=-1;
			}
		}

		header("Content-type: image/png");
		header("Content-Disposition: attachment; filename=".$nameofimage.".png");
		imagepng($outpic);
		imagedestroy($outpic);
		imagedestroy($pic);
		unlink('tmp/'.$imgfile['name']);
		exit();
	}

	function hidemsg($imgfile, $filename, $message, $key) {
		$binstream = "";
		$recordstream = "";
		$make_odd = Array();

		$extension = strtolower(substr($imgfile['name'],-3));
		$nameofimage = substr($imgfile['name'], 0, -4);
		if($extension=="jpg")
		{
			$createFunc = "imagecreatefromjpeg";
		} else {
			return "Only .jpg image files are supported";
		}

		$imgk = move_uploaded_file($imgfile["tmp_name"], 'tmp/'.$imgfile['name']);

		$pic = imagecreatefromjpeg('tmp/'.$imgfile['name']);
		$attributes = getimagesize('tmp/'.$imgfile['name']);
		$outpic = imagecreatefromjpeg('tmp/'.$imgfile['name']);

		if(!$pic || !$outpic || !$attributes)
		{
			return "cannot create images - maybe GDlib not installed?";
		}

		$message = $this->encrypt($message,$key);
		$filename = $filename.".txt";

		do
		{
			$border = chr(rand(0,255)).chr(rand(0,255)).chr(rand(0,255));
		} while(strpos($message,$border)!==false && strpos($textfile['name'],$border)!==false);

		$message = $border.$filename.$border.$message.$border;
		if(strlen($message)*8 > ($attributes[0]*$attributes[1])*3)
		{
			// remove images
			imagedestroy($outpic);
			imagedestroy($pic);
			return "Cannot fit ".$hidefile['name']." in ".$maskfile['name'].".<br />".$hidefile['name']." requires mask to contain at least ".(intval((strlen($data)*8)/3)+1)." pixels.<br />Maximum filesize that ".$maskfile['name']." can hide is ".intval((($attributes[0]*$attributes[1])*3)/8)." bytes";
		}
	//	unlink('tmp/'.$imgfile['name']);

		for($i=0; $i<strlen($message) ; $i++)
		{
			$char = $message[$i];

			$binary = $this->asc2bin($char);
			$binstream .= $binary;
			for($j=0 ; $j<strlen($binary) ; $j++)
			{
				$binpart = $binary[$j];
				if($binpart=="0")
				{
					$oddeven[] = "true";
				} else {
					$oddeven[] = "false";
				}
			}
		}

		$y=0;
		for($x=0,$i=0;$i<(sizeof($oddeven)-2);$x++,$i+=3) {
			$rgb = imagecolorat($pic,$x,$y);
			$rgbval = Array();
			$rgbval[] = ($rgb >> 16) & 0xFF;
			$rgbval[] = ($rgb >> 8) & 0xFF;
			$rgbval[] = ($rgb >> 0) & 0xFF;
			for($j=0 ; $j<3; $j++) {
				if($oddeven[$i+$j] == "true" && $this->is_even($rgbval[$j])) {
					$rgbval[$j]++;
				}
				else if($oddeven[$i+$j] == "false" && !$this->is_even($rgbval[$j])) {
					$rgbval[$j]--;
				}
			}
			$r = $rgbval[0];
			$g = $rgbval[1];
			$b = $rgbval[2];
			$temp_col = imagecolorallocate($outpic,$r, $g, $b);
			imagesetpixel($outpic,$x,$y,$temp_col);
			if($x==($attributes[0]-1))
			{
				$y++;
				$x=-1;
			}
		}
		//	die('test');
		header("Content-type: image/png");
		header("Content-Disposition: attachment; filename=".$nameofimage.".png");
		imagepng($outpic);
		imagedestroy($outpic);
		imagedestroy($pic);
		unlink('tmp/'.$imgfile['name']);
		exit();
	}

	function recover($maskfile, $key)
	{
		$binstream = "";
		$filename = "";
		$boundary = "";
		$ascii = "";
		$imgk = move_uploaded_file($maskfile["tmp_name"], 'tmp/'.$maskfile['name']);
		$attributes = getimagesize('tmp/'.$maskfile['name']);
		$pic = imagecreatefrompng('tmp/'.$maskfile['name']);
		if(!$pic || !$attributes)
		{
			return "could not read image";
		}
		$bin_boundary = "";
		for($x=0 ; $x<8 ; $x++)
		{
			$bin_boundary .= $this->rgb2bin(imagecolorat($pic, $x,0));
		}
		for($i=0 ; $i<strlen($bin_boundary) ; $i+=8)
		{
			$binchunk = substr($bin_boundary,$i,8);
			$boundary .= $this->bin2asc($binchunk);
		}

		$start_x = 8;

		for($y=0 ; $y<$attributes[1] ; $y++)
		{
			for($x=$start_x ; $x<$attributes[0] ; $x++)
			{
				$binstream .= $this->rgb2bin(imagecolorat($pic, $x,$y));
				if(strlen($binstream)>=8)
				{
					$binchar = substr($binstream,0,8);
					$ascii .= $this->bin2asc($binchar);
					$binstream = substr($binstream,8);
				}
				if(strpos($ascii,$boundary)!==false)
				{
					$ascii = substr($ascii,0,strlen($ascii)-3);

					if(empty($filename))
					{
						$filename = $ascii;
						$ascii = "";
					} else {
						break 2;
					}
				}
			}
			$start_x = 0;
		}

		imagedestroy($pic);
		$ascii = $this->decrypt($ascii,$key);
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=".$filename);
		echo $ascii;
		unlink('tmp/'.$maskfile['name']);
		exit();
	}

	private function is_even($num)
	{
		return ($num%2==0);
	}

	private function asc2bin($char)
	{
		return str_pad(decbin(ord($char)), 8, "0", STR_PAD_LEFT);
	}


	private function bin2asc($bin)
	{
		return chr(bindec($bin));
	}

	private function rgb2bin($rgb) {
		$binstream = "";
		$red = ($rgb >> 16) & 0xFF;
		$green = ($rgb >> 8) & 0xFF;
		$blue = $rgb & 0xFF;

		if($this->is_even($red))
		{
			$binstream .= "1";
		} else {
			$binstream .= "0";
		}
		if($this->is_even($green))
		{
			$binstream .= "1";
		} else {
			$binstream .= "0";
		}
		if($this->is_even($blue))
		{
			$binstream .= "1";
		} else {
			$binstream .= "0";
		}
		return $binstream;
	}

	private function encrypt($input,$ky)
	{
		$encryptionMethod = "AES-256-CBC";
		$encryptedMessage = openssl_encrypt($input, $encryptionMethod, $ky,0, '0A12X78PUi58P98T');
		return $encryptedMessage;
	}

	private function decrypt($crypt,$ky)
	{
		$encryptionMethod = "AES-256-CBC";
		$decryptedMessage = openssl_decrypt($crypt, $encryptionMethod, $ky, 0, '0A12X78PUi58P98T');
		return $decryptedMessage;
	}

}

?>
