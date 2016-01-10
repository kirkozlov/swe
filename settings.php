<?php session_start();
	if($_SESSION['login']!=true)
		header('Location: login.php');
	if(!isset($_COOKIE['km'])){
		$_COOKIE['km']=50;
	}
	$km=$_COOKIE['km'];
	$o="";
	$p="";
	$pw="";
	
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
			$streo="Das eingegebene alte Passwort ist nicht korrekt!;";
			$error=1;
		}
		if($p!=$pw )
		{
			$error=1;
			$streo=$streo."Das wiederholte Passwort ist nicht das gleiche wie das neue Passwort!";
		}
		if($error==0){
			$str1="UPDATE `users`SET `password`='".$p."' WHERE id='".$idu."'";
			$res=$conn->query($str1);
			$o="";
			$p="";
			$pw="";
			$streo="Passwort wurde geändert";
		}
		
		
	}
		
	$str1="SELECT * from tags";
	$res=$conn->query($str1);
	
	while($row=$res->fetch_row()){
		$filtr[$row[0]]=$row[1];
	}
	
	$conn->close();
		
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        <link href="css/materialize_own.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="includes/jquery.js"></script>
		<script src="includes/sha2.js"></script>
		<script type="text/javascript">
			//getLocation();
			$(document).ready(function(){
			    updateinout();
				PopUpHide();
				<?php
				if($streo==""){
					
				}
				else
				{
					echo "document.getElementById('ppt').innerHTML='".$streo."';
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
				var p0=document.getElementById("p0").value;
				var p1=document.getElementById("p").value;
				var p2=document.getElementById("pw").value;
				if(p1=="" || p2==""|| p0=="" ){
					document.getElementById("ppt").innerHTML="Bitte alle felder ausfüllen";
					PopUpShow();
					return false;
				}
				if(p1!=p2){
					document.getElementById("ppt").innerHTML="Passwörter sind nicht identisch";
					PopUpShow();
					return false;
				}
				else{
					document.getElementById("p0").value=CryptoJS.SHA256(p0);
					document.getElementById("p").value=CryptoJS.SHA256(p1);
					document.getElementById("pw").value=CryptoJS.SHA256(p2);
					return true;
				}
			}
			$( window ).resize(function() {
				var d = document.getElementById('newfiladd');
				d.style.position = "absolute";
				d.style.top=$("#openlist").offset().top;
				d.style.left=$("#openlist").offset().left+$("#openlist").width()+10; 
			});
			var flag=0;
			$(document).click(function(){
				if (flag==0){
			    var list = document.getElementById("newfiladd");
				list.style.display = "none";
				}
				else{
					flag=0;
				}
			});
			function vach(){
				var v=document.getElementById("elem").value;
				document.getElementById("te").innerHTML = v;
				document.cookie="km=" + v;
			}
			function filadd(){//button
				var list = document.getElementById("newfiladd");
				var but=document.getElementById("openlist");
				
                if(list.style.display == "none"){
                    list.style.display = "block";
					
                }
                else{
                    list.style.display = "none";
					
                }
				var d = document.getElementById('newfiladd');
				d.style.position = "absolute";
				d.style.top=$("#openlist").offset().top;
				d.style.left=$("#openlist").offset().left+$("#openlist").width()+10; 
				flag=1;
				
			}
			function updateinout(){
				var fil=getCookie("filter");
				if (fil == undefined){
						fil=0;
					}
					for(i=1;i<=31;i++){
						var liin = document.getElementById("liin"+i);
						var liout = document.getElementById("liout"+i);
						if(liin==undefined)break;
						var com=fil&Math.pow(2,i-1);
						if(com){
							$("#liin"+i).hide();
							$("#liout"+i).show();
						}
						else{
							$("#liin"+i).show();
							$("#liout"+i).hide();
						}
					}
			}
			function addfilter(key){
				var fil=getCookie("filter");
				if (fil == undefined){
					fil=0;
				}
				fil=fil|Math.pow(2,key-1);
				var options={};
				options.expires=24;//stunden
				setCookie("filter", fil,options);
				
				updateinout();
			}
			function deletefilter(key){
				var fil=getCookie("filter");
				if (fil == undefined){
					fil=0;
				}
				fil=fil&(Math.pow(2,32)-1-Math.pow(2,key-1));
				var options={};
				options.expires=24;//stunden
				setCookie("filter", fil,options);
				
				updateinout();
			}
			function deleteCookie(name) {
				setCookie(name, "", {expires: -1});
			}
			function getCookie(name) {
				var matches = document.cookie.match(new RegExp(
					"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
					));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			}
			function setCookie(name, value, options) {
			  options = options || {};

			  var expires = options.expires;

			  if (typeof expires == "number" && expires) {
				var d = new Date();
				d.setTime(d.getTime() + expires * 3600000);
				expires = options.expires = d;
			  }
			  if (expires && expires.toUTCString) {
				options.expires = expires.toUTCString();
			  }

			  value = encodeURIComponent(value);

			  var updatedCookie = name + "=" + value;

			  for (var propName in options) {
				updatedCookie += "; " + propName;
				var propValue = options[propName];
				if (propValue !== true) {
				  updatedCookie += "=" + propValue;
				}
			  }

			  document.cookie = updatedCookie;
}
		</script>
    </head>
    <body>
		<?php 
            include("includes/menu.php");
        ?>  
	<div	class="main">		
	<div class="content">
		<table style=" margin:0 auto;
									position:relative;
									max-width: 280px;
									width: 95%;">           
			
            <tr><td colspan="2">
            <form action="#">
                <p class="range-field">
                    <input id="elem" type="range" min="0" max="100" step="5" value=<?php echo $km ?> onSlide="vach()" onChange="vach()" /></td></tr>
                </p>
            </form>
            <tr><td >Umkreis in km:</td><td><div id="te" style="width:30"><?php echo $km ?></div></td></tr>
			<tr><td><div id="address"></div></td></tr>
			<tr><td><div id="location"></div></td></tr>
		</table>
	</div>
	</div>
	<br/>
	<div	class="main">		
	<div class="content" >
		<form action ="settings.php" method="post">
		<table style=" margin:0 auto;
									position:relative;
									max-width: 280px;
									width: 95%;">
			<tr><td>Altes Passwort:</td></tr>
			<tr><td><input id="p0"type="password" name="oldpass" value=<?php echo "'".$o."'" ?>/></td></tr>
			<tr><td>Neues Passwort:</td></tr>
			<tr><td><input id="p" type="password" name="password"value=<?php echo "'".$p."'" ?>/></td></tr>
			<tr><td>Wiederholung:</td></tr>
			<tr><td><input id="pw" type="password" name="passwordw"value=<?php echo "'".$pw."'" ?>/></td></tr>
			<tr><td colspan="2"><input type="submit" name="activ"value="Speichern" onclick="return clickbutton(this)" /></td></tr>
		</table>
		</form>
    </div>
	</div>
	<br/>
	<div	class="main" style="text-align:left;">		<!--gut fuer handy, schlecht for PC -->
	<div class="content" >
		
		<button id="openlist"onclick="filadd()">Kategorie hinzufügen</button>
		<ul >
		<?php
		if (isset($filtr)) {
			foreach ($filtr as $key => $value)
			{
				echo '<li id="liout'.$key.'" style="display: none;"> <a href="javascript:deletefilter('.$key.')">'.$value.' </a> </li>';
			}
		}
		?>
		</ul>
    </div>
	</div>
	<div id="newfiladd"  style='display: none; background:#FF0000; padding: 0 5 0 5; ' >
		<ul>
		<?php
		if (isset($filtr)) {
			foreach ($filtr as $key => $value)
			{
				echo '<li id="liin'.$key.'" style="display: none; background:#FFFF00; margin: 1 1 1 1;"> <a href="javascript:addfilter('.$key.')">'.$value.' </a> </li>';
			}
		}
		?>
		</ul>
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
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>
