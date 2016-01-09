<?php session_start(); 
	$contact = "";
	$contactdetails = false;
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
			$str1 = "SELECT maintext, price, amount, mainimage, userid FROM offers WHERE id='".$value."'";
			$res = $conn->query($str1);
			if($row=$res->fetch_row()) {	
				$interText[$key]=$row[0];	
				$interPrice[$key] = $row[1];	
				$interAmount[$key] = $row[2];	
				$interImage[$key] = $row[3];	
				$userid[$key] = $row[4];			
			}
		}
	}
	if (isset($_POST['contact'])) {
		$id = $_POST['contact'];
		$sql = "SELECT email FROM users WHERE id='".$id."'";
		$res = $conn->query($sql);
		$email = mysqli_fetch_array($res);
		$contact = $email[0];
		$contactdetails = true;
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
		
		<script>
			$(document).ready(function(){
			    PopUpHide();
			<?php
				if($contactdetails) {
					echo "document.getElementById('ppt').innerHTML='Die Kontaktdaten des Verkäufers: ".$contact."';
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
			function showContact() {
				PopUpShow();
			}
		</script>
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
                			echo '</tr><tr>';
							echo '<td colspan=2>
									<form id="contact" action="" method="post" enctype="multipart/form-data" >
										<button value="'.$userid[$key].'" name="contact"/>Kontakt</button>
									</form>
								</td>';
							echo '<td colspan=2>
									<form id="like" action="details.php" method="post" enctype="multipart/form-data" >
										<button value="'.$interid[$key].'" name="id"/>Details</button>
									</form>
								</td>';
                			echo '</tr>';
                			echo '<tr><td colspan=4><hr></td></tr>';
                		}
						echo '</table>';
                	}              
                 ?>
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
												height: 100px;
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

