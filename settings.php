<?php session_start();
	if($_SESSION['login']!=true)
		header('Location: login.php');
	
	$km=$_COOKIE['km'];
	if($km=="") $km=50;
	$o="";
	$p="";
	$pw="";
	$stree="";
	$strep="";
	$error=0; 
	$streo = "";
	include ('includes/ConectionOpen.php');
	if(isset($_POST['activ'])){
		$o=$_POST['oldpass'];
		$p=$_POST['password'];
		$pw=$_POST['passwordw'];
		$idu=$_SESSION['idu'];
		$str1="SELECT * from users WHERE id='".$idu."' AND password='".$o."'";
		
		$res=$conn->query($str1);
		if($res->fetch_row())
		{
			
		}
		else
		{
			$streo="alte pass falsh";
			$error=1;
		}
		if($p!=$pw )
		{
			$error=1;
			$strep="wiederholung ist falsh";
			
			
			
		}
		if($error==0){
			$str1="UPDATE `users`SET `password`='".$p."' WHERE id='".$idu."'";
			$res=$conn->query($str1);
		}
		
		
	}
		
	$str1="SELECT * from tags";
	$res=$conn->query($str1);
	
	while($row=$res->fetch_row()){
		$filtr[$row[0]]=$row[1];
	}
	$liststyle="none";
	if(isset($_POST['add'])){
		$liststyle="block";
		$kf=$_POST['kf'];
		$vf=$_POST['vf'];
		$_SESSION['filter'][$kf]=$vf;
	}
	if(isset($_POST['del'])){
		$kf=$_POST['kf'];
		$vf=$_POST['vf'];
		unset($_SESSION['filter'][$kf]);
	}
	$conn->close();
		
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
		<script type="text/javascript">
			
			function vach(){
				var v=document.getElementById("elem").value;
				document.getElementById("te").innerHTML = v;
				document.cookie="km=" + v;
			}
			function filadd(){
				var list = document.getElementById("newfiladd");
                if(list.style.display == "none"){
                    list.style.display = "block";
					
                }
                else{
                    list.style.display = "none";
					
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
		<table>
			<tr><td>Umkreis:</td><td><input id="elem" type="range" min="0" max="100" step="5" value=<?php echo $km ?> onSlide="vach()" onChange="vach()" /><div id="te" ><?php echo $km ?></div></td></tr>
        
	</div>
	</div>
	<br/>
	<div	class="main">		
	<div class="content">
		<form action ="settings.php" method="post">
		<table>
			<tr><td>Altes Passwort:</td><td><input type="password" name="oldpass" value=<?php echo "'".$o."'" ?>/></td></tr>
			<tr><td colspan="2"><?php echo $streo ?> </td><tr>
			<tr><td>Neues Passwort:</td><td><input type="password" name="password"value=<?php echo "'".$p."'" ?>/></td></tr>
			<tr><td>Wiederholung:</td><td><input type="password" name="passwordw"value=<?php echo "'".$pw."'" ?>/></td></tr>
			<tr><td colspan="2"><?php echo $strep ?> </td><tr>
			<tr><td colspan="2"><input type="submit" name="activ"value="Speichern"/></td></tr>
		</table>
		</form>
    </div>
	</div>
	<br/>
	<div	class="main">		
	<div class="content">
		
		<button id="openlist"onclick="filadd()">Kategorie hinzufügen</button>
		<?php
		if(isset($_SESSION['filter'])){
			foreach ($_SESSION['filter'] as $key => $value)
			{		 
				{
					echo '<form action ="settings.php" method="post">
						<input type="text" hidden="true" visibility="collapse " name="kf" value="'.$key.'">
						<input type="text" hidden="true" visibility="collapse " name="vf" value="'.$value.'">
						<input type="submit" name="del"value="'.$value.' -"/>
					</form>';
				}
			}
		}
		?>
    </div>
	</div>
	<div id="newfiladd" class="listforfilteradd" <?php echo 'style="display: '.$liststyle.';"' ?> >
		<?php
		if (isset($filtr)) {
			foreach ($filtr as $key => $value)
			{
				if(!isset($_SESSION['filter'][$key]))
				{
				echo '<form action ="settings.php" method="post">
						<input type="text" hidden="true" visibility="collapse" name="kf" value="'.$key.'">
						<input type="text" hidden="true" visibility="collapse" name="vf" value="'.$value.'">
						<input type="submit" name="add"value="'.$value.' +"/>
					</form>';
				}
			}
		}
		?>
	</div>
    </body>
</html>
