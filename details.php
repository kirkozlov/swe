<?php session_start(); 
	include("includes/ConectionOpen.php");
	$id = "";
	$gekauft = false;
	$contact = "";
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	}

	if (isset($_GET['like'])) {
		$id = $_GET['like'];
		if (!isset($_SESSION['idu'])) {
			header("Location: login.php");
		} else {
			$userid = $_SESSION['idu'];
			$sql = "INSERT INTO interests (offersid, userid) VALUES ('$id', '$userid')";
			$res = $conn->query($sql);
			
			$sql = "SELECT email FROM users INNER JOIN offers ON offers.userid = users.id WHERE offers.id='".$id."'";
			$res = $conn->query($sql);
			$email = mysqli_fetch_array($res);
			$contact = $email[0];
			$gekauft = true;
		}
	}
	
    $sql = "SELECT maintext, price, mainimage FROM offers WHERE id='".$id."'";
	$res = $conn->query($sql);
	if($row=$res->fetch_row()) {
		$interMaintext = $row[0];	
		$interPrice = $row[1];	
		$interImage = $row[2];	
	}
	
	$sql = "SELECT insideid, detailledtext FROM detailedtexts WHERE offersid='".$id."'";
	$res = $conn->query($sql);
	while($row=$res->fetch_row()) {	
		$interTexts[$row[0]] = $row[1];
	}

	$sql = "SELECT insideid, image FROM images WHERE offersid='".$id."'";
	$res = $conn->query($sql);
	while($row=$res->fetch_row()){	
		$interImages[$row[0]] = $row[1];
	}

    
    $conn->close();
    
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
        <link rel="stylesheet" href="css/details.css" type="text/css" />
		<script src="includes/jquery.js"></script>
		
		<script>
			$(document).ready(function(){
			    PopUpHide();
			<?php
				if($gekauft == 0) {
					
				}
				else {
					echo "document.getElementById('ppt').innerHTML='Herzlichen Glückwunsch! Sie haben den Artikel erworben. Die Kontaktdaten des Verkäufers: ".$contact."';
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
                <table>
                    <tr>
               	 		<td colspan=2>
                    		<?php echo '<img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/>'; ?>
                    	</td>
                    </tr>
                    <tr>
                    	<td><?php echo $interMaintext; ?></td><td><?php echo $interPrice; ?></td>
                    </tr>
                    <?php
                  		for ($i = 1; $i <= 10; $i++) {
                  			if (isset($interTexts[$i])) {
                  				echo '<tr><td colspan=2>'.$interTexts[$i].'</td></tr>';
                  			}
                  			if (isset($interImages[$i])) {
                  				echo '<tr><td colspan=2><img style="max-width: 300px; max-height: 300px;" src="data:image/jpeg;base64,'.base64_encode( $interImages[$i] ).'"/></td></tr>';
                  			}
                  		}
                    ?>
                    <tr>
		            	<td align="left">
		            		<form id="like" action="" method="get" enctype="multipart/form-data" >		
		            			<?php echo '<button onclick="showContact()" value="'.$id.'" name="like">♥</button>'; ?>
	            			</form>                         	
                    	</td>
                    	<td align="right">
							<form id="back" action="index.php" method="get" enctype="multipart/form-data" >
									<button value="" name="next"/>✗</button>
							</form>
						</td>
                    </tr>
           		</table>
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
    </body>
</html>

