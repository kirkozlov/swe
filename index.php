<?php session_start(); 
	include("includes/ConectionOpen.php");
	$dbempty = true;
/*    if (!isset($_SESSION['anzeigencounter'])) {        
    	$_SESSION['anzeigencounter'] = 0;
    
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
	}}*/

	if(!isset($_COOKIE['pos'])){
		$pageContent = file_get_contents('http://freegeoip.net/json/' );
		$parsedJson  = json_decode($pageContent);
		
		setcookie('pos', $parsedJson->latitude.', '.$parsedJson->longitude, time() + 1*3600, "/");
	}
	if(!isset($_COOKIE['pos'])){
		header('Location: index.php');
	}
	
	$sql = "SELECT offers.id, maintext, price, mainimage, userid 
			  FROM offers, offers_tags, users
			 WHERE offers.id = offers_tags.offersid 
			   AND amount > 0
			   AND users.id = offers.userid 
			   "; 
	if(isset($_SESSION['idu'])){
		$sql = $sql  ."AND userid != ". $_SESSION['idu'] ."
			   AND offers.id NOT IN (SELECT interests.offersid FROM interests)"; 	
	}
	if(isset($_COOKIE['idList'])){
		$sql = $sql. " AND offers.id NOT IN (". $_COOKIE['idList'] .") ";
	}
	$sql = (isset($_COOKIE['filter']) && $_COOKIE['filter'] > 0 )? $sql ."AND ". $_COOKIE['filter'] ."&POW(2, offers_tags.tagsid - 1) " : $sql;
	$sql = $sql . "AND calculateTheDistance(" . $_COOKIE['pos'] . ", offers.latitude, offers.longtitude) <= ". (isset($_COOKIE['km']) ? $_COOKIE['km']."000" : "50000");
	$sql = $sql ." ORDER BY -LOG(RAND()) / IF(users.goldflag = 0, 10, 30) LIMIT 1";
//	echo $sql;
    //$sql = "SELECT id, maintext, price, mainimage, userid FROM offers ORDER BY id LIMIT ".$_SESSION['anzeigencounter'].", 1";
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
        
			<script src="includes/cookies.js"></script>
		<script>	
			function setCookie(v){
				createCookie('idList', readCookie('idList') ? readCookie('idList') + ", " + v : v , 30);
			}
			
			function handleLocationError(navExists, elem){
				if(navExists){
					elem.innerHTML = "Für eine genauere Ortfindung, erlauben Sie bitte dem Browser Ihre Position zu bestimmen.";
				}
			}
			
			function getLocation(){
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						createCookie('pos', position.coords.latitude + ", " + position.coords.longitude, 1);
					}, function() {
					  handleLocationError(true, document.getElementById('message'));
					});
				} else {
					handleLocationError(false, document.getElementById('message'));
				}
			}
			getLocation();
		</script>
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
                                    <form class="fickdich_bild" id="like" action="details.php" method="post" enctype="multipart/form-data" > 
                                        <?php 
                                        if(isset($interImage)) {
                                            echo '<input class="img_responsivness" type="image" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'" value="'.$id.'" name="id"/>';
                                        }
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
                                    <p class="center-align fickdich_text">
                                        <?php 
                                            if (isset($interMaintext)) {
                                                echo $interMaintext;
		        				         } ?>
                                    </p>
                                    <?php if ($dbempty) {                           
                                            echo '<p class="center-align fickdich_text">
                                                    Bedauerlicherweise sind keine neuen Anzeigen vorhanden.</p>';
                                            echo '<p class="center-align fickdich_text">
                                                    Wollen Sie von Vorne anfangen?</p>'; 
                                    } ?>
                                </div>
                                <div class="card-action">
                                    <?php if ($dbempty) {
                                        echo '<div class="row">';
                                            echo '<div class="col s6 m6 l6">';
                                                echo '<form class="center_dings_neu" action="" method="post">';
                                                    echo '<button class="waves-effect waves-light btn" onclick="eraseCookie(\'idList\');">ja</button>';
                                                echo '</form>';
                                            echo '</div>'; 
                                            echo '<div class="col s6 m6 l6">'; 
                                                echo '<form class="center_dings_neu" action="" method="post">';
                                                    echo '<button class="waves-effect waves-light btn" onclick="document.location.href = \'settings.php\'; return false;">Filter anpassen</button>';
                                                echo '</form>'; 
                                            echo '</div>';
                                        echo '</div>';   
                                    } ?>
    
                                    <div class="row">
                                        <div class="col s6 m6 l6 left-align"><h10><?php 
                                            if (isset($interPrice)) {
                                                echo 'Preis:';} ?></h10></div>
                                                    
                                        <div class="col s6 m6 l6 right-align"><h10><?php 
                                            if (isset($interPrice)) {
		        					             echo ''.$interPrice.'€';
		        				            } ?></h10></div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="col s0 m3 l3"><p></p></div>
                    </div>                  

                    <div class="row">
                    <div class="col s1 m1 l2"><h1/></div>
                        <div class="col s3 m3 l3">
                            <form class="center_dings" id="like" action="details.php" method="post" enctype="multipart/form-data" > 
                                <?php
                                    if (isset($id)) {
                                    echo '<button value="'.$id.'" name="id" class="btn-floating btn-large waves-effect waves-light green accent-3">
                                        <i class="material-icons">favorite</i>
                                    </button>';}
                                ?>
                            </form>    
                        </div>
                        <div class="col s4 m4 l2"><h1/></div>
                        <div class="col s3 m3 l3">
                            <form class="center_dings" id="next" action="" method="get" enctype="multipart/form-data" >
                                <?php
                                    if (isset($id)) {
                                    echo '<button value="" name="next" onclick="setCookie('.$id.');" class="btn-floating btn-large waves-effect waves-light red accent-3 center_dings">
                                        <i class="material-icons">delete</i>
                                    </button>';}
                                ?>
                            </form>                        	
                        </div>
                        <div class="col s1 m1 l2"><h1/></div>
                    </div>
                </div>
            </div>

         
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
            $(".button-collapse").sideNav();
        </script>
    </body>
</html>

