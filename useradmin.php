<?php session_start(); 
	if($_SESSION['admin']!=1)
		header('Location: login.php');
	include("includes/ConectionOpen.php");
	if(isset($_POST['delete'])) {
		$id = "";
		$id = $id.$_POST["delete"];
		
		//alle Anzeigen des Benutzers löschen
		$sql = "SELECT id FROM offers WHERE userid='".$id."'";
		$res = $conn->query($sql);
		if ($res != null || isset($res)){
			$i = 0;
			$offers = [];
			while($row=$res->fetch_row()) {
				$offers[$i] = $row[0];
				$i++;
			}
			foreach($offers as $offersid) {
				$query = "DELETE FROM detailedtexts WHERE offersid='".$offersid."'";
				//echo $query;
				$res = $conn->query($query);
				$query = "DELETE FROM images WHERE offersid='".$offersid."'";
				//echo $query;
				$res = $conn->query($query);
				$query = "DELETE FROM interests WHERE offersid ='".$offersid."'";
				//echo $query;
				$res = $conn->query($query);
				$query = "DELETE FROM offers_tags WHERE offersid ='".$offersid."'";
				//echo $query;
				$res = $conn->query($query);
				$query = "DELETE FROM offers WHERE id ='".$offersid."'";
				//echo $query;
				$res = $conn->query($query);
			}
		}

		//$query = "DELETE FROM offers WHERE userid'".$id."'";
		//$res = $conn->query($query);
		$query = "DELETE FROM interests WHERE userid'".$id."'";
		$res = $conn->query($query);
		$query = "DELETE FROM users WHERE id='".$id."'";
		$res = $conn->query($query);
	}
	if(isset($_POST['changeFlag'])) {
		$id = "";
		$id = $id.$_POST["changeFlag"];
		$query = "";				  
		$query = "UPDATE users SET goldflag = 1 - goldflag WHERE id='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
    $str1 = "SELECT id,email,goldflag FROM users WHERE id != ".$_SESSION['idu'];
    $res = $conn->query($str1);
	while($row=$res->fetch_row()) {
		$user[$row[0]] = $row[1];
		$flag[$row[0]] = $row[2];
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
		<script >
		$(document).ready(function(){
			    PopUpHide();
		});
		function PopUpShow(){
			$("#popup").show();
		}
		function DeleteOnClick(value2,n, uName){
			var p = document.getElementById("BestP");
			if (n == 1) {
				p.innerHTML = "Wollen Sie den Benutzer '" + uName + "' wirklich löschen?";
			}else if(n == 2){
				p.innerHTML = "Wollen Sie den Status des Benutzers '" + uName + "' wirklich ändern?";
			}
			document.getElementById('jaB').value=value2;
			document.getElementById('jaB').name=( (n==1)?"delete":"changeFlag");
			PopUpShow();
		}
		function PopUpHide(){
			$("#popup").hide();
			
		}
		
		</script>
    </head>
    <body>
        <?php 
            include("includes/menu.php");
        ?>
        <div class="main">
            <div class="content">
				<table>
					<?php
						if (isset($user)) {
							echo '<tr><td>ID</td><td>Benutzer</td><td>Status</td></tr>';
							foreach ($user as $key => $value) {
								echo '<tr>';
								echo '<td>'.$key.'</td>';
								echo '<td>'.$value.'</td>';
								if ($flag[$key] == 1) {
									echo '<td>premium</td>';
								} else {
									echo "<td>normal</td>";
								}
								echo '</tr><tr>';
								echo '<td colspan=2>
										<button  onclick="DeleteOnClick('.$key.',1, \''. $value .'\')">Löschen</button>
										
									</td>';
								echo '<td>
										<button  onclick="DeleteOnClick('.$key.',2, \''. $value .'\')">Status ändern</button>
										
									</td>';
								echo '</tr>';
								echo '<tr><td colspan=3><hr></td></tr>';
							}
						}             
					 ?>
				</table>
            </div>
        </div>
		<div id="popup"  style=" width:100%;
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
				<div id="ppt" style="align:center;" >
					<p id="BestP">Sind Sie sicher?</p>
					<form id="changeFlagDelete" action="" method="post" enctype="multipart/form-data" >
						<button id="jaB" value="" name="changeFlag">Ja</button>
						<button id="neinB" onclick="PopUpHide(); return false;">Nein</button>
					</form>
				</div>
			</div>
		</div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>

