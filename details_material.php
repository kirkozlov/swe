<?php session_start(); 
	include("includes/ConectionOpen.php");
	$id = "";
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
			//TODO POPUP
			$message = "Gekauft!";
			header("Location: index.php?next=");
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
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
        <!--Import materialize.css-->
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        
        <!--Import materialize_own.css-->
        <link href="css/materialize_own.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<script src="http://code.jquery.com/jquery-2.0.2.min.js"></script>
		
		<script>
			function PopUpShow(){
				$("#popup").show();
			}
			function showContact() {
				PopUpShow();
			}
            
		</script>
    </head>
    <body>
        <?php 
            include("includes/menu_material.php");
        ?>
        <div class="main">
            <div class="content"> 
                
                <div class="row">
                    <div class="col s12 m12 l12">
                         <?php echo '<img class="img_responsivness" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/>';?>
                    </div>
                </div>
                
                <div class="row">
                    <?php echo '<div class="col s12 m12 l12 text_center">'.$interMaintext.'</div>';?>
                </div>';
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        if (isset($interTexts[$i])) {
                            echo '<div class="row">
                                    <div class="col s12 m12 l12">'.$interTexts[$i].'</div></div>';
                        }
                        if (isset($interImages[$i])) {
                            echo '<div class="row">
                                    <img class="img_responsivness" src="data:image/jpeg;base64,'.base64_encode( $interImages[$i] ).'"/></div></div>';
                        }
                    }?>  
                
               <div class="row">
                    <?php echo '<div class="col s6 m6 l6 text_center"><h5>Entfernung:</h5></div>';?>
                    <?php echo '<div class="col s6 m6 l6 text_center"><h5>50km</h5></div>';?>
                </div>

                <div class="row">
                    <?php echo '<div class="col s6 m6 l6 text_center"><h5>Preis:</h5></div>';?>
                    <?php echo '<div class="col s6 m6 l6 text_center"><h5>'.$interPrice.'€</h5></div>';?>
                </div>  
                
                <div class="row">
                    <div class="col s1 m1 l2"><h1/></div>
                    <div class="col s3 m3 l3">
                        <form class="center_dings" id="" action="" method="" enctype="multipart/form-data" > 
                            <button value="" name="" class="btn-floating btn-large waves-effect waves-light green accent-3">
                                <i class="material-icons">favorite</i>
                            </button>
                        </form>    
                    </div>
                    <div class="col s4 m4 l2"><h1/></div>
                    <div class="col s3 m3 l3">
                        <form class="center_dings" >
                            <button class="btn-floating btn-large waves-effect waves-light red accent-3 center_dings">
                                <i class="material-icons">delete</i>
                            </button>
                        </form>                        	
                    </div>
                    <div class="col s1 m1 l2"><h1/></div>
                </div>
               
            </div>
        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>$(".button-collapse").sideNav();</script>
    </body>
</html>

