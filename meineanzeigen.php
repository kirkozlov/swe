<?php session_start(); 
	if($_SESSION['login']!=true)
		header('Location: login.php');
	$uid=$_SESSION['idu'];
	include("includes/ConectionOpen.php");
	if(isset($_POST['delete'])) {
		$id = "";
		$id = $id.$_POST["delete"];
		$query = "";	  
		$query = "DELETE FROM detailedtexts WHERE offersid='".$id."'";
		//echo $query;
		$res = $conn->query($query);
		$query = "DELETE FROM images WHERE offersid='".$id."';";
		//echo $query;
		$res = $conn->query($query);
		$query = "DELETE FROM offers WHERE id ='".$id."'";
		//echo $query;
		$res = $conn->query($query);
	}
    $str1 = "SELECT id FROM offers WHERE userid='".$uid."'";
    $res = $conn->query($str1);
	while($row=$res->fetch_row()){
		$interid[$row[0]]=$row[0];
	}
	if (isset($interid)) {
		foreach ($interid as $key => $value) {
			$str1 = "SELECT maintext, price, amount, mainimage FROM offers WHERE id='".$value."'";
			$res = $conn->query($str1);
			if($row=$res->fetch_row()) {	
				$interText[$key]=$row[0];	
				$interPrice[$key] = $row[1];	
				$interAmount[$key] = $row[2];	
			}
		}
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
					<tr>
						<td colspan="3">
							<form action="additem.php"> 
								<input type="submit" value="Anzeige hinzufügen">
							</form>
						</td>
					</tr>
					<?php
						if (isset($interText) && isset($interPrice) && isset($interAmount)) {
							echo '<tr><td>Bild</td><td>Beschreibung</td></tr>';
							foreach ($interText as $key => $value) {
								echo '<tr>';
								echo '<td>PLATZHALTER</td>';
								echo '<td>'.$value.'</td>';
								echo '<td>
										<form id="delete" action="" method="post" enctype="multipart/form-data" >
											<button value="'.$interid[$key].'" name="delete"/>Löschen</button>
										</form>
									</td>';
								echo '</tr>';
							}
						}             
					 ?>
				</table>
            </div>
        </div>
    </body>
</html>

