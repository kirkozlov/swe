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
		$to=$_GET['e'];
		$str1="SELECT ID from users WHERE email='".$to."'";
		include ('includes/ConectionOpen.php');
		$res=$conn->query($str1);
		if($res != FALSE && $row=$res->fetch_row()){
			$sucsess=true;
			$errortext="Ihhre neuue pass ist gesendet";
			include("includes/sendmail.php");
			$str1="UPDATE `users`SET `password`='".$newpass."' WHERE id='".$row[0]."'";
			$res=$conn->query($str1);
			sendmail($to,$newpass);
		}
		else{
			$errortext="email nichht vorhanden";
		}
	}

?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
		
    </head>
    <body >
	
        <?php 
            include("includes/menu.php");
        ?>
		
        <div class="main">
            <div class="content">
                <?php
                    
					echo $errortext;
                    if($sucsess){
						echo '<form action ="login.php" method="post">
								<table style=" margin:0 auto;
									position:relative;
									max-width: 280px;
									width: 95%;">
			
									<tr><td colspan="2"><input id="email"style="max-width: 280px; width: 95%;" type="text" name="email" value="'.$to.'" hidden="true" visibility="collapse "/></td></tr>
									<tr><td>Passwort:</td></tr>
									<tr><td colspan="2"><input style="max-width: 280px; width: 95%;" type="password" name="password"/></td><tr>
									<tr><td><input type="submit" name="activ" value="Einloggen"/></td></tr>
								</table>
							</form>';
					
					
					}
				
                 ?>
				 <br/>
				 <a href="index.php">Haupseite</a>
                <a href="reg.php">Registrieren</a>
            </div>
        </div>
    </body>
</html>

