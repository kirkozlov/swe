<?php session_start(); 
	include("includes/ConectionOpen.php");
    if (!isset($_SESSION['anzeigencounter'])) {        
    	$_SESSION['anzeigencounter'] = 0;
    }
	if(isset($_GET['next'])) {
		$query = "SELECT COUNT(*) AS NumberOfOffers FROM offers;";
		$res = $conn->query($query);
		$numberofoffers = mysqli_fetch_array($res);
		if ($numberofoffers[0] == 0) {
			$dbempty = true;
		} else {
			$tmp = ($_SESSION['anzeigencounter'] + 1) % $numberofoffers[0];
			$_SESSION['anzeigencounter'] = $tmp;
		}
	}
    $sql = "SELECT id, maintext, price, mainimage, userid FROM offers ORDER BY id LIMIT ".$_SESSION['anzeigencounter'].", 1";
    $sth = $conn->query($sql);
    if (isset($sth) && $sth != null && $row = $sth->fetch_row()) {
    	$dbempty = false;
		$id = $row[0];
		$interMaintext = $row[1];	
		$interPrice = $row[2];	
		$interImage = $row[3];	
		$userid = $row[4];
    }
    
    if (isset($userid)) {
		$sql = "SELECT goldflag FROM users WHERE id='".$userid."'";
		$sth = $conn->query($sql);
		if (isset($sth) && $sth != null && $row = $sth->fetch_row()) {
			$goldflag = $row[0];
		}
	} else {
		$goldflag = 0;
	}

   	/*
	um mit filter zu vergleichen:
	
	SELECT * from offers_tags WHERE (2^tagsid & $_COOKIES['filter']) == 2^tagsid
	*/
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
        
    </head>
	<body>
            <?php 
                include("includes/menu_material.php");
                //echo $_SESSION['idu']; 
                //$_SESSION['filter']['1'] = 1;
                //var_dump($_SESSION); 
            ?>
            
            <div class="main">
                <div class="content">
                    <div class="row">
                        <div class="col s0 m3 l3"><p></p></div>
                        <div class="col s12 m6 l6" >
                            <div class="card pc">
                                <div class="card-image">
                                    <form class="fickdich_bild" id="like" action="details_material.php" method="get" enctype="multipart/form-data" > 
                                        <?php echo 
                                            '<input class="img_responsivness" type="image" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'" value="'.$id.'" name="id"/>'
                                        ?>
                                    </form>

                                    <form class="center-align">
                                        <?php 
                                            if($goldflag == 1) {
                                                echo ' 	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  ';
                                            }
                                        ?>
                                    </form>
                                </div>
                                <div class="card-content">
                                    <p class="center-align fickdich_text"><?php echo ''.$interMaintext.'' ?></p>
                                </div>
                                <div class="card-action">
                                    <div class="row">
                                        <div class="col s6 m6 l6 left-align"><h10>Preis:</h10></div>
                                        <div class="col s6 m6 l6 right-align"><h10><?php echo ''.$interPrice.'' ?>€</h10></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s0 m3 l3"><p></p></div>
                    </div>                  

                    <div class="row">
                    <div class="col s1 m1 l2"><h1/></div>
                        <div class="col s3 m3 l3">
                            <form class="center_dings" id="like" action="details_material.php" method="get" enctype="multipart/form-data" > 
                                <button value="<?php echo '.$id.' ?>" name="id" class="btn-floating btn-large waves-effect waves-light green accent-3">
                                    <i class="material-icons">favorite</i>
                                </button>
                            </form>    
                        </div>
                        <div class="col s4 m4 l2"><h1/></div>
                        <div class="col s3 m3 l3">
                            <form class="center_dings" id="next" action="" method="get" enctype="multipart/form-data" >
                                <button value="" name="next" class="btn-floating btn-large waves-effect waves-light red accent-3 center_dings">
                                    <i class="material-icons">delete</i>
                                </button>
                            </form>                        	
                        </div>
                        <div class="col s1 m1 l2"><h1/></div>
                    </div>
                </div>
            </div>
		   <?php
		   		if ($dbempty) {
		   			echo 'Bedauerlicherweise sind keine Anzeigen vorhanden.';
		   		}
		   ?>
         

        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
            $(".button-collapse").sideNav();
        </script>
    </body>
</html>

