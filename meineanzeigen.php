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
				$interText[$key] = $row[0];	
				$interPrice[$key] = $row[1];	
				$interAmount[$key] = $row[2];
				$interImage[$key] = $row[3];	
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
					<tr>
						<td colspan="4">
							<form action="additem.php"> 
								<input type="submit" value="Anzeige erstellen">
							</form>
						</td>
					</tr>
					<?php
						if (isset($interText) && isset($interPrice) && isset($interAmount)) {
							foreach ($interText as $key => $value) {
								echo '<tr>';
								echo '<td><img style="max-width: 100px; max-height: 100px;" src="data:image/jpeg;base64,'.base64_encode( $interImage[$key] ).'"/></td>';
								echo '<td>'.$value.'</td>';
								echo '<td>
										<form id="edit" action="edititem.php" method="post" enctype="multipart/form-data" >
											<button value="'.$interid[$key].'" name="edit">Editieren</button>
										</form>
									</td>';
								echo '<td>
										
											<button  onclick="DeleteOnClick('.$interid[$key].')">Löschen</button>
										
									</td>';
								echo '</tr>';
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
    </body>
</html>

