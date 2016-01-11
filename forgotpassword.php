<?php
	function passgeneric(){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
		$length=8;
		for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}

		return $result;
	}
	$errortextt="";
	$sucsess=false;
	if(isset($_GET['e'])){
		$newpass=passgeneric();
		$newpasssha2=hash('sha256', $newpass);
		$to=$_GET['e'];
		$str1="SELECT ID from users WHERE email='".$to."'";
		include ('includes/ConectionOpen.php');
		$res=$conn->query($str1);
		if($res != FALSE && $row=$res->fetch_row()){
			$sucsess=true;
			$errortext="Ihnen wurde ein neues Passwort zugesandt!";
			include("includes/sendmail.php");
			$str1="UPDATE `users`SET `password`='".$newpasssha2."' WHERE id='".$row[0]."'";
			$res=$conn->query($str1);
			sendmail($to,$newpass);
		}
		else{
			$errortext="Die E-Mail-Adresse ist nicht vorhanden";
		}
	}

?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="css/materialize_own.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="includes/sha2.js"></script>
		<script>
		function loginclick(){
				var p1=document.getElementById("p1").value;
				document.getElementById("p1").value=CryptoJS.SHA256(p1);
				
			}
		</script>	
    </head>
    <body >
	
        <?php 
            include("includes/menu.php");
        ?>
		
        <div class="main">
            <div class="content">
                <?php
                    
					echo "<table><tr><td>$errortext</td></tr></table>";
                    if($sucsess){
						echo '<form action ="login.php" method="post">
								<table style=" margin:0 auto;
									position:relative;
									max-width: 280px;
									width: 95%;">
			
									<tr><td colspan="2"><input id="email"style="max-width: 280px; width: 95%;" type="text" name="email" value="'.$to.'" hidden="true" visibility="collapse "/></td></tr>
									<tr><td>Passwort:</td></tr>
									<tr><td colspan="2"><input id="p1" style="max-width: 280px; width: 95%;" type="password" name="password"/></td><tr>
									<tr><td><button class="waves-effect waves-light btn" type="submit" name="activ"onclick="loginclick()" value="Einloggen">Einloggen</button></td></tr>
								</table>
							</form>';
					
					
					}
				
                 ?>
				 <br/>
                <table>
                    <tr><td><a href="index.php">Haupseite</a></td></tr>
                    <tr><td><a href="reg.php">Registrieren</a></td></tr>
                </table>
            </div>
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>

