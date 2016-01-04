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
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
		
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
										<form id="delete" action="" method="post" enctype="multipart/form-data" >
											<button value="'.$key.'" name="delete"/>Löschen</button>
										</form>
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
    </body>
</html>

