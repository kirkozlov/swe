<?php 
	session_start();

	if(isset($_SESSION['login'])&& $_SESSION['login']==true)
		header('Location: index.php');
	$e="";
	$p="";
	$pw="";
	$erroremail="";
	$error=0;
	if(isset($_POST['activ'])){
		$e=$_POST['email'];
		$p=$_POST['password'];
		$pw=$_POST['passwordw'];
		if(filter_var($e, FILTER_VALIDATE_EMAIL)||true)//true weg machen für release
		{
			$str1="SELECT * from users WHERE email='".$e."'";
			include ('includes/ConectionOpen.php');
			$res=$conn->query($str1);
			if($res->fetch_row())
			{
				$error=1;
				$erroremail="E-Mail-Adresse wird bereits verwendet";
			}
			else
			{
				$str1="INSERT INTO `users`(`email`,`password`) VALUES('".$e."','".$p."')";
				$res=$conn->query($str1);
				$_SESSION['login']=true;
				$_SESSION['idu']=$conn->insert_id;
				header('Location: index.php');
			}
			
			$conn->close();
		}
		else
		{
			$error=1;
			$erroremail="ungültige E-Mail-Adresse";
		}
	}
		

?>  
<html>
    <head>     
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
		<script src="includes/jquery.js"></script>
		<script src="includes/sha2.js"></script>
		<script>
			$(document).ready(function(){
			    PopUpHide();
			<?php
				if($error==0){
					
				}
				else
				{
					echo "document.getElementById('ppt').innerHTML='".$erroremail."';
							PopUpShow();";
				}
				
				?>
				
			});
		
			function PopUpShow(){
				$("#popup").show();
			}
		
			function PopUpHide(){
				$("#popup").hide();
			}
			function clickbutton(e){
				var p1=document.getElementById("p1").value;
				var p2=document.getElementById("p2").value;
				var e=document.getElementById("em").value;
				if(p1=="" || p2=="" ||e==""){
					document.getElementById("ppt").innerHTML="Bitte alle Pflichtfelder ausfüllen";
					PopUpShow();
					return false;
				}
				if(p1!=p2){
					document.getElementById("ppt").innerHTML="Passwörter sind nicht identisch";
					PopUpShow();
					return false;
				}
				else{
					document.getElementById("p1").value=CryptoJS.SHA256(p1);
					return true;
				}
			}
		</script>
    </head>
    <body>  
        <?php 
            include("includes/menu.php");
        ?>
		<div	class="main">		
			<div class="content">
				<form action ="reg.php" method="post">
				<table style=" margin:0 auto;
									position:relative;
									max-width: 280px;
									width: 95%;">
					<tr><td >E-Mail:</td></tr>
					<tr><td><input id="em" type="text" name="email" value=<?php echo "'".$e."'" ?>/></td></td>
					<tr><td>Passwort:</td></tr>
					<tr><td><input id="p1"type="password" name="password"/></td></td>
					<tr><td>Wiederholung:</td></tr>
					<tr><td><input id="p2"type="password" name="passwordw"/></td>
					<tr><td colspan="2"><input type="submit" name="activ"value="Registrieren" onclick="return clickbutton(this)"/></td></tr>
				</table>
				</form>
			</div>
		</div>
	
		<div id="popup" onclick="PopUpHide()" style=" width:100%;
												height: 2000px;
												background-color: rgba(0,0,0,0.5);
												overflow:hidden;
												position:fixed;
												top:0px;">
			<div id="ppc" style="margin:40px auto 0px auto;
												width:250px;
												height: 40px;
												padding:10px;
												
												background-color: #c5c5c5;
												border-radius:5px;
												box-shadow: 0px 0px 10px #000;">
				<div id="ppt" style="align:center;" ></div>
			
			</div>
		</div>
		
    
	</body>
</html>

