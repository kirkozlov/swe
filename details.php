<?php session_start(); 
	include("includes/ConectionOpen.php");
	$id = "";
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	}

	if (isset($_GET['like'])) {
		$id = $_GET['like'];
		// TODO popup
		if (!isset($_SESSION['idu'])) {
			header("Location: login.php");
		} else {
				$userid = $_SESSION['idu'];
			$sql = "INSERT INTO interests (offersid, userid) VALUES ('$id', '$userid')";
			echo $sql;
			$res = $conn->query($sql);
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

    </head>
    <body>
        <?php 
            include("includes/menu.php");
        ?>
        <div class="main">
            <div class="content">
                <?php                   
                    echo '<table>
                            <tr>
                                <td colspan=2><img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/></td>
                            </tr>
                            <tr>
                                <td>'.$interMaintext.'</td><td>'.$interPrice.'€</td>
                            </tr>';
                           
                      		for ($i = 1; $i <= 10; $i++) {
                      			if (isset($interTexts[$i])) {
                      				echo '<tr><td colspan=2>'.$interTexts[$i].'</td></tr>';
                      			}
                      			if (isset($interImages[$i])) {
                      				echo '<tr><td colspan=2><img style="max-width: 300px; max-height: 300px;" src="data:image/jpeg;base64,'.base64_encode( $interImages[$i] ).'"/></td></tr>';
                      			}
                      		}
                      		
                 	echo '<tr>
                            	<td align="left">
                            		<form id="like" action="" method="get" enctype="multipart/form-data" >
                            			<button value="'.$id.'" name="like"/>♥</button>  
                        			</form>                         	
                            	</td>
                            	<td align="right">
									<form id="back" action="index.php" method="get" enctype="multipart/form-data" >
										<button value="" name="next"/>✗</button>
									</form>
								</td>
                            </tr>
               		</table>';                      
                 ?>
            </div>
        </div>
    </body>
</html>

