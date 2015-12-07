<?php 
	session_start();
	if(isset($_SESSION['login']) && $_SESSION['login'] == true)
		header('Location: index.php');
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
			header('Location: index.php');		
		}
		else
		{
			$stre="email oder pass falsh";
		}
		
		
		$conn->close();
	}
		

?>  
<html>
    <head>     
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
    </head>
    <body>  
        <?php 
            include("includes/menu.php");

        ?>  
	<div class="main">	
		<div class="content">
		<form action ="login.php" method="post">
		<table>
			<tr><td>E-Mail:</td><td><input type="text" name="email" value=<?php echo "'".$e."'" ?>/></td></tr>
			<tr><td>Passwort:</td><td><input type="password" name="password"value=<?php echo "'".$p."'" ?>/></td></tr>
			<tr><td colspan="2"><?php echo $stre ?> </td><tr>
			<tr><td colspan="2"><input type="submit" name="activ" value="Einloggen"/></td></tr>
			<tr><td colspan="2"><a href="reg.php">Registrieren</a></td></tr>
		</table>
		</form>
		</div>
    </div>
		

    </body>
</html>

