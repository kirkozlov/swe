<?php
	function passgeneric(){
		return 2536;
	}
	$errortextt="";
	if(isset($_GET['e'])){
		$newpass=passgeneric();
		$to=$_GET['e'];
		$str1="SELECT ID from users WHERE email='".$to."'";
		include ('includes/ConectionOpen.php');
		$res=$conn->query($str1);
		if($res != FALSE && $row=$res->fetch_row()){
			
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
                    
                   
                 ?>
            </div>
        </div>
    </body>
</html>

