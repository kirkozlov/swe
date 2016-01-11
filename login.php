<?php 
	session_start();
	if(isset($_SESSION['login']) && $_SESSION['login'] == true){
		if(isset($_SESSION['admin']) && $_SESSION['admin'] == "1"){
			header('Location: useradmin.php');
		}
		else {
			header('Location: index.php'); 
		}
	}
	$e="";
	$p="";
	$stre="";
	if(isset($_POST['activ'])){
		$e=$_POST['email'];
		$p=$_POST['password'];
		
		
		$str1="SELECT ID,adminflag from users WHERE email='".$e."' AND password='".$p."'";
		include ('includes/ConectionOpen.php');
		$res=$conn->query($str1);
		if($res != FALSE && $row=$res->fetch_row())
		{
			$stre="ok";
			$_SESSION['login']=true;
			$_SESSION['idu']=$row[0];
			$_SESSION['admin']=$row[1];
			if(isset($_SESSION['admin']) && $_SESSION['admin'] == "1"){
				header('Location: useradmin.php');
			}
			else {
				header('Location: index.php'); 
			}		
		}
		else
		{
			$stre="E-Mail-Adresse oder Passwort falsch";
		}
		
		
		$conn->close();
	}
		

?>  
<html>
    <head>     
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
		<link rel="stylesheet" href="css/tablelogin.css" type="text/css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="css/materialize_own.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="includes/jquery.js"></script>
		<script src="includes/sha2.js"></script>
		<script>
			$(document).ready(function(){
			    PopUpHide();
			<?php
				if($stre==""){
					
				}
				else
				{
					echo "document.getElementById('ppt').innerHTML='".$stre."';
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
			function sendmypass(){
				var temp =document.getElementById('email').value;
				document.getElementById('passver').setAttribute("href","forgotpassword.php?e="+temp);
			}
			function loginclick(){
				var p1=document.getElementById("p1").value;
				document.getElementById("p1").value=CryptoJS.SHA256(p1);
				
			}
		</script>
    </head>
    <body>  
        <?php 
            include("includes/menu.php");

        ?>  
	<div class="main">	
		<div class="content">
		<form action ="login.php" method="post">
		<table style=" margin:0 auto;
	position:relative;
    max-width: 280px;
    width: 95%;">
			<tr><td>E-Mail:</td><td></td></tr>
			<tr><td colspan="2"><input id="email"style="max-width: 280px; width: 95%;" type="text" name="email" value=<?php echo "'".$e."'" ?>/></td></tr>
			<tr><td>Passwort:</td><td><a id ="passver" href="" onclick="sendmypass()">Passwort vergessen</a></td></tr>
			<tr><td colspan="2"><input style="max-width: 280px; width: 95%;" id="p1" type="password" name="password"/></td><tr>
			<tr><td><button  class="waves-effect waves-light btn"  type="submit" name="activ" onclick="loginclick()" />Einloggen</button></td><td><a href="reg.php">Registrieren</a></td></tr>
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
												height: 80px;
												padding:10px;
												
												background-color: #c5c5c5;
												border-radius:5px;
												box-shadow: 0px 0px 10px #000;">
				<div id="ppt" style="align:center;" ></div>
			
			</div>
	</div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>

