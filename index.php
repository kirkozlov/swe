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
			   AND users.id = offers.userid 
			   AND userid != ". $_SESSION['idu'] ."
			   AND offers.id NOT IN (SELECT interests.offersid FROM interests)"; 
	if(isset($_COOKIE['idList'])){
		$sql = $sql. " AND offers.id NOT IN (". $_COOKIE['idList'] .") ";
	}
	$sql = (isset($_COOKIE['filter']) && $_COOKIE['filter'] > 0 )? $sql ."AND ". $_COOKIE['filter'] ."&POW(2, offers_tags.tagsid - 1) " : $sql;
	$sql = $sql . "AND calculateTheDistance(" . $_COOKIE['pos'] . ", offers.latitude, offers.longtitude) <= ". (isset($_COOKIE['km']) ? $_COOKIE['km']."000" : "50000");
	$sql = $sql ." ORDER BY -LOG(RAND()) / IF(users.goldflag = 0, 10, 30) LIMIT 1";
	echo $sql;
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
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="stylesheet" href="css/menu.css" type="text/css" />
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
            include("includes/menu.php");
			//echo $_SESSION['idu']; 
			//$_SESSION['filter']['1'] = 1;
            //var_dump($_SESSION); 
        ?>
        <div class="main">
            <div class="content">
            	<table>
            		<tr>
            			<td colspan=2>
            				<?php 
								if(isset($interImage)) {
									echo '<img style="max-width: 600px; max-height: 600px;" src="data:image/jpeg;base64,'.base64_encode( $interImage ).'"/>';
								}
							?>					
						</td>
            		</tr>
            		<tr>
						<td colspan=2>
							<?php
								if($goldflag == 1) {
									echo ' 	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  	★  ';
								}
							?>
						</td>
            		</tr>
            		<tr>
		        		<td colspan=2>
		        			<?php
		        				if (isset($interMaintext)) {
		        					echo $interMaintext;
		        				}
		        			?>
		        		</td>
		        	</tr>
		        	<tr>
		        		<td colspan=2>
							<?php
		        				if (isset($interPrice)) {
		        					echo ''.$interPrice.'€';
		        				}
		        			?>
		        		</td>
            		</tr>
                    <tr>
                    	<td>
            				<form id="like" action="details.php" method="post" enctype="multipart/form-data" >
            					<?php
            						if (isset($id)) {
            							echo '<button value="'.$id.'" name="id"/>♥</button>';
            						}
            					?>
        					</form>                         	
                    	</td>
                    	<td>
							<form id="next" action="" method="get" enctype="multipart/form-data" >
            					<?php
            						if (isset($id)) {
            							echo '<button value="" name="next" onclick="setCookie('.$id.');" />✗</button>';
            						}
            					?>							
							</form>
						</td>
                    </tr>

               	</table>
				   <?php
				   		if ($dbempty) {
							echo '<form action="" method="post">';
				   			echo 'Bedauerlicherweise sind keine neuen Anzeige vorhanden.<br/>';
							echo 'Wollen Sie von Vorne anfangen?<br/>';
							echo '<button onclick="eraseCookie(\'idList\');">ja</button>';
							echo '<button onclick="document.location.href = \'settings.php\'; return false;">Filter anpassen</button>';
							echo '</form>';
				   		}
				   ?>
				<div id="message"></div>
            </div>
		</div>
    </body>
</html>

