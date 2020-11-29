
<?php
	function e_url( $s ) {
		return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); 
	}
	 
	function d_url($s) {
		return base64_decode(str_pad(strtr($s, '-_', '+/'), strlen($s) % 4, '=', STR_PAD_RIGHT));
	}

	if(isset($_GET['key']) && $_GET['key']=='aW5peXVwcHk'){
		///echo e_url("iniyuppy");
		//$api = "";
		$host = 'localhost';
		$user = 'axelposc_kopaja';
		$pass = '7O~$jcg3#h,I';
		$db = 'axelposc_kopasus';
/*
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$db = 'lapak_original';
*/
		$con=mysqli_connect($host, $user, $pass, $db);
		// Check connection
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$link =  '<a href="secret.php?key=aW5peXVwcHk&download">Download</a><br>';
		$sql = "SELECT notelp FROM pelanggan";
		$q = mysqli_query($con, $sql);
		$no = '';
		while($row = mysqli_fetch_array($q)){
			$notelp = $row['notelp'];
			$notelp = substr($notelp, 0,1)==0?"62".substr($notelp, 1,strlen($notelp)):$notelp; 
		 	$no .= $notelp.'<br>';
		} 
		if(isset($_GET['download'])){
			header ('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=notelp.txt');
			echo str_replace("<br>", "\n", $no);
		}else{
			echo $link;
			echo $no;
		}
	}else{
		header("location:login");
	}

	//aW5peXVwcHk
?>