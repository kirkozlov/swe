<?php session_start(); 
	if($_SESSION['login']!=true)
		header('Location: login.php');
	$uid=$_SESSION['idu'];
	include("includes/ConectionOpen.php");
    $str1 = "SELECT id,offersid FROM interests WHERE userid='".$uid."'";
    $res = $conn->query($str1);
	while($row=$res->fetch_row()){
		$interid[$row[0]]=$row[1];
	}
	if (isset($interid)) {
		foreach ($interid as $key => $value) {
			$str1 = "SELECT maintext, price, amount, mainimage FROM offers WHERE id='".$value."'";
			$res = $conn->query($str1);
			if($row=$res->fetch_row()) {	
				$interText[$key]=$row[0];	
				$interPrice[$key] = $row[1];	
				$interAmount[$key] = $row[2];	
				$interImage[$key] = $row[3];		//so nicht!					
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
                <?php
                	if (isset($interText) && isset($interPrice) && isset($interAmount)) {
                		echo '<table>';
                		echo '<tr><td>Bild</td><td>Beschreibung</td><td>Preis</td><td>Anzahl</td></tr>';
                		foreach ($interText as $key => $value) {
                			echo '<tr>';
                			echo '<td><img style="max-width: 100px; max-height: 100px;" src="data:image/jpeg;base64,'.base64_encode( $interImage[$key] ).'"/></td>';
							echo '<td>'.$value.'</td>';
                			echo '<td>'.$interPrice[$key].'€</td>';
                			echo '<td>'.$interAmount[$key].'</td>';
                			echo '</tr>';
                		}
						echo '</table>';
                	}              
                 ?>
            </div>
        </div>
    </body>
</html>

