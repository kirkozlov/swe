<?php session_start(); 
	if($_SESSION['admin']!=1)
		header('Location: login.php');
	include("includes/ConectionOpen.php");
	if(isset($_POST['delete'])) {
		$id = "";
		$id = $id.$_POST["delete"];
		$query = "DELETE FROM detailedtexts WHERE offersid='".$id."'";
		//echo $query;
		$res = $conn->query($query);
		$query = "DELETE FROM images WHERE offersid='".$id."'";
		//echo $query;
		$res = $conn->query($query);
		$query = "DELETE FROM interests WHERE offersid ='".$id."'";
		//echo $query;
		$res = $conn->query($query);
		$query = "DELETE FROM offers_tags WHERE offersid ='".$id."'";
		$res = $conn->query($query);
		$query = "DELETE FROM offers WHERE id ='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
    $str1 = "SELECT id, maintext, mainimage, amount, price, latitude, longtitude FROM offers" ;
    $res = $conn->query($str1);
	while($row=$res->fetch_row()){
		$offers[$row[0]] = $row[1];
		$images[$row[0]] = $row[2];
		$amount[$row[0]] = $row[3];
		$price[$row[0]] = $row[4];
		$lat[$row[0]] = $row[5];
		$lng[$row[0]] = $row[6];
	}
	
    $conn->close();?>
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
		function DeleteOnClick(value2){
			document.getElementById('jaB').value=value2;
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
						if (isset($offers) && isset($images)) {
							#echo '<tr><td>Bild</td><td>Beschreibung</td><td>Anzahl</td></tr>';
							foreach ($offers as $key => $value) {
								echo '<tr>';
								echo '<td><img style="max-width: 100px; max-height: 100px;" src="data:image/jpeg;base64,'.base64_encode( $images[$key] ).'"/></td>';
								echo '<td>'.$value.'</td>';
								echo '<td>Anzahl: '.$amount[$key].'</td></tr>';
								echo '<tr><td>'.$price[$key].'€</td>';
										$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat[$key].",".$lng[$key]."&sensor=true";
										$json = file_get_contents($url);
										$obj = json_decode($json, true);
										$adr = $obj['results'][0]['formatted_address'];
										echo '<td>'.$adr.'</td>';
								echo '<td>
										<button  onclick="DeleteOnClick('.$key.')">Löschen</button>
										
									</td>';
								echo '</tr>';
								echo '<tr><td colspan=3><hr></td></tr>';
							}
							#echo '<tr><td colspan=3><hr></td></tr>';
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
												height: 40px;
												padding:10px;
												
												background-color: #c5c5c5;
												border-radius:5px;
												box-shadow: 0px 0px 10px #000;">
				<div id="ppt" style="align:center;" >
					<p id="BestP">Sind Sie sicher?</p>
					<form id="delete" action="" method="post" enctype="multipart/form-data" >
						<button id="jaB" value="" name="delete">Ja</button>
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

